<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\CustomerService;

use Brick\DateTime\LocalDate;
use Factuarea\Sdk\Custom\Crm\CustomerService\CustomerService;
use Factuarea\Sdk\Custom\Crm\CustomerService\Model;
use Factuarea\Sdk\Custom\Crm\CustomerService\Request as R;
use Factuarea\Sdk\Custom\Crm\CustomerService\TicketOperation;
use Factuarea\Sdk\Custom\Crm\CustomerService\TicketResponse;
use Factuarea\Sdk\Custom\Crm\CustomerService\TicketTransport;
use Factuarea\Sdk\Custom\Crm\CustomerService\TicketWriteIntent;
use Factuarea\Sdk\Custom\Crm\CustomerService\TicketWriteUnconfirmed;
use Factuarea\Sdk\Custom\Version\FactuareaVersionHook;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Hooks;
use Factuarea\Sdk\Models\Components\Security;
use Factuarea\Sdk\Models\Errors;
use Factuarea\Sdk\Utils\Retry\RetryConfigBackoff;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\Utils;
use InvalidArgumentException;
use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/** Current native v1 wire only: no network/DB, activation, published-schema or producer certification. */
final class CustomerServiceContractTest extends TestCase
{
    private const TICKET = '01900000-0000-7000-8000-000000000001';
    private const PIPELINE = '01900000-0000-7000-8000-000000000002';
    private const STAGE = '01900000-0000-7000-8000-000000000003';
    private const DOCUMENT = '01900000-0000-7000-8000-000000000004';
    private const ATTACHMENT = '01900000-0000-7000-8000-000000000005';
    private const ACTOR = '550E8400-E29B-41D4-A716-446655440000';
    private const KEY = 'ticket-original-intent';
    /** @var list<array{request:RequestInterface,options:array<string,mixed>}> */
    private array $history = [];
    /** @param list<Response|ConnectException> $responses */
    private function sdk(array $responses, string $secret = 'fact_test_customer_service'): Factuarea
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));
        $sdk = Factuarea::builder()->setServerUrl('https://sdk.invalid/v1')->setClient(new Client(['handler' => $stack]))
            ->setSecurity(new Security(bearerAuth: $secret))->setRetryConfig(new RetryConfigBackoff(initialIntervalMs: 1, maxIntervalMs: 1, exponent: 1, maxElapsedTimeMs: 1000, retryConnectionErrors: true))->build();
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new FactuareaVersionHook);

        return $sdk;
    }
    /** @return array<string,array{string,string,array<string,int|string>}> */
    public static function writes(): array
    {
        return [
            'CRM168 stage' => ['stage', '/move-to-stage', ['expected_version' => 6, 'pipeline_id' => self::PIPELINE, 'stage_id' => self::STAGE]],
            'attach pinned document' => ['attach', '/attachments', ['expected_version' => 6, 'document_id' => self::DOCUMENT, 'document_version' => 4]],
            'detach owner attachment' => ['detach', '/attachments/remove', ['expected_version' => 6, 'attachment_id' => self::ATTACHMENT]],
        ];
    }
    /** @param array<string,int|string> $body */
    #[DataProvider('writes')]
    public function test_native_writes_use_exact_json_routes_fields_cas_key_and_server_receipt(string $operation, string $suffix, array $body): void
    {
        $client = CustomerService::fromSdk($this->sdk([$this->json(['id' => strtoupper(self::TICKET), 'version' => 7])]));
        $result = $this->write($client, $operation);
        self::assertCount(1, $this->history);
        $request = $this->history[0]['request'];
        self::assertSame('POST', $request->getMethod());
        self::assertSame('/v1/crm/service-tickets/'.self::TICKET.$suffix, $request->getUri()->getPath());
        self::assertSame('', $request->getUri()->getQuery());
        self::assertSame($body, json_decode((string) $request->getBody(), true, flags: JSON_THROW_ON_ERROR));
        self::assertSame(self::KEY, $request->getHeaderLine('Idempotency-Key'));
        self::assertSame('Bearer fact_test_customer_service', $request->getHeaderLine('Authorization'));
        self::assertSame('application/json', $request->getHeaderLine('Content-Type'));
        self::assertSame('2026-10-01', $request->getHeaderLine('Factuarea-Version'));
        self::assertFalse($this->history[0]['options']['allow_redirects']);
        self::assertSame(strtoupper(self::TICKET), $result->data->id);
        self::assertSame(7, $result->data->version);
    }
    public function test_move_to_current_stage_retains_the_original_noop_version(): void
    {
        $client = CustomerService::fromSdk($this->sdk([$this->json(['id' => self::TICKET, 'version' => 6])]));
        self::assertSame(6, $this->write($client, 'stage')->data->version);
        self::assertCount(1, $this->history);
    }
    /** @return array<string,array{string,int,string,?string}> */
    public static function uncertainties(): array
    {
        $cases = [];
        foreach (['stage', 'attach', 'detach'] as $operation) {
            foreach ([500 => ['internal_error', null], 503 => ['crm_operation_unavailable', 'service_ticket_document_unavailable'],
                403 => ['crm_context_revoked', 'revoked'], 404 => ['resource_not_found', null],
                409 => ['crm_effect_unconfirmed', 'service_ticket_result_unconfirmed'], 302 => ['redirect', null], 201 => ['unexpected_success', null]] as $status => [$code, $subcode]) {
                $cases[$operation.' '.$status] = [$operation, $status, $code, $subcode];
            }
        }

        return $cases;
    }
    #[DataProvider('uncertainties')]
    public function test_uncertain_statuses_require_explicit_same_operation_body_key_cas_recovery(string $operation, int $status, string $code, ?string $subcode): void
    {
        $client = CustomerService::fromSdk($this->sdk([$this->error($status, $code, $subcode), $this->json(['id' => self::TICKET, 'version' => 7], ['Idempotent-Replayed' => 'true'])]));
        try {
            $this->write($client, $operation);
            self::fail('The original result is still unconfirmed.');
        } catch (TicketWriteUnconfirmed $error) {
            self::assertCount(1, $this->history);
            self::assertSame(self::KEY, $error->intent->idempotencyKey);
            self::assertSame(6, $error->intent->expectedVersion);
            self::assertTrue($error->intent->headersFrozen);
            $result = $client->recover($error->intent);
        }
        self::assertCount(2, $this->history);
        self::assertSame((string) $this->history[0]['request']->getBody(), (string) $this->history[1]['request']->getBody());
        self::assertSame((string) $this->history[0]['request']->getUri(), (string) $this->history[1]['request']->getUri());
        self::assertSame(self::KEY, $this->history[1]['request']->getHeaderLine('Idempotency-Key'));
        self::assertSame(7, $result->data->version);
        self::assertSame('true', $result->headers()['Idempotent-Replayed'][0]);
    }
    public function test_a_newer_head_read_never_replaces_original_cas_or_receipt_during_recovery(): void
    {
        $client = CustomerService::fromSdk($this->sdk([$this->error(500, 'internal_error'), $this->json(['id' => self::TICKET, 'version' => 12]), $this->json(['id' => self::TICKET, 'version' => 7])]));
        try {
            $this->write($client, 'attach');
            self::fail('A timeout is not confirmed.');
        } catch (TicketWriteUnconfirmed $error) {
            self::assertSame(12, $client->getServiceTicket(self::TICKET)->data->version);
            self::assertSame(7, $client->recover($error->intent)->data->version);
        }
        self::assertSame((string) $this->history[0]['request']->getBody(), (string) $this->history[2]['request']->getBody());
    }
    public function test_timeout_keeps_original_intent_without_retry_and_can_be_recovered_once(): void
    {
        $client = CustomerService::fromSdk($this->sdk([new ConnectException('Ambiguous timeout', new Request('POST', 'https://sdk.invalid/v1')), $this->json(['id' => self::TICKET, 'version' => 7])]));
        try {
            $this->write($client, 'detach');
            self::fail('Timeout must retain the original intent.');
        } catch (TicketWriteUnconfirmed $error) {
            self::assertInstanceOf(ConnectException::class, $error->cause);
            self::assertCount(1, $this->history);
            self::assertSame(7, $client->recover($error->intent)->data->version);
        }
        self::assertCount(2, $this->history);
    }
    /** @return array<string,array{int,string,?string}> */
    public static function nativeRejections(): array
    {
        return ['CAS' => [409, 'crm_version_conflict', null], 'key reuse' => [409, 'idempotency_key_reused', null],
            'validation' => [422, 'business_rule_violation', null], 'quota' => [429, 'rate_limit_exceeded', null]];
    }
    #[DataProvider('nativeRejections')]
    public function test_native_rejections_keep_typed_request_id_and_do_not_retry(int $status, string $code, ?string $subcode): void
    {
        $client = CustomerService::fromSdk($this->sdk([$this->error($status, $code, $subcode)]));
        try {
            $this->write($client, 'attach');
            self::fail('The native rejection must remain typed.');
        } catch (Errors\ErrorThrowable $error) {
            self::assertSame($code, $error->container->error->code);
            self::assertSame('req_ticket', $error->container->error->requestId);
            self::assertSame($status, $error->container->rawResponse?->getStatusCode());
        }
        self::assertCount(1, $this->history);
    }
    /** @return array<string,array{string,mixed}> */
    public static function invalidReceipts(): array
    {
        return ['foreign ticket' => ['id', self::STAGE], 'numeric identity' => ['id', 1], 'UUID4 ticket' => ['id', self::ACTOR],
            'string CAS' => ['version', '7'], 'float CAS' => ['version', 7.0], 'newer head' => ['version', 8], 'unchanged attachment' => ['version', 6],
            'private effect' => ['effect_id', self::PIPELINE], 'invented attachment' => ['attachment_id', self::ATTACHMENT], 'queued' => ['delivery_state', 'queued']];
    }
    #[DataProvider('invalidReceipts')]
    public function test_partial_foreign_or_unbound_receipts_are_unconfirmed(string $field, mixed $value): void
    {
        $receipt = ['id' => self::TICKET, 'version' => 7];
        $receipt[$field] = $value;
        $client = CustomerService::fromSdk($this->sdk([$this->json($receipt)]));
        $this->expectException(TicketWriteUnconfirmed::class);
        $this->write($client, 'attach');
    }
    public function test_foreign_read_and_timeline_snapshot_are_not_synthesized_from_intent(): void
    {
        foreach ([['id' => self::DOCUMENT, 'version' => 7], ['id' => self::TICKET, 'version' => 7, 'attachments' => [['id' => self::ATTACHMENT, 'document_id' => self::DOCUMENT, 'document_version' => 4, 'url' => 'https://private.invalid']]]] as $wire) {
            $client = CustomerService::fromSdk($this->sdk([$this->json($wire)]));
            try {
                $client->getServiceTicket(self::TICKET);
                self::fail('The native read is invalid.');
            } catch (Errors\APIException $error) {
                self::assertSame(200, $error->statusCode);
            }
        }
    }
    public function test_get_preserves_omitted_null_masks_actual_attachment_id_version_and_native_actor_uuid(): void
    {
        $wire = ['id' => self::TICKET, 'version' => 7, 'pipeline_id' => null, 'description' => '',
            'writable_fields' => ['title'], 'authorization_version' => 'auth-current',
            'comments' => [['id' => self::PIPELINE, 'visibility' => 'internal', 'author_id' => self::ACTOR, 'edited_at' => null]],
            'attachments' => [['id' => self::ATTACHMENT, 'document_id' => self::DOCUMENT, 'document_version' => 4, 'created_by' => self::ACTOR]]];
        $client = CustomerService::fromSdk($this->sdk([$this->json($wire), $this->json(['id' => self::TICKET, 'version' => 8])]));
        $ticket = $client->getServiceTicket(strtoupper(self::TICKET))->data;
        self::assertTrue($ticket->hasField('pipeline_id'));
        self::assertNull($ticket->fields['pipeline_id']);
        self::assertFalse($ticket->hasField('stage_id'));
        self::assertSame(['title'], $ticket->fields['writable_fields']);
        $attachments = $ticket->attachments();
        self::assertNotNull($attachments);
        self::assertSame(self::ATTACHMENT, $attachments[0]->id);
        self::assertSame(self::DOCUMENT, $attachments[0]->documentId);
        self::assertSame(4, $attachments[0]->documentVersion);
        self::assertArrayNotHasKey('mime', $attachments[0]->fields);
        self::assertSame(self::ACTOR, $attachments[0]->fields['created_by']);
        self::assertNull($client->getServiceTicket(self::TICKET)->data->attachments());
        self::assertSame('', (string) $this->history[0]['request']->getBody());
    }
    public function test_real_list_query_and_offset_iteration_retain_native_filters_and_counts(): void
    {
        $client = CustomerService::fromSdk($this->sdk([$this->json(['items' => [['id' => self::TICKET, 'version' => 1]], 'total' => 2, 'page' => 1, 'per_page' => 1]),
            $this->json(['items' => [['id' => self::DOCUMENT, 'version' => 9]], 'total' => 2, 'page' => 2, 'per_page' => 1])]));
        $items = iterator_to_array($client->iterateServiceTickets(new R\TicketFilters(1, 1, 'new', 'id', 'asc')));
        self::assertCount(2, $items);
        self::assertSame(self::DOCUMENT, $items[1]->id);
        foreach ($this->history as $index => $request) {
            parse_str($request['request']->getUri()->getQuery(), $query);
            self::assertSame(['page' => (string) ($index + 1), 'per_page' => '1', 'status' => 'new', 'sort' => 'id', 'direction' => 'asc'], $query);
            self::assertSame('GET', $request['request']->getMethod());
            self::assertSame('/v1/crm/service-tickets', $request['request']->getUri()->getPath());
        }
    }
    public function test_iteration_is_bounded_without_extra_read(): void
    {
        $client = CustomerService::fromSdk($this->sdk([$this->json(['items' => [['id' => self::TICKET, 'version' => 1]], 'total' => 2, 'page' => 1, 'per_page' => 1])]));
        try {
            iterator_to_array($client->iterateServiceTickets(new R\TicketFilters(1, 1), maxPages: 1));
            self::fail('Iteration must respect its explicit bound.');
        } catch (LogicException) {
            self::assertCount(1, $this->history);
        }
    }
    public function test_real_timeline_keeps_original_snapshots_and_omitted_history_fields(): void
    {
        $client = CustomerService::fromSdk($this->sdk([$this->json(['id' => self::TICKET, 'items' => [['version' => 2], ['version' => 3, 'action' => 'document_attached', 'actor_id' => self::ACTOR,
            'snapshot' => ['id' => self::TICKET, 'version' => 3, 'attachments' => [['id' => self::ATTACHMENT, 'document_id' => self::DOCUMENT, 'document_version' => 4]]]]], 'total' => 3, 'page' => 2, 'per_page' => 2])]));
        $timeline = $client->getServiceTicketTimeline(self::TICKET, new R\PageRequest(2, 2))->data;
        self::assertSame([], $timeline->items[0]->fields);
        $snapshot = $timeline->items[1]->fields['snapshot'];
        self::assertInstanceOf(Model\Ticket::class, $snapshot);
        self::assertSame(3, $snapshot->version);
        self::assertSame('/v1/crm/service-tickets/'.self::TICKET.'/timeline', $this->history[0]['request']->getUri()->getPath());
        self::assertSame('page=2&per_page=2', $this->history[0]['request']->getUri()->getQuery());
    }
    /** @return array<string,array{string}> */
    public static function invalidReads(): array
    {
        return ['foreign snapshot' => ['foreign_snapshot'], 'wrong snapshot CAS' => ['snapshot_version'], 'wrong timeline parent' => ['foreign_timeline'],
            'wrong query page' => ['query_page'], 'string count' => ['string_total'], 'masked authority without version' => ['authority'], 'invalid actual instant' => ['time']];
    }
    #[DataProvider('invalidReads')]
    public function test_malformed_native_reads_do_not_default_fields_or_infer_authority(string $case): void
    {
        $wire = ['id' => self::TICKET, 'items' => [['version' => 3, 'snapshot' => ['id' => self::TICKET, 'version' => 3]]], 'total' => 1, 'page' => 1, 'per_page' => 50];
        match ($case) {
            'foreign_snapshot' => $wire['items'][0]['snapshot']['id'] = self::STAGE,
            'snapshot_version' => $wire['items'][0]['snapshot']['version'] = 4,
            'foreign_timeline' => $wire['id'] = self::STAGE,
            'query_page' => $wire['page'] = 2,
            'string_total' => $wire['total'] = '1',
            'authority' => $wire = ['id' => self::TICKET, 'version' => 3, 'writable_fields' => ['attachments']],
            'time' => $wire = ['id' => self::TICKET, 'version' => 3, 'created_at' => '2026-02-30T00:00:00Z'],
            default => throw new LogicException('Unknown malformed read.')
        };
        $client = CustomerService::fromSdk($this->sdk([$this->json($wire)]));
        $this->expectException(Errors\APIException::class);
        if (in_array($case, ['authority', 'time'], true)) {
            $client->getServiceTicket(self::TICKET);
        } else {
            $client->getServiceTicketTimeline(self::TICKET);
        }
    }
    /** @return array<string,array{string}> */
    public static function invalidRequests(): array
    {
        return ['CAS zero' => ['cas'], 'document CAS zero' => ['document_cas'], 'foreign numeric ID' => ['identity'], 'UUID4 document' => ['document_id'],
            'unsupported status' => ['status'], 'hidden sort' => ['sort'], 'page range' => ['page'], 'page size' => ['size'], 'empty key' => ['key'],
            'body operation mismatch' => ['mismatch'], 'fresh recovery' => ['fresh']];
    }
    #[DataProvider('invalidRequests')]
    public function test_invalid_closed_requests_fail_before_network(string $case): void
    {
        $sdk = $this->sdk([]);
        $client = CustomerService::fromSdk($sdk);
        try {
            match ($case) {
                'cas' => new R\MoveToStageRequest(0, self::PIPELINE, self::STAGE),
                'document_cas' => new R\AttachDocumentRequest(6, self::DOCUMENT, 0),
                'identity' => $client->getServiceTicket('1'),
                'document_id' => new R\AttachDocumentRequest(6, self::ACTOR, 4),
                'status' => new R\TicketFilters(status: 'resolved'),
                'sort' => new R\TicketFilters(sort: 'attachments'),
                'page' => new R\PageRequest(1000001),
                'size' => new R\PageRequest(perPage: 101),
                'key' => $client->detachServiceTicketDocument(self::TICKET, new R\DetachDocumentRequest(6, self::ATTACHMENT), ''),
                'mismatch' => TicketWriteIntent::create(TicketOperation::MoveToStage, self::TICKET, new R\AttachDocumentRequest(6, self::DOCUMENT, 4), self::KEY, (new TicketTransport($sdk->sdkConfiguration))->contextFingerprint()),
                'fresh' => $client->recover(TicketWriteIntent::create(TicketOperation::DetachDocument, self::TICKET, new R\DetachDocumentRequest(6, self::ATTACHMENT), self::KEY, (new TicketTransport($sdk->sdkConfiguration))->contextFingerprint())),
                default => throw new LogicException('Unknown invalid request.')
            };
            self::fail('This is not a valid native request.');
        } catch (InvalidArgumentException) {
            self::assertCount(0, $this->history);
        }
    }
    /** @return array<string,array{string}> */
    public static function contextChanges(): array
    {
        return ['server' => ['server'], 'credential' => ['credential'], 'profile default' => ['profile']];
    }
    #[DataProvider('contextChanges')]
    public function test_recovery_rejects_changed_server_credentials_or_default_profile(string $change): void
    {
        $sdk = $this->sdk([$this->error(500, 'internal_error')]);
        $client = CustomerService::fromSdk($sdk);
        try {
            $this->write($client, 'stage');
            self::fail('The original result is unconfirmed.');
        } catch (TicketWriteUnconfirmed $error) {
            if ($change === 'server') {
                $sdk->sdkConfiguration->serverUrl = 'https://another.invalid/v1';
            } elseif ($change === 'credential') {
                $sdk->sdkConfiguration->securitySource = function (): Security {
                    return new Security(bearerAuth: 'fact_test_other');
                };
            } else {
                $sdk->sdkConfiguration->client = new Client(['headers' => ['X-Active-Profile' => self::PIPELINE]]);
            }
            try {
                $client->recover($error->intent);
                self::fail('Recovery cannot cross owner credentials or server.');
            } catch (InvalidArgumentException) {
                self::assertCount(1, $this->history);
            }
        }
    }
    public function test_hook_cannot_change_sent_path_body_key_or_frozen_version_profile(): void
    {
        $sdk = $this->sdk([$this->error(500, 'internal_error'), $this->json(['id' => self::TICKET, 'version' => 7])]);
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new class implements Hooks\BeforeRequestHook
        {
            private int $calls = 0;
            public function beforeRequest(Hooks\BeforeRequestContext $context, RequestInterface $request): RequestInterface
            {
                $this->calls++;

                return $request->withMethod('DELETE')->withUri(new Uri('https://foreign.invalid'))->withBody(Utils::streamFor('{"expected_version":999}'))
                    ->withHeader('Idempotency-Key', 'new-key')->withHeader('Factuarea-Version', $this->calls === 1 ? '2026-10-01' : '2026-10-09')
                    ->withHeader('X-Active-Profile', $this->calls === 1 ? '01900000-0000-7000-8000-000000000002' : '01900000-0000-7000-8000-000000000003');
            }
        });
        $client = CustomerService::fromSdk($sdk);
        try {
            $client->attachServiceTicketDocument(self::TICKET, new R\AttachDocumentRequest(6, self::DOCUMENT, 4), self::KEY, LocalDate::parse('2026-10-01'), self::PIPELINE);
            self::fail('The effect is unconfirmed.');
        } catch (TicketWriteUnconfirmed $error) {
            self::assertSame(7, $client->recover($error->intent)->data->version);
        }
        foreach ($this->history as $entry) {
            self::assertSame('POST', $entry['request']->getMethod());
            self::assertSame('/v1/crm/service-tickets/'.self::TICKET.'/attachments', $entry['request']->getUri()->getPath());
            self::assertSame('sdk.invalid', $entry['request']->getUri()->getHost());
            self::assertSame(self::KEY, $entry['request']->getHeaderLine('Idempotency-Key'));
            self::assertSame('2026-10-01', $entry['request']->getHeaderLine('Factuarea-Version'));
            self::assertSame(self::PIPELINE, $entry['request']->getHeaderLine('X-Active-Profile'));
        }
        self::assertSame((string) $this->history[0]['request']->getBody(), (string) $this->history[1]['request']->getBody());
    }
    public function test_request_auth_hook_drift_is_rejected_before_recovery_send(): void
    {
        $sdk = $this->sdk([$this->error(500, 'internal_error')]);
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new class implements Hooks\BeforeRequestHook
        {
            private int $calls = 0;
            public function beforeRequest(Hooks\BeforeRequestContext $context, RequestInterface $request): RequestInterface
            {
                return $request->withHeader('Authorization', 'Bearer hook-token-'.++$this->calls);
            }
        });
        $client = CustomerService::fromSdk($sdk);
        try {
            $this->write($client, 'detach');
            self::fail('The effect is unconfirmed.');
        } catch (TicketWriteUnconfirmed $error) {
            try {
                $client->recover($error->intent);
                self::fail('The original request credential changed.');
            } catch (InvalidArgumentException) {
                self::assertCount(1, $this->history);
            }
        }
    }
    public function test_after_hooks_cannot_synthesize_confirmation_or_replay_mutation(): void
    {
        $sdk = $this->sdk([$this->error(500, 'internal_error'), $this->json(['id' => self::TICKET, 'version' => 7])]);
        $hook = new class implements Hooks\AfterErrorHook, Hooks\AfterSuccessHook
        {
            public int $calls = 0;
            public function afterSuccess(Hooks\AfterSuccessContext $context, ResponseInterface $response): ResponseInterface
            {
                $this->calls++;
                throw new LogicException('This hook may not certify an owner receipt.');
            }
            public function afterError(Hooks\AfterErrorContext $context, ?ResponseInterface $response, \Throwable $exception): Hooks\ErrorResponseContext
            {
                $this->calls++;
                throw new LogicException('This hook may not replay an owner mutation.');
            }
        };
        $sdk->sdkConfiguration->hooks->registerAfterSuccessHook($hook);
        $sdk->sdkConfiguration->hooks->registerAfterErrorHook($hook);
        $client = CustomerService::fromSdk($sdk);
        try {
            $this->write($client, 'attach');
            self::fail('The effect is unconfirmed.');
        } catch (TicketWriteUnconfirmed $error) {
            self::assertSame(7, $client->recover($error->intent)->data->version);
        }
        self::assertSame(0, $hook->calls);
        self::assertCount(2, $this->history);
    }
    public function test_two_clients_keep_their_own_configured_credentials(): void
    {
        $clientA = CustomerService::fromSdk($this->sdk([$this->json(['id' => self::TICKET, 'version' => 7])], 'fact_test_a'));
        $this->write($clientA, 'stage');
        $authA = $this->history[0]['request']->getHeaderLine('Authorization');
        $clientB = CustomerService::fromSdk($this->sdk([$this->json(['id' => self::TICKET, 'version' => 7])], 'fact_test_b'));
        $this->write($clientB, 'stage');
        self::assertSame('Bearer fact_test_a', $authA);
        self::assertSame('Bearer fact_test_b', $this->history[0]['request']->getHeaderLine('Authorization'));
    }
    /** @return TicketResponse<Model\TicketReceipt> */
    private function write(CustomerService $client, string $operation): TicketResponse
    {
        return match ($operation) {
            'stage' => $client->moveServiceTicketToStage(strtoupper(self::TICKET), new R\MoveToStageRequest(6, strtoupper(self::PIPELINE), strtoupper(self::STAGE)), self::KEY),
            'attach' => $client->attachServiceTicketDocument(strtoupper(self::TICKET), new R\AttachDocumentRequest(6, strtoupper(self::DOCUMENT), 4), self::KEY),
            'detach' => $client->detachServiceTicketDocument(strtoupper(self::TICKET), new R\DetachDocumentRequest(6, strtoupper(self::ATTACHMENT)), self::KEY),
            default => throw new LogicException('Unknown native operation.'),
        };
    }
    /** @param array<string,mixed> $data
     * @param  array<string,string>  $headers
     */
    private function json(array $data, array $headers = []): Response
    {
        return new Response(200, ['Content-Type' => 'application/json', ...$headers], json_encode(['data' => $data], JSON_THROW_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION));
    }
    private function error(int $status, string $code, ?string $subcode = null): Response
    {
        return new Response($status, ['Content-Type' => 'application/json'], json_encode(['error' => ['type' => $status >= 500 ? 'api_error' : 'invalid_request_error',
            'code' => $code, 'subcode' => $subcode, 'message' => 'Native rejection.', 'request_id' => 'req_ticket']], JSON_THROW_ON_ERROR));
    }
}
