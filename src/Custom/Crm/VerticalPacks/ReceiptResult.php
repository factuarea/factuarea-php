<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

use Factuarea\Sdk\Custom\Crm\WireModel;
final class ReceiptResult extends WireModel
{
    public const SCHEMA = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'], 'properties' => ['data' => Schemas::RECEIPT]];
    public const FIELDS = ['data' => ['property' => 'data', 'type' => ['model' => Receipt::class]]];
    public function __construct(public readonly Receipt $data)
    {
        $this->validate();
    }
}
