<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\CustomerService;

use Factuarea\Sdk\Custom\Crm\Documents\Wire;
use DateTimeImmutable;
use InvalidArgumentException;

/** Shared closed v1 primitives; ticket UUIDs are v7, actor UUIDs keep their native version. */
final class TicketWire
{
    /** @param list<string> $required
     * @param  list<string>  $optional
     * @return array<string,mixed>
     */
    public static function record(mixed $wire, array $required, array $optional = []): array
    {
        return Wire::record($wire, $required, $optional);
    }
    public static function actor(mixed $wire): string
    {
        if (!is_string($wire) || preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/iD', $wire) !== 1) {
            throw new InvalidArgumentException('An actor must retain its native UUID.');
        }

        return $wire;
    }
    /** Public enum strings are retained so newer server values can be read without inventing behavior.
     */
    public static function enumText(mixed $wire): string
    {
        $value = Wire::text($wire, 255);
        if (preg_match('/^[a-z][a-z0-9_]*$/D', $value) !== 1) {
            throw new InvalidArgumentException('A ticket enum must retain its native string.');
        }

        return $value;
    }
    public static function instant(mixed $wire): string
    {
        $value = Wire::timestamp($wire);
        new DateTimeImmutable($value);
        $errors = DateTimeImmutable::getLastErrors();
        if ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)) {
            throw new InvalidArgumentException('A ticket timestamp must be a valid instant.');
        }

        return $value;
    }
    public static function page(int $page, int $perPage): void
    {
        if ($page < 1 || $page > 1000000 || $perPage < 1 || $perPage > 100) {
            throw new InvalidArgumentException('Ticket pages require page 1..1000000 and per_page 1..100.');
        }
    }
}
