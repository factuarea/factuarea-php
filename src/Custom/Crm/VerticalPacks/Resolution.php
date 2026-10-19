<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class Resolution extends WireModel
{
    public const SCHEMA = Schemas::RESOLUTION;
    public const FIELDS = [
        'item_id' => ['property' => 'itemId', 'type' => ['scalar' => 'string']],
        'resolution' => ['property' => 'resolution', 'type' => ['scalar' => 'string']],
    ];

    public function __construct(public readonly string $itemId, public readonly string $resolution)
    {
        $this->validate();
    }
}
