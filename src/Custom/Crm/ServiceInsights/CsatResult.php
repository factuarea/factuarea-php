<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\ServiceInsights;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class CsatResult extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'], 'properties' => ['data' => ReadInput::CSAT]];
    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['model' => CsatMetrics::class]]];

    public function __construct(public readonly CsatMetrics $data)
    {
        $this->validate();
    }
}
