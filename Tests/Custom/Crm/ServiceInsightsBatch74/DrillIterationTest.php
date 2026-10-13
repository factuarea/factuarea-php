<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\ServiceInsightsBatch74;

use Factuarea\Sdk\Custom\Crm\CrmClient;
use Factuarea\Sdk\Custom\Crm\CrmApiException;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\DrillItem;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\RequiredPaths;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\ServiceFilters;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\ServiceInsightsClient;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\ServiceMetricsRequest;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyClient;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyHook;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Hooks\BeforeRequestContext;
use Factuarea\Sdk\Hooks\BeforeRequestHook;
use Factuarea\Sdk\Models\Components\Security;
use Factuarea\Sdk\Utils\Retry\RetryConfigNone;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class DrillIterationTest extends TestCase
{
    /** @var \ArrayObject<int,array{request:RequestInterface,response:?ResponseInterface,error:?\Throwable,options:array<string,mixed>}> */
    private \ArrayObject $history;
    /** @var \ArrayObject<int,string> */
    private \ArrayObject $operations;

    /** @param list<Response> $responses */
    private function client(array $responses): ServiceInsightsClient
    {
        $this->history = new \ArrayObject();
        $this->operations = new \ArrayObject();
        $stack = HandlerStack::create(new MockHandler($responses));
        $history = $this->history;
        $stack->push(Middleware::history($history));
        $sdk = Factuarea::builder()->setServerUrl('https://insights.fixture.invalid/v1')
            ->setSecurity(new Security(bearerAuth: 'native_insights_fixture'))
            ->setClient(new IdempotencyClient(new Client(['handler' => $stack])))->setRetryConfig(new RetryConfigNone())->build();
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new IdempotencyHook());
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new class($this->operations) implements BeforeRequestHook
        {
            /** @param \ArrayObject<int,string> $operations */
            public function __construct(private \ArrayObject $operations)
            {
            }
            public function beforeRequest(BeforeRequestContext $context, RequestInterface $request): RequestInterface
            {
                $this->operations->append($context->operationID);

                return $request;
            }
        });

        return (new CrmClient($sdk))->serviceInsights(new RequiredPaths('crm/service-insights',
            'crm/service-insights/service-metrics', 'crm/service-insights/drill'));
    }

    private function request(int $index): RequestInterface
    {
        $record = $this->history[$index] ?? null;
        if ($record === null) {
            throw new \LogicException('Native reporting request was not recorded.');
        }

        return $record['request'];
    }

    public function test_new_typed_metrics_and_stream_call_real_routes_with_zero_data_and_lossless_masked_values(): void
    {
        $analysis = ReportingMother::analysis(empty: true, filtered: true, business: true);
        $client = $this->client([ReportingMother::csatZero(), ReportingMother::analysisResponse($analysis), ReportingMother::page([], 1, 25, 0)]);
        $csat = $client->getServiceInsights(ReportingMother::START, ReportingMother::END)->body->data;
        self::assertSame(0, $csat->invited);
        self::assertSame(0, $csat->answered);
        self::assertNull($csat->mean);
        self::assertNull($csat->median);
        $metrics = $client->getServiceMetricsForRequest(new ServiceMetricsRequest(ReportingMother::START, ReportingMother::END,
            'business', new ServiceFilters(ReportingMother::AGENT, ReportingMother::THIRD, 'pending')))->body->data;
        self::assertSame(0, $metrics->cohortCount);
        self::assertSame(0, $metrics->metrics->frt->sampleCount);
        self::assertNull($metrics->metrics->frt->meanMicroseconds);
        self::assertNull($metrics->metrics->backlog->sampleCount);
        self::assertTrue($metrics->metrics->backlog->masked);
        self::assertSame([], iterator_to_array($client->getServiceInsightDrillThroughItems($metrics, 'nrt'), false));
        self::assertSame(['service_insights.metrics', 'service_insights.service_metrics', 'service_insights.drill'], $this->operations->getArrayCopy());
        foreach (['crm/service-insights', 'crm/service-insights/service-metrics', 'crm/service-insights/drill'] as $index => $path) {
            self::assertSame('/v1/'.$path, $this->request($index)->getUri()->getPath());
            self::assertSame('GET', $this->request($index)->getMethod());
            self::assertSame('', (string) $this->request($index)->getBody());
            self::assertFalse($this->request($index)->hasHeader('Idempotency-Key'));
            self::assertSame('Bearer native_insights_fixture', $this->request($index)->getHeaderLine('Authorization'));
        }
    }

    public function test_complete_numbered_iteration_reuses_factual_original_selection_microseconds_hash_and_c7_on_every_page(): void
    {
        $original = ReportingMother::analysis(filtered: true);
        $client = $this->client([ReportingMother::page([ReportingMother::FIRST, ReportingMother::SECOND], 1, 2, 3),
            ReportingMother::page([ReportingMother::THIRD], 2, 2, 3)]);
        $items = iterator_to_array($client->getServiceInsightDrillThroughItems($original, 'nrt', perPage: 2), false);
        self::assertSame([ReportingMother::FIRST, ReportingMother::SECOND, ReportingMother::THIRD], array_map(static fn (DrillItem $item): string => $item->id, $items));
        self::assertSame('9007199254740993.123456', $original->metrics->nrt->meanMicroseconds);
        self::assertNull($items[0]->label);
        self::assertNull($items[0]->status);
        self::assertSame(2, $items[0]->sampleCount);
        foreach ([0, 1] as $index) {
            parse_str($this->request($index)->getUri()->getQuery(), $query);
            self::assertSame(ReportingMother::AT, $query['as_of']);
            self::assertSame(ReportingMother::HASH, $query['predicate_hash']);
            self::assertSame(ReportingMother::AGENT, $query['agent_id']);
            self::assertSame(ReportingMother::THIRD, $query['team_id']);
            self::assertSame('pending', $query['status']);
            self::assertSame('natural', $query['clock_mode']);
            self::assertSame((string) ($index + 1), $query['page']);
            self::assertSame('2', $query['per_page']);
            self::assertArrayNotHasKey('authorization_version', $query);
            self::assertArrayNotHasKey('company_id', $query);
        }
        self::assertSame(ReportingMother::AT, $original->asOf);
        self::assertSame(ReportingMother::C7, $original->authorizationVersion);
        self::assertCount(2, $this->history);
    }

    public function test_resume_from_a_native_page_keeps_the_original_filters_and_omits_nullable_selection_fields(): void
    {
        $client = $this->client([ReportingMother::page([ReportingMother::THIRD], 2, 2, 3)]);
        $items = iterator_to_array($client->getServiceInsightDrillThroughItems(ReportingMother::analysis(), 'nrt', firstPage: 2, perPage: 2), false);
        self::assertCount(1, $items);
        self::assertSame(ReportingMother::THIRD, $items[0]->id);
        parse_str($this->request(0)->getUri()->getQuery(), $query);
        self::assertSame('2', $query['page']);
        self::assertArrayNotHasKey('agent_id', $query);
        self::assertArrayNotHasKey('team_id', $query);
        self::assertArrayNotHasKey('status', $query);
    }

    /** @return iterable<string,array{int}> */
    public static function currentDenials(): iterable
    {
        yield 'current ACL or privacy withdrawn' => [403];
        yield 'original selection changed' => [409];
        yield 'native cohort validation' => [422];
        yield 'rate limit' => [429];
    }

    #[DataProvider('currentDenials')]
    public function test_denial_on_a_later_page_cannot_become_a_partial_success_or_trigger_a_fresh_metrics_head(int $status): void
    {
        $client = $this->client([ReportingMother::page([ReportingMother::FIRST], 1, 1, 2), ReportingMother::error($status)]);
        $stream = $client->getServiceInsightDrillThroughItems(ReportingMother::analysis(), 'nrt', perPage: 1);
        self::assertSame(ReportingMother::FIRST, $stream->current()->id);
        try {
            $stream->next();
            self::fail('The current native denial must propagate.');
        } catch (CrmApiException $error) {
            self::assertSame($status, $error->statusCode);
            self::assertSame('req_insights74', $error->requestId);
            self::assertSame('11', $error->retryAfter);
        }
        self::assertSame(['service_insights.drill', 'service_insights.drill'], $this->operations->getArrayCopy());
        self::assertCount(2, $this->history);
    }

    /** @return iterable<string,array{Response}> */
    public static function inconsistentPages(): iterable
    {
        yield 'count drift' => [ReportingMother::page([ReportingMother::SECOND], 2, 1, 3)];
        yield 'duplicate contributor' => [ReportingMother::page([ReportingMother::FIRST], 2, 1, 2)];
        yield 'nonsequential page' => [ReportingMother::page([ReportingMother::SECOND], 1, 1, 2)];
        yield 'unexpected page size' => [ReportingMother::page([ReportingMother::SECOND], 2, 2, 2)];
        yield 'C7 changed' => [ReportingMother::page([ReportingMother::SECOND], 2, 1, 2, version: 'changed:C7')];
        yield 'one microsecond newer snapshot' => [ReportingMother::page([ReportingMother::SECOND], 2, 1, 2, at: '2026-10-27T12:30:00.000008Z')];
        yield 'different predicate' => [ReportingMother::page([ReportingMother::SECOND], 2, 1, 2, hash: str_repeat('b', 64))];
        yield 'short nonfinal page' => [ReportingMother::page([], 2, 1, 2)];
    }

    #[DataProvider('inconsistentPages')]
    public function test_incoherent_later_pages_are_rejected_before_yielding_their_ids(Response $wrong): void
    {
        $client = $this->client([ReportingMother::page([ReportingMother::FIRST], 1, 1, 2), $wrong]);
        $stream = $client->getServiceInsightDrillThroughItems(ReportingMother::analysis(), 'nrt', perPage: 1);
        self::assertSame(ReportingMother::FIRST, $stream->current()->id);
        $this->expectException(\UnexpectedValueException::class);
        $stream->next();
    }

    public function test_max_pages_throws_before_more_http_and_preserves_the_native_resume_page(): void
    {
        $client = $this->client([ReportingMother::page([ReportingMother::FIRST], 1, 1, 2)]);
        $stream = $client->getServiceInsightDrillThroughItems(ReportingMother::analysis(), 'nrt', perPage: 1, maxPages: 1);
        self::assertSame(ReportingMother::FIRST, $stream->current()->id);
        try {
            $stream->next();
            self::fail('A truncated contributing ID list must be explicit.');
        } catch (\OverflowException $error) {
            self::assertStringContainsString('maxPages', $error->getMessage());
        }
        self::assertCount(1, $this->history);
    }

    public function test_invalid_metric_or_native_page_bounds_fail_before_any_http(): void
    {
        $client = $this->client([]);
        foreach ([['currency', 1, 25, 100], ['nrt', 0, 25, 100], ['nrt', 1, 101, 100], ['nrt', 1, 25, 0]] as [$metric, $page, $size, $maximum]) {
            try {
                iterator_to_array($client->getServiceInsightDrillThroughItems(ReportingMother::analysis(), $metric, $page, $size, $maximum), false);
                self::fail('Invalid native drill selection or explicit limit must be rejected.');
            } catch (\InvalidArgumentException $error) {
                self::assertNotSame('', $error->getMessage());
            }
        }
        self::assertCount(0, $this->history);
    }
}
