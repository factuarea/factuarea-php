<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\ServiceInsights;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class CsatMetrics extends WireModel
{
    public const SCHEMA = ReadInput::CSAT;
    public const FIELDS = ['metric' => ['property' => 'metric', 'type' => ['scalar' => 'string']],
        'metric_version' => ['property' => 'metricVersion', 'type' => ['scalar' => 'int']],
        'cohort_start' => ['property' => 'cohortStart', 'type' => ['scalar' => 'string']],
        'cohort_end' => ['property' => 'cohortEnd', 'type' => ['scalar' => 'string']],
        'cohort_basis' => ['property' => 'cohortBasis', 'type' => ['scalar' => 'string']],
        'cycle_policy' => ['property' => 'cyclePolicy', 'type' => ['scalar' => 'string']],
        'clock_mode' => ['property' => 'clockMode', 'type' => ['scalar' => 'string']],
        'invited' => ['property' => 'invited', 'type' => ['scalar' => 'int']],
        'answered' => ['property' => 'answered', 'type' => ['scalar' => 'int']],
        'mean' => ['property' => 'mean', 'type' => ['scalar' => 'string']],
        'median' => ['property' => 'median', 'type' => ['scalar' => 'string']],
        'masked_fields' => ['property' => 'maskedFields', 'type' => ['items' => ['scalar' => 'string']]],
        'authorization_version' => ['property' => 'authorizationVersion', 'type' => ['scalar' => 'string']],
        'predicate_hash' => ['property' => 'predicateHash', 'type' => ['scalar' => 'string']]];

    /** @param list<string> $maskedFields */
    public function __construct(public readonly string $metric, public readonly int $metricVersion, public readonly string $cohortStart, public readonly string $cohortEnd, public readonly string $cohortBasis, public readonly string $cyclePolicy, public readonly string $clockMode, public readonly int $invited, public readonly ?int $answered, public readonly ?string $mean, public readonly ?string $median, public readonly array $maskedFields, public readonly string $authorizationVersion, public readonly string $predicateHash)
    {
        $this->validate();
        ReadInput::cohort($cohortStart, $cohortEnd);
        if ((in_array('answered_at', $maskedFields, true) && $answered !== null)
            || (in_array('rating', $maskedFields, true) && ($mean !== null || $median !== null))) {
            throw new \InvalidArgumentException('Masked CSAT denominators and ratings must remain null.');
        }
    }
}
