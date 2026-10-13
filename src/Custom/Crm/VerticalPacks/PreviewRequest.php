<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

use Factuarea\Sdk\Custom\Crm\WireModel;
final class PreviewRequest extends WireModel
{
    public const SCHEMA = Schemas::PREVIEW_REQUEST;
    public const FIELDS = ['pack_version' => ['property' => 'packVersion', 'type' => ['scalar' => 'int']],
        'demo_selected' => ['property' => 'demoSelected', 'type' => ['scalar' => 'bool']]];
    public function __construct(public readonly int $packVersion, public readonly bool $demoSelected)
    {
        $this->validate();
    }
}
