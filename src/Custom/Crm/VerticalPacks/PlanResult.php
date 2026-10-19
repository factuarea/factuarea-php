<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

use Factuarea\Sdk\Custom\Crm\WireModel;
final class PlanResult extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'], 'properties' => ['data' => Schemas::PLAN]];
    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['model' => Plan::class]]];
    public function __construct(public readonly Plan $data)
    {
        $this->validate();
    }
}
