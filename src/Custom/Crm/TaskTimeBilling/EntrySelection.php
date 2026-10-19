<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class EntrySelection extends WireModel
{
    public const SCHEMA = Schemas::ENTRY_SELECTION;
    public const FIELDS = ['id' => ['property' => 'id', 'type' => ['scalar' => 'string']], 'revision' => ['property' => 'revision', 'type' => ['scalar' => 'string']]];

    public function __construct(public string $id, public string $revision)
    {
        $this->validate();
    }
}
