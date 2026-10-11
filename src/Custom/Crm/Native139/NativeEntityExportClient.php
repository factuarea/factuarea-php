<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Native139;

use Factuarea\Sdk\Custom\Crm\CrmResponse;
use Factuarea\Sdk\Custom\Crm\EntityExports\ExportClient;
use Factuarea\Sdk\Custom\Crm\EntityExports\ExportDownload;
use Factuarea\Sdk\Custom\Crm\EntityExports\ExportIntent;
use Factuarea\Sdk\Custom\Crm\Transport;
use Factuarea\Sdk\Custom\Crm\WireModel;

/** Preserve SDK110 intent, resend fence and stream protocol; bind its wire to the actual private native export. */
final readonly class NativeEntityExportClient
{
    private ExportClient $native;

    public function __construct(Transport $transport)
    {
        $this->native = new ExportClient($transport);
    }

    /** @return CrmResponse<Model\PublicApiV1CrmEntityExportsRequestResult> */
    public function request(ExportIntent $intent): CrmResponse
    {
        Model\PublicApiV1CrmEntityExportsRequestParametersBody::fromArray($intent->request()->toArray());

        return $this->typed($this->native->request($intent), Model\PublicApiV1CrmEntityExportsRequestResult::class);
    }

    /** @return CrmResponse<Model\PublicApiV1CrmEntityExportsShowResult> */
    public function original(ExportIntent $intent): CrmResponse
    {
        new Model\PublicApiV1CrmEntityExportsShowParameters($intent->id);

        return $this->typed($this->native->original($intent), Model\PublicApiV1CrmEntityExportsShowResult::class);
    }

    /** @return CrmResponse<Model\PublicApiV1CrmEntityExportsShowResult> */
    public function status(ExportIntent $intent): CrmResponse
    {
        return $this->original($intent);
    }

    public function download(ExportIntent $intent): ExportDownload
    {
        $download = $this->native->download($intent);
        Model\PublicApiV1CrmEntityExportsShowResult::fromArray(['data' => $download->job->jsonSerialize()]);

        return $download;
    }

    /** @template T of WireModel @param class-string<T> $type @return CrmResponse<T> */
    private function typed(CrmResponse $response, string $type): CrmResponse
    {
        return new CrmResponse($type::fromArray($response->body->toArray()), $response->rawResponse, $response->idempotencyKey);
    }
}
