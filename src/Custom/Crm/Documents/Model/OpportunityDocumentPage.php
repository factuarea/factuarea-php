<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents\Model;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
use InvalidArgumentException;
final readonly class OpportunityDocumentPage
{
    /** @param list<OpportunityDocumentLink> $items */
    private function __construct(public string $opportunityId, public array $items, public ?string $nextCursor, public int $total, public int $pendingCount)
    {
    }
    public static function fromWire(mixed $value): self
    {
        $v = Wire::record($value, ['opportunity_id', 'items', 'next_cursor', 'total', 'pending_count']);
        $parent = Wire::uuid($v['opportunity_id']);
        $items = array_map(OpportunityDocumentLink::fromWire(...), Wire::items($v['items']));
        foreach ($items as $item) {
            if (strcasecmp($item->opportunityId, $parent) !== 0) {
                throw new InvalidArgumentException('The document page contains another parent.');
            }
        }

        return new self($parent, $items, Wire::nullableText($v['next_cursor'], 8192), Wire::integer($v['total'], true), Wire::integer($v['pending_count'], true));
    }
}
