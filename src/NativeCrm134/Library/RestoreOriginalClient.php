<?php

declare(strict_types=1);

namespace Factuarea\Sdk\NativeCrm134\Library;

use Factuarea\Sdk\Custom\Crm\CrmResponse;
use Factuarea\Sdk\NativeCrm134\Library;
use Factuarea\Sdk\NativeCrm134\Model;

/** Reads the native immutable receipt; unknown remains unknown even after a Current read. */
final readonly class RestoreOriginalClient
{
    public function __construct(private Library $native)
    {
    }

    /** @return CrmResponse<Model\CrmGetCommercialDocumentRestoreOriginalResult> */
    public function original(RestoreOriginalIntent $intent): CrmResponse
    {
        $response = $this->native->crmGetCommercialDocumentRestoreOriginal($intent->request());
        $data = $response->body->data;
        if (strtolower($data->document->id) !== strtolower($intent->document)
            || $data->document->expectedVersion !== $intent->expectedVersion) {
            throw new \UnexpectedValueException('The native restore Original must identify the retained document and CAS.');
        }
        if ($data instanceof Model\CrmDocumentRestoreOriginalVariant2
            && (strtolower($data->restoreOriginal->id) !== strtolower($intent->document)
                || $data->restoreOriginal->version !== $intent->expectedVersion + 1)) {
            throw new \UnexpectedValueException('The confirmed native restoration must retain its Original version.');
        }

        return $response;
    }
}
