<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Conversations;

use Factuarea\Sdk\Custom\Crm\Schema;

final class Validation
{
    public static function cursor(string $cursor): void
    {
        Schema::validate(Schemas::CURSOR, $cursor);
        if (trim($cursor) === '') {
            throw new \InvalidArgumentException('The native opaque cursor must not be blank.');
        }
    }

    public static function header(string $value): void
    {
        if ($value === '' || preg_match('/[\x00-\x1f\x7f]/', $value) === 1) {
            throw new \InvalidArgumentException('Invalid native header override.');
        }
    }

    public static function email(string $value): void
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false || strlen($value) > 254) {
            throw new \InvalidArgumentException('Invalid native participant email.');
        }
    }

    public static function instant(string $value): void
    {
        $date = \DateTimeImmutable::createFromFormat('!Y-m-d\TH:i:s\Z', $value, new \DateTimeZone('UTC'));
        if ($date === false || $date->format('Y-m-d\TH:i:s\Z') !== $value) {
            throw new \InvalidArgumentException('Native UTC timestamp is invalid.');
        }
    }

    /** @param array<string,true> $seen */
    public static function unique(array &$seen, string $key): void
    {
        $key = strtolower($key);
        if (isset($seen[$key])) {
            throw new \InvalidArgumentException('Duplicate native Conversation identity.');
        }
        $seen[$key] = true;
    }

    public static function rawFilters(string $wire): void
    {
        if (strlen($wire) > 1048576) {
            throw new \InvalidArgumentException('Native filters exceed the byte limit.');
        }
        try {
            $object = json_decode($wire, false, 32, JSON_THROW_ON_ERROR);
        } catch (\JsonException $error) {
            throw new \InvalidArgumentException('Native filters must be a JSON object string.', previous: $error);
        }
        Schema::validate(Schemas::FILTERS, $object);
        if (! $object instanceof \stdClass || isset($object->target_type) !== isset($object->target_uuid)
            || (isset($object->search) && is_string($object->search) && trim($object->search) === '')) {
            throw new \InvalidArgumentException('Native filters require a paired target and nonblank search.');
        }
    }

    public static function headMask(Conversation $head, ReadMeta $meta): void
    {
        foreach (['subject' => $head->subject, 'owner_uuid' => $head->ownerId, 'team_uuid' => $head->teamId, 'last_message_at' => $head->lastMessageAt] as $field => $value) {
            if (! in_array($field, $meta->readableFields, true) && $value !== null) {
                throw new \InvalidArgumentException('A hidden native Conversation field was not masked.');
            }
        }
        if (! in_array('links', $meta->readableFields, true) && $head->links !== []) {
            throw new \InvalidArgumentException('Native hidden links were not masked.');
        }
    }
}
