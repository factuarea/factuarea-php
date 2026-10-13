<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Conversations;

use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\Schema;

final readonly class GetConversationRequest
{
    public function __construct(public string $conversationId, public string|Omitted $cursor = Omitted::Value, public int|Omitted $limit = Omitted::Value)
    {
        Schema::validate(Schemas::UUID, $conversationId);
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
        return new self($this->conversationId, $cursor, $this->limit);
    }
}
