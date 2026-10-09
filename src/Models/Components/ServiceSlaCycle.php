<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Components;

final readonly class ServiceSlaCycle
{
    #[\Speakeasy\Serializer\Annotation\SerializedName('id')]
    #[\Speakeasy\Serializer\Annotation\Type('string')]
    public string $id;

    #[\Speakeasy\Serializer\Annotation\SerializedName('subject_id')]
    #[\Speakeasy\Serializer\Annotation\Type('string')]
    public string $subjectId;

    #[\Speakeasy\Serializer\Annotation\SerializedName('version')]
    #[\Speakeasy\Serializer\Annotation\Type('int')]
    public int $version;

    #[\Speakeasy\Serializer\Annotation\SerializedName('cycle_number')]
    #[\Speakeasy\Serializer\Annotation\Type('int')]
    public int $cycleNumber;

    #[\Speakeasy\Serializer\Annotation\SerializedName('previous_cycle_id')]
    #[\Speakeasy\Serializer\Annotation\Type('string|null')]
    public ?string $previousCycleId;

    #[\Speakeasy\Serializer\Annotation\SerializedName('opened_at')]
    #[\Speakeasy\Serializer\Annotation\Type('string')]
    public string $openedAt;

    #[\Speakeasy\Serializer\Annotation\SerializedName('observed_at')]
    #[\Speakeasy\Serializer\Annotation\Type('string')]
    public string $observedAt;

    #[\Speakeasy\Serializer\Annotation\SerializedName('terminal_at')]
    #[\Speakeasy\Serializer\Annotation\Type('string|null')]
    public ?string $terminalAt;

    #[\Speakeasy\Serializer\Annotation\SerializedName('clocks')]
    #[\Speakeasy\Serializer\Annotation\Type('\Factuarea\Sdk\Models\Components\ServiceSlaStoredClocks')]
    public ServiceSlaStoredClocks $clocks;

    /** @var list<\Factuarea\Sdk\Models\Components\ServiceSlaResponseHistory> */
    #[\Speakeasy\Serializer\Annotation\SerializedName('response_history')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\Factuarea\Sdk\Models\Components\ServiceSlaResponseHistory>')]
    public array $responseHistory;

    /** @var list<\Factuarea\Sdk\Models\Components\ServiceSlaConfiguration> */
    #[\Speakeasy\Serializer\Annotation\SerializedName('configurations')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\Factuarea\Sdk\Models\Components\ServiceSlaConfiguration>')]
    public array $configurations;

    /** @var list<\Factuarea\Sdk\Models\Components\ServiceSlaPause> */
    #[\Speakeasy\Serializer\Annotation\SerializedName('pauses')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\Factuarea\Sdk\Models\Components\ServiceSlaPause>')]
    public array $pauses;

    /** @var list<\Factuarea\Sdk\Models\Components\ServiceSlaMessage> */
    #[\Speakeasy\Serializer\Annotation\SerializedName('messages')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\Factuarea\Sdk\Models\Components\ServiceSlaMessage>')]
    public array $messages;

    /**
     * @param  list<\Factuarea\Sdk\Models\Components\ServiceSlaResponseHistory>  $responseHistory
     * @param  list<\Factuarea\Sdk\Models\Components\ServiceSlaConfiguration>  $configurations
     * @param  list<\Factuarea\Sdk\Models\Components\ServiceSlaPause>  $pauses
     * @param  list<\Factuarea\Sdk\Models\Components\ServiceSlaMessage>  $messages
     */
    public function __construct(
        string $id,
        string $subjectId,
        int $version,
        int $cycleNumber,
        ?string $previousCycleId,
        string $openedAt,
        string $observedAt,
        ?string $terminalAt,
        ServiceSlaStoredClocks $clocks,
        array $responseHistory,
        array $configurations,
        array $pauses,
        array $messages,
    ) {
        $this->id = $id;
        $this->subjectId = $subjectId;
        $this->version = $version;
        $this->cycleNumber = $cycleNumber;
        $this->previousCycleId = $previousCycleId;
        $this->openedAt = $openedAt;
        $this->observedAt = $observedAt;
        $this->terminalAt = $terminalAt;
        $this->clocks = $clocks;
        $this->responseHistory = $responseHistory;
        $this->configurations = $configurations;
        $this->pauses = $pauses;
        $this->messages = $messages;
    }
}
