<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\ServiceInsights;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class DrillItem extends WireModel
{
    public const SCHEMA = ReadInput::DRILL_ITEM;
    public const FIELDS = ['id' => ['property' => 'id', 'type' => ['scalar' => 'string']],
        'version' => ['property' => 'version', 'type' => ['scalar' => 'int']],
        'cycle' => ['property' => 'cycle', 'type' => ['scalar' => 'int']],
        'label' => ['property' => 'label', 'type' => ['scalar' => 'string']],
        'status' => ['property' => 'status', 'type' => ['scalar' => 'string']],
        'sample_count' => ['property' => 'sampleCount', 'type' => ['scalar' => 'int']]];

    public function __construct(public readonly string $id, public readonly int $version, public readonly int $cycle, public readonly ?string $label, public readonly ?string $status, public readonly int $sampleCount)
    {
        $this->validate();
    }
}
