<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Conversations;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class ConversationPage extends WireModel
{
    public const SCHEMA = Schemas::PAGE;

    public const FIELDS = ['items' => ['property' => 'items', 'type' => ['items' => ['model' => 'Factuarea\\Sdk\\Custom\\Crm\\Conversations\\Conversation']]], 'next_cursor' => ['property' => 'nextCursor', 'type' => ['scalar' => 'string']], 'total' => ['property' => 'total', 'type' => ['scalar' => 'int']], 'active_count' => ['property' => 'activeCount', 'type' => ['scalar' => 'int']], 'archived_count' => ['property' => 'archivedCount', 'type' => ['scalar' => 'int']], 'limit' => ['property' => 'limit', 'type' => ['scalar' => 'int']]];

    /**
     * @param  list<Conversation>  $items
     */
    public function __construct(
        public readonly array $items,
        public readonly ?string $nextCursor,
        public readonly int $total,
        public readonly int $activeCount,
        public readonly int $archivedCount,
        public readonly int $limit,
    ) {
        $this->validate();
        if (count($items) > $limit || $total < count($items) || $activeCount > $total || $archivedCount !== $total - $activeCount
            || ($nextCursor !== null && $items === [])) {
            throw new \InvalidArgumentException('Native Conversation page metadata is inconsistent.');
        }
        if ($nextCursor !== null) {
            Validation::cursor($nextCursor);
        }
        $seen = [];
        foreach ($items as $item) {
            Validation::unique($seen, $item->id);
        }
    }
}
