<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class BillableTimeResult extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'], 'properties' => ['data' => Schemas::BILLABLE_TIME]];
    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['model' => BillableTimeData::class]]];

    public function __construct(public BillableTimeData $data)
    {
        $this->validate();
    }
}
