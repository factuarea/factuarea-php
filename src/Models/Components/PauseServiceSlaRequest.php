<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Components;

final readonly class PauseServiceSlaRequest
{
    #[\Speakeasy\Serializer\Annotation\SerializedName('cycle_id')]
    #[\Speakeasy\Serializer\Annotation\Type('string')]
    public string $cycleId;

    #[\Speakeasy\Serializer\Annotation\SerializedName('expected_version')]
    #[\Speakeasy\Serializer\Annotation\Type('int')]
    public int $expectedVersion;

    #[\Speakeasy\Serializer\Annotation\SerializedName('reason')]
    #[\Speakeasy\Serializer\Annotation\Type('string')]
    public string $reason;

    #[\Speakeasy\Serializer\Annotation\SerializedName('first_response')]
    #[\Speakeasy\Serializer\Annotation\Type('bool')]
    public bool $firstResponse;

    #[\Speakeasy\Serializer\Annotation\SerializedName('next_response')]
    #[\Speakeasy\Serializer\Annotation\Type('bool')]
    public bool $nextResponse;

    #[\Speakeasy\Serializer\Annotation\SerializedName('resolution')]
    #[\Speakeasy\Serializer\Annotation\Type('bool')]
    public bool $resolution;

    public function __construct(
        string $cycleId,
        int $expectedVersion,
        string $reason,
        bool $firstResponse,
        bool $nextResponse,
        bool $resolution,
    ) {
        $this->cycleId = $cycleId;
        $this->expectedVersion = $expectedVersion;
        $this->reason = $reason;
        $this->firstResponse = $firstResponse;
        $this->nextResponse = $nextResponse;
        $this->resolution = $resolution;
    }
}
