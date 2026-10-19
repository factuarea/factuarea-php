<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class UndoRequest extends WireModel
{
    public const SCHEMA = Schemas::UNDO;
    public const FIELDS = [
        'expected_installation_version' => ['property' => 'expectedInstallationVersion', 'type' => ['scalar' => 'int']],
        'original_receipt_id' => ['property' => 'originalReceiptId', 'type' => ['scalar' => 'string']],
        'confirmed' => ['property' => 'confirmed', 'type' => ['scalar' => 'bool']],
    ];

    public function __construct(public readonly int $expectedInstallationVersion, public readonly string $originalReceiptId, public readonly bool $confirmed)
    {
        $this->validate();
    }
}
