<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

use Factuarea\Sdk\Custom\Crm\WireModel;
use Factuarea\Sdk\Custom\Crm\Omitted;

final class TaskBinding extends WireModel
{
    public const SCHEMA = Schemas::TASK_BINDING;
    public const FIELDS = ['id' => ['property' => 'id', 'type' => ['scalar' => 'string']], 'task_id' => ['property' => 'taskId', 'type' => ['scalar' => 'string']], 'version' => ['property' => 'version', 'type' => ['scalar' => 'int']], 'archived_at' => ['property' => 'archivedAt', 'type' => ['scalar' => 'string']], 'task_title' => ['property' => 'taskTitle', 'type' => ['scalar' => 'string']], 'task_version' => ['property' => 'taskVersion', 'type' => ['scalar' => 'int']]];

    public function __construct(public string $id, public string $taskId, public int|Omitted $version = Omitted::Value, public string|null|Omitted $archivedAt = Omitted::Value, public string|Omitted $taskTitle = Omitted::Value, public int|Omitted $taskVersion = Omitted::Value)
    {
        $this->validate();
    }
}
