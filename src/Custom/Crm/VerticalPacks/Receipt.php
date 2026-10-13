<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class Receipt extends WireModel
{
    public const SCHEMA = Schemas::RECEIPT;
    public const FIELDS = [
        'id' => ['property' => 'id', 'type' => ['scalar' => 'string']],
        'operation' => ['property' => 'operation', 'type' => ['scalar' => 'string']],
        'target_id' => ['property' => 'targetId', 'type' => ['scalar' => 'string']],
        'confirmed' => ['property' => 'confirmed', 'type' => ['scalar' => 'bool']],
        'installation' => ['property' => 'installation', 'type' => ['model' => Installation::class]],
        'items' => ['property' => 'items', 'type' => ['items' => ['model' => ReceiptItem::class]]],
    ];

    /** @param list<ReceiptItem> $items */
    public function __construct(public readonly string $id, public readonly string $operation, public readonly string $targetId, public readonly bool $confirmed, public readonly Installation $installation, public readonly array $items)
    {
        $this->validate();
        WireCodec::unique(array_map(static fn (ReceiptItem $item): string => $item->itemId, $items));
        if ($installation->receiptId !== $id || ($operation === 'undo' && $targetId !== $installation->id)) {
            throw new \InvalidArgumentException('The original receipt must retain its native installation linkage.');
        }
    }
}
