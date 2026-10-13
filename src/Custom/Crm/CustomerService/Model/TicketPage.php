<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\CustomerService\Model;

use Factuarea\Sdk\Custom\Crm\CustomerService\TicketWire;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
use InvalidArgumentException;

final readonly class TicketPage
{
    /** @param list<Ticket> $items */
    private function __construct(public array $items, public int $total, public int $page, public int $perPage)
    {
    }
    public static function fromWire(mixed $wire): self
    {
        $value = TicketWire::record($wire, ['items', 'total', 'page', 'per_page']);
        $items = array_map(Ticket::fromWire(...), Wire::items($value['items']));
        $total = Wire::integer($value['total'], true);
        $page = Wire::integer($value['page']);
        $perPage = Wire::integer($value['per_page']);
        TicketWire::page($page, $perPage);
        if (count($items) > $perPage || count($items) > $total) {
            throw new InvalidArgumentException('Ticket pagination must retain the native counts.');
        }

        return new self($items, $total, $page, $perPage);
    }
}
