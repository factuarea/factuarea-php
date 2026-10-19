<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\TaskTimeBilling;

use Factuarea\Sdk\Custom\Crm\Schema;
use stdClass;

/** Closed V1 grammar; validation never normalizes original bytes, IDs or keys. */
final class WireCodec
{
    public static function decode(string $wire): stdClass
    {
        if ($wire === '' || strlen($wire) > 262144) {
            throw new \InvalidArgumentException('Native Task time JSON is limited to 262144 bytes.');
        }
        $value = json_decode($wire, false, 32, JSON_THROW_ON_ERROR);
        if (! $value instanceof stdClass) {
            throw new \InvalidArgumentException('A closed native JSON object is required.');
        }
        preg_match_all('/"(?:[^"\\\\]|\\\\.)*"|[{}\[\]:,]|-?\d+(?:\.\d+)?(?:[eE][+-]?\d+)?|true|false|null/s', $wire, $matches);
        $index = 0;
        self::scan($matches[0], $index);

        return $value;
    }

    public static function intent(string $action, string $wire): stdClass
    {
        if (! in_array($action, ['link', 'unlink'], true)) {
            throw new \InvalidArgumentException('Only native link/unlink have an original write intent.');
        }
        $value = self::decode($wire);
        self::keys($value, ['task_id', 'expected_ticket_version', 'expected_task_version', 'confirmed', 'binding_id', 'expected_binding_version'],
            ['task_id', 'expected_ticket_version', 'expected_task_version', 'confirmed']);
        self::base($value);
        if (($value->confirmed ?? null) !== true) {
            throw new \InvalidArgumentException('The native write requires explicit confirmed=true.');
        }
        $binding = $value->binding_id ?? null;
        $version = $value->expected_binding_version ?? null;
        if ($binding !== null) {
            self::id($binding);
        }
        if ($version !== null) {
            self::version($version);
        }
        if (($binding === null) !== ($version === null) || ($action === 'unlink' && $binding === null)) {
            throw new \InvalidArgumentException('A retained binding and its CAS must be supplied together; unlink requires both.');
        }

        return $value;
    }

    public static function preview(string $wire): stdClass
    {
        $value = self::decode($wire);
        Schema::validate(Schemas::PREVIEW_REQUEST, $value);
        $seen = [];
        foreach ($value->entries as $entry) {
            if (isset($seen[$entry->id])) {
                throw new \InvalidArgumentException('Select each native entry only once.');
            }
            $seen[$entry->id] = true;
        }

        return $value;
    }

    public static function recovery(string $action, string $wire): stdClass
    {
        if (! in_array($action, ['link', 'unlink'], true)) {
            throw new \InvalidArgumentException('Unknown original receipt action.');
        }
        $value = self::decode($wire);
        self::keys($value, ['task_id', 'expected_ticket_version', 'expected_task_version', 'binding_id', 'expected_binding_version', 'action', 'effect_id'],
            ['task_id', 'expected_ticket_version', 'expected_task_version', 'binding_id', 'action', 'effect_id']);
        self::base($value);
        self::id($value->binding_id);
        self::id($value->effect_id);
        if ($value->action !== $action || ($action === 'unlink' && ($value->expected_binding_version ?? null) === null)) {
            throw new \InvalidArgumentException('Original receipt action/CAS does not match its native route.');
        }
        if (($value->expected_binding_version ?? null) !== null) {
            self::version($value->expected_binding_version);
        }

        return $value;
    }

    public static function id(mixed $id): string
    {
        if (! is_string($id) || preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/iD', $id) !== 1) {
            throw new \InvalidArgumentException('A native UUIDv7 id is required.');
        }

        return $id;
    }

    public static function version(mixed $version): int
    {
        if (! is_int($version) || $version < 1 || $version >= PHP_INT_MAX) {
            throw new \InvalidArgumentException('Supply the native positive integer CAS.');
        }

        return $version;
    }

    public static function key(string $key): void
    {
        if ($key === '' || trim($key) === '' || $key !== trim($key, ' ') || strlen($key) > 255 || preg_match('/^[\x20-\x7e]+$/D', $key) !== 1) {
            throw new \InvalidArgumentException('Supply the unchanged original printable ASCII key (1–255 bytes, no exterior header whitespace).');
        }
    }

    private static function base(stdClass $value): void
    {
        self::id($value->task_id);
        self::version($value->expected_ticket_version);
        self::version($value->expected_task_version);
    }

    /** @param list<string> $allowed
     * @param list<string> $required */
    private static function keys(stdClass $value, array $allowed, array $required): void
    {
        $keys = array_keys(get_object_vars($value));
        if (array_diff($keys, $allowed) !== [] || array_diff($required, $keys) !== []) {
            throw new \InvalidArgumentException('Unknown or missing native Task time member.');
        }
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
                if (! is_string($name) || isset($seen[$name])) {
                    throw new \InvalidArgumentException('Duplicate native JSON member.');
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
}
