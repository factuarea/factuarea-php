<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents\Request;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
use InvalidArgumentException;
final readonly class OpportunityDocumentFilters
{
    public function __construct(public ?string $documentType = null, public ?string $status = null, public ?bool $isPrimary = null, public ?string $sort = null, public ?string $direction = null)
    {
        if ($documentType !== null) {
            Wire::documentType($documentType);
        }
        if (($status !== null && !in_array($status, ['pending', 'linked', 'conflict'], true)) || ($sort !== null && !in_array($sort, ['id', 'version', 'document_type', 'document_id', 'status', 'is_primary'], true)) || ($direction !== null && !in_array($direction, ['asc', 'desc'], true))) {
            throw new InvalidArgumentException('Unsupported native document filter.');
        }
    }
    public function toJson(): string
    {
        $out = [];
        foreach (['document_type' => $this->documentType, 'status' => $this->status, 'is_primary' => $this->isPrimary, 'sort' => $this->sort, 'direction' => $this->direction] as $key => $value) {
            if ($value !== null) {
                $out[$key] = $value;
            }
        }

        return json_encode((object) $out, JSON_THROW_ON_ERROR);
    }
}
