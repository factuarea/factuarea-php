<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

use Factuarea\Sdk\Custom\Crm\WireModel;
final class PackPageResult extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'], 'properties' => ['data' => Schemas::PAGE]];
    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['model' => PackPage::class]]];
    public function __construct(public readonly PackPage $data)
    {
        $this->validate();
    }
}
