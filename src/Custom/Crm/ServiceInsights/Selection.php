<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\ServiceInsights;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class Selection extends WireModel
{
    public const SCHEMA = ReadInput::SELECTION;
    public const FIELDS = ['start' => ['property' => 'start', 'type' => ['scalar' => 'string']],
        'end' => ['property' => 'end', 'type' => ['scalar' => 'string']],
        'clock_mode' => ['property' => 'clockMode', 'type' => ['scalar' => 'string']],
        'agent_id' => ['property' => 'agentId', 'type' => ['scalar' => 'string']],
        'team_id' => ['property' => 'teamId', 'type' => ['scalar' => 'string']],
        'status' => ['property' => 'status', 'type' => ['scalar' => 'string']]];

    public function __construct(public readonly string $start, public readonly string $end, public readonly string $clockMode, public readonly ?string $agentId, public readonly ?string $teamId, public readonly ?string $status)
    {
        $this->validate();
        ReadInput::cohort($start, $end);
    }
}
