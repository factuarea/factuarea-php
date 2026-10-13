<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents;

use DateTimeImmutable;
use InvalidArgumentException;

/** Closed current v1 primitives. Identity/version/decimal values are never coerced. */
final class Wire
{
    /**
     * @param  list<string>  $required
     * @param  list<string>  $optional
     * @return array<string,mixed>
     */
    public static function record(mixed $value, array $required, array $optional = []): array
    {
        if (!is_array($value) || array_diff($required, array_keys($value)) !== [] || array_diff(array_keys($value), [...$required, ...$optional]) !== []) {
            throw new InvalidArgumentException('The document response contains missing or unsupported fields.');
        }
        $result = [];
        foreach ([...$required, ...$optional] as $key) {
            if (array_key_exists($key, $value)) {
                $result[$key] = $value[$key];
            }
        }

        return $result;
    }
    /** @return list<mixed> */
    public static function items(mixed $value): array
    {
        if (!is_array($value) || !array_is_list($value)) {
            throw new InvalidArgumentException('A document collection must be a list.');
        }

        return $value;
    }
    public static function text(mixed $value, int $max = 262144, bool $empty = false): string
    {
        if (!is_string($value) || strlen($value) > $max || (!$empty && trim($value) === '')) {
            throw new InvalidArgumentException('A document string has an invalid type or length.');
        }

        return $value;
    }
    public static function nullableText(mixed $value, int $max = 262144): ?string
    {
        return $value === null ? null : self::text($value, $max);
    }
    public static function integer(mixed $value, bool $zero = false): int
    {
        if (!is_int($value) || $value < ($zero ? 0 : 1) || $value >= PHP_INT_MAX) {
            throw new InvalidArgumentException('A document version/count must be an integer in its native range.');
        }

        return $value;
    }
    public static function boolean(mixed $value): bool
    {
        if (!is_bool($value)) {
            throw new InvalidArgumentException('A document boolean must retain its JSON type.');
        }

        return $value;
    }
    public static function uuid(mixed $value, bool $nativeOwner = false): string
    {
        $pattern = $nativeOwner ? '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/iD' : '/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/iD';
        if (!is_string($value) || preg_match($pattern, $value) !== 1 || in_array(strtolower($value), ['00000000-0000-0000-0000-000000000000', 'ffffffff-ffff-ffff-ffff-ffffffffffff'], true)) {
            throw new InvalidArgumentException('The document identity must be its native UUID.');
        }

        return $value;
    }
    public static function documentType(mixed $value): string
    {
        if (!is_string($value) || !in_array($value, ['quote', 'invoice', 'contract', 'recurring_schedule'], true)) {
            throw new InvalidArgumentException('Unsupported native document type.');
        }

        return $value;
    }
    public static function currency(mixed $value): string
    {
        if (!is_string($value) || preg_match('/^[A-Z]{3}$/D', $value) !== 1) {
            throw new InvalidArgumentException('Currency must retain its native code.');
        }

        return $value;
    }
    public static function decimal(mixed $value, int $scale = 2): string
    {
        if (!is_string($value) || preg_match('/^(?:0|[1-9][0-9]{0,17})\.[0-9]{'.$scale.'}$/D', $value) !== 1) {
            throw new InvalidArgumentException('Document money must be an exact decimal string.');
        }

        return $value;
    }
    public static function date(mixed $value): string
    {
        $value = self::text($value, 10);
        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);
        if ($date === false || $date->format('Y-m-d') !== $value) {
            throw new InvalidArgumentException('Document dates must use YYYY-MM-DD.');
        }

        return $value;
    }
    public static function timestamp(mixed $value): string
    {
        $value = self::text($value, 100);
        if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d{1,6})?(?:Z|[+-]\d{2}:\d{2})$/D', $value) !== 1) {
            throw new InvalidArgumentException('Document timestamps require an explicit timezone.');
        }
        new DateTimeImmutable($value);

        return $value;
    }
    public static function key(string $value): string
    {
        if (strlen($value) > 255 || preg_match('/^[\x20-\x7e]+$/D', $value) !== 1) {
            throw new InvalidArgumentException('Idempotency-Key requires 1 to 255 ASCII characters.');
        }

        return $value;
    }
    public static function mime(string $value): string
    {
        if (strlen($value) > 255 || preg_match('/^[a-z0-9][a-z0-9!#$&^_.+-]*\/[a-z0-9][a-z0-9!#$&^_.+-]*$/D', $value) !== 1) {
            throw new InvalidArgumentException('Invalid claimed MIME.');
        }

        return $value;
    }
}
