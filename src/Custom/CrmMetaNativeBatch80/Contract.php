<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\CrmMetaNativeBatch80;

/** Closed native human inputs. Published Fields and C7 remain server authority. */
final class Contract
{
    public const OPERATION = 'crm_connections.configure';
    public const FINE_SCOPE = 'crm_connections:write';
    public const OAUTH_SCOPE = 'crm_connections.write';
    public const ROUTES = [
        'prepareOAuth' => ['POST', '/oauth/prepare', 'prepareCrmMetaOAuth', true],
        'completeOAuth' => ['POST', '/oauth/complete', 'completeCrmMetaOAuth', true],
        'options' => ['GET', '/options', 'listCrmMetaConnectionOptions', false],
        'mapping' => ['GET', '/{connection}/mappings/{form}', 'getCrmMetaMapping', false],
        'inspect' => ['POST', '/{connection}/mappings/{form}/inspect', 'inspectCrmMetaMapping', false],
        'publishMapping' => ['PUT', '/{connection}/mappings/{form}', 'publishCrmMetaMapping', true],
        'publishConsent' => ['POST', '/{connection}/consent', 'publishCrmMetaConsent', true],
    ];
    public static function object(mixed $value): \stdClass
    {
        if (!$value instanceof \stdClass) {
            self::invalid();
        }

        return $value;
    }
    public static function keys(\stdClass $p, array $keys): void
    {
        $actual = array_keys(get_object_vars($p));
        if (count($actual) !== count($keys) || array_diff($keys, $actual) !== []) {
            self::invalid();
        }
    }
    public static function uuid(mixed $v, bool $credential = false): bool
    {
        return is_string($v) && preg_match($credential ? '/^[0-9a-f]{8}-[0-9a-f]{4}-[47][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/D' : '/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/D', $v) === 1;
    }
    public static function external(mixed $v): bool
    {
        return is_string($v) && preg_match('/^[1-9][0-9]{0,31}$/D', $v) === 1;
    }
    public static function version(mixed $v, bool $nullable = false): bool
    {
        return ($nullable && $v === null) || (is_int($v) && $v > 0 && $v < PHP_INT_MAX);
    }
    public static function checksum(mixed $v): bool
    {
        return is_string($v) && preg_match('/^[a-f0-9]{64}$/D', $v) === 1;
    }
    public static function text(mixed $v, int $max = 4000): bool
    {
        return is_string($v) && preg_match('//u', $v) === 1 && preg_match_all('/./us', $v) <= $max && preg_match('/[\x00-\x08\x0b\x0c\x0e-\x1f\x7f]/', $v) !== 1;
    }
    public static function strings(mixed $v, bool $ids = false): bool
    {
        if (!is_array($v) || !array_is_list($v)) {
            return false;
        }
        foreach ($v as $s) {
            if (!is_string($s) || ($ids && !self::uuid($s))) {
                return false;
            }
        }

        return count(array_unique($v)) === count($v);
    }
    public static function mappings(mixed $list): void
    {
        if (!is_array($list) || !array_is_list($list) || count($list) < 1 || count($list) > 100) {
            self::invalid();
        }
        $remote = [];
        $targets = [];
        foreach ($list as $value) {
            $m = self::object($value);
            self::keys($m, ['remote_name', 'target', 'type', 'field_version', 'required', 'options']);
            $o = self::object($m->options);
            if (!self::text($m->remote_name, 100) || trim($m->remote_name) === '' || !is_string($m->target) || !in_array($m->type, ['text', 'number', 'date', 'boolean', 'select', 'multi'], true) || !is_bool($m->required) || isset($remote[$m->remote_name]) || isset($targets[$m->target])) {
                self::invalid();
            }
            if (in_array($m->target, ['name', 'email', 'phone', 'mobile', 'company_name', 'notes'], true)) {
                if ($m->type !== 'text' || $m->field_version !== null || get_object_vars($o) !== []) {
                    self::invalid();
                }
            } elseif (!self::uuid($m->target) || !self::version($m->field_version)) {
                self::invalid();
            }
            if (!in_array($m->type, ['select', 'multi'], true) && get_object_vars($o) !== []) {
                self::invalid();
            }
            foreach (get_object_vars($o) as $name => $id) {
                if (!self::text((string) $name) || trim((string) $name) === '' || !self::uuid($id)) {
                    self::invalid();
                }
            }
            $remote[$m->remote_name] = true;
            $targets[$m->target] = true;
        }
    }
    public static function metaScopes(mixed $list): bool
    {
        if (!self::strings($list) || count($list) > 100) {
            return false;
        }
        foreach ($list as $scope) {
            if (preg_match('/^[a-z][a-z0-9_]{0,99}$/D', $scope) !== 1) {
                return false;
            }
        }

        return array_diff(['ads_management', 'leads_retrieval', 'pages_manage_ads', 'pages_manage_metadata', 'pages_read_engagement', 'pages_show_list'], $list) === [];
    }
    public static function grants(mixed $list): void
    {
        if (!is_array($list) || !array_is_list($list) || count($list) < 1 || count($list) > 100) {
            self::invalid();
        }
        $streams = [];
        foreach ($list as $value) {
            $g = self::object($value);
            self::keys($g, ['stream', 'actions']);
            if (!self::uuid($g->stream) || isset($streams[$g->stream]) || !self::strings($g->actions) || count($g->actions) < 1 || count($g->actions) > 2 || array_diff($g->actions, ['read', 'ingest']) !== []) {
                self::invalid();
            }
            $streams[$g->stream] = true;
        }
    }
    public static function input(string $action, ?\stdClass $p): void
    {
        if (in_array($action, ['options', 'mapping'], true)) {
            if ($p !== null) {
                self::invalid();
            }

            return;
        }
        $p = self::object($p);
        switch ($action) {
            case 'prepareOAuth': self::keys($p, ['page_id', 'confirmed']);
                if (!self::external($p->page_id) || $p->confirmed !== true) {
                    self::invalid();
                } break;
            case 'completeOAuth': self::keys($p, ['state', 'code']);
                if (!is_string($p->state) || preg_match('/^[0-9a-f-]{36}$/D', $p->state) !== 1 || !self::text($p->code, 8192) || strlen($p->code) > 8192 || preg_match('/^[^\x00-\x20\x7f]+$/D', $p->code) !== 1) {
                    self::invalid();
                } break;
            case 'inspect': self::keys($p, ['credential_uuid', 'remote_form_id']);
                if (!self::uuid($p->credential_uuid, true) || !self::external($p->remote_form_id)) {
                    self::invalid();
                } break;
            case 'publishMapping': self::keys($p, ['credential_uuid', 'remote_form_id', 'mapping', 'expected_mapping_version', 'expected_publication_version', 'fields_snapshot_checksum', 'confirmed']);
                if (!self::uuid($p->credential_uuid, true) || !self::external($p->remote_form_id) || !self::version($p->expected_mapping_version, true) || !self::version($p->expected_publication_version) || !self::checksum($p->fields_snapshot_checksum) || $p->confirmed !== true) {
                    self::invalid();
                } self::mappings($p->mapping);
                break;
            case 'publishConsent': self::keys($p, ['credential_uuid', 'grants', 'expected_consent_version', 'confirmed']);
                if (!self::uuid($p->credential_uuid, true) || !self::version($p->expected_consent_version, true) || $p->confirmed !== true) {
                    self::invalid();
                } self::grants($p->grants);
                break;
            default: self::invalid();
        }
    }
    public static function projection(RequestIntent $i, \stdClass $root): void
    {
        $a = $i->action;
        if (in_array($a, ['prepareOAuth', 'completeOAuth'], true)) {
            $p = $root;
        } else {
            self::keys($root, ['data']);
            $p = self::object($root->data);
        }
        if ($a === 'prepareOAuth') {
            self::keys($p, ['authorization_url', 'state']);
            $u = is_string($p->authorization_url) ? parse_url($p->authorization_url) : false;
            if (!is_array($u) || ($u['scheme'] ?? '') !== 'https' || empty($u['host']) || isset($u['user']) || isset($u['pass']) || !is_string($p->state) || preg_match('/^[0-9a-f-]{36}$/D', $p->state) !== 1) {
                self::invalid();
            }
        } elseif ($a === 'completeOAuth') {
            self::keys($p, ['status', 'connection_uuid', 'credential_uuid', 'page_id', 'scopes']);
            if ($p->status !== 'credentials_installed' || !self::uuid($p->connection_uuid) || !self::uuid($p->credential_uuid, true) || !self::external($p->page_id) || !self::metaScopes($p->scopes)) {
                self::invalid();
            }
        } elseif ($a === 'options') {
            self::keys($p, ['options']);
            if (!is_array($p->options) || !array_is_list($p->options)) {
                self::invalid();
            }
            foreach ($p->options as $v) {
                $o = self::object($v);
                self::keys($o, ['connection_uuid', 'credential_uuid', 'page_id', 'page_name']);
                if (!self::uuid($o->connection_uuid) || !self::uuid($o->credential_uuid, true) || !self::external($o->page_id) || ($o->page_name !== null && !self::text($o->page_name))) {
                    self::invalid();
                }
            }
        } elseif ($a === 'mapping' || $a === 'publishMapping') {
            self::keys($p, ['status', 'connection_uuid', 'credential_uuid', 'form_uuid', 'page_id', 'remote_form_id', 'mapping_version', 'publication_version', 'fields_snapshot_checksum', 'mapping']);
            if ($p->status !== 'mapping_published' || !self::version($p->mapping_version) || !self::version($p->publication_version) || !self::checksum($p->fields_snapshot_checksum)) {
                self::invalid();
            } self::mappings($p->mapping);
            if ($a === 'publishMapping' && ($p->publication_version !== $i->body()->expected_publication_version || $p->fields_snapshot_checksum !== $i->body()->fields_snapshot_checksum || $p->mapping != $i->body()->mapping)) {
                self::invalid();
            }
        } elseif ($a === 'inspect') {
            self::keys($p, ['status', 'connection_uuid', 'credential_uuid', 'form_uuid', 'page_id', 'remote_form_id', 'app_id', 'responsible_uuid', 'remote_field_names', 'publication_version', 'authorization_version', 'configuration_version', 'fields_snapshot_checksum', 'expected_mapping_version', 'expected_consent_version', 'destination', 'app_review']);
            if ($p->status !== 'mapping_inspected' || !self::external($p->app_id) || !self::uuid($p->responsible_uuid, true) || !self::strings($p->remote_field_names) || !self::version($p->publication_version) || !self::version($p->authorization_version) || !self::version($p->configuration_version) || !self::checksum($p->fields_snapshot_checksum) || !self::version($p->expected_mapping_version, true) || !self::version($p->expected_consent_version, true)) {
                self::invalid();
            }
            $d = self::object($p->destination);
            self::keys($d, ['basic_fields', 'required_basic_fields', 'allowed_field_ids', 'required_field_ids', 'fields_snapshot']);
            if (!self::strings($d->basic_fields) || !self::strings($d->required_basic_fields) || !self::strings($d->allowed_field_ids, true) || !self::strings($d->required_field_ids, true)) {
                self::invalid();
            } self::object($d->fields_snapshot);
            $review = self::object($p->app_review);
            self::keys($review, ['available', 'reason']);
            if ($review->available !== false || $review->reason !== 'app_review_evidence_unavailable') {
                self::invalid();
            }
        } else {
            self::keys($p, ['status', 'connection_uuid', 'authorization_uuid', 'consent_version', 'revision', 'grants']);
            if ($p->status !== 'consent_published' || !self::uuid($p->authorization_uuid, true) || !self::version($p->consent_version) || !self::text($p->revision) || $p->revision === '') {
                self::invalid();
            } self::grants($p->grants);
            if ($p->grants != $i->body()->grants) {
                self::invalid();
            }
        }
        if ($i->connection !== null && ($p->connection_uuid ?? null) !== $i->connection) {
            self::invalid();
        }
        if ($i->form !== null && ($p->form_uuid ?? null) !== $i->form) {
            self::invalid();
        }
        if ($i->connection !== null && $a !== 'publishConsent' && (!self::uuid($p->credential_uuid, true) || !self::external($p->page_id) || !self::external($p->remote_form_id))) {
            self::invalid();
        }
        $b = $i->body();
        if ($b !== null && $a !== 'publishConsent' && isset($b->credential_uuid) && $p->credential_uuid !== $b->credential_uuid) {
            self::invalid();
        }
        if ($b !== null && isset($b->remote_form_id) && $p->remote_form_id !== $b->remote_form_id) {
            self::invalid();
        }
    }
    public static function invalid(): never
    {
        throw new \InvalidArgumentException('Invalid closed native Meta contract.');
    }
}
