<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class OriginalReceipt extends WireModel
{
    public const SCHEMA = Schemas::ORIGINAL;
    public const FIELDS = [
        'data' => ['property' => 'receipt', 'type' => ['model' => Receipt::class]],
    ];

    public function __construct(public readonly ?Receipt $receipt)
    {
        $this->validate();
    }
}
