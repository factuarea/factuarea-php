<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\CustomerService\Model;

use Factuarea\Sdk\Custom\Crm\CustomerService\TicketWire;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
use InvalidArgumentException;

final readonly class Timeline
{
    /** @param list<TimelineEntry> $items */
    private function __construct(public string $id, public array $items, public int $total, public int $page, public int $perPage)
    {
    }
    public static function fromWire(mixed $wire): self
    {
        $value = TicketWire::record($wire, ['id', 'items', 'total', 'page', 'per_page']);
        $id = Wire::uuid($value['id']);
        $items = array_map(static fn (mixed $entry): TimelineEntry => TimelineEntry::fromWire($entry, $id), Wire::items($value['items']));
        $total = Wire::integer($value['total'], true);
        $page = Wire::integer($value['page']);
        $perPage = Wire::integer($value['per_page']);
        TicketWire::page($page, $perPage);
        if (count($items) > $perPage || count($items) > $total) {
            throw new InvalidArgumentException('Timeline pagination must retain the native counts.');
        }

        return new self($id, $items, $total, $page, $perPage);
    }
}
