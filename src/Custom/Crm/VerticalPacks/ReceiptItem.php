<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class ReceiptItem extends WireModel
{
    public const SCHEMA = Schemas::RECEIPT_ITEM;
    public const FIELDS = [
        'item_id' => ['property' => 'itemId', 'type' => ['scalar' => 'string']],
        'outcome' => ['property' => 'outcome', 'type' => ['scalar' => 'string']],
        'target_id' => ['property' => 'targetId', 'type' => ['scalar' => 'string']],
        'version' => ['property' => 'version', 'type' => ['scalar' => 'int']],
    ];

    public function __construct(public readonly string $itemId, public readonly string $outcome, public readonly ?string $targetId, public readonly ?int $version)
    {
        $this->validate();
        if (($targetId === null) !== ($version === null)) {
            throw new \InvalidArgumentException('Original target identity and version nullability must remain paired.');
        }
    }
}
