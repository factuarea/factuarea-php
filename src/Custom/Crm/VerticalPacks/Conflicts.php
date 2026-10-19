<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class Conflicts extends WireModel
{
    public const SCHEMA = Schemas::CONFLICTS;
    public const FIELDS = [
        'items' => ['property' => 'items', 'type' => ['items' => ['model' => PlanItem::class]]],
        'preview_hash' => ['property' => 'previewHash', 'type' => ['scalar' => 'string']],
    ];

    /** @param list<PlanItem> $items */
    public function __construct(public readonly array $items, public readonly string $previewHash)
    {
        $this->validate();
        WireCodec::unique(array_map(static fn (PlanItem $item): string => $item->id, $items));
        foreach ($items as $item) {
            if ($item->decision !== 'conflict') {
                throw new \InvalidArgumentException('The native conflicts query cannot substitute other plan items.');
            }
        }
    }
}
