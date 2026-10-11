<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\EntityExports;

use Factuarea\Sdk\Custom\Crm\WireModel;

/** Hand-written Source107 contract; no reference to the SDK's historical OpenAPI pin. */
final class ExportRequest extends WireModel
{
    public const FIELDS = [
        'id' => ['property' => 'id', 'type' => ['scalar' => 'string']],
        'entity_type' => ['property' => 'entityType', 'type' => ['scalar' => 'string']],
        'filters' => ['property' => 'filters', 'type' => ['object' => true]],
        'columns' => ['property' => 'columns', 'type' => ['items' => ['scalar' => 'string']]],
        'confirmed' => ['property' => 'confirmed', 'type' => ['scalar' => 'bool']],
    ];

    public const SCHEMA = [
        'type' => 'object', 'additionalProperties' => false,
        'required' => ['id', 'entity_type', 'filters', 'columns', 'confirmed'],
        'properties' => [
            'id' => ['type' => 'string', 'pattern' => '^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$'],
            'entity_type' => ['type' => 'string', 'enum' => ['organization', 'person', 'lead', 'opportunity', 'activity', 'service_ticket']],
            'filters' => ['type' => 'object'],
            'columns' => ['type' => 'array', 'minItems' => 1, 'maxItems' => 128, 'uniqueItems' => true, 'items' => ['type' => 'string']],
            'confirmed' => ['const' => true],
        ],
    ];

    /** @param list<string> $columns */
    public function __construct(
        public readonly string $id,
        public readonly string $entityType,
        public readonly object $filters,
        public readonly array $columns,
        public readonly bool $confirmed,
    ) {
        $this->validate();
        Selection::validate(EntityType::from($entityType), (array) $filters, $columns);
    }
}
