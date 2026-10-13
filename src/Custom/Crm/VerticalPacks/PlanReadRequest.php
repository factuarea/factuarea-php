<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

use Factuarea\Sdk\Custom\Crm\WireModel;
final class PlanReadRequest extends WireModel
{
    public const SCHEMA = Schemas::PLAN_REQUEST;
    public const FIELDS = ['plan_token' => ['property' => 'planToken', 'type' => ['scalar' => 'string']]];
    public function __construct(#[\SensitiveParameter] public readonly string $planToken)
    {
        $this->validate();
        WireCodec::token($planToken);
    }
}
