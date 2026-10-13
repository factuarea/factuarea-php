<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\CustomerService;

use Factuarea\Sdk\Custom\Crm\CustomerService\CustomerService;
use Factuarea\Sdk\Custom\Crm\CustomerService\Model\Ticket;
use Factuarea\Sdk\Custom\Crm\CustomerService\Request\MoveToStageRequest;
use Factuarea\Sdk\Custom\Crm\CustomerService\TicketWriteUnconfirmed;
use Factuarea\Sdk\Custom\Crm\Documents\Omitted;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Models\Components\Security;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/** New causal wire delta only; Guzzle fixtures use no network or DB and grant no producer availability. */
final class CustomerServiceWaitingContractTest extends TestCase
{
    private const TICKET = '01900000-0000-7000-8000-000000000001';
    private const PIPELINE = '01900000-0000-7000-8000-000000000002';
    private const STAGE = '01900000-0000-7000-8000-000000000003';
    private const MESSAGE = '019A0000-ABCD-7000-8000-000000000004';
    private const KEY = 'original-customer-wait-release';
    /** @var list<array{request:RequestInterface,options:array<string,mixed>}> */
    private array $history = [];

    /** @return iterable<string,array{string|null|Omitted,string|null|Omitted,array<string,string|null>}> */
    public static function waitingBodies(): iterable
    {
        yield 'original constructor preserves omission' => [Omitted::Value, Omitted::Value, []];
        yield 'open waiting' => ['waiting_customer', Omitted::Value, ['waiting_reason' => 'waiting_customer']];
        yield 'manual release' => [null, Omitted::Value, ['waiting_reason' => null]];
        yield 'original message releases waiting' => [null, self::MESSAGE, ['waiting_reason' => null, 'customer_message_id' => strtolower(self::MESSAGE)]];
        yield 'null message does not create a release' => [Omitted::Value, null, ['customer_message_id' => null]];
        yield 'open waiting with null message' => ['waiting_customer', null, ['waiting_reason' => 'waiting_customer', 'customer_message_id' => null]];
        yield 'manual release with null message' => [null, null, ['waiting_reason' => null, 'customer_message_id' => null]];
    }

    /** @param array<string,string|null> $optional */
    #[DataProvider('waitingBodies')]
    public function test_waiting_fields_preserve_omission_null_and_original_message_wire(string|null|Omitted $reason, string|null|Omitted $message, array $optional): void
    {
        $client = $this->client([$this->json(['id' => self::TICKET, 'version' => 7])]);
        $body = $reason === Omitted::Value && $message === Omitted::Value
            ? new MoveToStageRequest(6, self::PIPELINE, self::STAGE)
            : new MoveToStageRequest(6, self::PIPELINE, self::STAGE, $reason, $message);
        $result = $client->moveServiceTicketToStage(self::TICKET, $body, self::KEY);
        self::assertCount(1, $this->history);
        $request = $this->history[0]['request'];
        self::assertSame(['expected_version' => 6, 'pipeline_id' => self::PIPELINE, 'stage_id' => self::STAGE, ...$optional],
            json_decode((string) $request->getBody(), true, flags: JSON_THROW_ON_ERROR));
        self::assertSame('POST', $request->getMethod());
        self::assertSame('/v1/crm/service-tickets/'.self::TICKET.'/move-to-stage', $request->getUri()->getPath());
        self::assertSame(self::KEY, $request->getHeaderLine('Idempotency-Key'));
        self::assertSame('Bearer fact_test_waiting_delta', $request->getHeaderLine('Authorization'));
        self::assertSame('application/json', $request->getHeaderLine('Content-Type'));
        self::assertSame(7, $result->data->version);
    }

    /** @return iterable<string,array{mixed,mixed}> */
    public static function invalidWaitingBodies(): iterable
    {
        yield 'message without explicit release' => [Omitted::Value, self::MESSAGE];
        yield 'message while opening waiting' => ['waiting_customer', self::MESSAGE];
        yield 'invented cause' => ['waiting_agent', Omitted::Value];
        yield 'case is not a native cause' => ['WAITING_CUSTOMER', Omitted::Value];
        yield 'boolean cause' => [false, Omitted::Value];
        yield 'historical actor UUID is not a customer message' => [null, '550e8400-e29b-41d4-a716-446655440000'];
        yield 'integer identity' => [null, '42'];
        yield 'empty identity' => [null, ''];
        yield 'boolean message' => [null, false];
    }

    #[DataProvider('invalidWaitingBodies')]
    public function test_invalid_waiting_or_message_combination_fails_before_network(mixed $reason, mixed $message): void
    {
        $client = $this->client([]);
        try {
            $client->moveServiceTicketToStage(self::TICKET, new MoveToStageRequest(6, self::PIPELINE, self::STAGE, $reason, $message), self::KEY);
            self::fail('The waiting request must retain its native cause and explicit causal release.');
        } catch (InvalidArgumentException|\TypeError) {
            self::assertCount(0, $this->history);
        }
    }

    /** @return iterable<string,array{int,string,?string}> */
    public static function uncertainWaitingWrites(): iterable
    {
        yield 'postcommit infrastructure' => [500, 'internal_error', null];
        yield 'postcommit authority revoked' => [403, 'crm_context_revoked', 'revoked'];
        yield 'postcommit hidden owner' => [404, 'resource_not_found', null];
        yield 'private result unconfirmed' => [409, 'crm_effect_unconfirmed', 'service_ticket_result_unconfirmed'];
    }

    #[DataProvider('uncertainWaitingWrites')]
    public function test_recovery_retains_original_message_release_cas_body_and_same_key(int $status, string $code, ?string $subcode): void
    {
        $client = $this->client([$this->error($status, $code, $subcode), $this->json(['id' => self::TICKET, 'version' => 7])]);
        try {
            $client->moveServiceTicketToStage(self::TICKET, $this->release(), self::KEY);
            self::fail('The original causal result is not confirmed.');
        } catch (TicketWriteUnconfirmed $failure) {
            self::assertCount(1, $this->history);
            self::assertSame(6, $failure->intent->expectedVersion);
            self::assertSame(self::KEY, $failure->intent->idempotencyKey);
            self::assertSame(['expected_version' => 6, 'pipeline_id' => self::PIPELINE, 'stage_id' => self::STAGE,
                'waiting_reason' => null, 'customer_message_id' => strtolower(self::MESSAGE)], json_decode($failure->intent->bodyJson, true, flags: JSON_THROW_ON_ERROR));
            self::assertSame(7, $client->recover($failure->intent)->data->version);
        }
        $this->assertOriginalRecovery(1);
    }

    public function test_newer_ticket_head_does_not_replace_original_message_release_or_receipt(): void
    {
        $client = $this->client([$this->error(500, 'internal_error'), $this->json(['id' => self::TICKET, 'version' => 18, 'status' => 'pending']),
            $this->json(['id' => self::TICKET, 'version' => 7])]);
        try {
            $client->moveServiceTicketToStage(self::TICKET, $this->release(), self::KEY);
            self::fail('The original causal result is not confirmed.');
        } catch (TicketWriteUnconfirmed $failure) {
            $head = $client->getServiceTicket(self::TICKET)->data;
            self::assertSame(18, $head->version);
            self::assertFalse($head->hasField('waiting_reason'));
            self::assertSame(7, $client->recover($failure->intent)->data->version);
        }
        $this->assertOriginalRecovery(2);
    }

    public function test_timeout_keeps_the_original_causal_message_without_automatic_retry(): void
    {
        $client = $this->client([new ConnectException('Connection interrupted.', new Request('POST', 'https://sdk.invalid/v1')),
            $this->json(['id' => self::TICKET, 'version' => 7])]);
        try {
            $client->moveServiceTicketToStage(self::TICKET, $this->release(), self::KEY);
            self::fail('A timeout cannot certify a causal release.');
        } catch (TicketWriteUnconfirmed $failure) {
            self::assertCount(1, $this->history);
            self::assertSame(7, $client->recover($failure->intent)->data->version);
        }
        $this->assertOriginalRecovery(1);
    }

    public function test_waiting_write_receipt_cannot_invent_a_public_causal_projection(): void
    {
        $client = $this->client([$this->json(['id' => self::TICKET, 'version' => 7, 'waiting_reason' => null]),
            $this->json(['id' => self::TICKET, 'version' => 7])]);
        try {
            $client->moveServiceTicketToStage(self::TICKET, $this->release(), self::KEY);
            self::fail('The current native receipt contains only its original ticket id/version.');
        } catch (TicketWriteUnconfirmed $failure) {
            self::assertSame(7, $client->recover($failure->intent)->data->version);
        }
        $this->assertOriginalRecovery(1);
    }

    /** @return iterable<string,array{array<string,string|null>}> */
    public static function optionalWaitingProjection(): iterable
    {
        yield 'current producer omits it' => [[]];
        yield 'explicit null if emitted' => [['waiting_reason' => null]];
        yield 'current enum if emitted' => [['waiting_reason' => 'waiting_customer']];
        yield 'future native enum if emitted' => [['waiting_reason' => 'future_native_cause']];
    }

    /** @param array<string,string|null> $optional */
    #[DataProvider('optionalWaitingProjection')]
    public function test_optional_waiting_projection_retains_presence_without_requiring_a_new_server_field(array $optional): void
    {
        $client = $this->client([$this->json(['id' => self::TICKET, 'version' => 6, ...$optional])]);
        $ticket = $client->getServiceTicket(self::TICKET)->data;
        self::assertSame(array_key_exists('waiting_reason', $optional), $ticket->hasField('waiting_reason'));
        self::assertSame($optional, $ticket->fields);
        self::assertFalse($ticket->hasField('comments'));
        self::assertFalse($ticket->hasField('writable_fields'));
    }

    public function test_list_and_timeline_preserve_optional_waiting_fields_of_their_exact_snapshots(): void
    {
        $client = $this->client([$this->json(['items' => [['id' => self::TICKET, 'version' => 6, 'waiting_reason' => null]], 'total' => 1, 'page' => 1, 'per_page' => 50]),
            $this->json(['id' => self::TICKET, 'items' => [['version' => 4, 'snapshot' => ['id' => self::TICKET, 'version' => 4, 'waiting_reason' => 'future_native_cause']]],
                'total' => 1, 'page' => 1, 'per_page' => 50])]);
        $ticket = $client->listServiceTickets()->data->items[0];
        self::assertTrue($ticket->hasField('waiting_reason'));
        self::assertNull($ticket->fields['waiting_reason']);
        $snapshot = $client->getServiceTicketTimeline(self::TICKET)->data->items[0]->fields['snapshot'];
        self::assertInstanceOf(Ticket::class, $snapshot);
        self::assertSame(4, $snapshot->version);
        self::assertSame('future_native_cause', $snapshot->fields['waiting_reason']);
    }

    private function release(): MoveToStageRequest
    {
        return new MoveToStageRequest(6, self::PIPELINE, self::STAGE, waitingReason: null, customerMessageId: self::MESSAGE);
    }

    private function assertOriginalRecovery(int $index): void
    {
        self::assertCount($index + 1, $this->history);
        $original = $this->history[0]['request'];
        $retry = $this->history[$index]['request'];
        self::assertSame((string) $original->getBody(), (string) $retry->getBody());
        self::assertSame((string) $original->getUri(), (string) $retry->getUri());
        self::assertSame(self::KEY, $retry->getHeaderLine('Idempotency-Key'));
        self::assertSame('POST', $retry->getMethod());
    }

    /** @param list<Response|ConnectException> $responses */
    private function client(array $responses): CustomerService
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));
        $sdk = Factuarea::builder()->setServerUrl('https://sdk.invalid/v1')->setClient(new Client(['handler' => $stack]))
            ->setSecurity(new Security(bearerAuth: 'fact_test_waiting_delta'))->build();

        return CustomerService::fromSdk($sdk);
    }

    /** @param array<string,mixed> $data */
    private function json(array $data): Response
    {
        return new Response(200, ['Content-Type' => 'application/json'], json_encode(['data' => $data], JSON_THROW_ON_ERROR));
    }

    private function error(int $status, string $code, ?string $subcode = null): Response
    {
        return new Response($status, ['Content-Type' => 'application/json'], json_encode(['error' => [
            'type' => $status >= 500 ? 'api_error' : 'invalid_request_error', 'code' => $code, 'subcode' => $subcode,
            'message' => 'The native owner rejected the original request.', 'request_id' => 'req_waiting_delta']], JSON_THROW_ON_ERROR));
    }
}
