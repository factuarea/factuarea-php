<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Pagination;

use Factuarea\Sdk\Custom\Pagination\PageIterator;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

final class PageIteratorTest extends TestCase
{
    private function page(array $data, ?string $nextCursor, bool $hasMore): ResponseInterface
    {
        return new Response(200, ['Content-Type' => 'application/json'], (string) json_encode([
            'data' => $data,
            'next_cursor' => $nextCursor,
            'has_more' => $hasMore,
        ]));
    }

    public function test_iterates_items_across_multiple_pages(): void
    {
        $pages = [
            $this->page([['id' => 1], ['id' => 2]], 'cursor_2', true),
            $this->page([['id' => 3], ['id' => 4]], 'cursor_3', true),
            $this->page([['id' => 5]], null, false),
        ];

        $requestedCursors = [];
        $iterator = new PageIterator(function (?string $cursor) use (&$pages, &$requestedCursors): ResponseInterface {
            $requestedCursors[] = $cursor;

            return array_shift($pages);
        });

        $ids = [];
        foreach ($iterator->items() as $item) {
            $ids[] = $item['id'];
        }

        $this->assertSame([1, 2, 3, 4, 5], $ids);
        $this->assertSame([null, 'cursor_2', 'cursor_3'], $requestedCursors);
    }

    public function test_all_collects_every_item(): void
    {
        $pages = [
            $this->page([['id' => 'a']], 'c2', true),
            $this->page([['id' => 'b']], null, false),
        ];

        $iterator = new PageIterator(function (?string $cursor) use (&$pages): ResponseInterface {
            return array_shift($pages);
        });

        $this->assertSame([['id' => 'a'], ['id' => 'b']], $iterator->all());
    }

    public function test_stops_when_has_more_is_false_even_if_a_cursor_is_present(): void
    {
        $calls = 0;
        $iterator = new PageIterator(function (?string $cursor) use (&$calls): ResponseInterface {
            $calls++;

            return $this->page([['id' => 1]], 'still_here', false);
        });

        iterator_to_array($iterator->items());

        $this->assertSame(1, $calls);
    }

    public function test_stops_when_next_cursor_is_null(): void
    {
        $calls = 0;
        $iterator = new PageIterator(function (?string $cursor) use (&$calls): ResponseInterface {
            $calls++;

            return $this->page([['id' => 1]], null, true);
        });

        iterator_to_array($iterator->items());

        $this->assertSame(1, $calls);
    }

    public function test_handles_an_empty_listing(): void
    {
        $iterator = new PageIterator(fn (?string $cursor): ResponseInterface => $this->page([], null, false));

        $this->assertSame([], $iterator->all());
    }

    public function test_supports_the_alternate_cursor_key(): void
    {
        // Overdue/pending purchase-invoice listings use `cursor` rather than `starting_after`,
        // but the response envelope keys are identical, so default keys still apply.
        $pages = [
            new Response(200, [], (string) json_encode(['data' => [1], 'next_cursor' => 'n', 'has_more' => true])),
            new Response(200, [], (string) json_encode(['data' => [2], 'next_cursor' => null, 'has_more' => false])),
        ];

        $iterator = new PageIterator(function (?string $cursor) use (&$pages): ResponseInterface {
            return array_shift($pages);
        });

        $this->assertSame([1, 2], $iterator->all());
    }
}
