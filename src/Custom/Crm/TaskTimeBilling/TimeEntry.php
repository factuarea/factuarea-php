<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

use Factuarea\Sdk\Custom\Crm\WireModel;
use Factuarea\Sdk\Custom\Crm\Omitted;

final class TimeEntry extends WireModel
{
    public const SCHEMA = Schemas::TIME_ENTRY;
    public const FIELDS = ['id' => ['property' => 'id', 'type' => ['scalar' => 'string']], 'task_id' => ['property' => 'taskId', 'type' => ['scalar' => 'string']], 'revision' => ['property' => 'revision', 'type' => ['scalar' => 'string']], 'started_at' => ['property' => 'startedAt', 'type' => ['scalar' => 'string']], 'ended_at' => ['property' => 'endedAt', 'type' => ['scalar' => 'string']], 'duration_seconds' => ['property' => 'durationSeconds', 'type' => ['scalar' => 'int']], 'description' => ['property' => 'description', 'type' => ['scalar' => 'string']], 'billable' => ['property' => 'billable', 'type' => ['scalar' => 'bool']], 'user_id' => ['property' => 'userId', 'type' => ['scalar' => 'string']], 'invoice_id' => ['property' => 'invoiceId', 'type' => ['scalar' => 'string']], 'billing_batch_id' => ['property' => 'billingBatchId', 'type' => ['scalar' => 'string']]];

    public function __construct(public string $id, public string $taskId, public string|Omitted $revision = Omitted::Value, public string|Omitted $startedAt = Omitted::Value, public string|null|Omitted $endedAt = Omitted::Value, public int|null|Omitted $durationSeconds = Omitted::Value, public string|null|Omitted $description = Omitted::Value, public bool|Omitted $billable = Omitted::Value, public string|Omitted $userId = Omitted::Value, public string|null|Omitted $invoiceId = Omitted::Value, public string|null|Omitted $billingBatchId = Omitted::Value)
    {
        $this->validate();
    }
}
