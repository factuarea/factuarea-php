<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Components;

final readonly class ServiceSlaStoredClock
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

    #[\Speakeasy\Serializer\Annotation\SerializedName('message_id')]
    #[\Speakeasy\Serializer\Annotation\Type('string|null')]
    public ?string $messageId;

    #[\Speakeasy\Serializer\Annotation\SerializedName('configuration')]
    #[\Speakeasy\Serializer\Annotation\Type('\Factuarea\Sdk\Models\Components\ServiceSlaConfiguration|null')]
    public ?ServiceSlaConfiguration $configuration;

    public function __construct(
        ?string $startedAt,
        ?string $stoppedAt,
        bool $satisfied,
        ?string $messageId,
        ?ServiceSlaConfiguration $configuration,
    ) {
        $this->startedAt = $startedAt;
        $this->stoppedAt = $stoppedAt;
        $this->satisfied = $satisfied;
        $this->messageId = $messageId;
        $this->configuration = $configuration;
    }
}
