<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

use Factuarea\Sdk\Custom\Crm\WireModel;
use Factuarea\Sdk\Custom\Crm\Omitted;

final class Binding extends WireModel
{
    public const SCHEMA = Schemas::BINDING;
    public const FIELDS = ['id' => ['property' => 'id', 'type' => ['scalar' => 'string']], 'task_id' => ['property' => 'taskId', 'type' => ['scalar' => 'string']], 'version' => ['property' => 'version', 'type' => ['scalar' => 'int']], 'archived_at' => ['property' => 'archivedAt', 'type' => ['scalar' => 'string']]];

    public function __construct(public string $id, public string $taskId, public int $version, public string|null|Omitted $archivedAt = Omitted::Value)
    {
        $this->validate();
    }
}
