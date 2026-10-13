<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Bookings;

use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\WireModel;

final class ServicePageData extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['items', 'total', 'next_cursor'], 'properties' => ['items' => ['type' => 'array', 'items' => Schemas::LIST_SERVICE], 'total' => ['type' => 'integer', 'minimum' => 0], 'next_cursor' => Schemas::CURSOR]];
    public const FIELDS = ['items' => ['property' => 'items', 'type' => ['items' => ['model' => Service::class]]], 'total' => ['property' => 'total', 'type' => ['scalar' => 'int']], 'next_cursor' => ['property' => 'nextCursor', 'type' => ['scalar' => 'string']]];

    /** @param list<Service> $items */
    public function __construct(public array $items, public int $total, public ?string $nextCursor)
    {
        $this->validate();
    }
}
