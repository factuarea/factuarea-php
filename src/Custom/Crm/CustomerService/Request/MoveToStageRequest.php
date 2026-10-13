<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\CustomerService\Request;

use Factuarea\Sdk\Custom\Crm\Documents\Omitted;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
use InvalidArgumentException;

/** Omission preserves the current cause; null releases it. The owner accredits any original customer message. */
final readonly class MoveToStageRequest
{
    public function __construct(public int $expectedVersion, public string $pipelineId, public string $stageId,
        public string|null|Omitted $waitingReason = Omitted::Value, public string|null|Omitted $customerMessageId = Omitted::Value)
    {
        Wire::integer($expectedVersion);
        Wire::uuid($pipelineId);
        Wire::uuid($stageId);
        if (is_string($waitingReason) && $waitingReason !== 'waiting_customer') {
            throw new InvalidArgumentException('The native waiting cause must be waiting_customer.');
        }
        if (is_string($customerMessageId)) {
            Wire::uuid($customerMessageId);
            if ($waitingReason !== null) {
                throw new InvalidArgumentException('The original customer message only confirms an explicit waiting release.');
            }
        }
    }
    /** @return array{expected_version:int,pipeline_id:string,stage_id:string,waiting_reason?:?string,customer_message_id?:?string} */
    public function toArray(): array
    {
        $body = ['expected_version' => $this->expectedVersion, 'pipeline_id' => strtolower($this->pipelineId), 'stage_id' => strtolower($this->stageId)];
        if (!$this->waitingReason instanceof Omitted) {
            $body['waiting_reason'] = $this->waitingReason;
        }
        if (!$this->customerMessageId instanceof Omitted) {
            $body['customer_message_id'] = $this->customerMessageId === null ? null : strtolower($this->customerMessageId);
        }

        return $body;
    }
}
