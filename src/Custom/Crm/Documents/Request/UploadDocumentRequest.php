<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents\Request;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;

/** Current JSON ingestion-reference contract. Raw bytes require the native multipart HTTP contract. */
final readonly class UploadDocumentRequest
{
    public function __construct(public string $title, public string $producerReferenceId, public string $claimedMime)
    {
        Wire::text($title, 255);
        Wire::uuid($producerReferenceId);
        Wire::mime($claimedMime);
    }
    /** @return array{title:string,producer_reference_id:string,claimed_mime:string} */
    public function toArray(): array
    {
        return ['title' => $this->title, 'producer_reference_id' => $this->producerReferenceId, 'claimed_mime' => $this->claimedMime];
    }
}
