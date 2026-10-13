<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Conversations;

use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\Schema;

/** An omitted query preserves native defaults; an explicitly supplied raw JSON filter keeps its exact bytes. */
final readonly class ListConversationsRequest
{
    public function __construct(public Filters|string|Omitted $filters = Omitted::Value, public string|Omitted $cursor = Omitted::Value,
        public int|Omitted $limit = Omitted::Value)
    {
        if (is_string($filters)) {
            Validation::rawFilters($filters);
        }
        if ($cursor !== Omitted::Value) {
            Validation::cursor($cursor);
        }
        if ($limit !== Omitted::Value) {
            Schema::validate(['type' => 'integer', 'minimum' => 1, 'maximum' => 100], $limit);
        }
    }

    /** @return array<string,scalar|null> */
    public function query(): array
    {
        $query = [];
        if ($this->filters !== Omitted::Value) {
            $query['filters'] = is_string($this->filters) ? $this->filters : $this->filters->toJson();
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
