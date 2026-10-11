<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\EntityExports;

use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\WireModel;

/** Closed factual public projection: no selection, source proof, row counts or URL grants. */
final class ExportJob extends WireModel
{
    public const FIELDS = [
        'id' => ['property' => 'id', 'type' => ['scalar' => 'string']],
        'entity_type' => ['property' => 'entityType', 'type' => ['scalar' => 'string']],
        'status' => ['property' => 'status', 'type' => ['scalar' => 'string']],
        'filename' => ['property' => 'filename', 'type' => ['scalar' => 'string']],
        'size_bytes' => ['property' => 'sizeBytes', 'type' => ['scalar' => 'int']],
    ];

    public const SCHEMA = [
        'type' => 'object', 'additionalProperties' => false, 'required' => ['id', 'entity_type', 'status'],
        'properties' => [
            'id' => ExportRequest::SCHEMA['properties']['id'],
            'entity_type' => ExportRequest::SCHEMA['properties']['entity_type'],
            'status' => ['enum' => ['processing', 'ready', 'failed', 'expired']],
            'filename' => ['type' => 'string', 'minLength' => 1],
            'size_bytes' => ['type' => 'integer', 'minimum' => 0],
        ],
        'if' => ['properties' => ['status' => ['const' => 'ready']]],
        'then' => ['required' => ['filename', 'size_bytes']],
        'else' => ['not' => ['anyOf' => [['required' => ['filename']], ['required' => ['size_bytes']]]]],
    ];

    public function __construct(
        public readonly string $id,
        public readonly string $entityType,
        public readonly string $status,
        public readonly string|Omitted $filename = Omitted::Value,
        public readonly int|Omitted $sizeBytes = Omitted::Value,
    ) {
        $this->validate();
    }

    public function state(): ExportStatus
    {
        return ExportStatus::from($this->status);
    }
}
