<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Components;

final readonly class ServiceSlaConfiguration
{
    #[\Speakeasy\Serializer\Annotation\SerializedName('effective_at')]
    #[\Speakeasy\Serializer\Annotation\Type('string')]
    public string $effectiveAt;

    #[\Speakeasy\Serializer\Annotation\SerializedName('targets')]
    #[\Speakeasy\Serializer\Annotation\Type('\Factuarea\Sdk\Models\Components\ServiceSlaTargets')]
    public ServiceSlaTargets $targets;

    #[\Speakeasy\Serializer\Annotation\SerializedName('calendar')]
    #[\Speakeasy\Serializer\Annotation\Type('\Factuarea\Sdk\Models\Components\ServiceCalendar|null')]
    public ?ServiceCalendar $calendar;

    public function __construct(
        string $effectiveAt,
        ServiceSlaTargets $targets,
        ?ServiceCalendar $calendar,
    ) {
        $this->effectiveAt = $effectiveAt;
        $this->targets = $targets;
        $this->calendar = $calendar;
    }
}
