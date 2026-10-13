<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\Opportunities;

use Factuarea\Sdk\Custom\Crm\CrmClient;
use Factuarea\Sdk\Custom\Crm\CrmApiException;
use Factuarea\Sdk\Custom\Crm\Opportunities\Filters;
use Factuarea\Sdk\Custom\Crm\Opportunities\ListOpportunitiesRequest;
use Factuarea\Sdk\Custom\Crm\Opportunities\MissingOpportunity;
use Factuarea\Sdk\Custom\Crm\Opportunities\OpportunitiesClient;
use Factuarea\Sdk\Custom\Crm\Opportunities\Opportunity;
use Factuarea\Sdk\Custom\Crm\Transport;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyClient;
use Factuarea\Sdk\Custom\Version\FactuareaVersionHook;
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

final class NativeTransportTest extends TestCase
{
    /** @var \ArrayObject<int,array{request:RequestInterface,response:?ResponseInterface,error:?\Throwable,options:array<string,mixed>}> */
    private \ArrayObject $history;
    /** @var \ArrayObject<int,string> */
    private \ArrayObject $operations;
    private Factuarea $sdk;

    /** @param list<Response> $responses */
    private function client(array $responses, string $token = 'native_opportunity_fixture', string $host = 'https://opportunity.fixture.invalid/v1'): OpportunitiesClient
    {
        $this->history = new \ArrayObject();
        $this->operations = new \ArrayObject();
        $history = $this->history;
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($history));
        $this->sdk = Factuarea::builder()->setServerUrl($host)->setSecurity(new Security(bearerAuth: $token))
            ->setClient(new IdempotencyClient(new Client(['handler' => $stack])))->setRetryConfig(new RetryConfigNone())->build();
        $this->sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new FactuareaVersionHook());
        $this->sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new IdempotencyHook());
        $this->sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new class($this->operations) implements BeforeRequestHook
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

        return (new CrmClient($this->sdk))->opportunities()->withHeaders(OpportunityMother::USER, '2026-10-01');
    }

    private function request(int $index): RequestInterface
    {
        $record = $this->history[$index] ?? null;
        if ($record === null) {
            throw new \LogicException('Native request was not recorded.');
        }

        return $record['request'];
    }

    public function test_native_list_and_show_use_real_singular_routes_operation_ids_and_same_sdk_security_hooks_without_keys_or_bodies(): void
    {
        $client = $this->client([OpportunityMother::page(), OpportunityMother::detail()]);
        $page = $client->listOpportunities(new ListOpportunitiesRequest());
        $detail = $client->getOpportunity(OpportunityMother::FIRST);
        self::assertSame(200, $page->statusCode());
        self::assertNull($page->idempotencyKey);
        self::assertInstanceOf(Opportunity::class, $detail->body->data);
        self::assertSame(['crm_opportunities.list', 'crm_opportunities.show'], $this->operations->getArrayCopy());
        foreach ([0, 1] as $index) {
            $request = $this->request($index);
            self::assertSame('GET', $request->getMethod());
            self::assertSame('', $request->getUri()->getQuery());
            self::assertSame('', (string) $request->getBody());
            self::assertSame('Bearer native_opportunity_fixture', $request->getHeaderLine('Authorization'));
            self::assertSame(OpportunityMother::USER, $request->getHeaderLine('X-Active-Profile'));
            self::assertSame('2026-10-01', $request->getHeaderLine('Factuarea-Version'));
            self::assertFalse($request->hasHeader('Idempotency-Key'));
        }
        self::assertSame('/v1/crm/opportunity', $this->request(0)->getUri()->getPath());
        self::assertSame('/v1/crm/opportunity/'.OpportunityMother::FIRST, $this->request(1)->getUri()->getPath());
    }

    public function test_complete_cursor_iteration_retains_exact_filters_limit_and_initial_cursor_without_mutating_request(): void
    {
        $client = $this->client([OpportunityMother::page([OpportunityMother::FIRST], 'opaque:+/=ñ', 2), OpportunityMother::page([OpportunityMother::SECOND], null, 2)]);
        $filters = new Filters(ownerId: OpportunityMother::USER, sort: 'id', direction: 'desc');
        $request = new ListOpportunitiesRequest($filters, 'original.cursor', 1);
        $items = iterator_to_array($client->listOpportunityItems($request), false);
        self::assertSame([OpportunityMother::FIRST, OpportunityMother::SECOND], array_map(static fn (Opportunity $item): string => $item->id, $items));
        self::assertSame('original.cursor', $request->cursor);
        foreach ([0, 1] as $index) {
            parse_str($this->request($index)->getUri()->getQuery(), $query);
            self::assertSame($filters->toJson(), $query['filters']);
            self::assertSame('1', $query['limit']);
            self::assertSame($index === 0 ? 'original.cursor' : 'opaque:+/=ñ', $query['cursor']);
            self::assertFalse($this->request($index)->hasHeader('Idempotency-Key'));
        }
        self::assertCount(2, $this->history);
    }

    public function test_empty_terminal_page_returns_no_items_but_does_not_hide_permission_errors(): void
    {
        $client = $this->client([OpportunityMother::page([], null, 0)]);
        self::assertSame([], iterator_to_array($client->listOpportunityItems(new ListOpportunitiesRequest()), false));
        self::assertCount(1, $this->history);
    }

    public function test_native_missing_variant_remains_distinct_from_a_current_hidden_record_or_latest_head(): void
    {
        $client = $this->client([new Response(200, ['Content-Type' => 'application/json'], '{"data":{"opportunity":null}}'), OpportunityMother::detail()]);
        self::assertInstanceOf(MissingOpportunity::class, $client->getOpportunity(OpportunityMother::FIRST)->body->data);
        self::assertInstanceOf(Opportunity::class, $client->getOpportunity(OpportunityMother::FIRST)->body->data);
        self::assertCount(2, $this->history);
    }

    public function test_detail_cannot_return_a_different_native_identity(): void
    {
        $client = $this->client([OpportunityMother::detail(OpportunityMother::SECOND)]);
        $this->expectException(\UnexpectedValueException::class);
        $client->getOpportunity(OpportunityMother::FIRST);
    }

    /** @return iterable<string,array{int}> */
    public static function deniedPages(): iterable
    {
        yield 'scope revoked' => [403];
        yield 'cursor current selection changed' => [409];
        yield 'filter validation' => [422];
        yield 'rate limit' => [429];
    }

    #[DataProvider('deniedPages')]
    public function test_error_on_a_later_page_preserves_native_status_request_id_and_retry_after_without_silent_truncation(int $status): void
    {
        $client = $this->client([OpportunityMother::page([OpportunityMother::FIRST], 'next.cursor', 2), OpportunityMother::error($status)]);
        $iterator = $client->listOpportunityItems(new ListOpportunitiesRequest());
        self::assertSame(OpportunityMother::FIRST, $iterator->current()->id);
        try {
            $iterator->next();
            self::fail('A denied page must not be treated as the end of the list.');
        } catch (CrmApiException $error) {
            self::assertSame($status, $error->statusCode);
            self::assertSame('req_opportunity73', $error->requestId);
            self::assertSame('9', $error->retryAfter);
            self::assertSame('cursor', $error->param);
        }
        self::assertCount(2, $this->history);
    }

    public function test_repeated_initial_cursor_is_rejected_before_yielding_a_duplicate_page(): void
    {
        $client = $this->client([OpportunityMother::page([OpportunityMother::FIRST], 'original.cursor', 2)]);
        $this->expectException(\UnexpectedValueException::class);
        iterator_to_array($client->listOpportunityItems(new ListOpportunitiesRequest(cursor: 'original.cursor')), false);
    }

    public function test_max_pages_throws_before_an_extra_request_instead_of_silently_stopping(): void
    {
        $client = $this->client([OpportunityMother::page([OpportunityMother::FIRST], 'next.cursor', 2)]);
        $iterator = $client->listOpportunityItems(new ListOpportunitiesRequest(), 1);
        self::assertSame(OpportunityMother::FIRST, $iterator->current()->id);
        try {
            $iterator->next();
            self::fail('An incomplete list must expose its explicit page bound.');
        } catch (\OverflowException $error) {
            self::assertStringContainsString('maxPages', $error->getMessage());
        }
        self::assertCount(1, $this->history);
    }

    public function test_factory_preserves_independent_canonical_client_tokens_and_servers(): void
    {
        $first = $this->client([OpportunityMother::page()], 'first_fixture_token', 'https://first.fixture.invalid/v1');
        $firstHistory = $this->history;
        $second = $this->client([OpportunityMother::detail()], 'second_fixture_token', 'https://second.fixture.invalid/v1');
        $first->listOpportunities(new ListOpportunitiesRequest());
        $second->getOpportunity(OpportunityMother::FIRST);
        $firstRecord = $firstHistory[0];
        if ($firstRecord === null) {
            throw new \LogicException('First native configured client did not dispatch.');
        }
        self::assertSame('Bearer first_fixture_token', $firstRecord['request']->getHeaderLine('Authorization'));
        self::assertSame('first.fixture.invalid', $firstRecord['request']->getUri()->getHost());
        self::assertSame('Bearer second_fixture_token', $this->request(0)->getHeaderLine('Authorization'));
        self::assertSame('second.fixture.invalid', $this->request(0)->getUri()->getHost());
    }

    public function test_native_uuid_header_and_page_bounds_reject_invalid_input_before_any_http(): void
    {
        $client = $this->client([]);
        foreach (['42', OpportunityMother::USER, OpportunityMother::FIRST.'?company_id=42'] as $id) {
            try {
                $client->getOpportunity($id);
                self::fail('A business UUIDv7 is required.');
            } catch (\InvalidArgumentException $error) {
                self::assertNotSame('', $error->getMessage());
            }
        }
        try {
            $client->withHeaders("other\r\nAuthorization: injected");
            self::fail('A header override must not inject another header.');
        } catch (\InvalidArgumentException $error) {
            self::assertNotSame('', $error->getMessage());
        }
        try {
            new OpportunitiesClient(new Transport($this->sdk->sdkConfiguration), ['Authorization-Version' => 'fabricated']);
            self::fail('The client cannot accept caller authority.');
        } catch (\InvalidArgumentException $error) {
            self::assertNotSame('', $error->getMessage());
        }
        try {
            iterator_to_array($client->listOpportunityItems(new ListOpportunitiesRequest(), 0));
            self::fail('The explicit page bound must be positive.');
        } catch (\InvalidArgumentException $error) {
            self::assertNotSame('', $error->getMessage());
        }
        self::assertCount(0, $this->history);
    }
}
