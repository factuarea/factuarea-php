<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\ServiceInsights;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class DrillResult extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'], 'properties' => ['data' => ReadInput::DRILL]];
    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['model' => DrillPage::class]]];

    public function __construct(public readonly DrillPage $data)
    {
        $this->validate();
    }
}
