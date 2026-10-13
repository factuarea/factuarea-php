<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Opportunities;

use Factuarea\Sdk\Custom\Crm\WireModel;

/** Null means the current native field projection is hidden or nullable; no field is defaulted. */
final class Opportunity extends WireModel
{
    public const SCHEMA = Schemas::OPPORTUNITY;

    public const FIELDS = ['id' => ['property' => 'id', 'type' => ['scalar' => 'string']], 'version' => ['property' => 'version', 'type' => ['scalar' => 'int']], 'status' => ['property' => 'status', 'type' => ['scalar' => 'string']], 'title' => ['property' => 'title', 'type' => ['scalar' => 'string']], 'lead_id' => ['property' => 'leadId', 'type' => ['scalar' => 'string']], 'contact_id' => ['property' => 'contactId', 'type' => ['scalar' => 'string']], 'pipeline_id' => ['property' => 'pipelineId', 'type' => ['scalar' => 'string']], 'stage_id' => ['property' => 'stageId', 'type' => ['scalar' => 'string']], 'owner_id' => ['property' => 'ownerId', 'type' => ['scalar' => 'string']], 'team_id' => ['property' => 'teamId', 'type' => ['scalar' => 'string']], 'amount' => ['property' => 'amount', 'type' => ['scalar' => 'string']], 'currency' => ['property' => 'currency', 'type' => ['scalar' => 'string']], 'probability' => ['property' => 'probability', 'type' => ['scalar' => 'string']], 'probability_override' => ['property' => 'probabilityOverride', 'type' => ['scalar' => 'string']], 'override_reason' => ['property' => 'overrideReason', 'type' => ['scalar' => 'string']], 'weighted_value' => ['property' => 'weightedValue', 'type' => ['scalar' => 'string']], 'expected_close' => ['property' => 'expectedClose', 'type' => ['scalar' => 'string']], 'next_step' => ['property' => 'nextStep', 'type' => ['scalar' => 'string']], 'closed_at' => ['property' => 'closedAt', 'type' => ['scalar' => 'string']], 'loss_reason_id' => ['property' => 'lossReasonId', 'type' => ['scalar' => 'string']], 'close_reason' => ['property' => 'closeReason', 'type' => ['scalar' => 'string']], 'stage_changed_at' => ['property' => 'stageChangedAt', 'type' => ['scalar' => 'string']], 'participants' => ['property' => 'participants', 'type' => ['items' => ['model' => 'Factuarea\\Sdk\\Custom\\Crm\\Opportunities\\Participant']]], 'playbook_answers' => ['property' => 'playbookAnswers', 'type' => ['items' => ['model' => 'Factuarea\\Sdk\\Custom\\Crm\\Opportunities\\PlaybookAnswer']]], 'history' => ['property' => 'history', 'type' => ['items' => ['model' => 'Factuarea\\Sdk\\Custom\\Crm\\Opportunities\\HistoryEntry']]]];

    /**
     * @param  list<Participant>|null  $participants
     * @param  list<PlaybookAnswer>|null  $playbookAnswers
     * @param  list<HistoryEntry>|null  $history
     */
    public function __construct(
        public readonly string $id,
        public readonly int $version,
        public readonly string $status,
        public readonly ?string $title,
        public readonly ?string $leadId,
        public readonly ?string $contactId,
        public readonly ?string $pipelineId,
        public readonly ?string $stageId,
        public readonly ?string $ownerId,
        public readonly ?string $teamId,
        public readonly ?string $amount,
        public readonly ?string $currency,
        public readonly ?string $probability,
        public readonly ?string $probabilityOverride,
        public readonly ?string $overrideReason,
        public readonly ?string $weightedValue,
        public readonly ?string $expectedClose,
        public readonly ?string $nextStep,
        public readonly ?string $closedAt,
        public readonly ?string $lossReasonId,
        public readonly ?string $closeReason,
        public readonly ?string $stageChangedAt,
        public readonly ?array $participants,
        public readonly ?array $playbookAnswers,
        public readonly ?array $history,
    ) {
        $this->validate();
        if ($this->title !== null && trim($this->title) === '') {
            throw new \InvalidArgumentException('Opportunity title cannot be blank.');
        }
        if ($this->expectedClose !== null) {
            Validation::date($this->expectedClose);
        }
        foreach ([$this->closedAt, $this->stageChangedAt] as $at) {
            if ($at !== null) {
                Validation::instant($at);
            }
        }
        $seen = [];
        foreach ($this->participants ?? [] as $participant) {
            $key = $participant->personId.':'.$participant->role;
            Validation::unique($seen, $key);
        }
        $seen = [];
        foreach ($this->playbookAnswers ?? [] as $answer) {
            Validation::unique($seen, $answer->definitionId.':'.$answer->definitionVersion.':'.$answer->key);
        }
        $previous = 0;
        foreach ($this->history ?? [] as $entry) {
            if ($entry->version <= $previous || $entry->version > $this->version) {
                throw new \InvalidArgumentException('History versions must increase within the native Opportunity CAS.');
            }
            $previous = $entry->version;
        }
    }
}
