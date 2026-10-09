<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Components;

final readonly class ServiceSlaProjectedClocks
{
    #[\Speakeasy\Serializer\Annotation\SerializedName('first_response')]
    #[\Speakeasy\Serializer\Annotation\Type('\Factuarea\Sdk\Models\Components\ServiceSlaProjectedClock')]
    public ServiceSlaProjectedClock $firstResponse;

    #[\Speakeasy\Serializer\Annotation\SerializedName('next_response')]
    #[\Speakeasy\Serializer\Annotation\Type('\Factuarea\Sdk\Models\Components\ServiceSlaProjectedClock')]
    public ServiceSlaProjectedClock $nextResponse;

    #[\Speakeasy\Serializer\Annotation\SerializedName('resolution')]
    #[\Speakeasy\Serializer\Annotation\Type('\Factuarea\Sdk\Models\Components\ServiceSlaProjectedClock')]
    public ServiceSlaProjectedClock $resolution;

    public function __construct(
        ServiceSlaProjectedClock $firstResponse,
        ServiceSlaProjectedClock $nextResponse,
        ServiceSlaProjectedClock $resolution,
    ) {
        $this->firstResponse = $firstResponse;
        $this->nextResponse = $nextResponse;
        $this->resolution = $resolution;
    }
}
