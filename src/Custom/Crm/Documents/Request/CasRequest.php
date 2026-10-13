<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents\Request;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
final readonly class CasRequest
{
    public function __construct(public int $expectedVersion)
    {
        Wire::integer($expectedVersion);
    }
    /** @return array{expected_version:int} */
    public function toArray(): array
    {
        return ['expected_version' => $this->expectedVersion];
    }
}
