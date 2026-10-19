<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm;

use Psr\Http\Message\ResponseInterface;

/** @template-covariant T of WireModel */
final readonly class CrmResponse
{
    /** @param T $body */
    public function __construct(
        public WireModel $body,
        public ResponseInterface $rawResponse,
        public ?string $idempotencyKey = null,
    ) {
    }

    public function statusCode(): int
    {
        return $this->rawResponse->getStatusCode();
    }

    /** Native replay headers are evidence; a matching entity ID alone is not. */
    public function replayed(): bool
    {
        return in_array(strtolower($this->rawResponse->getHeaderLine('Idempotent-Replayed')), ['true', '1'], true);
    }
}
