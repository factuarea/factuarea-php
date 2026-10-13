<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\ServiceInsightsBatch74;

use Factuarea\Sdk\Custom\Crm\ServiceInsights\AnalysisMetrics;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\AnalysisResult;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\CsatMetrics;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\CsatResult;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\DrillItem;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\DrillPage;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\DrillResult;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\Metric;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\MetricSet;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\Selection;
use GuzzleHttp\Psr7\Response;

/** Controlled SDK V1 wire fixtures, without native Company/authority issuer assumptions. */
final class ReportingMother
{
    public const START = '2026-10-24T00:00:00.123456Z';
    public const END = '2026-10-27T00:00:00.654321Z';
    public const AT = '2026-10-27T12:30:00.000007Z';
    public const FIRST = '019a4afd-7400-7000-8000-000000000001';
    public const SECOND = '019a4afd-7400-7000-8000-000000000002';
    public const THIRD = '019a4afd-7400-7000-8000-000000000003';
    public const AGENT = '550e8400-e29b-41d4-a716-446655440000';
    public const HASH = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
    public const C7 = 'actual-native:C7';

    public static function analysis(bool $empty = false, bool $filtered = false, bool $business = false): AnalysisMetrics
    {
        $duration = $empty ? new Metric(0, 0, 0, null, null, null, null, 0, false)
            : new Metric(3, 5, 3, '9007199254740993.123456', '100000.000007', null, null, 0, false);
        $masked = new Metric($empty ? 0 : 3, null, null, null, null, null, null, null, true);

        return new AnalysisMetrics(new Selection(self::START, self::END, $business ? 'business' : 'natural',
            $filtered ? self::AGENT : null, $filtered ? self::THIRD : null, $filtered ? 'pending' : null),
            'ticket_created_at', 'current_cycle', self::AT, self::C7, self::HASH, $empty ? 0 : 3,
            new MetricSet($duration, $duration, $duration, $masked, $masked));
    }

    public static function analysisResponse(AnalysisMetrics $analysis): Response
    {
        return new Response(200, ['Content-Type' => 'application/json'], (new AnalysisResult($analysis))->toJson());
    }

    public static function csatZero(): Response
    {
        $dto = new CsatMetrics('csat', 1, self::START, self::END, 'original_closed_at', 'each_closure_participant',
            'not_applicable', 0, 0, null, null, [], self::C7, self::HASH);

        return new Response(200, ['Content-Type' => 'application/json'], (new CsatResult($dto))->toJson());
    }

    /** @param list<string> $ids */
    public static function page(array $ids, int $page, int $size, int $total, string $hash = self::HASH,
        string $at = self::AT, string $version = self::C7): Response
    {
        $items = array_map(static fn (string $id): DrillItem => new DrillItem($id, 9, 2, null, null, 2), $ids);

        return new Response(200, ['Content-Type' => 'application/json'], (new DrillResult(
            new DrillPage('nrt', $at, $version, $hash, $total, $page, $size, $items)))->toJson());
    }

    public static function error(int $status): Response
    {
        return new Response($status, ['Content-Type' => 'application/json', 'Retry-After' => '11'], json_encode(['error' => [
            'type' => 'invalid_request_error', 'code' => 'parameter_invalid', 'message' => 'Native reporting query denied',
            'param' => 'as_of', 'request_id' => 'req_insights74']], JSON_THROW_ON_ERROR));
    }
}
