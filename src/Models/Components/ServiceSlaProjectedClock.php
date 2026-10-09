<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Components;

final readonly class ServiceSlaProjectedClock
{
    #[\Speakeasy\Serializer\Annotation\SerializedName('started_at')]
    #[\Speakeasy\Serializer\Annotation\Type('string|null')]
    public ?string $startedAt;

    #[\Speakeasy\Serializer\Annotation\SerializedName('stopped_at')]
    #[\Speakeasy\Serializer\Annotation\Type('string|null')]
    public ?string $stoppedAt;

    #[\Speakeasy\Serializer\Annotation\SerializedName('satisfied')]
    #[\Speakeasy\Serializer\Annotation\Type('bool')]
    public bool $satisfied;

    #[\Speakeasy\Serializer\Annotation\SerializedName('paused')]
    #[\Speakeasy\Serializer\Annotation\Type('bool')]
    public bool $paused;

    #[\Speakeasy\Serializer\Annotation\SerializedName('elapsed_microseconds')]
    #[\Speakeasy\Serializer\Annotation\Type('int')]
    public int $elapsedMicroseconds;

    #[\Speakeasy\Serializer\Annotation\SerializedName('target_minutes')]
    #[\Speakeasy\Serializer\Annotation\Type('int')]
    public int $targetMinutes;

    #[\Speakeasy\Serializer\Annotation\SerializedName('at_risk_minutes')]
    #[\Speakeasy\Serializer\Annotation\Type('int|null')]
    public ?int $atRiskMinutes;

    #[\Speakeasy\Serializer\Annotation\SerializedName('policy_id')]
    #[\Speakeasy\Serializer\Annotation\Type('string')]
    public string $policyId;

    #[\Speakeasy\Serializer\Annotation\SerializedName('policy_version')]
    #[\Speakeasy\Serializer\Annotation\Type('int')]
    public int $policyVersion;

    #[\Speakeasy\Serializer\Annotation\SerializedName('elapsed_calendar_version')]
    #[\Speakeasy\Serializer\Annotation\Type('int|null')]
    public ?int $elapsedCalendarVersion;

    #[\Speakeasy\Serializer\Annotation\SerializedName('deadline')]
    #[\Speakeasy\Serializer\Annotation\Type('string|null')]
    public ?string $deadline;

    #[\Speakeasy\Serializer\Annotation\SerializedName('deadline_is_provisional')]
    #[\Speakeasy\Serializer\Annotation\Type('bool')]
    public bool $deadlineIsProvisional;

    #[\Speakeasy\Serializer\Annotation\SerializedName('state')]
    #[\Speakeasy\Serializer\Annotation\Type('string')]
    public string $state;

    public function __construct(
        ?string $startedAt,
        ?string $stoppedAt,
        bool $satisfied,
        bool $paused,
        int $elapsedMicroseconds,
        int $targetMinutes,
        ?int $atRiskMinutes,
        string $policyId,
        int $policyVersion,
        ?int $elapsedCalendarVersion,
        ?string $deadline,
        bool $deadlineIsProvisional,
        string $state,
    ) {
        $this->startedAt = $startedAt;
        $this->stoppedAt = $stoppedAt;
        $this->satisfied = $satisfied;
        $this->paused = $paused;
        $this->elapsedMicroseconds = $elapsedMicroseconds;
        $this->targetMinutes = $targetMinutes;
        $this->atRiskMinutes = $atRiskMinutes;
        $this->policyId = $policyId;
        $this->policyVersion = $policyVersion;
        $this->elapsedCalendarVersion = $elapsedCalendarVersion;
        $this->deadline = $deadline;
        $this->deadlineIsProvisional = $deadlineIsProvisional;
        $this->state = $state;
    }
}
