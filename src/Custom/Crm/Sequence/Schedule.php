<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Sequence;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class Schedule extends WireModel
{
    public const SCHEMA = Schemas::SCHEDULE;
    public const FIELDS = ['id' => ['property' => 'id', 'type' => ['scalar' => 'string']], 'version' => ['property' => 'version', 'type' => ['scalar' => 'int']], 'authorization_version' => ['property' => 'authorizationVersion', 'type' => ['scalar' => 'string']], 'sandbox' => ['property' => 'sandbox', 'type' => ['scalar' => 'bool']], 'state' => ['property' => 'state', 'type' => ['scalar' => 'string']], 'owner_id' => ['property' => 'ownerId', 'type' => ['scalar' => 'string']], 'team_id' => ['property' => 'teamId', 'type' => ['scalar' => 'string']], 'fields' => ['property' => 'fields', 'type' => ['object' => true]]];

    public function __construct(public string $id, public int $version, public string $authorizationVersion, public bool $sandbox, public string $state, public string $ownerId, public ?string $teamId, public \stdClass $fields)
    {
        $this->validate();
    }
}
