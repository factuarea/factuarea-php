<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\ServiceFormReferences;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class ReferencesResult extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'],
        'properties' => ['data' => Schemas::REFERENCES]];
    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['model' => References::class]]];
    public function __construct(public readonly References $data)
    {
        $this->validate();
    }
}
