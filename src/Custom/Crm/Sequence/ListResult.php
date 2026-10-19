<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Sequence;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class ListResult extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'], 'properties' => ['data' => Schemas::LIST]];
    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['object' => true]]];

    /** @param \stdClass&object{items:list<\stdClass>,enrolments:list<\stdClass>,resource:string,next_cursor:?string,visible_count:int} $data */
    public function __construct(public \stdClass $data)
    {
        $this->validate();
    }
}
