<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\SalesBatch81;

use Factuarea\Sdk\Custom\Crm\Documents\Omitted;
use InvalidArgumentException;

/** Pure native input rules. No upstream Org, PAT, numeric identity or authority is introduced. */
final class Wire
{
    public static function uuid(string $value, bool $user = false): string
    {
        $version = $user ? '[47]' : '7';
        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-'.$version.'[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/iD', $value) !== 1) {
            throw new InvalidArgumentException('A native UUID is required.');
        }

        return $value;
    }
    public static function leadUuid(string $value): string
    {
        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/D', $value) !== 1) {
            throw new InvalidArgumentException('An exact lowercase UUID v7 is required for a native lead request.');
        }

        return $value;
    }
    public static function text(?string $value, int $maximum, bool $nonempty = false): void
    {
        if ($value !== null && (mb_strlen($value) > $maximum || ($nonempty && trim($value) === '')
            || preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', $value) !== 0)) {
            throw new InvalidArgumentException('The native text field is invalid.');
        }
    }
    public static function decimal(string $value): string
    {
        if (preg_match('/^(?:0|[1-9][0-9]{0,15})(?:\.[0-9]{1,4})?$/D', $value) !== 1) {
            throw new InvalidArgumentException('An exact nonnegative native decimal string is required.');
        }

        return $value;
    }
    public static function currency(string $value): string
    {
        if (preg_match('/^[A-Z]{3}$/D', $value) !== 1) {
            throw new InvalidArgumentException('An explicit native currency is required.');
        }

        return $value;
    }
    public static function optional(array $values): array
    {
        return array_filter($values, static fn (mixed $value): bool => !$value instanceof Omitted);
    }
    public static function encode(array $values): string
    {
        return json_encode((object) $values, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
