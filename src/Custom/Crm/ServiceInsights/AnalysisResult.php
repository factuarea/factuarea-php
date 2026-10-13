<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\ServiceInsights;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class AnalysisResult extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'], 'properties' => ['data' => ReadInput::ANALYSIS]];
    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['model' => AnalysisMetrics::class]]];

    public function __construct(public readonly AnalysisMetrics $data)
    {
        $this->validate();
    }
}
