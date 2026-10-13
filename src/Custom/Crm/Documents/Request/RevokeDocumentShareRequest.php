<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents\Request;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
final readonly class RevokeDocumentShareRequest
{
    public function __construct(public int $expectedVersion, public string $shareId)
    {
        Wire::integer($expectedVersion);
        Wire::uuid($shareId);
    }
    /** @return array{expected_version:int,share_id:string} */
    public function toArray(): array
    {
        return ['expected_version' => $this->expectedVersion, 'share_id' => $this->shareId];
    }
}
