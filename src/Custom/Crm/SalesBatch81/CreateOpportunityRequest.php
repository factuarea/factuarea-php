<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\SalesBatch81;

use Factuarea\Sdk\Custom\Crm\Documents\Omitted;
use InvalidArgumentException;

final readonly class CreateOpportunityRequest
{
    public string $amount;

    /** Amount is checked before assigning its string property, including for PHP callers without strict_types. */
    public function __construct(public string $title, public string $pipelineId, public string $stageId, mixed $amount,
        public string $currency, public string|null|Omitted $leadId = Omitted::Value, public string|null|Omitted $contactId = Omitted::Value,
        public string|null|Omitted $ownerId = Omitted::Value, public string|null|Omitted $teamId = Omitted::Value)
    {
        Wire::text($title, 255, true);
        Wire::uuid($pipelineId);
        Wire::uuid($stageId);
        if (!is_string($amount)) {
            throw new InvalidArgumentException('The native amount must be an original decimal string.');
        }
        $this->amount = Wire::decimal($amount);
        Wire::currency($currency);
        if (!is_string($leadId) && !is_string($contactId)) {
            throw new InvalidArgumentException('An original lead or contact UUID is required.');
        }
        foreach (['lead' => $leadId, 'contact' => $contactId, 'owner' => $ownerId, 'team' => $teamId] as $kind => $id) {
            if (is_string($id)) {
                Wire::uuid($id, $kind === 'owner');
            }
        }
    }
    public function toArray(): array
    {
        return ['title' => $this->title, 'pipeline_id' => $this->pipelineId, 'stage_id' => $this->stageId, 'amount' => $this->amount,
            'currency' => $this->currency] + Wire::optional(['lead_id' => $this->leadId, 'contact_id' => $this->contactId,
                'owner_id' => $this->ownerId, 'team_id' => $this->teamId]);
    }
}
