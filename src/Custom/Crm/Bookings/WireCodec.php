<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Bookings;

/** Public IDs and ORIGINAL bytes are never normalized or converted into authority. */
final class WireCodec
{
    public static function object(#[\SensitiveParameter] string $wire, int $maximum = 65536): \stdClass
    {
        if ($wire === '' || strlen($wire) > $maximum) {
            throw new \InvalidArgumentException('Invalid original Booking JSON size.');
        }
        $value = json_decode($wire, false, 32, JSON_THROW_ON_ERROR);
        if (! $value instanceof \stdClass) {
            throw new \InvalidArgumentException('A closed native Booking JSON object is required.');
        }
        if (preg_match_all('/"(?:[^"\\\\]++|\\\\.)*"|[{}\[\]:,]|-?\d+(?:\.\d+)?(?:[eE][+-]?\d+)?|true|false|null/s', $wire, $matches) === false) {
            throw new \InvalidArgumentException('The original Booking JSON could not be inspected completely.');
        }
        $index = 0;
        self::scan($matches[0], $index);

        return $value;
    }

    /** @param list<string> $tokens */
    private static function scan(array $tokens, int &$index): void
    {
        $open = $tokens[$index++] ?? '';
        if ($open !== '{' && $open !== '[') {
            return;
        }
        $close = $open === '{' ? '}' : ']';
        $seen = [];
        while (($tokens[$index] ?? '') !== $close) {
            if ($open === '{') {
                $name = json_decode($tokens[$index++] ?? '', true, 32, JSON_THROW_ON_ERROR);
                if (! is_string($name) || isset($seen[$name]) || $name === 'uuid' || str_ends_with($name, '_uuid')) {
                    throw new \InvalidArgumentException('Duplicate or internal Booking JSON member.');
                }
                $seen[$name] = true;
                $index++;
            }
            self::scan($tokens, $index);
            if (($tokens[$index] ?? '') === ',') {
                $index++;
            }
        }
        $index++;
    }

    public static function id(string $id): void
    {
        if (preg_match(Schemas::ID_PATTERN, $id) !== 1) {
            throw new \InvalidArgumentException('A lowercase native Booking UUIDv7 id is required.');
        }
    }

    public static function publicId(string $id): void
    {
        if (preg_match('/^[A-Za-z0-9_-]{32,128}$/D', $id) !== 1) {
            throw new \InvalidArgumentException('The original native Booking public ID is required.');
        }
    }

    public static function version(int $version): void
    {
        if ($version < 1 || $version >= PHP_INT_MAX) {
            throw new \InvalidArgumentException('A positive native Booking CAS is required.');
        }
    }

    public static function key(#[\SensitiveParameter] string $key, bool $public): void
    {
        if ($public ? preg_match('/^[a-f0-9]{64}$/D', $key) !== 1
            : ($key === '' || strlen($key) > 200 || preg_match('/[\x00-\x1f\x7f]/', $key) === 1)) {
            throw new \InvalidArgumentException('Retain the original native Booking key for this surface.');
        }
        if (! $public && (str_starts_with($key, ' ') || str_ends_with($key, ' '))) {
            throw new \InvalidArgumentException('HTTP header whitespace must not change the original Booking key.');
        }
    }

    public static function managementToken(#[\SensitiveParameter] string $token): void
    {
        if (preg_match('/^bk1_[a-f0-9]{64}$/D', $token) !== 1) {
            throw new \InvalidArgumentException('The original native Booking management token is required.');
        }
    }

    public static function instant(string $instant): void
    {
        $date = \DateTimeImmutable::createFromFormat('!Y-m-d\TH:i:s\Z', $instant, new \DateTimeZone('UTC'));
        if ($date === false || $date->format('Y-m-d\TH:i:s\Z') !== $instant) {
            throw new \InvalidArgumentException('Booking requires an explicit UTC second-precision instant.');
        }
    }

    public static function interval(string $from, string $through): void
    {
        self::instant($from);
        self::instant($through);
        if ($from >= $through) {
            throw new \InvalidArgumentException('The native Booking interval must be ordered.');
        }
    }
}
