<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents\Request;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
final readonly class LinkOpportunityDocumentRequest
{
    public function __construct(public string $documentType, public string $documentId, public bool $isPrimary)
    {
        Wire::documentType($documentType);
        Wire::uuid($documentId, true);
    }
    /** @return array{document_type:string,document_id:string,is_primary:bool} */
    public function toArray(): array
    {
        return ['document_type' => $this->documentType, 'document_id' => $this->documentId, 'is_primary' => $this->isPrimary];
    }
}
