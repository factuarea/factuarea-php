<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\CustomerService\Request;

use Factuarea\Sdk\Custom\Crm\CustomerService\TicketWire;
use InvalidArgumentException;

final readonly class TicketFilters
{
    public function __construct(public int $page = 1, public int $perPage = 50, public ?string $status = null, public ?string $sort = null, public ?string $direction = null)
    {
        TicketWire::page($page, $perPage);
        if (($status !== null && !in_array($status, ['new', 'assigned', 'pending', 'closed', 'rejected', 'duplicate'], true))
            || ($sort !== null && !in_array($sort, ['id', 'version', 'title', 'status', 'priority', 'created_at', 'updated_at'], true))
            || ($direction !== null && !in_array($direction, ['asc', 'desc'], true))) {
            throw new InvalidArgumentException('Unsupported native ticket filter.');
        }
    }
    /** @return array<string,int|string> */
    public function toArray(): array
    {
        $query = ['page' => $this->page, 'per_page' => $this->perPage];
        foreach (['status' => $this->status, 'sort' => $this->sort, 'direction' => $this->direction] as $key => $value) {
            if ($value !== null) {
                $query[$key] = $value;
            }
        }

        return $query;
    }
    public function atPage(int $page): self
    {
        return new self($page, $this->perPage, $this->status, $this->sort, $this->direction);
    }
}
