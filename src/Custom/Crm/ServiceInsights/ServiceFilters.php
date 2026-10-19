<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\ServiceInsights;

use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\WireModel;

/** Closed actual V1 filters. Absent filters remain absent; null is not a native GET selection. */
final class ServiceFilters extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false,
        'properties' => ['agent_id' => ReadInput::ACTOR_ID, 'team_id' => ReadInput::ID, 'status' => ReadInput::STATUS]];

    public const FIELDS = ['agent_id' => ['property' => 'agentId', 'type' => ['scalar' => 'string']],
        'team_id' => ['property' => 'teamId', 'type' => ['scalar' => 'string']],
        'status' => ['property' => 'status', 'type' => ['scalar' => 'string']]];

    public function __construct(public readonly string|Omitted $agentId = Omitted::Value,
        public readonly string|Omitted $teamId = Omitted::Value,
        public readonly string|Omitted $status = Omitted::Value)
    {
        $this->validate();
    }

    /** @return array<string,string> */
    public function query(): array
    {
        $values = [];
        foreach (['agent_id' => $this->agentId, 'team_id' => $this->teamId, 'status' => $this->status] as $key => $value) {
            if ($value !== Omitted::Value) {
                $values[$key] = $value;
            }
        }

        return $values;
    }
}
