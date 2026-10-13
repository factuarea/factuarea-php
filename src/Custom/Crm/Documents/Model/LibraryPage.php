<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents\Model;
use Factuarea\Sdk\Custom\Crm\Documents\Wire;
final readonly class LibraryPage
{
    /** @param list<LibraryDocument> $items */
    private function __construct(public array $items, public int $total, public int $page, public int $perPage)
    {
    }
    public static function fromWire(mixed $value): self
    {
        $v = Wire::record($value, ['items', 'total', 'page', 'per_page']);

        return new self(array_map(LibraryDocument::fromWire(...), Wire::items($v['items'])), Wire::integer($v['total'], true), Wire::integer($v['page']), Wire::integer($v['per_page']));
    }
}
