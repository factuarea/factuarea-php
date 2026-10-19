<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\ServiceInsights;

use Factuarea\Sdk\Custom\Crm\ServiceInsights\AnalysisMetrics;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\CsatMetrics;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\CsatResult;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\DrillItem;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\DrillPage;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\Metric;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\MetricSet;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\ReadInput;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\RequiredPaths;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\Selection;
use PHPUnit\Framework\TestCase;

final class CodecTest extends TestCase
{
    public const START = '2026-10-09T00:00:00.000001Z';
    public const END = '2026-10-10T00:00:00.000002Z';
    public const AT = '2026-10-10T12:00:00.000003Z';
    public const ID = '019a4afd-5000-7000-8000-000000000001';
    public const AGENT = '550e8400-e29b-41d4-a716-446655440000';

    public static function csat(bool $masked = false): CsatMetrics
    {
        return new CsatMetrics('csat', 1, self::START, self::END, 'original_closed_at', 'each_closure_participant', 'not_applicable',
            7, $masked ? null : 3, $masked ? null : '4.3333', $masked ? null : '4.0000',
            $masked ? ['answered_at', 'rating'] : [], 'native-current:C7', hash('sha256', 'original current cohort'));
    }

    public static function analysis(bool $filters = false): AnalysisMetrics
    {
        $duration = new Metric(5, 3, 2, '9007199254740993.123456', '120000.000001', null, null, 2, false);
        $masked = new Metric(5, null, null, null, null, null, null, null, true);

        return new AnalysisMetrics(new Selection(self::START, self::END, 'natural', $filters ? self::AGENT : null,
            $filters ? self::ID : null, $filters ? 'pending' : null), 'ticket_created_at', 'current_cycle', self::AT,
            'native-current:C7', hash('sha256', 'original current cohort'), 5,
            new MetricSet($duration, $duration, $duration, $masked, $masked));
    }

    public static function drill(int $page = 1, int $perPage = 25, ?string $hash = null, ?string $at = null, ?string $version = null): DrillPage
    {
        return new DrillPage('nrt', $at ?? self::AT, $version ?? 'native-current:C7', $hash ?? hash('sha256', 'original current cohort'),
            1, $page, $perPage, [new DrillItem(self::ID, 8, 3, null, null, 2)]);
    }

    public function test_masked_csat_denominator_and_rating_remain_null_and_four_decimal_values_remain_strings(): void
    {
        $visible = self::csat();
        $decoded = CsatResult::fromArray(['data' => $visible->jsonSerialize()]);
        self::assertSame('4.3333', $decoded->data->mean);
        self::assertSame('4.0000', $decoded->data->median);
        self::assertArrayNotHasKey('as_of', $decoded->data->toArray());
        $masked = CsatResult::fromArray(['data' => self::csat(true)->jsonSerialize()]);
        self::assertNull($masked->data->answered);
        self::assertNull($masked->data->mean);
        self::assertSame(7, $masked->data->invited);
        self::assertSame(['answered_at', 'rating'], $masked->data->maskedFields);
    }

    public function test_masked_analytics_cannot_expose_denominators_and_large_microsecond_decimals_are_lossless(): void
    {
        $facts = self::analysis();
        $decoded = AnalysisMetrics::fromArray($facts->toArray());
        self::assertSame('9007199254740993.123456', $decoded->metrics->frt->meanMicroseconds);
        self::assertNull($decoded->metrics->backlog->sampleCount);
        self::assertSame(5, $decoded->metrics->backlog->eligibleCount);
        self::reject(static fn () => new Metric(5, 0, null, null, null, null, null, null, true));
        $wrong = $decoded->metrics->frt->toArray();
        $wrong['mean_microseconds'] = 1.25;
        self::reject(static fn () => Metric::fromArray($wrong));
    }

    public function test_public_drill_ids_are_closed_and_private_body_or_internal_uuid_is_never_decoded(): void
    {
        $item = new DrillItem(self::ID, 7, 3, null, null, 2);
        self::assertNull(DrillItem::fromArray($item->toArray())->label);
        $private = $item->toArray();
        $private['body'] = 'private body';
        self::reject(static fn () => DrillItem::fromArray($private));
        $internal = $item->toArray();
        unset($internal['id']);
        $internal['uuid'] = self::ID;
        self::reject(static fn () => DrillItem::fromArray($internal));
        $abbreviated = $item->toArray();
        unset($abbreviated['version']);
        $abbreviated['v'] = 7;
        self::reject(static fn () => DrillItem::fromArray($abbreviated));
    }

    public function test_native_calendar_and_query_inputs_reject_normalization_internal_ids_and_extra_authority(): void
    {
        self::assertSame(self::START, ReadInput::instant(self::START)->format('Y-m-d\TH:i:s.u\Z'));
        self::assertSame(self::AGENT, ReadInput::selection(self::START, self::END, 'business', ['agent_id' => self::AGENT])['agent_id']);
        foreach (['2026-02-30T00:00:00.000001Z', '2026-10-09T00:00:00.000001+00:00', '2026-10-09T00:00:00Z'] as $bad) {
            self::reject(static fn () => ReadInput::instant($bad));
        }
        self::reject(static fn () => ReadInput::cohort(self::END, self::START));
        self::reject(static fn () => ReadInput::selection(self::START, self::END, 'natural', ['agent_uuid' => self::AGENT]));
        self::reject(static fn () => ReadInput::selection(self::START, self::END, 'natural', ['company_id' => '41']));
    }

    public function test_required_paths_have_no_uri_defaults_or_query_substitution(): void
    {
        $paths = new RequiredPaths('configured/csat', 'configured/metrics', 'configured/drill');
        self::assertSame('configured/drill', $paths->getServiceInsightDrillThrough);
        foreach (['', '/crm/service-insights', '../private', 'https://other.invalid', 'crm/insights?company_id=41', 'crm/{ticket}'] as $bad) {
            self::reject(static fn () => new RequiredPaths($bad, 'configured/metrics', 'configured/drill'));
        }
    }

    private static function reject(callable $operation): void
    {
        try {
            $operation();
            self::fail('The native closed read boundary must reject this input.');
        } catch (\InvalidArgumentException $error) {
            self::assertNotSame('', $error->getMessage());
        }
    }
}
