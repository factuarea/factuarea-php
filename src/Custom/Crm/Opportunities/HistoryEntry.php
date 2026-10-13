<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Opportunities;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class HistoryEntry extends WireModel
{
    public const SCHEMA = Schemas::HISTORY;

    public const FIELDS = ['version' => ['property' => 'version', 'type' => ['scalar' => 'int']], 'kind' => ['property' => 'kind', 'type' => ['scalar' => 'string']], 'actor_id' => ['property' => 'actorId', 'type' => ['scalar' => 'string']], 'occurred_at' => ['property' => 'occurredAt', 'type' => ['scalar' => 'string']], 'pipeline_id' => ['property' => 'pipelineId', 'type' => ['scalar' => 'string']], 'stage_id' => ['property' => 'stageId', 'type' => ['scalar' => 'string']], 'pipeline_label' => ['property' => 'pipelineLabel', 'type' => ['scalar' => 'string']], 'stage_label' => ['property' => 'stageLabel', 'type' => ['scalar' => 'string']], 'amount' => ['property' => 'amount', 'type' => ['scalar' => 'string']], 'currency' => ['property' => 'currency', 'type' => ['scalar' => 'string']], 'probability' => ['property' => 'probability', 'type' => ['scalar' => 'string']], 'outcome' => ['property' => 'outcome', 'type' => ['scalar' => 'string']], 'loss_reason_id' => ['property' => 'lossReasonId', 'type' => ['scalar' => 'string']], 'reason' => ['property' => 'reason', 'type' => ['scalar' => 'string']]];

    public function __construct(
        public readonly int $version,
        public readonly string $kind,
        public readonly ?string $actorId,
        public readonly string $occurredAt,
        public readonly ?string $pipelineId,
        public readonly ?string $stageId,
        public readonly ?string $pipelineLabel,
        public readonly ?string $stageLabel,
        public readonly ?string $amount,
        public readonly ?string $currency,
        public readonly ?string $probability,
        public readonly string $outcome,
        public readonly ?string $lossReasonId,
        public readonly ?string $reason,
    ) {
        $this->validate();
        Validation::instant($this->occurredAt);
    }
}
