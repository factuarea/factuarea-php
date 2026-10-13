<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents\Model;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
use InvalidArgumentException;
final readonly class CandidatePage
{
    /** @param list<Candidate> $items */
    private function __construct(public string $opportunityId, public string $documentType, public array $items, public ?string $nextCursor, public bool $available, public ?string $unavailableReason)
    {
    }
    public static function fromWire(mixed $value): self
    {
        $v = Wire::record($value, ['opportunity_id', 'document_type', 'items', 'next_cursor', 'available', 'unavailable_reason']);
        $items = array_map(Candidate::fromWire(...), Wire::items($v['items']));
        $type = Wire::documentType($v['document_type']);
        $available = Wire::boolean($v['available']);
        $reason = Wire::nullableText($v['unavailable_reason'], 255);
        $cursor = Wire::nullableText($v['next_cursor'], 32768);
        if (($available && $reason !== null) || (!$available && ($reason === null || $items !== [] || $cursor !== null))) {
            throw new InvalidArgumentException('Unavailable candidates must not fabricate entries or a cursor.');
        }
        foreach ($items as $item) {
            if ($item->type !== $type) {
                throw new InvalidArgumentException('Candidate belongs to another owner.');
            }
        }

        return new self(Wire::uuid($v['opportunity_id']), $type, $items, $cursor, $available, $reason);
    }
}
