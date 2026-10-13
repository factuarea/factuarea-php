<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class TimesheetResult extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'], 'properties' => ['data' => Schemas::TIMESHEET]];
    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['model' => TimesheetData::class]]];

    public function __construct(public TimesheetData $data)
    {
        $this->validate();
    }
}
