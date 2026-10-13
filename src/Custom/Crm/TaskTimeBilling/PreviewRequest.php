<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class PreviewRequest extends WireModel
{
    public const SCHEMA = Schemas::PREVIEW_REQUEST;
    public const FIELDS = ['task_id' => ['property' => 'taskId', 'type' => ['scalar' => 'string']], 'expected_ticket_version' => ['property' => 'expectedTicketVersion', 'type' => ['scalar' => 'int']], 'expected_task_version' => ['property' => 'expectedTaskVersion', 'type' => ['scalar' => 'int']], 'entries' => ['property' => 'entries', 'type' => ['items' => ['model' => EntrySelection::class]]], 'grouping' => ['property' => 'grouping', 'type' => ['scalar' => 'string']]];

    /**
     * @param  list<EntrySelection>  $entries
     */
    public function __construct(public string $taskId, public int $expectedTicketVersion, public int $expectedTaskVersion, public array $entries, public string $grouping)
    {
        $this->validate();
        $seen = [];
        foreach ($entries as $entry) {
            if (! $entry instanceof EntrySelection || isset($seen[$entry->id])) {
                throw new \InvalidArgumentException('Select each native entry only once.');
            }
            $seen[$entry->id] = true;
        }
    }
}
