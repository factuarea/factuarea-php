<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\VerticalPacks;

/** Closed native producer projections; no HTTP envelope, price, grant or profile is invented. */
final class Schemas
{
    public const UUID = ['type' => 'string', 'pattern' => '^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$'];
    public const HASH = ['type' => 'string', 'pattern' => '^[0-9a-f]{64}$'];
    public const POSITIVE = ['type' => 'integer', 'minimum' => 1];
    public const SECTOR = ['type' => 'string', 'enum' => ['real_estate', 'professional_services', 'admissions', 'events']];
    public const TRANSLATION = ['type' => 'string', 'minLength' => 1, 'maxLength' => 2000, 'pattern' => '^[^\x00-\x1f\x7f]+$'];
    public const TEXT = ['type' => 'object', 'additionalProperties' => false, 'required' => ['es', 'en', 'ca'],
        'properties' => ['es' => self::TRANSLATION, 'en' => self::TRANSLATION, 'ca' => self::TRANSLATION]];
    public const PACK = ['type' => 'object', 'additionalProperties' => false, 'required' => ['id', 'version', 'sector', 'title', 'description', 'supports_demo', 'installation'],
        'properties' => ['id' => self::UUID, 'version' => self::POSITIVE, 'sector' => self::SECTOR, 'title' => self::TEXT, 'description' => self::TEXT, 'supports_demo' => ['type' => 'boolean'],
            'installation' => ['anyOf' => [self::INSTALLATION, ['type' => 'null']]]]];
    public const PAGE = ['type' => 'object', 'additionalProperties' => false, 'required' => ['items', 'next_cursor'],
        'properties' => ['items' => ['type' => 'array', 'items' => self::PACK], 'next_cursor' => ['type' => 'null']]];
    public const ITEM = ['type' => 'object', 'additionalProperties' => false,
        'required' => ['id', 'kind', 'machine_key', 'label', 'decision', 'current_version', 'allowed_resolutions', 'current_display', 'proposed_display'],
        'properties' => ['id' => self::UUID, 'kind' => ['type' => 'string', 'enum' => ['field', 'pipeline', 'label', 'demo']],
            'machine_key' => ['type' => 'string', 'pattern' => '^[a-z][a-z0-9_.]{0,149}$'], 'label' => self::TEXT,
            'decision' => ['type' => 'string', 'enum' => ['add', 'reuse', 'conflict']],
            'current_version' => ['anyOf' => [self::POSITIVE, ['type' => 'null']]],
            'allowed_resolutions' => ['type' => 'array', 'uniqueItems' => true, 'maxItems' => 2, 'items' => ['type' => 'string', 'enum' => ['keep', 'replace']]],
            'current_display' => ['anyOf' => [self::TEXT, ['type' => 'null']]], 'proposed_display' => self::TEXT]];
    public const PLAN = ['type' => 'object', 'additionalProperties' => false,
        'required' => ['id', 'version', 'pack_id', 'pack_version', 'preview_hash', 'plan_token', 'authorization_version', 'sandbox', 'demo_selected', 'items', 'blocking_codes'],
        'properties' => ['id' => self::UUID, 'version' => ['type' => 'integer', 'const' => 1], 'pack_id' => self::UUID, 'pack_version' => self::POSITIVE,
            'preview_hash' => self::HASH, 'plan_token' => ['type' => 'string', 'minLength' => 1, 'maxLength' => 32768],
            'authorization_version' => ['type' => 'string', 'minLength' => 1], 'sandbox' => ['type' => 'boolean'], 'demo_selected' => ['type' => 'boolean'],
            'items' => ['type' => 'array', 'maxItems' => 200, 'items' => self::ITEM], 'blocking_codes' => ['type' => 'array', 'items' => ['type' => 'string']]]];
    public const PREVIEW_REQUEST = ['type' => 'object', 'additionalProperties' => false, 'required' => ['pack_version', 'demo_selected'],
        'properties' => ['pack_version' => self::POSITIVE, 'demo_selected' => ['type' => 'boolean']]];
    public const PLAN_REQUEST = ['type' => 'object', 'additionalProperties' => false, 'required' => ['plan_token'],
        'properties' => ['plan_token' => ['type' => 'string', 'minLength' => 1, 'maxLength' => 32768]]];
    public const RECOVERY_REQUEST = ['type' => 'object', 'additionalProperties' => false,
        'required' => ['operation', 'target_id', 'original_key', 'original_wire'],
        'properties' => ['operation' => ['type' => 'string', 'enum' => ['apply', 'undo']], 'target_id' => self::UUID,
            'original_key' => self::HASH, 'original_wire' => ['type' => 'string', 'minLength' => 1, 'maxLength' => 65536]]];
    public const RESOLUTION = ['type' => 'object', 'additionalProperties' => false, 'required' => ['item_id', 'resolution'],
        'properties' => ['item_id' => self::UUID, 'resolution' => ['type' => 'string', 'enum' => ['keep', 'replace']]]];
    public const APPLY = ['type' => 'object', 'additionalProperties' => false,
        'required' => ['expected_plan_version', 'preview_hash', 'plan_token', 'resolutions', 'demo_selected', 'confirmed'],
        'properties' => ['expected_plan_version' => ['type' => 'integer', 'const' => 1], 'preview_hash' => self::HASH,
            'plan_token' => ['type' => 'string', 'minLength' => 1, 'maxLength' => 32768],
            'resolutions' => ['type' => 'array', 'maxItems' => 200, 'items' => self::RESOLUTION],
            'demo_selected' => ['type' => 'boolean'], 'confirmed' => ['type' => 'boolean', 'const' => true]]];
    public const UNDO = ['type' => 'object', 'additionalProperties' => false,
        'required' => ['expected_installation_version', 'original_receipt_id', 'confirmed'],
        'properties' => ['expected_installation_version' => self::POSITIVE, 'original_receipt_id' => self::UUID,
            'confirmed' => ['type' => 'boolean', 'const' => true]]];
    public const INSTALLATION = ['type' => 'object', 'additionalProperties' => false, 'required' => ['id', 'version', 'receipt_id', 'pack_id', 'pack_version'],
        'properties' => ['id' => self::UUID, 'version' => self::POSITIVE, 'receipt_id' => self::UUID, 'pack_id' => self::UUID, 'pack_version' => self::POSITIVE]];
    public const RECEIPT_ITEM = ['type' => 'object', 'additionalProperties' => false, 'required' => ['item_id', 'outcome', 'target_id', 'version'],
        'properties' => ['item_id' => self::UUID, 'outcome' => ['type' => 'string', 'enum' => ['added', 'reused', 'kept', 'replaced', 'undone']],
            'target_id' => ['anyOf' => [self::UUID, ['type' => 'null']]], 'version' => ['anyOf' => [self::POSITIVE, ['type' => 'null']]]]];
    public const RECEIPT = ['type' => 'object', 'additionalProperties' => false, 'required' => ['id', 'operation', 'target_id', 'confirmed', 'installation', 'items'],
        'properties' => ['id' => self::UUID, 'operation' => ['type' => 'string', 'enum' => ['apply', 'undo']], 'target_id' => self::UUID,
            'confirmed' => ['type' => 'boolean', 'const' => true], 'installation' => self::INSTALLATION,
            'items' => ['type' => 'array', 'maxItems' => 200, 'items' => self::RECEIPT_ITEM]]];
    public const ORIGINAL = ['type' => 'object', 'additionalProperties' => false, 'required' => ['data'],
        'properties' => ['data' => ['anyOf' => [self::RECEIPT, ['type' => 'null']]]]];
    public const CONFLICTS = ['type' => 'object', 'additionalProperties' => false, 'required' => ['items', 'preview_hash'],
        'properties' => ['items' => ['type' => 'array', 'maxItems' => 200, 'items' => self::ITEM], 'preview_hash' => self::HASH]];
}
