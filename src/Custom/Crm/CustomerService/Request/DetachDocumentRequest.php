<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\CustomerService\Request;

use Factuarea\Sdk\Custom\Crm\Documents\Wire;

final readonly class DetachDocumentRequest
{
    public function __construct(public int $expectedVersion, public string $attachmentId)
    {
        Wire::integer($expectedVersion);
        Wire::uuid($attachmentId);
    }
    /** @return array{expected_version:int,attachment_id:string} */
    public function toArray(): array
    {
        return ['expected_version' => $this->expectedVersion, 'attachment_id' => strtolower($this->attachmentId)];
    }
}
