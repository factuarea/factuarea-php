<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\ServiceInsightsBatch74;

use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\ServiceFilters;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\ServiceMetricsRequest;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class TypedRequestsTest extends TestCase
{
    public function test_native_empty_and_supplied_filters_preserve_omission_and_public_identity_types(): void
    {
        $empty = new ServiceFilters();
        self::assertSame(Omitted::Value, $empty->agentId);
        self::assertSame([], $empty->query());
        self::assertSame('{}', $empty->toJson());
        $filter = new ServiceFilters(ReportingMother::AGENT, ReportingMother::THIRD, 'pending');
        $copy = ServiceFilters::fromArray($filter->toArray());
        self::assertSame($filter->toJson(), $copy->toJson());
        self::assertSame(['agent_id' => ReportingMother::AGENT, 'team_id' => ReportingMother::THIRD, 'status' => 'pending'], $copy->query());
        self::assertSame(ReportingMother::AGENT, $copy->agentId);
    }

    public function test_native_typed_request_retains_exact_microseconds_business_clock_and_has_no_snapshot_authority_fields(): void
    {
        $request = new ServiceMetricsRequest(ReportingMother::START, ReportingMother::END, 'business',
            new ServiceFilters(agentId: ReportingMother::AGENT));
        self::assertSame(['start' => ReportingMother::START, 'end' => ReportingMother::END, 'clock_mode' => 'business',
            'agent_id' => ReportingMother::AGENT], $request->query());
        self::assertArrayNotHasKey('as_of', $request->query());
        self::assertArrayNotHasKey('authorization_version', $request->query());
        self::assertArrayNotHasKey('company_id', $request->query());
        self::assertSame(ReportingMother::START, $request->start);
    }

    /** @return iterable<string,array{string,mixed}> */
    public static function invalidFilters(): iterable
    {
        yield 'input null cannot mean a hidden GET field' => ['team_id', null];
        yield 'numeric actor' => ['agent_id', 3];
        yield 'historical user is not a business Team' => ['team_id', ReportingMother::AGENT];
        yield 'new invented money filter' => ['currency', 'EUR'];
        yield 'caller authorization' => ['authorization_version', ReportingMother::C7];
        yield 'Company input' => ['company_id', 42];
        yield 'SPA alias' => ['agent_uuid', ReportingMother::AGENT];
        yield 'foreign status' => ['status', 'open'];
    }

    #[DataProvider('invalidFilters')]
    public function test_invalid_or_foreign_query_inputs_cannot_be_coerced_into_native_filters(string $field, mixed $value): void
    {
        $this->expectException(\InvalidArgumentException::class);
        ServiceFilters::fromArray([$field => $value]);
    }

    public function test_typed_cohort_and_clock_are_validated_without_normalizing_an_incompatible_timezone_or_instant(): void
    {
        foreach ([['2026-10-24T00:00:00Z', ReportingMother::END, 'natural'],
            ['2026-10-24T02:00:00.123456+02:00', ReportingMother::END, 'natural'],
            [ReportingMother::START, ReportingMother::START, 'natural'],
            [ReportingMother::START, ReportingMother::END, 'calendar']] as [$start, $end, $clock]) {
            try {
                new ServiceMetricsRequest($start, $end, $clock);
                self::fail('The exact native cohort and clock should reject this input.');
            } catch (\InvalidArgumentException $error) {
                self::assertNotSame('', $error->getMessage());
            }
        }
    }
}
