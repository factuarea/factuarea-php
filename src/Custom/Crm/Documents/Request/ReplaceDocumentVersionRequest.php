<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents\Request;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
final readonly class ReplaceDocumentVersionRequest
{
    public function __construct(public int $expectedVersion, public string $producerReferenceId, public string $claimedMime)
    {
        Wire::integer($expectedVersion);
        Wire::uuid($producerReferenceId);
        Wire::mime($claimedMime);
    }
    /** @return array{expected_version:int,producer_reference_id:string,claimed_mime:string} */
    public function toArray(): array
    {
        return ['expected_version' => $this->expectedVersion, 'producer_reference_id' => $this->producerReferenceId, 'claimed_mime' => $this->claimedMime];
    }
}
