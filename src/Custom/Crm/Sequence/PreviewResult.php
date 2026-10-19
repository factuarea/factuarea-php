<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Sequence;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class PreviewResult extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'], 'properties' => ['data' => Schemas::PREVIEW]];
    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['object' => true]]];

    /** @param \stdClass&object{sequence_id:string,definition_version:int,subject_id:string,timezone:string,steps:list<\stdClass&object{step_index:int,type:string,due_at:string,recipe_id:?string}>} $data */
    public function __construct(public \stdClass $data)
    {
        $this->validate();
    }
}
