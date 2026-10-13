<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Opportunities;

use Factuarea\Sdk\Custom\Crm\WireModel;
use Factuarea\Sdk\Custom\Crm\Omitted;

final class FilterPredicate extends WireModel
{
    public const SCHEMA = Schemas::PREDICATE;

    public const FIELDS = ['field_id' => ['property' => 'fieldId', 'type' => ['scalar' => 'string']], 'field_version' => ['property' => 'fieldVersion', 'type' => ['scalar' => 'int']], 'operator' => ['property' => 'operator', 'type' => ['scalar' => 'string']], 'value' => ['property' => 'value', 'type' => []]];

    /**
     * @param  string|bool|list<string|bool>|null|Omitted  $value
     */
    public function __construct(
        public readonly string $fieldId,
        public readonly int $fieldVersion,
        public readonly string $operator,
        public readonly string|bool|array|null|Omitted $value = Omitted::Value,
    ) {
        $this->validate();
    }
}
