<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

use Factuarea\Sdk\Custom\Crm\Schema;
use stdClass;

/** Exact bytes/key validation; current C7, template, TTL and grants remain the native server's decision. */
final class WireCodec
{
    public static function id(string $id): void
    {
        Schema::validate(Schemas::UUID, $id);
    }
    public static function key(string $key): void
    {
        Schema::validate(Schemas::HASH, $key);
    }
    public static function token(string $token): void
    {
        if ($token === '' || strlen($token) > 32768) {
            throw new \InvalidArgumentException('Keep the original opaque plan token (1–32768 bytes) unchanged.');
        }
    }
    /** @param list<string> $values */
    public static function unique(array $values): void
    {
        if (count($values) !== count(array_unique($values))) {
            throw new \InvalidArgumentException('Native item identities must be unique.');
        }
    }
    public static function object(string $wire, int $maximum = 65536): stdClass
    {
        if ($wire === '' || strlen($wire) > $maximum) {
            throw new \InvalidArgumentException('The native wire exceeds its bounded request size.');
        }
        $value = json_decode($wire, false, 32, JSON_THROW_ON_ERROR);
        if (!$value instanceof stdClass) {
            throw new \InvalidArgumentException('A closed original JSON object is required.');
        }
        $index = 0;
        self::scan($wire, $index);

        return $value;
    }
    public static function apply(string $wire): ApplyRequest
    {
        return ApplyRequest::fromArray(self::fields(self::object($wire)));
    }
    public static function undo(string $wire): UndoRequest
    {
        return UndoRequest::fromArray(self::fields(self::object($wire)));
    }
    public static function query(string $case, string $wire): void
    {
        $values = self::fields(self::object($wire, 262144));
        match ($case) {
            'preview' => PreviewRequest::fromArray($values), 'plan', 'conflicts' => PlanReadRequest::fromArray($values),
            'recover' => RecoveryRequest::fromArray($values), default => throw new \InvalidArgumentException('Unknown native query-only request.')
        };
    }
    /** @return array<string,mixed> */
    private static function fields(stdClass $object): array
    {
        $fields = [];
        foreach (get_object_vars($object) as $name => $value) {
            if (!is_string($name)) {
                throw new \InvalidArgumentException('A named native JSON member is required.');
            }
            $fields[$name] = $value;
        }

        return $fields;
    }

    /** Walk already valid, depth-bounded JSON without a regex limit on escaped recovery strings. */
    private static function scan(string $wire, int &$index): void
    {
        $index += strspn($wire, " \t\r\n", $index);
        $open = $wire[$index] ?? '';
        if ($open === '"') {
            self::stringToken($wire, $index);

            return;
        }
        if ($open !== '{' && $open !== '[') {
            $index += strcspn($wire, ",]} \t\r\n", $index);

            return;
        }
        $index++;
        $close = $open === '{' ? '}' : ']';
        $seen = [];
        while (true) {
            $index += strspn($wire, " \t\r\n", $index);
            if (($wire[$index] ?? '') === $close) {
                $index++;

                return;
            }
            if ($open === '{') {
                $name = json_decode(self::stringToken($wire, $index), true, 32, JSON_THROW_ON_ERROR);
                if (!is_string($name) || isset($seen[$name])) {
                    throw new \InvalidArgumentException('Duplicate original JSON member.');
                }
                $seen[$name] = true;
                $index += strspn($wire, " \t\r\n", $index);
                $index++;
            }
            self::scan($wire, $index);
            $index += strspn($wire, " \t\r\n", $index);
            if (($wire[$index] ?? '') === ',') {
                $index++;
            }
        }
    }

    private static function stringToken(string $wire, int &$index): string
    {
        $start = $index++;
        while ($index < strlen($wire)) {
            $char = $wire[$index++];
            if ($char === '\\') {
                $index++;
            } elseif ($char === '"') {
                return substr($wire, $start, $index - $start);
            }
        }
        throw new \InvalidArgumentException('An original JSON string must be closed.');
    }
}
