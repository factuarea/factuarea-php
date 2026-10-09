<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Components;

final readonly class ServiceSlaClockTargets
{
    #[\Speakeasy\Serializer\Annotation\SerializedName('first_response')]
    #[\Speakeasy\Serializer\Annotation\Type('\Factuarea\Sdk\Models\Components\ServiceSlaClockTarget')]
    public ServiceSlaClockTarget $firstResponse;

    #[\Speakeasy\Serializer\Annotation\SerializedName('next_response')]
    #[\Speakeasy\Serializer\Annotation\Type('\Factuarea\Sdk\Models\Components\ServiceSlaClockTarget')]
    public ServiceSlaClockTarget $nextResponse;

    #[\Speakeasy\Serializer\Annotation\SerializedName('resolution')]
    #[\Speakeasy\Serializer\Annotation\Type('\Factuarea\Sdk\Models\Components\ServiceSlaClockTarget')]
    public ServiceSlaClockTarget $resolution;

    public function __construct(
        ServiceSlaClockTarget $firstResponse,
        ServiceSlaClockTarget $nextResponse,
        ServiceSlaClockTarget $resolution,
    ) {
        $this->firstResponse = $firstResponse;
        $this->nextResponse = $nextResponse;
        $this->resolution = $resolution;
    }
}
