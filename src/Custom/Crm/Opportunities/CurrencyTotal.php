<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Opportunities;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class CurrencyTotal extends WireModel
{
    public const SCHEMA = Schemas::CURRENCY_TOTAL;

    public const FIELDS = ['currency' => ['property' => 'currency', 'type' => ['scalar' => 'string']], 'amount' => ['property' => 'amount', 'type' => ['scalar' => 'string']], 'weighted_amount' => ['property' => 'weightedAmount', 'type' => ['scalar' => 'string']]];

    public function __construct(
        public readonly string $currency,
        public readonly string $amount,
        public readonly string $weightedAmount,
    ) {
        $this->validate();
    }
}
