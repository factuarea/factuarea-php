<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\SalesBatch81;

use UnexpectedValueException;

final readonly class LeadPage
{
    private function __construct(public array $items, public ?string $nextCursor, public bool $hasMore)
    {
    }
    public static function decode(string $raw): self
    {
        $root = json_decode($raw, false, 64, JSON_THROW_ON_ERROR);
        $page = $root->data ?? null;
        if (!$root instanceof \stdClass || !$page instanceof \stdClass || !is_array($page->data ?? null) || !array_is_list($page->data)
            || !is_bool($page->has_more ?? null) || !property_exists($page, 'next_cursor')
            || ($page->next_cursor !== null && (!is_string($page->next_cursor) || $page->next_cursor === '' || strlen($page->next_cursor) > 4096))
            || $page->has_more !== ($page->next_cursor !== null)) {
            throw new UnexpectedValueException('Invalid native Lead cursor envelope.');
        }
        $items = [];
        foreach ($page->data as $data) {
            $items[] = SalesResult::decode('lead', json_encode(['data' => $data], JSON_THROW_ON_ERROR), 200, null);
        }

        return new self($items, $page->next_cursor, $page->has_more);
    }
}
