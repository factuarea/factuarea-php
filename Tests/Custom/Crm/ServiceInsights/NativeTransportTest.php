<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\ServiceInsights;

use Factuarea\Sdk\Custom\Crm\CrmClient;
use Factuarea\Sdk\Custom\Crm\CrmApiException;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\AnalysisResult;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\CsatResult;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\DrillResult;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\RequiredPaths;
use Factuarea\Sdk\Custom\Crm\ServiceInsights\ServiceInsightsClient;
use Factuarea\Sdk\Custom\Crm\WireModel;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyClient;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyHook;
use Factuarea\Sdk\Custom\Version\FactuareaVersionHook;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Models\Components\Security;
use Factuarea\Sdk\Utils\Retry\RetryConfigNone;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/** Exercises the canonical SDK/Guzzle boundary offline, never a backend engine or unmounted live endpoint. */
final class NativeTransportTest extends TestCase
{
    /** @var \ArrayObject<int,array{request:RequestInterface,response:?ResponseInterface,error:?\Throwable,options:array<string,mixed>}> */
    private \ArrayObject $history;

    /** @param list<Response> $responses */
    private function client(array $responses): ServiceInsightsClient
    {
        $this->history = new \ArrayObject;
        $stack = HandlerStack::create(new MockHandler($responses));
        $history = $this->history;
        $stack->push(Middleware::history($history));
        $sdk = Factuarea::builder()->setServerUrl('https://configured-native.fixture.invalid/v1')
            ->setClient(new IdempotencyClient(new Client(['handler' => $stack])))
            ->setSecurity(new Security(bearerAuth: 'native_read_fixture'))
            ->setRetryConfig(new RetryConfigNone)->build();
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new FactuareaVersionHook);
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new IdempotencyHook);

        return (new CrmClient($sdk))->serviceInsights(new RequiredPaths('configured/csat', 'configured/metrics', 'configured/drill'));
    }

    private function response(WireModel $model): Response
    {
        return new Response(200, ['Content-Type' => 'application/json', 'Cache-Control' => 'private, no-store'], $model->toJson());
    }

    private function error(int $status): Response
    {
        return new Response($status, ['Content-Type' => 'application/json', 'Retry-After' => '7', 'X-Request-Id' => 'req_native_read'], json_encode(['error' => [
            'code' => match ($status) {
                403 => 'crm_access_denied', 409 => 'resource_conflict', 429 => 'rate_limit_exceeded', default => 'service_unavailable'
            },
            'type' => match ($status) {
                403 => 'authorization_error', 409 => 'conflict_error', 429 => 'rate_limit_error', default => 'service_unavailable_error'
            },
            'message' => 'No se puede completar la consulta.', 'param' => null, 'subcode' => null, 'request_id' => 'req_native_read',
        ]], JSON_THROW_ON_ERROR));
    }

    private function request(int $index): RequestInterface
    {
        $record = $this->history[$index] ?? null;
        if ($record === null) {
            throw new \LogicException('The native SDK request was not recorded.');
        }

        return $record['request'];
    }

    public function test_csat_uses_the_same_configured_authentication_and_get_without_a_mutation_body_or_generated_key(): void
    {
        $client = $this->client([$this->response(new CsatResult(CodecTest::csat(true)))]);
        $result = $client->getServiceInsights(CodecTest::START, CodecTest::END);
        self::assertSame(200, $result->statusCode());
        self::assertNull($result->body->data->answered);
        self::assertNull($result->idempotencyKey);
        $request = $this->request(0);
        self::assertSame('GET', $request->getMethod());
        self::assertSame('/v1/configured/csat', $request->getUri()->getPath());
        self::assertSame('Bearer native_read_fixture', $request->getHeaderLine('Authorization'));
        self::assertNotSame('', $request->getHeaderLine('Factuarea-Version'));
        self::assertFalse($request->hasHeader('Idempotency-Key'));
        self::assertSame('', (string) $request->getBody());
        parse_str($request->getUri()->getQuery(), $query);
        self::assertSame(['start' => CodecTest::START, 'end' => CodecTest::END], $query);
    }

    public function test_metrics_and_drill_reuse_exact_original_selection_hash_microseconds_and_numbered_page(): void
    {
        $client = $this->client([$this->response(new AnalysisResult(CodecTest::analysis(true))), $this->response(new DrillResult(CodecTest::drill(2, 10)))]);
        $original = $client->getServiceMetrics(CodecTest::START, CodecTest::END, 'natural',
            ['agent_id' => CodecTest::AGENT, 'team_id' => CodecTest::ID, 'status' => 'pending'])->body->data;
        $drill = $client->getServiceInsightDrillThrough($original, 'nrt', 2, 10);
        self::assertSame(8, $drill->body->data->items[0]->version);
        self::assertNull($drill->body->data->items[0]->label);
        self::assertSame('9007199254740993.123456', $original->metrics->frt->meanMicroseconds);
        parse_str($this->request(1)->getUri()->getQuery(), $query);
        self::assertSame(['start' => CodecTest::START, 'end' => CodecTest::END, 'clock_mode' => 'natural', 'agent_id' => CodecTest::AGENT,
            'team_id' => CodecTest::ID, 'status' => 'pending', 'as_of' => CodecTest::AT, 'predicate_hash' => $original->predicateHash,
            'metric' => 'nrt', 'page' => '2', 'per_page' => '10'], $query);
        self::assertArrayNotHasKey('cursor', $query);
        foreach ($this->history as $record) {
            self::assertSame('GET', $record['request']->getMethod());
            self::assertSame('', (string) $record['request']->getBody());
            self::assertFalse($record['request']->hasHeader('Idempotency-Key'));
        }
    }

    public function test_nullable_original_filters_are_omitted_from_the_drill_query_instead_of_becoming_null_strings(): void
    {
        $client = $this->client([$this->response(new DrillResult(CodecTest::drill()))]);
        $client->getServiceInsightDrillThrough(CodecTest::analysis(), 'nrt');
        parse_str($this->request(0)->getUri()->getQuery(), $query);
        foreach (['agent_id', 'team_id', 'status'] as $field) {
            self::assertArrayNotHasKey($field, $query);
        }
        self::assertSame('1', $query['page']);
        self::assertSame('25', $query['per_page']);
    }

    public function test_current_denial_conflict_rate_limit_and_unavailable_keep_the_canonical_native_error_and_headers(): void
    {
        $client = $this->client(array_map($this->error(...), [403, 409, 429, 503]));
        foreach ([403, 409, 429, 503] as $status) {
            try {
                $client->getServiceInsightDrillThrough(CodecTest::analysis(), 'nrt');
                self::fail('Current native authorization or original predicate must fail closed.');
            } catch (CrmApiException $failure) {
                self::assertSame($status, $failure->statusCode);
                self::assertSame('req_native_read', $failure->requestId);
                self::assertSame('7', $failure->retryAfter);
            }
        }
        self::assertCount(4, $this->history);
    }

    public function test_a_different_original_hash_cannot_be_accepted_as_the_requested_drill_result(): void
    {
        $client = $this->client([$this->response(new DrillResult(CodecTest::drill(hash: hash('sha256', 'foreign predicate'))))]);
        $this->expectException(\UnexpectedValueException::class);
        $client->getServiceInsightDrillThrough(CodecTest::analysis(), 'nrt');
    }

    public function test_a_changed_c7_or_one_microsecond_as_of_cannot_replace_the_original_snapshot(): void
    {
        $client = $this->client([$this->response(new DrillResult(CodecTest::drill(version: 'changed:C7'))),
            $this->response(new DrillResult(CodecTest::drill(at: '2026-10-10T12:00:00.000004Z')))]);
        foreach ([0, 1] as $index) {
            try {
                $client->getServiceInsightDrillThrough(CodecTest::analysis(), 'nrt');
                self::fail('A changed authorization or snapshot is not the original drill selection.');
            } catch (\UnexpectedValueException) {
                self::assertCount($index + 1, $this->history);
            }
        }
    }

    public function test_a_foreign_metrics_selection_is_rejected_without_requesting_a_second_head(): void
    {
        $client = $this->client([$this->response(new AnalysisResult(CodecTest::analysis(true)))]);
        try {
            $client->getServiceMetrics(CodecTest::START, CodecTest::END, 'natural');
            self::fail('A foreign current selection cannot replace the query.');
        } catch (\UnexpectedValueException) {
            self::assertCount(1, $this->history);
        }
    }

    public function test_closed_native_filters_are_validated_before_any_http_request(): void
    {
        $client = $this->client([]);
        try {
            $client->getServiceMetrics(CodecTest::START, CodecTest::END, 'natural', ['agent_uuid' => CodecTest::AGENT]);
            self::fail('An internal identity alias is not a public query field.');
        } catch (\InvalidArgumentException) {
            self::assertCount(0, $this->history);
        }
    }
}
