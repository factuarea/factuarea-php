<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

use Factuarea\Sdk\Custom\Crm\WireModel;
use Factuarea\Sdk\Custom\Crm\Omitted;

final class BillableTimeData extends WireModel
{
    public const SCHEMA = Schemas::BILLABLE_TIME;
    public const FIELDS = ['ticket_id' => ['property' => 'ticketId', 'type' => ['scalar' => 'string']], 'ticket_version' => ['property' => 'ticketVersion', 'type' => ['scalar' => 'int']], 'authorization_version' => ['property' => 'authorizationVersion', 'type' => ['scalar' => 'string']], 'revision' => ['property' => 'revision', 'type' => ['scalar' => 'string']], 'entries' => ['property' => 'entries', 'type' => ['items' => ['model' => TimeEntry::class]]], 'next_cursor' => ['property' => 'nextCursor', 'type' => ['scalar' => 'string']], 'total_billable_closed_seconds' => ['property' => 'totalBillableClosedSeconds', 'type' => ['scalar' => 'int']]];

    /**
     * @param  list<TimeEntry>  $entries
     */
    public function __construct(public string $ticketId, public int $ticketVersion, public string $authorizationVersion, public string $revision, public array $entries, public ?string $nextCursor, public int|Omitted $totalBillableClosedSeconds = Omitted::Value)
    {
        $this->validate();
    }
}
