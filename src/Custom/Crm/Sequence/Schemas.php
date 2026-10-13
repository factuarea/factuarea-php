<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Sequence;

/** Closed handwritten schemas from the native BHTTP43 V1 resources, independent of generated spec pins. */
final class Schemas
{
    public const ID = ['type' => 'string', 'pattern' => '^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-7[0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}$'];
    public const OWNER = ['type' => 'string', 'pattern' => '^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[47][0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}$'];
    public const VERSION = ['type' => 'integer', 'minimum' => 1, 'maximum' => PHP_INT_MAX - 1];
    public const TEXT = ['type' => 'string'];
    public const NULL_TEXT = ['type' => ['string', 'null']];
    public const SCHEDULE_STATE = ['type' => 'string', 'enum' => ['draft', 'active', 'paused', 'retired']];
    public const ENROL_STATE = ['type' => 'string', 'enum' => ['active', 'waiting', 'paused', 'completed', 'cancelled', 'blocked']];
    public const STOP = ['type' => ['string', 'null'], 'enum' => [null, 'human_reply', 'optout', 'closure', 'booking_confirmed', 'owner_disabled', 'revoked', 'suppressed', 'manual', 'privacy', 'unconfirmed']];
    public const SUBJECT = ['type' => 'string', 'enum' => ['lead', 'opportunity', 'business_contact']];
    public const DEFINITION = ['type' => 'object', 'additionalProperties' => false,
        'required' => ['timezone', 'ambiguous_offset', 'allowed_windows', 'steps', 'stop_on_closure', 'purpose', 'channel'],
        'properties' => ['timezone' => self::TEXT, 'ambiguous_offset' => ['type' => 'string', 'enum' => ['earlier', 'later']],
            'allowed_windows' => ['type' => 'array', 'minItems' => 1, 'items' => ['type' => 'object', 'additionalProperties' => false,
                'required' => ['weekday', 'start_minute', 'end_minute'], 'properties' => ['weekday' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 7],
                    'start_minute' => ['type' => 'integer', 'minimum' => 0, 'maximum' => 1439], 'end_minute' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 1440]]]],
            'steps' => ['type' => 'array', 'minItems' => 1, 'items' => ['type' => 'object', 'additionalProperties' => false,
                'required' => ['type', 'wait_seconds', 'recipe_id'], 'properties' => ['type' => ['type' => 'string', 'enum' => ['wait', 'create_activity', 'draft', 'send']],
                    'wait_seconds' => ['type' => ['integer', 'null'], 'minimum' => 1, 'maximum' => PHP_INT_MAX - 1], 'recipe_id' => ['anyOf' => [self::ID, ['type' => 'null']]]]]],
            'stop_on_closure' => ['type' => 'boolean'], 'purpose' => ['type' => 'string', 'pattern' => '^[a-z][a-z0-9_]{0,63}$'],
            'channel' => ['type' => 'string', 'enum' => ['email', 'whatsapp']],
            'stop_conditions' => ['type' => 'array', 'uniqueItems' => true, 'items' => ['type' => 'string', 'enum' => ['booking_confirmed', 'hard_bounce', 'complaint', 'contactability_revoked']]]]];
    public const SCHEDULE_FIELDS = ['type' => 'object', 'additionalProperties' => false, 'properties' => ['definition' => self::TEXT,
        'state' => self::SCHEDULE_STATE, 'owner_id' => self::OWNER, 'team_id' => ['anyOf' => [self::ID, ['type' => 'null']]],
        'next_due_at' => self::NULL_TEXT, 'created_at' => self::TEXT, 'updated_at' => self::TEXT]];
    public const ORIGINAL_FIELDS = ['type' => 'object', 'additionalProperties' => false, 'properties' => [
        'state' => self::SCHEDULE_STATE, 'owner_id' => self::OWNER, 'team_id' => ['anyOf' => [self::ID, ['type' => 'null']]],
        'next_due_at' => self::NULL_TEXT, 'created_at' => self::TEXT, 'updated_at' => self::TEXT]];
    public const SCHEDULE = ['type' => 'object', 'additionalProperties' => false,
        'required' => ['id', 'version', 'authorization_version', 'sandbox', 'state', 'owner_id', 'team_id', 'fields'],
        'properties' => ['id' => self::ID, 'version' => self::VERSION, 'authorization_version' => ['type' => 'string', 'minLength' => 1],
            'sandbox' => ['type' => 'boolean'], 'state' => self::SCHEDULE_STATE, 'owner_id' => self::OWNER,
            'team_id' => ['anyOf' => [self::ID, ['type' => 'null']]], 'fields' => self::SCHEDULE_FIELDS]];
    public const ORIGINAL_SCHEDULE = ['type' => 'object', 'additionalProperties' => false,
        'required' => self::SCHEDULE['required'], 'properties' => ['id' => self::ID, 'version' => self::VERSION,
            'authorization_version' => self::SCHEDULE['properties']['authorization_version'], 'sandbox' => ['type' => 'boolean'],
            'state' => self::SCHEDULE_STATE, 'owner_id' => self::OWNER, 'team_id' => self::SCHEDULE['properties']['team_id'], 'fields' => self::ORIGINAL_FIELDS]];
    public const ENROLMENT = ['type' => 'object', 'additionalProperties' => false,
        'required' => ['id', 'version', 'authorization_version', 'sandbox', 'sequence_id', 'subject_type', 'subject_id', 'generation', 'definition_version', 'state', 'current_step', 'due_at', 'stop_reason', 'fields'],
        'properties' => ['id' => self::ID, 'version' => self::VERSION, 'authorization_version' => self::SCHEDULE['properties']['authorization_version'],
            'sandbox' => ['type' => 'boolean'], 'sequence_id' => self::ID, 'subject_type' => self::SUBJECT, 'subject_id' => self::ID,
            'generation' => self::VERSION, 'definition_version' => self::VERSION, 'state' => self::ENROL_STATE,
            'current_step' => ['type' => 'integer', 'minimum' => 0, 'maximum' => PHP_INT_MAX - 1], 'due_at' => self::NULL_TEXT, 'stop_reason' => self::STOP,
            'fields' => ['type' => 'object', 'additionalProperties' => false, 'properties' => ['state' => self::ENROL_STATE, 'next_due_at' => self::NULL_TEXT, 'created_at' => self::TEXT]]]];
    public const ENROL_METADATA = ['type' => 'object', 'additionalProperties' => false,
        'required' => ['id', 'version', 'authorization_version', 'sandbox', 'sequence_id', 'owner_id', 'team_id', 'generation', 'definition_version', 'state', 'current_step', 'next_due_at', 'stop_reason', 'created_at', 'updated_at'],
        'properties' => ['id' => self::ID, 'version' => self::VERSION, 'authorization_version' => self::SCHEDULE['properties']['authorization_version'],
            'sandbox' => ['type' => 'boolean'], 'sequence_id' => self::ID, 'owner_id' => self::OWNER, 'team_id' => self::SCHEDULE['properties']['team_id'],
            'generation' => self::VERSION, 'definition_version' => self::VERSION, 'state' => self::ENROL_STATE,
            'current_step' => self::ENROLMENT['properties']['current_step'], 'next_due_at' => self::NULL_TEXT, 'stop_reason' => self::STOP,
            'created_at' => self::TEXT, 'updated_at' => self::TEXT]];
    public const LIST = ['type' => 'object', 'additionalProperties' => false,
        'required' => ['items', 'enrolments', 'resource', 'next_cursor', 'visible_count'], 'properties' => [
            'items' => ['type' => 'array', 'items' => self::SCHEDULE], 'enrolments' => ['type' => 'array', 'items' => self::ENROL_METADATA],
            'resource' => ['type' => 'string', 'enum' => ['schedules', 'enrolments']], 'next_cursor' => self::NULL_TEXT,
            'visible_count' => ['type' => 'integer', 'minimum' => 0, 'maximum' => PHP_INT_MAX - 1]]];
    public const PREVIEW = ['type' => 'object', 'additionalProperties' => false,
        'required' => ['sequence_id', 'definition_version', 'subject_id', 'timezone', 'steps'], 'properties' => [
            'sequence_id' => self::ID, 'definition_version' => self::VERSION, 'subject_id' => self::ID, 'timezone' => self::TEXT,
            'steps' => ['type' => 'array', 'items' => ['type' => 'object', 'additionalProperties' => false,
                'required' => ['step_index', 'type', 'due_at', 'recipe_id'], 'properties' => ['step_index' => ['type' => 'integer', 'minimum' => 0],
                    'type' => self::DEFINITION['properties']['steps']['items']['properties']['type'], 'due_at' => self::TEXT,
                    'recipe_id' => ['anyOf' => [self::ID, ['type' => 'null']]]]]]]];
}
