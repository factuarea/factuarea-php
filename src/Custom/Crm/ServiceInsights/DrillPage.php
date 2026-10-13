<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\ServiceInsights;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class DrillPage extends WireModel
{
    public const SCHEMA = ReadInput::DRILL;
    public const FIELDS = ['metric' => ['property' => 'metric', 'type' => ['scalar' => 'string']],
        'as_of' => ['property' => 'asOf', 'type' => ['scalar' => 'string']],
        'authorization_version' => ['property' => 'authorizationVersion', 'type' => ['scalar' => 'string']],
        'predicate_hash' => ['property' => 'predicateHash', 'type' => ['scalar' => 'string']],
        'total' => ['property' => 'total', 'type' => ['scalar' => 'int']],
        'page' => ['property' => 'page', 'type' => ['scalar' => 'int']],
        'per_page' => ['property' => 'perPage', 'type' => ['scalar' => 'int']],
        'items' => ['property' => 'items', 'type' => ['items' => ['model' => DrillItem::class]]]];

    /** @param list<DrillItem> $items */
    public function __construct(public readonly string $metric, public readonly string $asOf, public readonly string $authorizationVersion, public readonly string $predicateHash, public readonly int $total, public readonly int $page, public readonly int $perPage, public readonly array $items)
    {
        $this->validate();
        ReadInput::instant($asOf);
        if (count($items) > $perPage || count($items) > $total) {
            throw new \InvalidArgumentException('A native drill page cannot exceed its admitted pagination.');
        }
    }
}
