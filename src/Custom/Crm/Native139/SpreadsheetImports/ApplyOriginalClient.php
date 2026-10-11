<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Native139\SpreadsheetImports;

use Factuarea\Sdk\Custom\Crm\CrmResponse;
use Factuarea\Sdk\Custom\Crm\Native139\Model;
use Factuarea\Sdk\Custom\Crm\Native139\SpreadsheetImports;
use Factuarea\Sdk\Custom\Crm\Transport;
use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Models\Errors\APIException;
use Factuarea\Sdk\Models\Errors\ErrorThrowable;

/** Current and Original are distinct owner Queries. A Current read never confirms a retained import mutation. */
final class ApplyOriginalClient
{
    /** Process-local resend fence; it stores neither authority nor durable receipts. @var array<string,string> */
    private static array $issued = [];

    public function __construct(private readonly Transport $transport, private readonly SpreadsheetImports $native)
    {
    }

    /** @return CrmResponse<Model\PublicApiV1CrmSpreadsheetImportsApplyResult|Model\PublicApiV1CrmSpreadsheetImportsApplyOriginalResult> */
    public function apply(ApplyIntent $intent): CrmResponse
    {
        $identity = strtolower($intent->run)."\0".$intent->idempotencyKey;
        if (isset(self::$issued[$identity]) && ! hash_equals(self::$issued[$identity], $intent->originalBodySha256)) {
            throw new \InvalidArgumentException('A known Apply intent cannot replace its original body or key.');
        }
        if ($intent->isReadbackOnly() || isset(self::$issued[$identity])) {
            return $this->original($intent->original());
        }
        $request = $intent->request();
        self::$issued[$identity] = $intent->originalBodySha256;
        try {
            $response = $this->native->publicApiV1CrmSpreadsheetImportsApply($request);
            $run = $response->body->data;
            $sameRun = strtolower($run->id) === strtolower($intent->run);
            $childRun = $request->body->mode === 'retry_failed' && $run->parentRunId !== Omitted::Value
                && strtolower($run->parentRunId) === strtolower($intent->run);
            if (! $sameRun && ! $childRun) {
                throw new \UnexpectedValueException('The native Apply result must identify its original run or authorized retry child.');
            }

            return $response;
        } catch (\Throwable $failure) {
            $status = $failure instanceof APIException ? $failure->statusCode
                : ($failure instanceof ErrorThrowable ? ($failure->container->rawResponse?->getStatusCode() ?? 0) : 0);
            if ($status >= 400 && $status < 500 && $status !== 408) {
                throw $failure;
            }
            throw new ApplyUnconfirmedException($intent, $failure);
        }
    }

    /** @return CrmResponse<Model\PublicApiV1CrmSpreadsheetImportsApplyOriginalResult> */
    public function original(ApplyOriginalIntent $intent): CrmResponse
    {
        $request = new Model\PublicApiV1CrmSpreadsheetImportsApplyOriginalParameters($intent->run, $intent->originalBodySha256, $intent->idempotencyKey);
        $response = $this->transport->request('GET', 'crm/imports/'.rawurlencode($request->run).'/apply/original',
            'public-api.v1.crm-spreadsheet-imports.apply-original', Model\PublicApiV1CrmSpreadsheetImportsApplyOriginalResult::class,
            ['original_body_sha256' => $request->originalBodySha256], null, $request->idempotencyKey, false, [], hash('sha256', ''), [200]);
        if ($response->body->data->originalBodySha256 !== $intent->originalBodySha256) {
            throw new \UnexpectedValueException('The native Original must identify the retained request hash.');
        }

        return $response;
    }

    /** @return CrmResponse<Model\PublicApiV1CrmSpreadsheetImportsShowResult> */
    public function current(Model\PublicApiV1CrmSpreadsheetImportsShowParameters $request): CrmResponse
    {
        return $this->native->publicApiV1CrmSpreadsheetImportsShow($request);
    }
}
