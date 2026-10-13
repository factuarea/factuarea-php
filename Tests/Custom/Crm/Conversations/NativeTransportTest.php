<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\Conversations;

use Factuarea\Sdk\Custom\Crm\Conversations\ConversationsClient;
use Factuarea\Sdk\Custom\Crm\Conversations\GetConversationRequest;
use Factuarea\Sdk\Custom\Crm\Conversations\ListConversationsRequest;
use Factuarea\Sdk\Custom\Crm\CrmClient;
use Factuarea\Sdk\Custom\Crm\CrmApiException;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyClient;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyHook;
use Factuarea\Sdk\Custom\Version\FactuareaVersionHook;
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

/** Exercises only the canonical configured SDK/Guzzle boundary with an offline handler. */
final class NativeTransportTest extends TestCase
{
    /** @var \ArrayObject<int,array{request:RequestInterface,response:?ResponseInterface,error:?\Throwable,options:array<string,mixed>}> */
    private \ArrayObject $history;
    /** @var \ArrayObject<int,string> */
    private \ArrayObject $operations;

    /** @param list<Response> $responses */
    private function client(array $responses, string $token = 'conversation_read_fixture', string $server = 'https://conversation.fixture.invalid/v1'): ConversationsClient
    {
        $this->history = new \ArrayObject;
        $this->operations = new \ArrayObject;
        $stack = HandlerStack::create(new MockHandler($responses));
        $history = $this->history;
        $stack->push(Middleware::history($history));
        $sdk = Factuarea::builder()->setServerUrl($server)
            ->setClient(new IdempotencyClient(new Client(['handler' => $stack])))
            ->setSecurity(new Security(bearerAuth: $token))->setRetryConfig(new RetryConfigNone)->build();
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new FactuareaVersionHook);
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new IdempotencyHook);
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

        return (new CrmClient($sdk))->conversations();
    }

    /** @param array<string,mixed> $wire */
    private function response(array $wire): Response
    {
        return new Response(200, ['Content-Type' => 'application/json', 'Cache-Control' => 'private, no-store'], json_encode($wire, JSON_THROW_ON_ERROR));
    }

    private function request(int $index): RequestInterface
    {
        $record = $this->history[$index] ?? null;
        if ($record === null) {
            throw new \LogicException('The configured SDK request was not recorded.');
        }

        return $record['request'];
    }

    public function test_real_list_and_get_use_the_same_sdk_security_hooks_and_singular_v1_paths_without_effect_intents(): void
    {
        $client = $this->client([$this->response(ConversationMother::listing()), $this->response(ConversationMother::thread())]);
        $list = $client->withHeaders('native-profile', '2026-10-10')->listConversations(new ListConversationsRequest);
        $thread = $client->getConversation(new GetConversationRequest(ConversationMother::CONVERSATION));
        self::assertSame(ConversationMother::CONVERSATION, $list->body->data->items[0]->id);
        self::assertSame('unconfirmed', $thread->body->data->messages[0]->deliveryStatus);
        self::assertNull($list->idempotencyKey);
        self::assertSame(['listCrmConversations', 'getCrmConversation'], $this->operations->getArrayCopy());
        self::assertSame('/v1/crm/conversation', $this->request(0)->getUri()->getPath());
        self::assertSame('/v1/crm/conversation/'.ConversationMother::CONVERSATION, $this->request(1)->getUri()->getPath());
        self::assertSame('native-profile', $this->request(0)->getHeaderLine('X-Active-Profile'));
        self::assertSame('2026-10-10', $this->request(0)->getHeaderLine('Factuarea-Version'));
        self::assertFalse($this->request(1)->hasHeader('X-Active-Profile'));
        foreach ($this->history as $record) {
            self::assertSame('GET', $record['request']->getMethod());
            self::assertSame('Bearer conversation_read_fixture', $record['request']->getHeaderLine('Authorization'));
            self::assertSame('', (string) $record['request']->getBody());
            self::assertSame('', $record['request']->getUri()->getQuery());
            self::assertFalse($record['request']->hasHeader('Idempotency-Key'));
        }
    }

    public function test_list_iteration_preserves_exact_raw_filters_and_opaque_cursors_without_mutating_the_initial_request(): void
    {
        $cursor = 'native:/Opaque+%== é';
        $wire = " { \"state\" : \"active\", \"search\" : \"A&B\" } \n";
        $client = $this->client([$this->response(ConversationMother::listing($cursor, limit: 1)),
            $this->response(ConversationMother::listing(null, [ConversationMother::OTHER_CONVERSATION], 1))]);
        $request = new ListConversationsRequest(filters: $wire, cursor: 'initial::cursor', limit: 1);
        $items = iterator_to_array($client->listConversationItems($request), false);
        self::assertSame([ConversationMother::CONVERSATION, ConversationMother::OTHER_CONVERSATION], array_map(static fn ($item): string => $item->id, $items));
        self::assertSame('initial::cursor', $request->cursor);
        foreach ([0 => 'initial::cursor', 1 => $cursor] as $index => $nativeCursor) {
            parse_str($this->request($index)->getUri()->getQuery(), $query);
            self::assertSame(['filters' => $wire, 'cursor' => $nativeCursor, 'limit' => '1'], $query);
        }
        self::assertCount(2, $this->history);
    }

    public function test_thread_pages_keep_each_pages_attachments_and_native_delivery_facts(): void
    {
        $client = $this->client([$this->response(ConversationMother::thread('message:cursor', limit: 1)),
            $this->response(ConversationMother::thread(null, ConversationMother::OTHER_MESSAGE, ConversationMother::OTHER_ATTACHMENT, 1))]);
        $pages = iterator_to_array($client->getConversationPages(new GetConversationRequest(ConversationMother::CONVERSATION, limit: 1)), false);
        self::assertCount(2, $pages);
        self::assertSame(ConversationMother::MESSAGE, $pages[0]->data->attachments[0]->messageId);
        self::assertSame(ConversationMother::OTHER_MESSAGE, $pages[1]->data->attachments[0]->messageId);
        self::assertSame('unconfirmed', $pages[1]->data->messages[0]->deliveryStatus);
        parse_str($this->request(1)->getUri()->getQuery(), $query);
        self::assertSame(['cursor' => 'message:cursor', 'limit' => '1'], $query);
        self::assertArrayNotHasKey('filters', $query);
    }

    public function test_message_iteration_is_lazy_and_preserves_the_callers_start_cursor(): void
    {
        $client = $this->client([$this->response(ConversationMother::thread('native-next')),
            $this->response(ConversationMother::thread(null, ConversationMother::OTHER_MESSAGE, ConversationMother::OTHER_ATTACHMENT))]);
        $generator = $client->getConversationMessages(new GetConversationRequest(ConversationMother::CONVERSATION, cursor: 'native-start'));
        self::assertCount(0, $this->history);
        self::assertSame(ConversationMother::MESSAGE, $generator->current()->id);
        self::assertCount(1, $this->history);
        $generator->next();
        self::assertSame(ConversationMother::OTHER_MESSAGE, $generator->current()->id);
        self::assertCount(2, $this->history);
        parse_str($this->request(0)->getUri()->getQuery(), $query);
        self::assertSame(['cursor' => 'native-start'], $query);
    }

    /** @return iterable<string,array{string}> */
    public static function changedPages(): iterable
    {
        foreach (['authorization', 'mask', 'limit', 'duplicate_identity', 'repeated_cursor', 'initial_cursor'] as $case) {
            yield $case => [$case];
        }
    }

    #[DataProvider('changedPages')]
    public function test_pagination_rejects_changed_authority_duplicate_entities_or_cursor_cycles_before_an_extra_request(string $case): void
    {
        $first = ConversationMother::listing('next');
        $second = ConversationMother::listing(null, [ConversationMother::OTHER_CONVERSATION]);
        switch ($case) {
            case 'authorization': $second['meta']['authorization_version'] = 'current:C7:changed';
                break;
            case 'mask': $second['meta']['filterable_fields'] = [];
                break;
            case 'limit': $second['data']['limit'] = 5;
                break;
            case 'duplicate_identity': $second['data']['items'][0]['id'] = ConversationMother::CONVERSATION;
                break;
            case 'repeated_cursor': $second['data']['next_cursor'] = 'next';
                break;
            case 'initial_cursor': $first['data']['next_cursor'] = 'start';
                break;
        }
        $client = $this->client([$this->response($first), $this->response($second)]);
        try {
            iterator_to_array($client->listConversationItems(new ListConversationsRequest(cursor: 'start')), false);
            self::fail('A changed native pagination proof must fail.');
        } catch (\InvalidArgumentException|\UnexpectedValueException) {
            self::assertCount($case === 'initial_cursor' ? 1 : 2, $this->history);
        }
    }

    public function test_thread_iteration_cannot_present_a_previous_message_again_as_a_new_page(): void
    {
        $client = $this->client([$this->response(ConversationMother::thread('next')), $this->response(ConversationMother::thread())]);
        $this->expectException(\InvalidArgumentException::class);
        iterator_to_array($client->getConversationPages(new GetConversationRequest(ConversationMother::CONVERSATION)), false);
    }

    public function test_max_pages_stops_before_http_and_does_not_silently_return_a_complete_list(): void
    {
        $client = $this->client([$this->response(ConversationMother::listing('native_next'))]);
        try {
            iterator_to_array($client->listConversationItems(new ListConversationsRequest, 1), false);
            self::fail('A truncated list is not a complete traversal.');
        } catch (\OverflowException) {
            self::assertCount(1, $this->history);
        }
    }

    public function test_empty_factually_visible_list_is_not_an_error_or_an_invented_next_page(): void
    {
        $wire = ConversationMother::listing(null, []);
        $wire['data']['total'] = $wire['data']['active_count'] = $wire['data']['archived_count'] = 0;
        $client = $this->client([$this->response($wire)]);
        self::assertSame([], iterator_to_array($client->listConversationItems(new ListConversationsRequest), false));
        self::assertCount(1, $this->history);
    }

    public function test_foreign_thread_identity_does_not_replace_the_requested_target(): void
    {
        $wire = ConversationMother::thread();
        $wire['data']['conversation']['id'] = ConversationMother::OTHER_CONVERSATION;
        $wire['data']['messages'][0]['conversation_id'] = ConversationMother::OTHER_CONVERSATION;
        $wire['data']['attachments'][0]['conversation_id'] = ConversationMother::OTHER_CONVERSATION;
        $client = $this->client([$this->response($wire)]);
        $this->expectException(\UnexpectedValueException::class);
        $client->getConversation(new GetConversationRequest(ConversationMother::CONVERSATION));
    }

    public function test_a_known_uuid_case_variant_matches_the_native_canonical_target_without_minting_an_identity(): void
    {
        $client = $this->client([$this->response(ConversationMother::thread())]);
        $result = $client->getConversation(new GetConversationRequest(strtoupper(ConversationMother::CONVERSATION)));
        self::assertSame(ConversationMother::CONVERSATION, $result->body->data->conversation->id);
        self::assertSame('/v1/crm/conversation/'.strtoupper(ConversationMother::CONVERSATION), $this->request(0)->getUri()->getPath());
    }

    /** @return iterable<string,array{int,string,string}> */
    public static function nativeErrors(): iterable
    {
        yield 'invisible' => [404, 'resource_not_found', 'invalid_request_error'];
        yield 'revoked' => [403, 'crm_access_denied', 'authorization_error'];
        yield 'cursor_conflict' => [409, 'resource_conflict', 'conflict_error'];
        yield 'invalid_filters' => [422, 'parameter_invalid', 'invalid_request_error'];
        yield 'rate_limit' => [429, 'rate_limit_exceeded', 'rate_limit_error'];
        yield 'unavailable' => [503, 'service_unavailable', 'service_unavailable_error'];
    }

    #[DataProvider('nativeErrors')]
    public function test_native_failures_keep_status_original_error_headers_and_request_identity_without_a_recovery_write(int $status, string $code, string $type): void
    {
        $response = new Response($status, ['Content-Type' => 'application/json', 'Retry-After' => '9'], json_encode(['error' => [
            'code' => $code, 'type' => $type, 'message' => 'La lectura no está disponible.', 'param' => null,
            'subcode' => null, 'request_id' => 'req_conversation75',
        ]], JSON_THROW_ON_ERROR));
        $client = $this->client([$response]);
        try {
            $client->getConversation(new GetConversationRequest(ConversationMother::CONVERSATION));
            self::fail('Current native failure must be preserved.');
        } catch (CrmApiException $error) {
            self::assertSame($status, $error->statusCode);
            self::assertSame($code, $error->machineCode);
            self::assertSame('req_conversation75', $error->requestId);
            self::assertSame('9', $error->retryAfter);
            self::assertCount(1, $this->history);
            self::assertSame('GET', $this->request(0)->getMethod());
        }
    }

    public function test_different_sdk_instances_keep_their_own_configured_server_and_token(): void
    {
        $first = $this->client([$this->response(ConversationMother::listing())], 'first-token', 'https://first.fixture.invalid/v1');
        $firstHistory = $this->history;
        $second = $this->client([$this->response(ConversationMother::listing())], 'second-token', 'https://second.fixture.invalid/v1');
        $first->listConversations(new ListConversationsRequest);
        $second->listConversations(new ListConversationsRequest);
        $firstRecord = $firstHistory[0] ?? null;
        self::assertNotNull($firstRecord);
        self::assertSame('first.fixture.invalid', $firstRecord['request']->getUri()->getHost());
        self::assertSame('Bearer first-token', $firstRecord['request']->getHeaderLine('Authorization'));
        self::assertSame('second.fixture.invalid', $this->request(0)->getUri()->getHost());
        self::assertSame('Bearer second-token', $this->request(0)->getHeaderLine('Authorization'));
    }

    public function test_invalid_native_selections_and_header_injection_are_rejected_without_http(): void
    {
        $client = $this->client([]);
        foreach ([static fn () => new ListConversationsRequest(limit: 0), static fn () => new GetConversationRequest(ConversationMother::USER),
            static fn () => new ListConversationsRequest(cursor: ' '), static fn () => $client->withHeaders("profile\r\nAuthorization: other")] as $invalid) {
            try {
                $invalid();
                self::fail('An invalid selection/header cannot reach HTTP.');
            } catch (\InvalidArgumentException) {
                self::assertCount(0, $this->history);
            }
        }
    }
}
