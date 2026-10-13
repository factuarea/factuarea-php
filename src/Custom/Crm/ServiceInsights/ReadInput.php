<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\ServiceInsights;

use Factuarea\Sdk\Custom\Crm\Schema;

/** Closed primitives from the native V1 read seam, preserving microseconds and masked nulls. */
final readonly class ReadInput
{
    public const ID = ['type' => 'string', 'pattern' => '^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$'];
    public const ACTOR_ID = ['type' => 'string', 'pattern' => '^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$'];
    public const INSTANT = ['type' => 'string', 'pattern' => '^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{6}Z$'];
    public const HASH = ['type' => 'string', 'pattern' => '^[a-f0-9]{64}$'];
    public const COUNT = ['type' => 'integer', 'minimum' => 0];
    public const POSITIVE = ['type' => 'integer', 'minimum' => 1];
    public const STATUS = ['type' => 'string', 'enum' => ['new', 'assigned', 'pending', 'closed', 'rejected', 'duplicate']];
    public const METRIC = ['type' => 'string', 'enum' => ['frt', 'nrt', 'mttr', 'backlog', 'sla_compliance']];
    public const VERSION = ['type' => 'string', 'minLength' => 1];
    public const SELECTION = ['type' => 'object', 'additionalProperties' => false,
        'required' => ['start', 'end', 'clock_mode', 'agent_id', 'team_id', 'status'],
        'properties' => ['start' => self::INSTANT, 'end' => self::INSTANT,
            'clock_mode' => ['type' => 'string', 'enum' => ['natural', 'business']],
            'agent_id' => ['anyOf' => [self::ACTOR_ID, ['type' => 'null']]],
            'team_id' => ['anyOf' => [self::ID, ['type' => 'null']]],
            'status' => ['anyOf' => [self::STATUS, ['type' => 'null']]]]];
    public const NULL_COUNT = ['anyOf' => [self::COUNT, ['type' => 'null']]];
    public const NULL_DURATION = ['anyOf' => [['type' => 'string', 'pattern' => '^[0-9]+\.[0-9]{6}$'], ['type' => 'null']]];
    public const ANALYTIC_METRIC = ['type' => 'object', 'additionalProperties' => false,
        'required' => ['eligible_count', 'sample_count', 'contributor_count', 'mean_microseconds', 'median_microseconds',
            'value_count', 'compliant_count', 'missing_count', 'masked'],
        'properties' => ['eligible_count' => self::COUNT, 'sample_count' => self::NULL_COUNT, 'contributor_count' => self::NULL_COUNT,
            'mean_microseconds' => self::NULL_DURATION, 'median_microseconds' => self::NULL_DURATION,
            'value_count' => self::NULL_COUNT, 'compliant_count' => self::NULL_COUNT, 'missing_count' => self::NULL_COUNT,
            'masked' => ['type' => 'boolean']]];
    public const METRIC_SET = ['type' => 'object', 'additionalProperties' => false,
        'required' => ['frt', 'nrt', 'mttr', 'backlog', 'sla_compliance'],
        'properties' => ['frt' => self::ANALYTIC_METRIC, 'nrt' => self::ANALYTIC_METRIC, 'mttr' => self::ANALYTIC_METRIC,
            'backlog' => self::ANALYTIC_METRIC, 'sla_compliance' => self::ANALYTIC_METRIC]];
    public const CSAT = ['type' => 'object', 'additionalProperties' => false,
        'required' => ['metric', 'metric_version', 'cohort_start', 'cohort_end', 'cohort_basis', 'cycle_policy', 'clock_mode',
            'invited', 'answered', 'mean', 'median', 'masked_fields', 'authorization_version', 'predicate_hash'],
        'properties' => ['metric' => ['const' => 'csat'], 'metric_version' => ['const' => 1],
            'cohort_start' => self::INSTANT, 'cohort_end' => self::INSTANT, 'cohort_basis' => ['const' => 'original_closed_at'],
            'cycle_policy' => ['const' => 'each_closure_participant'], 'clock_mode' => ['const' => 'not_applicable'],
            'invited' => self::COUNT, 'answered' => self::NULL_COUNT,
            'mean' => ['anyOf' => [['type' => 'string', 'pattern' => '^[0-9]+\.[0-9]{4}$'], ['type' => 'null']]],
            'median' => ['anyOf' => [['type' => 'string', 'pattern' => '^[0-9]+\.[0-9]{4}$'], ['type' => 'null']]],
            'masked_fields' => ['type' => 'array', 'uniqueItems' => true, 'items' => ['type' => 'string', 'enum' => ['answered_at', 'rating']]],
            'authorization_version' => self::VERSION, 'predicate_hash' => self::HASH]];
    public const ANALYSIS = ['type' => 'object', 'additionalProperties' => false,
        'required' => ['selection', 'cohort_basis', 'cycle_basis', 'as_of', 'authorization_version', 'predicate_hash', 'cohort_count', 'metrics'],
        'properties' => ['selection' => self::SELECTION, 'cohort_basis' => ['const' => 'ticket_created_at'],
            'cycle_basis' => ['const' => 'current_cycle'], 'as_of' => self::INSTANT, 'authorization_version' => self::VERSION,
            'predicate_hash' => self::HASH, 'cohort_count' => self::COUNT, 'metrics' => self::METRIC_SET]];
    public const DRILL_ITEM = ['type' => 'object', 'additionalProperties' => false,
        'required' => ['id', 'version', 'cycle', 'label', 'status', 'sample_count'],
        'properties' => ['id' => self::ID, 'version' => self::POSITIVE, 'cycle' => self::POSITIVE,
            'label' => ['type' => ['string', 'null']], 'status' => ['anyOf' => [self::STATUS, ['type' => 'null']]],
            'sample_count' => self::POSITIVE]];
    public const DRILL = ['type' => 'object', 'additionalProperties' => false,
        'required' => ['metric', 'as_of', 'authorization_version', 'predicate_hash', 'total', 'page', 'per_page', 'items'],
        'properties' => ['metric' => self::METRIC, 'as_of' => self::INSTANT, 'authorization_version' => self::VERSION,
            'predicate_hash' => self::HASH, 'total' => self::COUNT, 'page' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 9999999],
            'per_page' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100],
            'items' => ['type' => 'array', 'maxItems' => 100, 'items' => self::DRILL_ITEM]]];

    public static function instant(string $value): \DateTimeImmutable
    {
        Schema::validate(self::INSTANT, $value);
        $date = \DateTimeImmutable::createFromFormat('!Y-m-d\TH:i:s.u\Z', $value, new \DateTimeZone('UTC'));
        if ($date === false || $date->format('Y-m-d\TH:i:s.u\Z') !== $value) {
            throw new \InvalidArgumentException('Supply the native UTC instant with six unchanged fractional digits.');
        }

        return $date;
    }

    public static function cohort(string $start, string $end): void
    {
        $from = self::instant($start);
        $through = self::instant($end);
        if ($from >= $through || $from->modify('+366 days') < $through) {
            throw new \InvalidArgumentException('Supply the native half-open cohort of at most 366 days.');
        }
    }

    /** @param array<string,string> $filters
     * @return array<string,string> */
    public static function selection(string $start, string $end, string $clockMode, array $filters): array
    {
        self::cohort($start, $end);
        $properties = ['agent_id' => self::ACTOR_ID, 'team_id' => self::ID, 'status' => self::STATUS];
        Schema::validate(['type' => 'object', 'additionalProperties' => false, 'properties' => $properties], (object) $filters);
        Schema::validate(self::SELECTION['properties']['clock_mode'], $clockMode);

        return ['start' => $start, 'end' => $end, 'clock_mode' => $clockMode, ...$filters];
    }
}
