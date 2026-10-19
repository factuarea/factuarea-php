<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class PreviewResult extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'], 'properties' => ['data' => Schemas::PREVIEW]];
    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['model' => PreviewData::class]]];

    public function __construct(public PreviewData $data)
    {
        $this->validate();
    }
}
