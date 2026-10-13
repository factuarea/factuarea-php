<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\ServiceInsights;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class MetricSet extends WireModel
{
    public const SCHEMA = ReadInput::METRIC_SET;
    public const FIELDS = ['frt' => ['property' => 'frt', 'type' => ['model' => Metric::class]],
        'nrt' => ['property' => 'nrt', 'type' => ['model' => Metric::class]],
        'mttr' => ['property' => 'mttr', 'type' => ['model' => Metric::class]],
        'backlog' => ['property' => 'backlog', 'type' => ['model' => Metric::class]],
        'sla_compliance' => ['property' => 'slaCompliance', 'type' => ['model' => Metric::class]]];

    public function __construct(public readonly Metric $frt, public readonly Metric $nrt, public readonly Metric $mttr, public readonly Metric $backlog, public readonly Metric $slaCompliance)
    {
        $this->validate();
    }
}
