<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

/** Only a known original effect/binding tuple. This query neither takes a key nor resends an intent. */
final readonly class RecoveryRequest
{
    public function __construct(public string $action, public string $taskId, public int $expectedTicketVersion,
        public int $expectedTaskVersion, public string $bindingId, public string $effectId, public ?int $expectedBindingVersion = null)
    {
        WireCodec::recovery($action, $this->toJson());
    }

    public function toJson(): string
    {
        $body = ['task_id' => $this->taskId, 'expected_ticket_version' => $this->expectedTicketVersion,
            'expected_task_version' => $this->expectedTaskVersion, 'binding_id' => $this->bindingId,
            'action' => $this->action, 'effect_id' => $this->effectId];
        if ($this->expectedBindingVersion !== null) {
            $body['expected_binding_version'] = $this->expectedBindingVersion;
        }

        return json_encode($body, JSON_THROW_ON_ERROR);
    }
}
