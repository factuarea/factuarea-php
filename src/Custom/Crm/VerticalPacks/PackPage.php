<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class PackPage extends WireModel
{
    public const SCHEMA = Schemas::PAGE;
    public const FIELDS = [
        'items' => ['property' => 'items', 'type' => ['items' => ['model' => Pack::class]]],
        'next_cursor' => ['property' => 'nextCursor', 'type' => ['scalar' => 'string']],
    ];

    /** @param list<Pack> $items */
    public function __construct(public readonly array $items, public readonly ?string $nextCursor)
    {
        $this->validate();
        WireCodec::unique(array_map(static fn (Pack $pack): string => $pack->id, $items));
    }
}
