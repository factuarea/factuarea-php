<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Components;

final readonly class ServiceSlaStoredClocks
{
    #[\Speakeasy\Serializer\Annotation\SerializedName('first_response')]
    #[\Speakeasy\Serializer\Annotation\Type('\Factuarea\Sdk\Models\Components\ServiceSlaStoredClock')]
    public ServiceSlaStoredClock $firstResponse;

    #[\Speakeasy\Serializer\Annotation\SerializedName('next_response')]
    #[\Speakeasy\Serializer\Annotation\Type('\Factuarea\Sdk\Models\Components\ServiceSlaStoredClock')]
    public ServiceSlaStoredClock $nextResponse;

    #[\Speakeasy\Serializer\Annotation\SerializedName('resolution')]
    #[\Speakeasy\Serializer\Annotation\Type('\Factuarea\Sdk\Models\Components\ServiceSlaStoredClock')]
    public ServiceSlaStoredClock $resolution;

    public function __construct(
        ServiceSlaStoredClock $firstResponse,
        ServiceSlaStoredClock $nextResponse,
        ServiceSlaStoredClock $resolution,
    ) {
        $this->firstResponse = $firstResponse;
        $this->nextResponse = $nextResponse;
        $this->resolution = $resolution;
    }
}
