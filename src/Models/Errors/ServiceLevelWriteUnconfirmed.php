<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Errors;

use Factuarea\Sdk\Models\Operations\ServiceLevelWriteIntent;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/** No success is inferred. Pass intent to ServiceLevel::recover explicitly with the original credentials. */
final class ServiceLevelWriteUnconfirmed extends APIException
{
    public function __construct(
        public readonly ServiceLevelWriteIntent $intent,
        ?ResponseInterface $response = null,
        public readonly ?Throwable $cause = null,
    ) {
        parent::__construct('El resultado original del SLA no está confirmado. Conserva la intención y su clave originales.',
            $response?->getStatusCode() ?? 0, $response === null ? '' : (string) $response->getBody(), $response);
    }
}
