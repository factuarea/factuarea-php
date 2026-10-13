<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Opportunities;

use Factuarea\Sdk\Custom\Crm\Omitted;

/** Closed GET query. Omission retains native defaults; the current server sets the maximum page size. */
final readonly class ListOpportunitiesRequest
{
    public function __construct(
        public Filters|Omitted $filters = Omitted::Value,
        public string|Omitted $cursor = Omitted::Value,
        public int|Omitted $limit = Omitted::Value,
    ) {
        if ($cursor !== Omitted::Value) {
            Validation::cursor($cursor);
        }
        if ($limit !== Omitted::Value && $limit < 1) {
            throw new \InvalidArgumentException('Opportunity page limit must be positive.');
        }
    }

    /** @return array<string,scalar|null> */
    public function query(): array
    {
        $query = [];
        if ($this->filters !== Omitted::Value) {
            $query['filters'] = $this->filters->toJson();
        }
        if ($this->cursor !== Omitted::Value) {
            $query['cursor'] = $this->cursor;
        }
        if ($this->limit !== Omitted::Value) {
            $query['limit'] = $this->limit;
        }

        return $query;
    }

    public function withCursor(string $cursor): self
    {
        return new self($this->filters, $cursor, $this->limit);
    }
}
