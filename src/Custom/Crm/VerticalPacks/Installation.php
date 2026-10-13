<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class Installation extends WireModel
{
    public const SCHEMA = Schemas::INSTALLATION;
    public const FIELDS = [
        'id' => ['property' => 'id', 'type' => ['scalar' => 'string']],
        'version' => ['property' => 'version', 'type' => ['scalar' => 'int']],
        'receipt_id' => ['property' => 'receiptId', 'type' => ['scalar' => 'string']],
        'pack_id' => ['property' => 'packId', 'type' => ['scalar' => 'string']],
        'pack_version' => ['property' => 'packVersion', 'type' => ['scalar' => 'int']],
    ];

    public function __construct(public readonly string $id, public readonly int $version, public readonly string $receiptId, public readonly string $packId, public readonly int $packVersion)
    {
        $this->validate();
    }
}
