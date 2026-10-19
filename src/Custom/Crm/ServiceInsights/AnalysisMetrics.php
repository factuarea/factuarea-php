<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\ServiceInsights;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class AnalysisMetrics extends WireModel
{
    public const SCHEMA = ReadInput::ANALYSIS;
    public const FIELDS = ['selection' => ['property' => 'selection', 'type' => ['model' => Selection::class]],
        'cohort_basis' => ['property' => 'cohortBasis', 'type' => ['scalar' => 'string']],
        'cycle_basis' => ['property' => 'cycleBasis', 'type' => ['scalar' => 'string']],
        'as_of' => ['property' => 'asOf', 'type' => ['scalar' => 'string']],
        'authorization_version' => ['property' => 'authorizationVersion', 'type' => ['scalar' => 'string']],
        'predicate_hash' => ['property' => 'predicateHash', 'type' => ['scalar' => 'string']],
        'cohort_count' => ['property' => 'cohortCount', 'type' => ['scalar' => 'int']],
        'metrics' => ['property' => 'metrics', 'type' => ['model' => MetricSet::class]]];

    public function __construct(public readonly Selection $selection, public readonly string $cohortBasis, public readonly string $cycleBasis, public readonly string $asOf, public readonly string $authorizationVersion, public readonly string $predicateHash, public readonly int $cohortCount, public readonly MetricSet $metrics)
    {
        $this->validate();
        ReadInput::instant($asOf);
    }
}
