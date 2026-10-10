<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm;

/** Validates DTO values against the frozen owner's JSON Schema without coercion. */
final class Schema
{
    /** @var array<string, mixed>|null */
    private static ?array $document = null;

    /** @param array<string, mixed> $schema */
    public static function validate(array $schema, mixed $value, string $path = '$'): void
    {
        if (isset($schema['$ref']) && is_string($schema['$ref'])) {
            $name = substr($schema['$ref'], strlen('#/components/schemas/'));
            self::validate(self::definition($name), $value, $path);

            return;
        }
        foreach (['allOf', 'anyOf', 'oneOf'] as $keyword) {
            if (isset($schema[$keyword]) && is_array($schema[$keyword])) {
                $matches = 0;
                foreach ($schema[$keyword] as $candidate) {
                    if (! is_array($candidate)) {
                        throw new \LogicException('Invalid generated CRM schema.');
                    }
                    try {
                        self::validate($candidate, $value, $path);
                        $matches++;
                    } catch (\InvalidArgumentException) {
                        // Each branch is the native owner's schema, including closed variants.
                    }
                }
                if (($keyword === 'allOf' && $matches !== count($schema[$keyword]))
                    || ($keyword === 'anyOf' && $matches === 0) || ($keyword === 'oneOf' && $matches !== 1)) {
                    self::invalid($path, $keyword);
                }
            }
        }
        if (isset($schema['not']) && is_array($schema['not']) && self::matches($schema['not'], $value)) {
            self::invalid($path, 'not');
        }
        if (isset($schema['if']) && is_array($schema['if'])) {
            $branch = self::matches($schema['if'], $value) ? 'then' : 'else';
            if (isset($schema[$branch]) && is_array($schema[$branch])) {
                self::validate($schema[$branch], $value, $path);
            }
        }
        if (array_key_exists('const', $schema) && $value !== $schema['const']) {
            self::invalid($path, 'const');
        }
        if (isset($schema['enum']) && is_array($schema['enum']) && ! in_array($value, $schema['enum'], true)) {
            self::invalid($path, 'enum');
        }
        if (isset($schema['type'])) {
            $types = is_array($schema['type']) ? $schema['type'] : [$schema['type']];
            $valid = false;
            foreach ($types as $type) {
                $valid = $valid || match ($type) {
                    'null' => $value === null,
                    'string' => is_string($value),
                    'integer' => is_int($value),
                    'number' => is_int($value) || is_float($value),
                    'boolean' => is_bool($value),
                    'object' => $value instanceof \stdClass || (is_array($value) && ! array_is_list($value)),
                    'array' => is_array($value) && array_is_list($value),
                    default => false,
                };
            }
            if (! $valid) {
                self::invalid($path, 'type');
            }
        }
        if (is_string($value)) {
            $length = mb_strlen($value);
            if ((isset($schema['minLength']) && $length < $schema['minLength'])
                || (isset($schema['maxLength']) && $length > $schema['maxLength'])) {
                self::invalid($path, 'length');
            }
            if (isset($schema['pattern']) && is_string($schema['pattern'])) {
                $pattern = '~'.str_replace('~', '\\~', $schema['pattern']).'~uD';
                if (preg_match($pattern, $value) !== 1) {
                    self::invalid($path, 'pattern');
                }
            }
        }
        if (is_int($value) || is_float($value)) {
            if ((isset($schema['minimum']) && $value < $schema['minimum'])
                || (isset($schema['maximum']) && $value > $schema['maximum'])) {
                self::invalid($path, 'range');
            }
        }
        if ($value instanceof \stdClass || (is_array($value) && ! array_is_list($value))) {
            $fields = (array) $value;
            $properties = $schema['properties'] ?? [];
            if (! is_array($properties)) {
                throw new \LogicException('Invalid CRM object schema.');
            }
            foreach ($schema['required'] ?? [] as $required) {
                if (! array_key_exists($required, $fields)) {
                    self::invalid($path.'.'.$required, 'required');
                }
            }
            foreach ($fields as $key => $item) {
                if (isset($schema['propertyNames']) && is_array($schema['propertyNames'])) {
                    self::validate($schema['propertyNames'], (string) $key, $path);
                }
                if (isset($properties[$key]) && is_array($properties[$key])) {
                    self::validate($properties[$key], $item, $path.'.'.$key);
                } elseif (($schema['additionalProperties'] ?? true) === false) {
                    self::invalid($path.'.'.$key, 'unknown field');
                } elseif (isset($schema['additionalProperties']) && is_array($schema['additionalProperties'])) {
                    self::validate($schema['additionalProperties'], $item, $path.'.'.$key);
                }
            }
            if ((isset($schema['minProperties']) && count($fields) < $schema['minProperties'])
                || (isset($schema['maxProperties']) && count($fields) > $schema['maxProperties'])) {
                self::invalid($path, 'properties');
            }
        }
        if (is_array($value) && array_is_list($value)) {
            if ((isset($schema['minItems']) && count($value) < $schema['minItems'])
                || (isset($schema['maxItems']) && count($value) > $schema['maxItems'])) {
                self::invalid($path, 'items');
            }
            if (($schema['uniqueItems'] ?? false) && count(array_unique(array_map(serialize(...), $value))) !== count($value)) {
                self::invalid($path, 'unique items');
            }
            if (isset($schema['items']) && is_array($schema['items'])) {
                foreach ($value as $index => $item) {
                    self::validate($schema['items'], $item, $path.'.'.$index);
                }
            }
        }
    }

    /** @return array<string, mixed> */
    public static function definition(string $name): array
    {
        if (self::$document === null) {
            $contents = file_get_contents(dirname(__DIR__, 3).'/spec/crm-openapi.json');
            if ($contents === false) {
                throw new \LogicException('The frozen CRM contract is missing.');
            }
            self::$document = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        }
        $definition = self::$document['components']['schemas'][$name] ?? null;
        if (! is_array($definition)) {
            throw new \LogicException('The CRM schema is missing: '.$name);
        }

        return $definition;
    }

    /** @param array<string, mixed> $schema */
    private static function matches(array $schema, mixed $value): bool
    {
        try {
            self::validate($schema, $value);

            return true;
        } catch (\InvalidArgumentException) {
            return false;
        }
    }

    private static function invalid(string $path, string $reason): never
    {
        throw new \InvalidArgumentException('Invalid CRM field '.$path.' ('.$reason.').');
    }
}
