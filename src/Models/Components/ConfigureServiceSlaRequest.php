<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Components;

final readonly class ConfigureServiceSlaRequest
{
    #[\Speakeasy\Serializer\Annotation\SerializedName('cycle_id')]
    #[\Speakeasy\Serializer\Annotation\Type('string|null')]
    public ?string $cycleId;

    #[\Speakeasy\Serializer\Annotation\SerializedName('expected_version')]
    #[\Speakeasy\Serializer\Annotation\Type('int|null')]
    public ?int $expectedVersion;

    #[\Speakeasy\Serializer\Annotation\SerializedName('policy_id')]
    #[\Speakeasy\Serializer\Annotation\Type('string|null')]
    public ?string $policyId;

    #[\Speakeasy\Serializer\Annotation\SerializedName('policy_version')]
    #[\Speakeasy\Serializer\Annotation\Type('int')]
    public int $policyVersion;

    #[\Speakeasy\Serializer\Annotation\SerializedName('first_response_minutes')]
    #[\Speakeasy\Serializer\Annotation\Type('int')]
    public int $firstResponseMinutes;

    #[\Speakeasy\Serializer\Annotation\SerializedName('first_response_risk_minutes')]
    #[\Speakeasy\Serializer\Annotation\Type('int|null')]
    public ?int $firstResponseRiskMinutes;

    #[\Speakeasy\Serializer\Annotation\SerializedName('next_response_minutes')]
    #[\Speakeasy\Serializer\Annotation\Type('int')]
    public int $nextResponseMinutes;

    #[\Speakeasy\Serializer\Annotation\SerializedName('next_response_risk_minutes')]
    #[\Speakeasy\Serializer\Annotation\Type('int|null')]
    public ?int $nextResponseRiskMinutes;

    #[\Speakeasy\Serializer\Annotation\SerializedName('resolution_minutes')]
    #[\Speakeasy\Serializer\Annotation\Type('int')]
    public int $resolutionMinutes;

    #[\Speakeasy\Serializer\Annotation\SerializedName('resolution_risk_minutes')]
    #[\Speakeasy\Serializer\Annotation\Type('int|null')]
    public ?int $resolutionRiskMinutes;

    #[\Speakeasy\Serializer\Annotation\SerializedName('pause_first_response')]
    #[\Speakeasy\Serializer\Annotation\Type('bool')]
    public bool $pauseFirstResponse;

    #[\Speakeasy\Serializer\Annotation\SerializedName('pause_next_response')]
    #[\Speakeasy\Serializer\Annotation\Type('bool')]
    public bool $pauseNextResponse;

    #[\Speakeasy\Serializer\Annotation\SerializedName('pause_resolution')]
    #[\Speakeasy\Serializer\Annotation\Type('bool')]
    public bool $pauseResolution;

    #[\Speakeasy\Serializer\Annotation\SerializedName('calendar_id')]
    #[\Speakeasy\Serializer\Annotation\Type('string|null')]
    public ?string $calendarId;

    #[\Speakeasy\Serializer\Annotation\SerializedName('calendar_version')]
    #[\Speakeasy\Serializer\Annotation\Type('int|null')]
    public ?int $calendarVersion;

    public function __construct(
        ?string $cycleId,
        ?int $expectedVersion,
        ?string $policyId,
        int $policyVersion,
        int $firstResponseMinutes,
        ?int $firstResponseRiskMinutes,
        int $nextResponseMinutes,
        ?int $nextResponseRiskMinutes,
        int $resolutionMinutes,
        ?int $resolutionRiskMinutes,
        bool $pauseFirstResponse,
        bool $pauseNextResponse,
        bool $pauseResolution,
        ?string $calendarId,
        ?int $calendarVersion,
    ) {
        $this->cycleId = $cycleId;
        $this->expectedVersion = $expectedVersion;
        $this->policyId = $policyId;
        $this->policyVersion = $policyVersion;
        $this->firstResponseMinutes = $firstResponseMinutes;
        $this->firstResponseRiskMinutes = $firstResponseRiskMinutes;
        $this->nextResponseMinutes = $nextResponseMinutes;
        $this->nextResponseRiskMinutes = $nextResponseRiskMinutes;
        $this->resolutionMinutes = $resolutionMinutes;
        $this->resolutionRiskMinutes = $resolutionRiskMinutes;
        $this->pauseFirstResponse = $pauseFirstResponse;
        $this->pauseNextResponse = $pauseNextResponse;
        $this->pauseResolution = $pauseResolution;
        $this->calendarId = $calendarId;
        $this->calendarVersion = $calendarVersion;
    }
}
