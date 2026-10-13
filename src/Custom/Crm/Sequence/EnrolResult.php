<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Sequence;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class EnrolResult extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'], 'properties' => ['data' => Schemas::ENROLMENT]];
    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['model' => Enrolment::class]]];

    public function __construct(public Enrolment $data)
    {
        $this->validate();
    }
}
