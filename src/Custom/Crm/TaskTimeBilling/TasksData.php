<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class TasksData extends WireModel
{
    public const SCHEMA = Schemas::TASKS;
    public const FIELDS = ['ticket_id' => ['property' => 'ticketId', 'type' => ['scalar' => 'string']], 'ticket_version' => ['property' => 'ticketVersion', 'type' => ['scalar' => 'int']], 'authorization_version' => ['property' => 'authorizationVersion', 'type' => ['scalar' => 'string']], 'revision' => ['property' => 'revision', 'type' => ['scalar' => 'string']], 'tasks' => ['property' => 'tasks', 'type' => ['items' => ['model' => TaskBinding::class]]]];

    /**
     * @param  list<TaskBinding>  $tasks
     */
    public function __construct(public string $ticketId, public int $ticketVersion, public string $authorizationVersion, public string $revision, public array $tasks)
    {
        $this->validate();
    }
}
