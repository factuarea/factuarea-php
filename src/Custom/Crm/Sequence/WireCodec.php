<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Sequence;

use Factuarea\Sdk\Custom\Crm\Schema;
use stdClass;

/** Validates public V1 grammar without rewriting original JSON bytes or identifier values. */
final class WireCodec
{
    /** @param list<string> $keys */
    public static function object(string $wire, array $keys): stdClass
    {
        $value = self::decode($wire);
        self::keys($value, $keys);

        return $value;
    }

    public static function decode(string $wire): stdClass
    {
        if ($wire === '' || strlen($wire) > 65536) {
            throw new \InvalidArgumentException('Invalid original JSON size.');
        }
        $value = json_decode($wire, false, 32, JSON_THROW_ON_ERROR);
        if (! $value instanceof stdClass) {
            throw new \InvalidArgumentException('A closed JSON object is required.');
        }
        // JSON decoding discards duplicate members. Inspect tokens first, including escaped names.
        preg_match_all('/"(?:[^"\\\\]|\\\\.)*"|[{}\[\]:,]|-?\d+(?:\.\d+)?(?:[eE][+-]?\d+)?|true|false|null/s', $wire, $matches);
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
                    throw new \InvalidArgumentException('Duplicate or internal UUID JSON member.');
                }
                $seen[$name] = true;
                $index++; // Colon; complete JSON syntax has already been checked by the native decoder.
            }
            self::scan($tokens, $index);
            if (($tokens[$index] ?? '') === ',') {
                $index++;
            }
        }
        $index++;
    }

    public static function id(mixed $id): string
    {
        if (! is_string($id) || preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/iD', $id) !== 1) {
            throw new \InvalidArgumentException('A native UUIDv7 public id is required.');
        }

        return $id;
    }

    public static function key(string $key): void
    {
        if ($key === '' || strlen($key) > 512 || preg_match('/[\x00-\x20\x7f]/', $key) === 1) {
            throw new \InvalidArgumentException('Supply the unchanged opaque original key (1–512 bytes, no whitespace/control bytes).');
        }
    }

    public static function version(mixed $version, int $minimum = 1): int
    {
        if (! is_int($version) || $version < $minimum || $version >= PHP_INT_MAX) {
            throw new \InvalidArgumentException('Supply the native integer CAS.');
        }

        return $version;
    }

    public static function subject(mixed $type): string
    {
        if (! in_array($type, ['lead', 'opportunity', 'business_contact'], true)) {
            throw new \InvalidArgumentException('Unknown native sequence subject.');
        }

        return $type;
    }

    public static function definition(mixed $definition): void
    {
        Schema::validate(Schemas::DEFINITION, $definition);
        if (! $definition instanceof stdClass || ! in_array($definition->timezone, \DateTimeZone::listIdentifiers(), true)) {
            throw new \InvalidArgumentException('Supply the native IANA timezone.');
        }
        /** @var list<object{weekday:int,start_minute:int,end_minute:int}> $windows */
        $windows = $definition->allowed_windows;
        foreach ($windows as $index => $window) {
            if ($window->start_minute >= $window->end_minute) {
                throw new \InvalidArgumentException('Invalid sequence time window.');
            }
            foreach (array_slice($windows, $index + 1) as $other) {
                if ($window->weekday === $other->weekday && $window->start_minute < $other->end_minute
                    && $other->start_minute < $window->end_minute) {
                    throw new \InvalidArgumentException('Sequence time windows overlap.');
                }
            }
        }
        /** @var list<object{type:string,recipe_id:?string,wait_seconds:?int}> $steps */
        $steps = $definition->steps;
        foreach ($steps as $step) {
            if (($step->type === 'wait' && ($step->recipe_id !== null || $step->wait_seconds === null))
                || ($step->type !== 'wait' && ($step->recipe_id === null || $step->wait_seconds !== null))) {
                throw new \InvalidArgumentException('Sequence step recipe/wait fields do not match its native type.');
            }
        }
    }

    /** @param list<string> $keys */
    private static function keys(stdClass $value, array $keys): void
    {
        $actual = array_keys(get_object_vars($value));
        if (count($actual) !== count($keys) || array_diff($actual, $keys) !== [] || array_diff($keys, $actual) !== []) {
            throw new \InvalidArgumentException('Unknown or missing sequence JSON member.');
        }
    }
}
