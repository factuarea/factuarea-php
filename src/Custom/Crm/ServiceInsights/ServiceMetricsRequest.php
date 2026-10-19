<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\ServiceInsights;

/** Required original cohort/clock and typed native filters; no authorization or snapshot is synthesized. */
final readonly class ServiceMetricsRequest
{
    public function __construct(public string $start, public string $end, public string $clockMode,
        public ServiceFilters $filters = new ServiceFilters())
    {
        ReadInput::selection($start, $end, $clockMode, $filters->query());
    }

    /** @return array<string,string> */
    public function query(): array
    {
        return ReadInput::selection($this->start, $this->end, $this->clockMode, $this->filters->query());
    }
}
