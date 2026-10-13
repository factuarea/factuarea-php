<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

use Factuarea\Sdk\Custom\Crm\WireModel;
final class ConflictsResult extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'], 'properties' => ['data' => Schemas::CONFLICTS]];
    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['model' => Conflicts::class]]];
    public function __construct(public readonly Conflicts $data)
    {
        $this->validate();
    }
}
