<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\ServiceInsights;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class Metric extends WireModel
{
    public const SCHEMA = ReadInput::ANALYTIC_METRIC;
    public const FIELDS = ['eligible_count' => ['property' => 'eligibleCount', 'type' => ['scalar' => 'int']],
        'sample_count' => ['property' => 'sampleCount', 'type' => ['scalar' => 'int']],
        'contributor_count' => ['property' => 'contributorCount', 'type' => ['scalar' => 'int']],
        'mean_microseconds' => ['property' => 'meanMicroseconds', 'type' => ['scalar' => 'string']],
        'median_microseconds' => ['property' => 'medianMicroseconds', 'type' => ['scalar' => 'string']],
        'value_count' => ['property' => 'valueCount', 'type' => ['scalar' => 'int']],
        'compliant_count' => ['property' => 'compliantCount', 'type' => ['scalar' => 'int']],
        'missing_count' => ['property' => 'missingCount', 'type' => ['scalar' => 'int']],
        'masked' => ['property' => 'masked', 'type' => ['scalar' => 'bool']]];

    public function __construct(public readonly int $eligibleCount, public readonly ?int $sampleCount, public readonly ?int $contributorCount, public readonly ?string $meanMicroseconds, public readonly ?string $medianMicroseconds, public readonly ?int $valueCount, public readonly ?int $compliantCount, public readonly ?int $missingCount, public readonly bool $masked)
    {
        $this->validate();
        if ($masked && array_filter([$sampleCount, $contributorCount, $meanMicroseconds, $medianMicroseconds, $valueCount, $compliantCount, $missingCount], static fn (mixed $value): bool => $value !== null) !== []) {
            throw new \InvalidArgumentException('Masked native metric values must remain null.');
        }
    }
}
