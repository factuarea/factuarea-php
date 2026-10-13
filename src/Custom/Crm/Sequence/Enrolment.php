<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Sequence;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class Enrolment extends WireModel
{
    public const SCHEMA = Schemas::ENROLMENT;
    public const FIELDS = ['id' => ['property' => 'id', 'type' => ['scalar' => 'string']], 'version' => ['property' => 'version', 'type' => ['scalar' => 'int']], 'authorization_version' => ['property' => 'authorizationVersion', 'type' => ['scalar' => 'string']], 'sandbox' => ['property' => 'sandbox', 'type' => ['scalar' => 'bool']], 'sequence_id' => ['property' => 'sequenceId', 'type' => ['scalar' => 'string']], 'subject_type' => ['property' => 'subjectType', 'type' => ['scalar' => 'string']], 'subject_id' => ['property' => 'subjectId', 'type' => ['scalar' => 'string']], 'generation' => ['property' => 'generation', 'type' => ['scalar' => 'int']], 'definition_version' => ['property' => 'definitionVersion', 'type' => ['scalar' => 'int']], 'state' => ['property' => 'state', 'type' => ['scalar' => 'string']], 'current_step' => ['property' => 'currentStep', 'type' => ['scalar' => 'int']], 'due_at' => ['property' => 'dueAt', 'type' => ['scalar' => 'string']], 'stop_reason' => ['property' => 'stopReason', 'type' => ['scalar' => 'string']], 'fields' => ['property' => 'fields', 'type' => ['object' => true]]];

    public function __construct(public string $id, public int $version, public string $authorizationVersion, public bool $sandbox, public string $sequenceId, public string $subjectType, public string $subjectId, public int $generation, public int $definitionVersion, public string $state, public int $currentStep, public ?string $dueAt, public ?string $stopReason, public \stdClass $fields)
    {
        $this->validate();
    }
}
