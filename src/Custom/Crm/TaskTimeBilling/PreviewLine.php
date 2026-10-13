<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class PreviewLine extends WireModel
{
    public const SCHEMA = Schemas::PREVIEW_LINE;
    public const FIELDS = ['description' => ['property' => 'description', 'type' => ['scalar' => 'string']], 'quantity' => ['property' => 'quantity', 'type' => ['scalar' => 'string']], 'unit' => ['property' => 'unit', 'type' => ['scalar' => 'string']], 'unit_price' => ['property' => 'unitPrice', 'type' => ['scalar' => 'string']], 'amount' => ['property' => 'amount', 'type' => ['scalar' => 'string']]];

    public function __construct(public string $description, public string $quantity, public string $unit, public string $unitPrice, public string $amount)
    {
        $this->validate();
    }
}
