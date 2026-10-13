<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents\Model;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
final readonly class UnlinkedDocument
{
    private function __construct(public string $id, public int $version, public string $opportunityId, public string $documentType, public string $documentId)
    {
    }
    public static function fromWire(mixed $value): self
    {
        $v = Wire::record($value, ['id', 'version', 'opportunity_id', 'document_type', 'document_id']);

        return new self(Wire::uuid($v['id']), Wire::integer($v['version']), Wire::uuid($v['opportunity_id']), Wire::documentType($v['document_type']), Wire::uuid($v['document_id'], true));
    }
}
