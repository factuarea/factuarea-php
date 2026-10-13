<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\CustomerService\Request;

use Factuarea\Sdk\Custom\Crm\Documents\Wire;

/** Attachment identity and private file metadata are owned by the server. */
final readonly class AttachDocumentRequest
{
    public function __construct(public int $expectedVersion, public string $documentId, public int $documentVersion)
    {
        Wire::integer($expectedVersion);
        Wire::uuid($documentId);
        Wire::integer($documentVersion);
    }
    /** @return array{expected_version:int,document_id:string,document_version:int} */
    public function toArray(): array
    {
        return ['expected_version' => $this->expectedVersion, 'document_id' => strtolower($this->documentId), 'document_version' => $this->documentVersion];
    }
}
