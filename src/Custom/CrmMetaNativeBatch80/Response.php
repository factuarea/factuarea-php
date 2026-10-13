<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\CrmMetaNativeBatch80;

/** Raw native response preserves original Fields object/property/list order. */
final readonly class Response
{
    public function __construct(public \stdClass $data, public string $rawBody, public int $status, public ?string $requestId)
    {
    }
}
