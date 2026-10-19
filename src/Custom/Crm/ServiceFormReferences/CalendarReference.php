<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\ServiceFormReferences;

use Factuarea\Sdk\Custom\Crm\WireModel;

/** Calendar identity/version remain independent of the selected policy. */
final class CalendarReference extends WireModel
{
    public const SCHEMA = Schemas::CALENDAR;
    public const FIELDS = ['id' => ['property' => 'id', 'type' => ['scalar' => 'string']],
        'name' => ['property' => 'name', 'type' => ['scalar' => 'string']], 'version' => ['property' => 'version', 'type' => ['scalar' => 'int']]];
    public function __construct(public readonly string $id, public readonly string $name, public readonly int $version)
    {
        $this->validate();
        if (trim($name) === '') {
            throw new \InvalidArgumentException('Keep the actual nonempty native calendar name.');
        }
    }
}
