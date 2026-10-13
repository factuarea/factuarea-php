<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class PreviewTotals extends WireModel
{
    public const SCHEMA = Schemas::PREVIEW_TOTALS;
    public const FIELDS = ['subtotal' => ['property' => 'subtotal', 'type' => ['scalar' => 'string']], 'vat' => ['property' => 'vat', 'type' => ['scalar' => 'string']], 'surcharge' => ['property' => 'surcharge', 'type' => ['scalar' => 'string']], 'retention' => ['property' => 'retention', 'type' => ['scalar' => 'string']], 'total' => ['property' => 'total', 'type' => ['scalar' => 'string']], 'vat_rate' => ['property' => 'vatRate', 'type' => ['scalar' => 'float']], 'surcharge_rate' => ['property' => 'surchargeRate', 'type' => ['scalar' => 'float']], 'retention_rate' => ['property' => 'retentionRate', 'type' => ['scalar' => 'float']]];

    public function __construct(public string $subtotal, public string $vat, public string $surcharge, public string $retention, public string $total, public ?float $vatRate, public ?float $surchargeRate, public ?float $retentionRate)
    {
        $this->validate();
    }
}
