<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\EntityExports;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class ExportEnvelope extends WireModel
{
    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['model' => ExportJob::class]]];

    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'], 'properties' => ['data' => ExportJob::SCHEMA]];

    public function __construct(public readonly ExportJob $data)
    {
        $this->validate();
    }
}
