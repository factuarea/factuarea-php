<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Bookings;

/** Closed shapes from the native Booking requests/resources, separate from widget/staff authority. */
final class Schemas
{
    public const ID_PATTERN = '/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/D';
    public const ID = ['type' => 'string', 'pattern' => '^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$'];
    public const HOST = ['type' => 'string', 'pattern' => '^[0-9a-f]{8}-[0-9a-f]{4}-[47][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$'];
    public const VERSION = ['type' => 'integer', 'minimum' => 1, 'maximum' => PHP_INT_MAX - 1];
    public const TEXT = ['type' => 'string', 'minLength' => 1, 'maxLength' => 200];
    public const INSTANT = ['type' => 'string', 'pattern' => '^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}Z$'];
    public const STATUS = ['type' => 'string', 'enum' => ['held', 'confirmed', 'cancelled', 'expired']];
    public const WINDOW_FIELDS = ['start_minute' => ['type' => 'integer', 'minimum' => 0, 'maximum' => 1439], 'end_minute' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 1440]];
    public const WINDOW = ['type' => 'object', 'additionalProperties' => false, 'required' => ['start_minute', 'end_minute'], 'properties' => self::WINDOW_FIELDS];
    public const SCHEDULE = ['type' => 'object', 'additionalProperties' => false,
        'required' => ['timezone', 'dst_policy', 'windows', 'exceptions', 'duration_minutes', 'buffer_before', 'buffer_after', 'capacity', 'minimum_notice_minutes', 'booking_horizon_days', 'slot_step_minutes'],
        'properties' => ['timezone' => ['type' => 'string', 'minLength' => 1, 'maxLength' => 100], 'dst_policy' => ['type' => 'string', 'enum' => ['reject_ambiguous', 'both_offsets']],
            'windows' => ['type' => 'array', 'items' => ['type' => 'object', 'additionalProperties' => false, 'required' => ['weekday', 'start_minute', 'end_minute'], 'properties' => ['weekday' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 7]] + self::WINDOW_FIELDS]],
            'exceptions' => ['type' => 'array', 'items' => ['type' => 'object', 'additionalProperties' => false, 'required' => ['date', 'windows'], 'properties' => ['date' => ['type' => 'string', 'pattern' => '^\d{4}-\d{2}-\d{2}$'], 'windows' => ['type' => 'array', 'items' => self::WINDOW]]]],
            'duration_minutes' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 1440], 'buffer_before' => ['type' => 'integer', 'minimum' => 0, 'maximum' => 1440], 'buffer_after' => ['type' => 'integer', 'minimum' => 0, 'maximum' => 1440],
            'capacity' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 10000], 'minimum_notice_minutes' => ['type' => 'integer', 'minimum' => 0, 'maximum' => 525600], 'booking_horizon_days' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 366], 'slot_step_minutes' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 1440]]];
    public const DEFINITION_FIELDS = ['public_id' => self::TEXT, 'state' => ['type' => 'string', 'enum' => ['draft', 'published', 'paused']], 'host_id' => self::HOST, 'resource_id' => self::ID, 'schedule' => self::SCHEDULE,
        'required_sources' => ['type' => 'array', 'minItems' => 1, 'uniqueItems' => true, 'items' => self::TEXT], 'notice_version' => self::TEXT, 'hold_minutes' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 1440],
        'location' => ['type' => ['string', 'null'], 'minLength' => 1, 'maxLength' => 1000]];
    public const WRITE = ['type' => 'object', 'additionalProperties' => false, 'required' => ['definition'], 'properties' => ['definition' => ['type' => 'object', 'additionalProperties' => false, 'properties' => self::DEFINITION_FIELDS],
        'expected_version' => ['anyOf' => [self::VERSION, ['type' => 'null']]], 'publication_confirmation_id' => self::ID]];
    public const SERVICE = ['type' => 'object', 'additionalProperties' => false, 'required' => ['id', 'version'], 'properties' => ['id' => self::ID, 'version' => self::VERSION, 'publication_version' => self::VERSION] + self::DEFINITION_FIELDS];
    public const SOURCE_STATUSES = ['type' => 'object', 'additionalProperties' => ['type' => 'object', 'additionalProperties' => false, 'required' => ['verified', 'revision', 'reason'], 'properties' => ['verified' => ['type' => 'boolean'], 'revision' => ['type' => 'string', 'pattern' => '^[0-9a-f]{64}$'], 'reason' => ['type' => ['string', 'null'], 'maxLength' => 200]]]];
    public const LIST_SERVICE = ['type' => 'object', 'additionalProperties' => false, 'required' => ['id', 'version'], 'properties' => self::SERVICE['properties'] + ['source_statuses' => self::SOURCE_STATUSES]];
    public const RESERVATION = ['type' => 'object', 'additionalProperties' => false, 'required' => ['id', 'version'], 'properties' => ['id' => self::ID, 'version' => self::VERSION, 'service_id' => self::ID, 'host_id' => self::HOST, 'resource_id' => self::ID, 'status' => self::STATUS, 'start_at' => self::INSTANT, 'end_at' => self::INSTANT, 'projection_status' => ['type' => 'string', 'minLength' => 1, 'maxLength' => 100]]];
    public const CURSOR = ['type' => ['string', 'null'], 'minLength' => 1, 'maxLength' => 4096];
    public const SLOT = ['type' => 'object', 'additionalProperties' => false, 'required' => ['start_at', 'end_at'], 'properties' => ['start_at' => self::INSTANT, 'end_at' => self::INSTANT]];
    public const PUBLIC_ID = ['type' => 'string', 'pattern' => '^[A-Za-z0-9_-]{32,128}$'];
    public const TOKEN = ['type' => 'string', 'pattern' => '^bk1_[a-f0-9]{64}$'];
    public const PUBLIC_CREATE = ['type' => 'object', 'additionalProperties' => false, 'required' => ['slot', 'visitor_fields', 'notice_version', 'captcha_token'], 'properties' => ['slot' => self::SLOT,
        'visitor_fields' => ['type' => 'object', 'additionalProperties' => false, 'required' => ['name', 'email'], 'properties' => ['name' => ['type' => 'string', 'minLength' => 1, 'maxLength' => 600], 'email' => ['type' => 'string', 'minLength' => 1, 'maxLength' => 254], 'phone' => ['type' => ['string', 'null'], 'maxLength' => 100]]],
        'notice_version' => self::TEXT, 'captcha_token' => ['type' => 'string', 'minLength' => 1, 'maxLength' => 4096]]];
    public const PUBLIC_RESCHEDULE = ['type' => 'object', 'additionalProperties' => false, 'required' => ['manage_token', 'expected_version', 'new_slot'], 'properties' => ['manage_token' => self::TOKEN, 'expected_version' => self::VERSION, 'new_slot' => self::SLOT]];
    public const PUBLIC_CANCEL = ['type' => 'object', 'additionalProperties' => false, 'required' => ['manage_token', 'expected_version'], 'properties' => ['manage_token' => self::TOKEN, 'expected_version' => self::VERSION]];
    public const PUBLIC_HEAD = ['type' => 'object', 'additionalProperties' => false, 'required' => ['id', 'version', 'status', 'start_at', 'end_at'], 'properties' => ['id' => self::ID, 'version' => self::VERSION, 'status' => self::STATUS, 'start_at' => self::INSTANT, 'end_at' => self::INSTANT]];
    public const PUBLIC_RECEIPT = ['type' => 'object', 'additionalProperties' => false, 'required' => ['id', 'version', 'status', 'start_at', 'end_at', 'effect_id'], 'properties' => self::PUBLIC_HEAD['properties'] + ['effect_id' => self::ID, 'manage_token' => self::TOKEN]];
    public const PUBLIC_CONFIGURATION = ['type' => 'object', 'additionalProperties' => false, 'required' => ['public_id', 'publication_version', 'business_timezone', 'is_sandbox', 'service_display', 'duration_minutes', 'buffer_before', 'buffer_after', 'notice_version', 'notice_copy', 'captcha'],
        'properties' => ['public_id' => self::PUBLIC_ID, 'publication_version' => self::VERSION, 'business_timezone' => self::SCHEDULE['properties']['timezone'], 'is_sandbox' => ['type' => 'boolean'], 'service_display' => ['type' => 'string', 'minLength' => 1, 'maxLength' => 500], 'duration_minutes' => self::SCHEDULE['properties']['duration_minutes'],
            'buffer_before' => self::SCHEDULE['properties']['buffer_before'], 'buffer_after' => self::SCHEDULE['properties']['buffer_after'], 'notice_version' => self::TEXT, 'notice_copy' => ['type' => 'string', 'minLength' => 1, 'maxLength' => 20000],
            'captcha' => ['oneOf' => [['type' => 'object', 'additionalProperties' => false, 'required' => ['provider'], 'properties' => ['provider' => ['const' => 'none']]], ['type' => 'object', 'additionalProperties' => false, 'required' => ['provider', 'site_key'], 'properties' => ['provider' => ['const' => 'turnstile'], 'site_key' => ['type' => 'string', 'pattern' => '^[A-Za-z0-9_-]{1,256}$']]]]]]];
    public const PUBLIC_AVAILABILITY = ['type' => 'object', 'additionalProperties' => false, 'required' => ['public_id', 'publication_version', 'business_timezone', 'visitor_timezone', 'slots', 'as_of', 'notice_version', 'is_sandbox'], 'properties' => ['public_id' => self::PUBLIC_ID, 'publication_version' => self::VERSION,
        'business_timezone' => self::SCHEDULE['properties']['timezone'], 'visitor_timezone' => self::SCHEDULE['properties']['timezone'], 'slots' => ['type' => 'array', 'items' => self::SLOT], 'as_of' => self::INSTANT, 'notice_version' => self::TEXT, 'is_sandbox' => ['type' => 'boolean']]];
}
