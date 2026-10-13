<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm;

use Factuarea\Sdk\Custom\Pagination\PageIterator;
use GuzzleHttp\Psr7\Response;

/** Adapts native CRM envelopes to the existing SDK cursor iterator. */
final class Pagination
{
    /**
     * @param  callable(?string): CrmResponse<WireModel>  $fetch
     * @param  list<string>  $itemsPath
     * @param  array<string, mixed>  $itemType
     * @return \Generator<int, WireModel>
     */
    public static function items(callable $fetch, array $itemsPath, array $itemType, string $mode, int $maxPages): \Generator
    {
        if ($maxPages < 1) {
            throw new \InvalidArgumentException('maxPages must be positive.');
        }
        $pages = 0;
        $seen = 0;
        $seenCursors = [];
        $nativeItems = [];
        $iterator = new PageIterator(static function (?string $cursor) use ($fetch, $itemsPath, $mode, $maxPages, &$pages, &$seen, &$seenCursors, &$nativeItems): Response {
            if (++$pages > $maxPages) {
                throw new \OverflowException('CRM pagination reached maxPages; resume with the last native cursor or page.');
            }
            $result = $fetch($cursor);
            $body = json_decode((string) $result->rawResponse->getBody(), false, 512, JSON_THROW_ON_ERROR);
            if (! $body instanceof \stdClass) {
                throw new \UnexpectedValueException('The CRM page must be an object.');
            }
            $body = (array) $body;
            $items = self::at($body, $itemsPath);
            if (! is_array($items) || ! array_is_list($items)) {
                throw new \UnexpectedValueException('The CRM page must contain a list of items.');
            }
            $nativeItems = $items;
            $seen += count($items);
            $parent = self::at($body, array_slice($itemsPath, 0, -1));
            if ($parent instanceof \stdClass) {
                $parent = (array) $parent;
            }
            if (! is_array($parent)) {
                throw new \UnexpectedValueException('The CRM page metadata is missing.');
            }
            if ($mode === 'cursor') {
                $hasMore = $parent['has_more'] ?? null;
                $next = $parent['next_cursor'] ?? null;
                if (! is_bool($hasMore) || ($hasMore && (! is_string($next) || $next === ''))) {
                    throw new \UnexpectedValueException('The CRM cursor metadata is invalid.');
                }
            } elseif ($mode === 'numbered') {
                $page = $parent['page'] ?? null;
                $perPage = $parent['per_page'] ?? null;
                $total = $parent['total'] ?? null;
                if (! is_int($page) || $page < 1 || ! is_int($perPage) || $perPage < 1 || ! is_int($total) || $total < 0) {
                    throw new \UnexpectedValueException('The CRM numbered page metadata is invalid.');
                }
                $hasMore = $page * $perPage < $total;
                $next = $hasMore ? (string) ($page + 1) : null;
            } else {
                $meta = (array) ($body['meta'] ?? []);
                $page = $meta['current_page'] ?? null;
                $total = $meta['total'] ?? null;
                if (! is_int($page) || ! is_int($total)) {
                    throw new \UnexpectedValueException('The CRM page metadata is invalid.');
                }
                $hasMore = count($items) > 0 && (isset($meta['last_page'])
                    ? $page < $meta['last_page'] : ! ($pages === $page && $seen >= $total));
                $next = $hasMore ? (string) ($page + 1) : null;
            }
            if ($hasMore) {
                if (isset($seenCursors[$next])) {
                    throw new \UnexpectedValueException('CRM pagination repeated its cursor or page.');
                }
                $seenCursors[$next] = true;
            }

            return new Response(200, ['Content-Type' => 'application/json'], json_encode([
                // Keep objects outside the canonical iterator's associative JSON decoder.
                'data' => array_keys($items), 'has_more' => $hasMore, 'next_cursor' => $next,
            ], JSON_THROW_ON_ERROR));
        });
        foreach ($iterator->items() as $index) {
            if (! is_int($index) || ! array_key_exists($index, $nativeItems)) {
                throw new \UnexpectedValueException('The CRM item index is invalid.');
            }
            yield WireModel::hydrateValue($nativeItems[$index], $itemType);
        }
    }

    /** @param array<string, mixed> $body @param list<string> $path */
    private static function at(array $body, array $path): mixed
    {
        $value = $body;
        foreach ($path as $key) {
            if ($value instanceof \stdClass) {
                $value = (array) $value;
            }
            if (! is_array($value) || ! array_key_exists($key, $value)) {
                throw new \UnexpectedValueException('A documented CRM page field is missing.');
            }
            $value = $value[$key];
        }

        return $value;
    }
}
