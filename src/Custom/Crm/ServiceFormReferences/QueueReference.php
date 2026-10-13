<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\ServiceFormReferences;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class QueueReference extends WireModel
{
    public const SCHEMA = Schemas::OPTION;
    public const FIELDS = ['id' => ['property' => 'id', 'type' => ['scalar' => 'string']],
        'name' => ['property' => 'name', 'type' => ['scalar' => 'string']]];
    public function __construct(public readonly string $id, public readonly string $name)
    {
        $this->validate();
        if (trim($name) === '') {
            throw new \InvalidArgumentException('Keep the actual nonempty native reference name.');
        }
    }
}
