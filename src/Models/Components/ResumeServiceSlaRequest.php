<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Components;

final readonly class ResumeServiceSlaRequest
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

    public function __construct(
        string $cycleId,
        int $expectedVersion,
        string $reason,
    ) {
        $this->cycleId = $cycleId;
        $this->expectedVersion = $expectedVersion;
        $this->reason = $reason;
    }
}
