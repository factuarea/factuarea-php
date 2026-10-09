<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Components;

final readonly class ServiceSlaTargets
{
    #[\Speakeasy\Serializer\Annotation\SerializedName('policy_id')]
    #[\Speakeasy\Serializer\Annotation\Type('string')]
    public string $policyId;

    #[\Speakeasy\Serializer\Annotation\SerializedName('version')]
    #[\Speakeasy\Serializer\Annotation\Type('int')]
    public int $version;

    #[\Speakeasy\Serializer\Annotation\SerializedName('clocks')]
    #[\Speakeasy\Serializer\Annotation\Type('\Factuarea\Sdk\Models\Components\ServiceSlaClockTargets')]
    public ServiceSlaClockTargets $clocks;

    /** @var list<string> */
    #[\Speakeasy\Serializer\Annotation\SerializedName('pause_on_waiting_customer')]
    #[\Speakeasy\Serializer\Annotation\Type('array<string>')]
    public array $pauseOnWaitingCustomer;

    /**
     * @param  list<string>  $pauseOnWaitingCustomer
     */
    public function __construct(
        string $policyId,
        int $version,
        ServiceSlaClockTargets $clocks,
        array $pauseOnWaitingCustomer,
    ) {
        $this->policyId = $policyId;
        $this->version = $version;
        $this->clocks = $clocks;
        $this->pauseOnWaitingCustomer = $pauseOnWaitingCustomer;
    }
}
