<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Opportunities;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class TypedFilters extends WireModel
{
    public const SCHEMA = Schemas::TYPED_FILTERS;

    public const FIELDS = ['definition_version' => ['property' => 'definitionVersion', 'type' => ['scalar' => 'int']], 'filters' => ['property' => 'filters', 'type' => ['items' => ['model' => 'Factuarea\\Sdk\\Custom\\Crm\\Opportunities\\FilterPredicate']]]];

    /**
     * @param  list<FilterPredicate>  $filters
     */
    public function __construct(
        public readonly int $definitionVersion,
        public readonly array $filters,
    ) {
        $this->validate();
    }
}
