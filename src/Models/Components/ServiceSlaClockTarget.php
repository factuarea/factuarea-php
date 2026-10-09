<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Components;

final readonly class ServiceSlaClockTarget
{
    #[\Speakeasy\Serializer\Annotation\SerializedName('minutes')]
    #[\Speakeasy\Serializer\Annotation\Type('int')]
    public int $minutes;

    #[\Speakeasy\Serializer\Annotation\SerializedName('at_risk_minutes')]
    #[\Speakeasy\Serializer\Annotation\Type('int|null')]
    public ?int $atRiskMinutes;

    public function __construct(
        int $minutes,
        ?int $atRiskMinutes,
    ) {
        $this->minutes = $minutes;
        $this->atRiskMinutes = $atRiskMinutes;
    }
}
