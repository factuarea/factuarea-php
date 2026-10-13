<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class PreviewContact extends WireModel
{
    public const SCHEMA = Schemas::PREVIEW_CONTACT;
    public const FIELDS = ['id' => ['property' => 'id', 'type' => ['scalar' => 'string']], 'name' => ['property' => 'name', 'type' => ['scalar' => 'string']]];

    public function __construct(public string $id, public string $name)
    {
        $this->validate();
    }
}
