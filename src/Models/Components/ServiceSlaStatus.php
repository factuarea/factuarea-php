<?php

/** Hand-written ServiceLevel adapter for the native v1 contract. */

declare(strict_types=1);

namespace Factuarea\Sdk\Models\Components;

final readonly class ServiceSlaStatus
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

    #[\Speakeasy\Serializer\Annotation\SerializedName('policy_id')]
    #[\Speakeasy\Serializer\Annotation\Type('string')]
    public string $policyId;

    #[\Speakeasy\Serializer\Annotation\SerializedName('policy_version')]
    #[\Speakeasy\Serializer\Annotation\Type('int')]
    public int $policyVersion;

    #[\Speakeasy\Serializer\Annotation\SerializedName('calendar_id')]
    #[\Speakeasy\Serializer\Annotation\Type('string|null')]
    public ?string $calendarId;

    #[\Speakeasy\Serializer\Annotation\SerializedName('calendar_version')]
    #[\Speakeasy\Serializer\Annotation\Type('int|null')]
    public ?int $calendarVersion;

    #[\Speakeasy\Serializer\Annotation\SerializedName('as_of')]
    #[\Speakeasy\Serializer\Annotation\Type('string')]
    public string $asOf;

    #[\Speakeasy\Serializer\Annotation\SerializedName('terminal')]
    #[\Speakeasy\Serializer\Annotation\Type('bool')]
    public bool $terminal;

    #[\Speakeasy\Serializer\Annotation\SerializedName('state')]
    #[\Speakeasy\Serializer\Annotation\Type('string')]
    public string $state;

    #[\Speakeasy\Serializer\Annotation\SerializedName('public_customer_messages')]
    #[\Speakeasy\Serializer\Annotation\Type('int')]
    public int $publicCustomerMessages;

    #[\Speakeasy\Serializer\Annotation\SerializedName('public_agent_responses')]
    #[\Speakeasy\Serializer\Annotation\Type('int')]
    public int $publicAgentResponses;

    /** @var list<\Factuarea\Sdk\Models\Components\ServiceSlaProjectedClock> */
    #[\Speakeasy\Serializer\Annotation\SerializedName('next_response_history')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\Factuarea\Sdk\Models\Components\ServiceSlaProjectedClock>')]
    public array $nextResponseHistory;

    #[\Speakeasy\Serializer\Annotation\SerializedName('clocks')]
    #[\Speakeasy\Serializer\Annotation\Type('\Factuarea\Sdk\Models\Components\ServiceSlaProjectedClocks')]
    public ServiceSlaProjectedClocks $clocks;

    /**
     * @param  list<\Factuarea\Sdk\Models\Components\ServiceSlaProjectedClock>  $nextResponseHistory
     */
    public function __construct(
        string $id,
        string $subjectId,
        int $version,
        int $cycleNumber,
        string $policyId,
        int $policyVersion,
        ?string $calendarId,
        ?int $calendarVersion,
        string $asOf,
        bool $terminal,
        string $state,
        int $publicCustomerMessages,
        int $publicAgentResponses,
        array $nextResponseHistory,
        ServiceSlaProjectedClocks $clocks,
    ) {
        $this->id = $id;
        $this->subjectId = $subjectId;
        $this->version = $version;
        $this->cycleNumber = $cycleNumber;
        $this->policyId = $policyId;
        $this->policyVersion = $policyVersion;
        $this->calendarId = $calendarId;
        $this->calendarVersion = $calendarVersion;
        $this->asOf = $asOf;
        $this->terminal = $terminal;
        $this->state = $state;
        $this->publicCustomerMessages = $publicCustomerMessages;
        $this->publicAgentResponses = $publicAgentResponses;
        $this->nextResponseHistory = $nextResponseHistory;
        $this->clocks = $clocks;
    }
}
