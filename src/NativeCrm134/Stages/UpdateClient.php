<?php

declare(strict_types=1);

namespace Factuarea\Sdk\NativeCrm134\Stages;

use Factuarea\Sdk\Custom\Crm\CrmResponse;
use Factuarea\Sdk\Models\Errors\APIException;
use Factuarea\Sdk\Models\Errors\ErrorThrowable;
use Factuarea\Sdk\NativeCrm134\Model;
use Factuarea\Sdk\NativeCrm134\StageAdministration;

final class UpdateClient
{
    /** Process-local resend fence, never authority or a durable receipt. @var array<string, string> */
    private static array $issued = [];

    public function __construct(private readonly StageAdministration $native)
    {
    }

    /** @return CrmResponse<Model\UpdateServiceStageResult|Model\GetServiceStageOriginalResult> */
    public function update(UpdateIntent $intent): CrmResponse
    {
        $identity = strtolower($intent->stage)."\0".$intent->idempotencyKey;
        if (isset(self::$issued[$identity]) && ! hash_equals(self::$issued[$identity], $intent->requestFingerprint)) {
            throw new \InvalidArgumentException('A known stage intent cannot replace its original configuration, CAS or key.');
        }
        if ($intent->isReadbackOnly() || isset(self::$issued[$identity])) {
            return $this->original($intent);
        }
        $request = $intent->request();
        self::$issued[$identity] = $intent->requestFingerprint;
        try {
            $response = $this->native->updateServiceStage($request);
            $this->validateReceipt($intent, $response);

            return $response;
        } catch (\Throwable $failure) {
            $status = $failure instanceof APIException ? $failure->statusCode
                : ($failure instanceof ErrorThrowable ? ($failure->container->rawResponse?->getStatusCode() ?? 0) : 0);
            if ($status >= 400 && $status < 500 && $status !== 408) {
                throw $failure;
            }
            throw new UpdateUnconfirmedException($intent, $failure);
        }
    }

    /** @return CrmResponse<Model\GetServiceStageOriginalResult> */
    public function original(UpdateIntent $intent): CrmResponse
    {
        $response = $this->native->getServiceStageOriginal($intent->originalRequest());
        $this->validateReceipt($intent, $response);

        return $response;
    }

    /** @param CrmResponse<Model\UpdateServiceStageResult|Model\GetServiceStageOriginalResult> $response */
    private function validateReceipt(UpdateIntent $intent, CrmResponse $response): void
    {
        $stage = $response->body->data;
        $body = $intent->body();
        if (strtolower($stage->id) !== strtolower($intent->stage)
            || strtolower($stage->pipelineId) !== strtolower($body->pipelineId)
            || $stage->version !== $body->expectedVersion + 1
            || $stage->pipelineVersion !== $body->expectedPipelineVersion
            || $stage->status !== 'active' || $stage->name !== $body->name
            || $stage->mappedStatus !== $body->mappedStatus || $stage->wipLimit !== $body->wipLimit) {
            throw new \UnexpectedValueException('The native stage receipt must identify the retained update and its Original version.');
        }
    }
}
