<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Components;

final readonly class ServiceSlaPause
{
    #[\Speakeasy\Serializer\Annotation\SerializedName('reason')]
    #[\Speakeasy\Serializer\Annotation\Type('string')]
    public string $reason;

    /** @var list<string> */
    #[\Speakeasy\Serializer\Annotation\SerializedName('clocks')]
    #[\Speakeasy\Serializer\Annotation\Type('array<string>')]
    public array $clocks;

    #[\Speakeasy\Serializer\Annotation\SerializedName('started_at')]
    #[\Speakeasy\Serializer\Annotation\Type('string')]
    public string $startedAt;

    #[\Speakeasy\Serializer\Annotation\SerializedName('ended_at')]
    #[\Speakeasy\Serializer\Annotation\Type('string|null')]
    public ?string $endedAt;

    /**
     * @param  list<string>  $clocks
     */
    public function __construct(
        string $reason,
        array $clocks,
        string $startedAt,
        ?string $endedAt,
    ) {
        $this->reason = $reason;
        $this->clocks = $clocks;
        $this->startedAt = $startedAt;
        $this->endedAt = $endedAt;
    }
}
