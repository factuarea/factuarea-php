<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\EntityExports;

use Factuarea\Sdk\Custom\Crm\Schema;

/** Possible scalar columns/native filter vocabulary from Source107; never a permission catalog. */
final class Selection
{
    public const COLUMNS = ['organization' => ['id', 'kind', 'name', 'job_title', 'contact_id', 'version', 'status', 'updated_at'], 'person' => ['id', 'kind', 'name', 'job_title', 'contact_id', 'version', 'status', 'updated_at'], 'lead' => ['id', 'uuid', 'version', 'status', 'stage', 'name', 'email', 'phone', 'mobile', 'company_name', 'owner_id', 'team_id', 'contact_id', 'person_id', 'source', 'source_term_id', 'score', 'rules_version', 'created_at', 'updated_at', 'erased_at', 'estimated_value_amount', 'estimated_value_currency', 'country_code', 'province', 'city', 'postal_code'], 'opportunity' => ['uuid', 'version', 'status', 'title', 'lead_uuid', 'contact_uuid', 'pipeline_uuid', 'stage_uuid', 'owner_uuid', 'team_uuid', 'amount', 'currency', 'probability', 'weighted_value', 'expected_close', 'next_step', 'closed_at'], 'activity' => ['id', 'version', 'type', 'status', 'due_at', 'timezone', 'completed_at'], 'service_ticket' => ['uuid', 'version', 'title', 'description', 'status', 'priority', 'type', 'pipeline_uuid', 'stage_uuid', 'counterparty_uuid', 'person_uuid', 'assignee_uuid', 'created_at', 'updated_at', 'first_response_at', 'resolved_at', 'closed_at', 'archived_at', 'cycle']];

    public const FILTERS = ['organization' => ['search', 'kind', 'status', 'contact_uuid', 'owner_uuid', 'team_uuid', 'country_code', 'locality_key', 'tag', 'sort', 'typed_filters', 'updated_from', 'updated_to'], 'person' => ['search', 'kind', 'status', 'contact_uuid', 'owner_uuid', 'team_uuid', 'country_code', 'locality_key', 'tag', 'sort', 'typed_filters', 'updated_from', 'updated_to'], 'lead' => ['status', 'stage', 'owner_id', 'team_id', 'source_term_id', 'contact_person_id', 'form_id', 'installation_key', 'created_from', 'created_to', 'score_min', 'score_max', 'has_email', 'has_phone', 'country_code', 'locality_key', 'typed_filters'], 'opportunity' => ['status', 'pipeline_uuid', 'stage_uuid', 'owner_uuid', 'team_uuid', 'contact_uuid', 'lead_uuid', 'phone', 'source', 'created_from', 'created_to', 'expected_close_from', 'expected_close_to', 'typed_filters', 'sort', 'direction'], 'activity' => ['status', 'type', 'owner_uuid', 'target_type', 'target_uuid', 'overdue', 'due_from', 'due_to', 'search', 'order'], 'service_ticket' => ['status', 'assignee_uuid', 'team_uuid', 'period']];

    /** @param array<string,mixed> $filters @param list<string> $columns */
    public static function validate(EntityType $type, array $filters, array $columns): void
    {
        foreach ($columns as $column) {
            if (! in_array($column, self::COLUMNS[$type->value], true)) {
                throw new \InvalidArgumentException('Select only native scalar columns for this owner.');
            }
        }
        $properties = [];
        foreach (self::FILTERS[$type->value] as $key) {
            $properties[$key] = self::filterSchema($type, $key);
        }
        Schema::validate(['type' => 'object', 'additionalProperties' => false, 'properties' => $properties], (object) $filters);
        self::canonicalObject($filters);
        if (isset($filters['kind']) && $filters['kind'] !== $type->value) {
            throw new \InvalidArgumentException('Contact kind must be the selected native owner.');
        }
        if ((array_key_exists('target_type', $filters)) !== (array_key_exists('target_uuid', $filters))) {
            throw new \InvalidArgumentException('Activity target type and UUID must be supplied together.');
        }
        if (isset($filters['score_min'], $filters['score_max']) && $filters['score_min'] > $filters['score_max']) {
            throw new \InvalidArgumentException('The native score range is ordered.');
        }
        foreach ([['updated_from', 'updated_to'], ['created_from', 'created_to'], ['expected_close_from', 'expected_close_to'], ['due_from', 'due_to']] as [$from, $until]) {
            foreach ([$from, $until] as $key) {
                if (isset($filters[$key])) {
                    self::instant($filters[$key]);
                }
            }
            if (isset($filters[$from], $filters[$until]) && new \DateTimeImmutable($filters[$from]) > new \DateTimeImmutable($filters[$until])) {
                throw new \InvalidArgumentException('The native period requires from <= to.');
            }
        }
        if (array_key_exists('period', $filters)) {
            $period = (array) $filters['period'];
            self::instant($period['from']);
            self::instant($period['until']);
            if (new \DateTimeImmutable($period['until']) <= new \DateTimeImmutable($period['from'])) {
                throw new \InvalidArgumentException('The ticket period is [from, until), with until > from.');
            }
        }
    }

    /** @return array<string,mixed> */
    private static function filterSchema(EntityType $type, string $key): array
    {
        $uuid7 = ['type' => 'string', 'pattern' => '^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$'];
        $reference = ['type' => 'string', 'pattern' => '^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$'];
        $user = ['type' => 'string', 'pattern' => '^[0-9a-f]{8}-[0-9a-f]{4}-[47][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$'];
        $text = ['type' => 'string', 'maxLength' => 255];
        $enums = [
            'status' => match ($type) {
                EntityType::Organization, EntityType::Person => ['active', 'archived'],
                EntityType::Lead => ['open', 'disqualified', 'converted', 'deleted'],
                EntityType::Opportunity => ['open', 'won', 'lost'],
                EntityType::Activity => ['pending', 'completed', 'cancelled'],
                EntityType::ServiceTicket => ['new', 'assigned', 'pending', 'closed', 'rejected', 'duplicate'],
            },
            'stage' => ['inbox', 'contacted', 'qualified'], 'kind' => ['person', 'organization'],
            'type' => ['task', 'call', 'meeting', 'note'], 'target_type' => ['contact', 'person', 'lead', 'opportunity', 'booking'],
            'direction' => ['asc', 'desc'],
            'order' => ['due_at', '-due_at', 'uuid', '-uuid'],
        ];
        if (isset($enums[$key])) {
            $enum = ['type' => 'string', 'enum' => $enums[$key]];

            return $type === EntityType::Lead && in_array($key, ['status', 'stage'], true)
                ? ['oneOf' => [$enum, ['type' => 'array', 'minItems' => 1, 'maxItems' => 100, 'items' => $enum]]] : $enum;
        }
        if ($key === 'sort') {
            return ['type' => 'string', 'enum' => $type === EntityType::Opportunity
                ? ['uuid', 'version', 'title', 'status', 'amount', 'currency', 'probability', 'weighted_value', 'expected_close', 'stage_changed_at', 'created_at', 'owner_uuid', 'team_uuid', 'pipeline_uuid', 'stage_uuid']
                : ['name', '-name', 'updated_at', '-updated_at']];
        }
        if (in_array($key, ['score_min', 'score_max'], true)) {
            return ['type' => 'integer', 'minimum' => 0, 'maximum' => 100];
        }
        if (in_array($key, ['has_email', 'has_phone', 'overdue'], true)) {
            return ['type' => 'boolean'];
        }
        if ($key === 'typed_filters' && in_array($type, [EntityType::Organization, EntityType::Person], true)) {
            return ['type' => 'string', 'maxLength' => 262144];
        }
        if ($key === 'typed_filters') {
            return ['oneOf' => [
                ['type' => 'object'], ...($type === EntityType::Opportunity ? [] : [['type' => 'string', 'maxLength' => 262144]]),
            ]];
        }
        $instant = ['type' => 'string', 'pattern' => '^\\d{4}-\\d{2}-\\d{2}T\\d{2}:\\d{2}:\\d{2}(?:\\.\\d{1,6})?(?:Z|[+-]\\d{2}:\\d{2})$'];
        if ($key === 'period') {
            return ['type' => 'object', 'additionalProperties' => false, 'required' => ['from', 'until'], 'properties' => ['from' => $instant, 'until' => $instant]];
        }
        if (str_ends_with($key, '_from') || str_ends_with($key, '_to')) {
            if ($type === EntityType::Opportunity) {
                return ['type' => 'string', 'pattern' => '^\\d{4}-\\d{2}-\\d{2}$'];
            }

            return ['type' => 'string', 'pattern' => $type === EntityType::Lead
                ? '^\\d{4}-\\d{2}-\\d{2}T\\d{2}:\\d{2}:\\d{2}(?:\\.\\d{1,6})?Z$'
                : '^\\d{4}-\\d{2}-\\d{2}T\\d{2}:\\d{2}:\\d{2}Z$'];
        }
        if (str_ends_with($key, '_uuid') || str_ends_with($key, '_id')) {
            $schema = $type === EntityType::Lead && $key !== 'form_id' ? $reference
                : (in_array($key, ['owner_uuid', 'assignee_uuid'], true) || in_array($type, [EntityType::Person, EntityType::Organization], true) ? $user : $uuid7);

            return $type === EntityType::ServiceTicket && in_array($key, ['assignee_uuid', 'team_uuid'], true)
                ? ['oneOf' => [$schema, ['type' => 'null']]] : $schema;
        }
        if ($key === 'phone') {
            return ['type' => 'string', 'pattern' => '^\\+[1-9][0-9]{1,14}$'];
        }
        if ($key === 'installation_key') {
            return ['type' => 'string', 'maxLength' => 100];
        }
        if ($key === 'tag') {
            return ['type' => 'string', 'minLength' => 1, 'maxLength' => 100];
        }
        if ($key === 'source') {
            return ['type' => 'string', 'minLength' => 1, 'maxLength' => 255];
        }
        if ($key === 'locality_key') {
            return ['type' => 'string', 'minLength' => 1, 'maxLength' => $type === EntityType::Lead ? 700 : 2404,
                ...($type === EntityType::Lead ? [] : ['pattern' => '^[\\x20-\\x7e]+$'])];
        }

        return $text;
    }

    private static function instant(string $value): void
    {
        new \DateTimeImmutable($value);
        $errors = \DateTimeImmutable::getLastErrors();
        if ($errors !== false && ($errors['warning_count'] || $errors['error_count'])) {
            throw new \InvalidArgumentException('Retain a valid native calendar instant.');
        }
    }

    /** @param array<string,mixed> $values */
    public static function canonicalObject(array $values): object
    {
        if ($values !== [] && array_is_list($values)) {
            throw new \InvalidArgumentException('Native filters are a closed object.');
        }

        return self::canonical((object) $values, 0);
    }

    private static function canonical(mixed $value, int $depth): mixed
    {
        if ($depth > 60 || is_float($value)) {
            throw new \InvalidArgumentException('Retain exact decimals as strings and finite JSON structure.');
        }
        if ($value instanceof \stdClass || (is_array($value) && ! array_is_list($value))) {
            $object = (array) $value;
            ksort($object, SORT_STRING);
            foreach ($object as $key => $item) {
                $object[$key] = self::canonical($item, $depth + 1);
            }

            return (object) $object;
        }
        if (is_array($value)) {
            return array_map(static fn (mixed $item): mixed => self::canonical($item, $depth + 1), $value);
        }
        if ($value === null || is_int($value) || is_bool($value) || (is_string($value) && preg_match('//u', $value) === 1)) {
            return $value;
        }
        throw new \InvalidArgumentException('Use native JSON scalars, lists and objects; no credential or authority objects.');
    }
}
