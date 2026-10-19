<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\ServiceFormReferences;

/** Exact native V1 whitelist: four collections and current authorization metadata. */
final class Schemas
{
    public const UUID = ['type' => 'string', 'pattern' => '^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$'];
    public const USER_UUID = ['type' => 'string', 'pattern' => '^[0-9a-f]{8}-[0-9a-f]{4}-[47][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$'];
    public const NAME = ['type' => 'string', 'minLength' => 1, 'maxLength' => 150];
    public const OPTION = ['type' => 'object', 'additionalProperties' => false, 'required' => ['id', 'name'],
        'properties' => ['id' => self::UUID, 'name' => self::NAME]];
    public const USER = ['type' => 'object', 'additionalProperties' => false, 'required' => ['id', 'name'],
        'properties' => ['id' => self::USER_UUID, 'name' => self::NAME]];
    public const CALENDAR = ['type' => 'object', 'additionalProperties' => false, 'required' => ['id', 'name', 'version'],
        'properties' => ['id' => self::UUID, 'name' => self::NAME, 'version' => ['type' => 'integer', 'minimum' => 1]]];
    public const REFERENCES = ['type' => 'object', 'additionalProperties' => false,
        'required' => ['queues', 'policies', 'calendars', 'responsible_users', 'authorization_version'],
        'properties' => ['queues' => ['type' => 'array', 'maxItems' => 100, 'items' => self::OPTION],
            'policies' => ['type' => 'array', 'maxItems' => 100, 'items' => self::OPTION],
            'calendars' => ['type' => 'array', 'maxItems' => 100, 'items' => self::CALENDAR],
            'responsible_users' => ['type' => 'array', 'maxItems' => 100, 'items' => self::USER],
            'authorization_version' => ['type' => 'string', 'minLength' => 1]]];
}
