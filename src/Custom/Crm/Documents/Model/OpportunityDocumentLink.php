<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents\Model;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
use InvalidArgumentException;
final readonly class OpportunityDocumentLink
{
    private function __construct(public string $id, public int $version, public string $opportunityId, public string $documentType,
        public string $documentId, public bool $isPrimary, public string $status, public ?NativeDocument $document)
    {
    }
    public static function fromWire(mixed $value): self
    {
        $v = Wire::record($value, ['id', 'version', 'opportunity_id', 'document_type', 'document_id', 'is_primary', 'status', 'document']);
        $document = $v['document'] === null ? null : NativeDocument::fromWire($v['document']);
        $type = Wire::documentType($v['document_type']);
        $id = Wire::uuid($v['document_id'], true);
        if ($document !== null && ($document->type !== $type || strcasecmp($document->id, $id) !== 0)) {
            throw new InvalidArgumentException('The association references another native document.');
        }

        return new self(Wire::uuid($v['id']), Wire::integer($v['version']), Wire::uuid($v['opportunity_id']), $type, $id, Wire::boolean($v['is_primary']), Wire::text($v['status'], 100), $document);
    }
}
