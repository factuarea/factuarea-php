<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Components;

final readonly class ServiceSlaResponseHistory
{
    #[\Speakeasy\Serializer\Annotation\SerializedName('clock')]
    #[\Speakeasy\Serializer\Annotation\Type('\Factuarea\Sdk\Models\Components\ServiceSlaStoredClock')]
    public ServiceSlaStoredClock $clock;

    #[\Speakeasy\Serializer\Annotation\SerializedName('configuration')]
    #[\Speakeasy\Serializer\Annotation\Type('\Factuarea\Sdk\Models\Components\ServiceSlaConfiguration')]
    public ServiceSlaConfiguration $configuration;

    public function __construct(
        ServiceSlaStoredClock $clock,
        ServiceSlaConfiguration $configuration,
    ) {
        $this->clock = $clock;
        $this->configuration = $configuration;
    }
}
