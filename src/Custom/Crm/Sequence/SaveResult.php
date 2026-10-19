<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Sequence;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class SaveResult extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'], 'properties' => ['data' => Schemas::ORIGINAL_SCHEDULE]];
    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['model' => Schedule::class]]];

    public function __construct(public Schedule $data)
    {
        $this->validate();
    }
}
