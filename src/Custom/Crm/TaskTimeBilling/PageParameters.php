<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

/** Cursor belongs to the native current revision; it is never derived from a local ID. */
final readonly class PageParameters
{
    public function __construct(public ?string $cursor = null, public int $limit = 50)
    {
        if ($limit < 1 || $limit > 100 || ($cursor !== null && preg_match('/^[a-f0-9]{64}:[a-f0-9-]{36}$/D', $cursor) !== 1)) {
            throw new \InvalidArgumentException('Invalid native Task time page.');
        }
    }

    /** @return array<string,scalar|null> */
    public function query(): array
    {
        return $this->cursor === null ? ['limit' => $this->limit] : ['limit' => $this->limit, 'cursor' => $this->cursor];
    }
}
