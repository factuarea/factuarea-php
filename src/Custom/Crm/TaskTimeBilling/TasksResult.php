<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class TasksResult extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'], 'properties' => ['data' => Schemas::TASKS]];
    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['model' => TasksData::class]]];

    public function __construct(public TasksData $data)
    {
        $this->validate();
    }
}
