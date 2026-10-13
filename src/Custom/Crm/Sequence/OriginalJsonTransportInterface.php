<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Sequence;

use Factuarea\Sdk\Custom\Crm\CrmResponse;
use Factuarea\Sdk\Custom\Crm\WireModel;
use Psr\Http\Message\ResponseInterface;

/** Required capability of the SAME canonical CRM Transport, not another HTTP client. */
interface OriginalJsonTransportInterface
{
    /**
     * Existing canonical Transport::request; the factory supplies the SAME concrete object.
     * @template T of WireModel
     * @param  class-string<T>  $responseType
     * @param  array<string,scalar|null>  $query
     * @param  array<string,string>  $headers
     * @return CrmResponse<T>
     */
    public function request(string $method, string $path, string $operationId, string $responseType,
        array $query = [], ?WireModel $body = null, ?string $idempotencyKey = null, bool $hasEffect = true, array $headers = []): CrmResponse;

    /**
     * @template T of WireModel
     * @param  class-string<T>  $responseType
     * @param  array<string,string>  $headers
     * @return CrmResponse<T>
     */
    public function requestOriginalJson(string $path, string $operationId, string $responseType,
        string $originalWire, string $originalKey, array $headers = []): CrmResponse;

    /** Single PATCH; no Idempotency-Key generation, retry, JSON success body or recovery receipt.
     * @param  array<string,string>  $headers */
    public function requestControl(string $path, string $operationId, string $wire, array $headers = []): ResponseInterface;
}
