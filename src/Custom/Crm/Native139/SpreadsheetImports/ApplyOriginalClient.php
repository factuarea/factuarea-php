<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Native139\SpreadsheetImports;

use Factuarea\Sdk\Custom\Crm\CrmResponse;
use Factuarea\Sdk\Custom\Crm\Native139\Model;
use Factuarea\Sdk\Custom\Crm\Native139\SpreadsheetImports;
use Factuarea\Sdk\Custom\Crm\Transport;

/** Current and Original are distinct owner Queries. A Current read never confirms a retained import mutation. */
final readonly class ApplyOriginalClient
{
    public function __construct(private Transport $transport, private SpreadsheetImports $native)
    {
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
