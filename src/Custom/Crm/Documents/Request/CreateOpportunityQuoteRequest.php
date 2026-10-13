<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents\Request;
use Factuarea\Sdk\Custom\Crm\Documents\Omitted;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
final readonly class CreateOpportunityQuoteRequest
{
    public function __construct(public QuoteDraft $quoteDraft, public string|null|Omitted $contactId = Omitted::Value)
    {
        if (is_string($contactId)) {
            Wire::uuid($contactId);
        }
    }
    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = ['quote_draft' => $this->quoteDraft->toArray()];
        if (!$this->contactId instanceof Omitted) {
            $out['contact_id'] = $this->contactId;
        }

return $out;
    }
}
