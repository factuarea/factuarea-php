<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\EntityExports;

use Factuarea\Sdk\Custom\Crm\CrmResponse;
use Factuarea\Sdk\Custom\Crm\Transport;
use Factuarea\Sdk\Models\Errors\APIException;
use Factuarea\Sdk\Models\Errors\ErrorThrowable;

final class ExportClient
{
    /** @var array<string,array{hash:string,key:string}> */
    private static array $issued = [];

    public function __construct(private readonly Transport $transport)
    {
    }

    /** Issue once; another call with the known exact intent is a Query-only original read. @return CrmResponse<ExportEnvelope> */
    public function request(ExportIntent $intent): CrmResponse
    {
        if ($intent->isReadbackOnly() || $this->remember($intent)) {
            return $this->original($intent);
        }
        $body = $intent->request();
        try {
            $response = $this->transport->request('POST', 'crm/entity-exports', 'public-api.v1.crm-entity-exports.request',
                ExportEnvelope::class, [], $body, $intent->idempotencyKey, true, [], $intent->originalBodySha256);

            return $this->checked($response, $intent, [200, 202]);
        } catch (\Throwable $failure) {
            $status = $failure instanceof APIException ? $failure->statusCode
                : ($failure instanceof ErrorThrowable ? ($failure->container->rawResponse?->getStatusCode() ?? 0) : 0);
            if ($status >= 400 && $status < 500 && $status !== 408) {
                throw $failure;
            }
            throw new ExportUnconfirmedException($intent, $failure);
        }
    }

    /** No body/query, no requeue: server reauthorizes the same native original. @return CrmResponse<ExportEnvelope> */
    public function original(ExportIntent $intent): CrmResponse
    {
        $this->remember($intent);

        return $this->checked($this->transport->request('GET', 'crm/entity-exports/'.$intent->id,
            'public-api.v1.crm-entity-exports.show', ExportEnvelope::class, [], null, $intent->idempotencyKey, false, [], hash('sha256', '')), $intent, [200]);
    }

    /** @return CrmResponse<ExportEnvelope> */
    public function status(ExportIntent $intent): CrmResponse
    {
        return $this->original($intent);
    }

    public function download(ExportIntent $intent): ExportDownload
    {
        $job = $this->original($intent)->body->data;
        if ($job->state() !== ExportStatus::Ready) {
            throw new ExportNotReadyException($job);
        }
        $response = $this->transport->requestStream('crm/entity-exports/'.$intent->id.'/download',
            'public-api.v1.crm-entity-exports.download', $intent->idempotencyKey);

        return new ExportDownload($job, $response);
    }

    /** @param CrmResponse<ExportEnvelope> $response @param list<int> $statuses @return CrmResponse<ExportEnvelope> */
    private function checked(CrmResponse $response, ExportIntent $intent, array $statuses): CrmResponse
    {
        $job = $response->body->data;
        if (! in_array($response->statusCode(), $statuses, true) || $job->id !== $intent->id || $job->entityType !== $intent->entityType->value
            || ($response->statusCode() === 202 && $job->state() !== ExportStatus::Processing)) {
            throw new \UnexpectedValueException('The native export response must identify the original job and factual status.');
        }

        return $response;
    }

    /** Process-local resend guard only; it stores no job, binary, credential or authority. */
    private function remember(ExportIntent $intent): bool
    {
        if (isset(self::$issued[$intent->id])) {
            $original = self::$issued[$intent->id];
            if (! hash_equals($original['hash'], $intent->originalBodySha256) || $original['key'] !== $intent->idempotencyKey) {
                throw new \InvalidArgumentException('A known export identity cannot replace its original selection or key.');
            }

            return true;
        }
        self::$issued[$intent->id] = ['hash' => $intent->originalBodySha256, 'key' => $intent->idempotencyKey];

        return false;
    }
}
