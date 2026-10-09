<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Components;

final readonly class ServiceSlaMessage
{
    #[\Speakeasy\Serializer\Annotation\SerializedName('id')]
    #[\Speakeasy\Serializer\Annotation\Type('string')]
    public string $id;

    #[\Speakeasy\Serializer\Annotation\SerializedName('kind')]
    #[\Speakeasy\Serializer\Annotation\Type('string')]
    public string $kind;

    #[\Speakeasy\Serializer\Annotation\SerializedName('occurred_at')]
    #[\Speakeasy\Serializer\Annotation\Type('string')]
    public string $occurredAt;

    public function __construct(
        string $id,
        string $kind,
        string $occurredAt,
    ) {
        $this->id = $id;
        $this->kind = $kind;
        $this->occurredAt = $occurredAt;
    }
}
