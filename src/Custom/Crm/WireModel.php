<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm;

/**
 * Base for DTOs generated from the frozen native CRM contract.
 *
 */
abstract class WireModel implements \JsonSerializable
{
    /** @var array<string, array{property: string, type: array<string, mixed>}> */
    public const FIELDS = [];

    /** @var array<string, mixed> */
    public const SCHEMA = [];

    /** @var array<string, mixed> */
    private array $additional = [];

    /** @param array<string, mixed> $values */
    final public static function fromArray(array $values): static
    {
        Schema::validate(static::SCHEMA, (object) $values);
        $arguments = [];
        foreach (static::FIELDS as $wire => $field) {
            if (array_key_exists($wire, $values)) {
                $arguments[$field['property']] = self::hydrate($values[$wire], $field['type']);
            }
        }
        $model = (new \ReflectionClass(static::class))->newInstanceArgs($arguments);
        $model->additional = array_diff_key($values, static::FIELDS);

        return $model;
    }

    /** @return array<string, mixed> */
    final public function toArray(): array
    {
        $values = $this->additional;
        foreach (static::FIELDS as $wire => $field) {
            $value = $this->{$field['property']};
            if ($value !== Omitted::Value) {
                $values[$wire] = self::encode($value);
            }
        }

        return $values;
    }

    final public function toJson(): string
    {
        $this->validate();

        return json_encode($this, JSON_THROW_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION);
    }

    final public function validate(): void
    {
        Schema::validate(static::SCHEMA, $this->jsonSerialize());
    }

    final public function jsonSerialize(): object
    {
        return (object) $this->toArray();
    }

    /** @param array<string, mixed> $type */
    final public static function hydrateValue(mixed $value, array $type): mixed
    {
        return self::hydrate($value, $type);
    }

    /** @param array<string, mixed> $type */
    private static function hydrate(mixed $value, array $type): mixed
    {
        if ($value === null) {
            return null;
        }
        if (isset($type['object'])) {
            if (! $value instanceof \stdClass && (! is_array($value) || array_is_list($value))) {
                throw new \InvalidArgumentException('A CRM object was expected.');
            }

            return (object) $value;
        }
        if (isset($type['union']) && is_array($type['union'])) {
            foreach ($type['union'] as $variant) {
                if (! is_array($variant)) {
                    continue;
                }
                try {
                    return self::hydrate($value, $variant);
                } catch (\TypeError|\InvalidArgumentException|\ArgumentCountError) {
                    // Select a native success variant by its required fields.
                }
            }
            throw new \UnexpectedValueException('The CRM response does not match a documented variant.');
        }
        if (isset($type['model']) && is_string($type['model'])) {
            if (! $value instanceof \stdClass && (! is_array($value) || array_is_list($value))) {
                throw new \InvalidArgumentException('A CRM object was expected.');
            }
            $class = $type['model'];
            if (! is_subclass_of($class, self::class)) {
                throw new \LogicException('The generated CRM DTO type is invalid.');
            }

            return $class::fromArray((array) $value);
        }
        if (isset($type['items']) && is_array($type['items'])) {
            if (! is_array($value)) {
                throw new \InvalidArgumentException('A CRM array was expected.');
            }

            return array_map(static fn (mixed $item): mixed => self::hydrate($item, $type['items']), $value);
        }
        if (isset($type['scalar']) && is_string($type['scalar'])) {
            $valid = match ($type['scalar']) {
                'string' => is_string($value),
                'int' => is_int($value),
                'float' => is_int($value) || is_float($value),
                'bool' => is_bool($value),
                'array' => is_array($value),
                'null' => false,
                default => true,
            };
            if (! $valid) {
                throw new \InvalidArgumentException('A CRM field has an unexpected type.');
            }
        }

        return $value;
    }

    private static function encode(mixed $value): mixed
    {
        if ($value instanceof self) {
            return $value->jsonSerialize();
        }
        if (is_array($value)) {
            return array_map(self::encode(...), $value);
        }

        return $value;
    }
}
