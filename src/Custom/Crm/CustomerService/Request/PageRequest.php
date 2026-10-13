<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\CustomerService\Request;

use Factuarea\Sdk\Custom\Crm\CustomerService\TicketWire;

final readonly class PageRequest
{
    public function __construct(public int $page = 1, public int $perPage = 50)
    {
        TicketWire::page($page, $perPage);
    }
    /** @return array{page:int,per_page:int} */
    public function toArray(): array
    {
        return ['page' => $this->page, 'per_page' => $this->perPage];
    }
}
