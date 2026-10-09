<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\ServiceLevel;

use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Errors\ErrorThrowable;
use Factuarea\Sdk\Models\Errors\ServiceLevelWriteUnconfirmed;
use Factuarea\Sdk\Utils\Retry\RetryConfigBackoff;
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

/** Written contract cases; no network, product bootstrap or SQL. */
final class ServiceLevelContractTest extends TestCase
{
    private const TICKET = '01900000-0000-7000-8000-000000000001';
    private const CYCLE = '01900000-0000-7000-8000-000000000002';
    private const CALENDAR = '01900000-0000-7000-8000-000000000003';
    private const POLICY = '01900000-0000-7000-8000-000000000004';
    private const KEY = 'service-level-original-intent';

    /** @var list<array{request:RequestInterface}> */
    private array $history = [];

    /** @param list<Response|ConnectException> $responses */
    private function sdk(array $responses): Factuarea
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));

        return Factuarea::builder()->setServerUrl('https://sdk.invalid/v1')
            ->setClient(new Client(['handler' => $stack]))
            ->setSecurity(new Components\Security(bearerAuth: 'fact_test_contract_fixture'))
            ->setRetryConfig(new RetryConfigBackoff(initialIntervalMs: 1, maxIntervalMs: 1, exponent: 1, maxElapsedTimeMs: 1000, retryConnectionErrors: true))
            ->build();
    }

    private function calendar(?string $id = null, ?int $version = null): Components\UpdateServiceCalendarRequest
    {
        return new Components\UpdateServiceCalendarRequest($id, $version, 'Servicio', 'Europe/Madrid', 'business',
            [new Components\ServiceCalendarWindow(1, 540, 1020)], ['2026-12-25'],
            [new Components\ServiceCalendarException('2026-12-24', [new Components\ServiceCalendarDayWindow(540, 720)])]);
    }

    private function configure(?string $id = null, ?int $version = null): Components\ConfigureServiceSlaRequest
    {
        return new Components\ConfigureServiceSlaRequest($id, $version, null, 1, 30, null, 60, 10, 240, null, true, true, false, null, null);
    }

    /** @return array<string,array{string,string,string}> */
    public static function writes(): array
    {
        return [
            'configure' => ['configureServiceSla', 'PUT', '/v1/crm/service-tickets/'.self::TICKET.'/sla'],
            'pause' => ['pauseServiceSla', 'POST', '/v1/crm/service-tickets/'.self::TICKET.'/sla/pause'],
            'resume' => ['resumeServiceSla', 'POST', '/v1/crm/service-tickets/'.self::TICKET.'/sla/resume'],
            'calendar' => ['updateServiceCalendar', 'PUT', '/v1/crm/service-calendars'],
        ];
    }

    #[DataProvider('writes')]
    public function test_all_writes_use_native_routes_and_original_keys_once(string $operation, string $method, string $path): void
    {
        $id = $operation === 'updateServiceCalendar' ? self::CALENDAR : self::CYCLE;
        $sdk = $this->sdk([$this->json(['data' => ['id' => $id, 'version' => 7]])]);
        $result = match ($operation) {
            'configureServiceSla' => $sdk->serviceLevel->configureServiceSla(self::TICKET, $this->configure(self::CYCLE, 6), self::KEY),
            'pauseServiceSla' => $sdk->serviceLevel->pauseServiceSla(self::TICKET, new Components\PauseServiceSlaRequest(self::CYCLE, 6, 'manual', true, false, false), self::KEY),
            'resumeServiceSla' => $sdk->serviceLevel->resumeServiceSla(self::TICKET, new Components\ResumeServiceSlaRequest(self::CYCLE, 6, 'manual'), self::KEY),
            'updateServiceCalendar' => $sdk->serviceLevel->updateServiceCalendar($this->calendar(self::CALENDAR, 6), self::KEY),
            default => throw new InvalidArgumentException('Unknown contract case.'),
        };
        self::assertCount(1, $this->history);
        $request = $this->history[0]['request'];
        self::assertSame($method, $request->getMethod());
        self::assertSame($path, $request->getUri()->getPath());
        self::assertSame(self::KEY, $request->getHeaderLine('Idempotency-Key'));
        self::assertSame('Bearer fact_test_contract_fixture', $request->getHeaderLine('Authorization'));
        self::assertSame($id, $result->object->data->id);
        self::assertSame(7, $result->object->data->version);
        $wire = json_decode((string) $request->getBody(), true, flags: JSON_THROW_ON_ERROR);
        self::assertSame(6, $wire['expected_version']);
        self::assertArrayNotHasKey('company_id', $wire);
        self::assertArrayNotHasKey('effect_id', $wire);
        self::assertArrayNotHasKey('confirmed', $wire);
    }

    public function test_create_keeps_explicit_null_server_reservations_and_integer_minutes(): void
    {
        $sdk = $this->sdk([$this->json(['data' => ['id' => self::CYCLE, 'version' => 1]])]);
        $sdk->serviceLevel->configureServiceSla(self::TICKET, $this->configure(), self::KEY);
        $wire = json_decode((string) $this->history[0]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);
        foreach (['cycle_id', 'expected_version', 'policy_id', 'calendar_id', 'calendar_version', 'first_response_risk_minutes', 'resolution_risk_minutes'] as $field) {
            self::assertArrayHasKey($field, $wire);
            self::assertNull($wire[$field]);
        }
        self::assertSame(30, $wire['first_response_minutes']);
        self::assertSame(15, count($wire));
    }

    public function test_calendar_nested_types_keep_wire_fields_and_null_creation(): void
    {
        $sdk = $this->sdk([$this->json(['data' => ['id' => self::CALENDAR, 'version' => 1]])]);
        $sdk->serviceLevel->updateServiceCalendar($this->calendar(), self::KEY);
        $wire = json_decode((string) $this->history[0]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);
        self::assertNull($wire['id']);
        self::assertNull($wire['expected_version']);
        self::assertSame(['weekday' => 1, 'start_minute' => 540, 'end_minute' => 1020], $wire['windows'][0]);
        self::assertSame(['date' => '2026-12-24', 'windows' => [['start_minute' => 540, 'end_minute' => 720]]], $wire['exceptions'][0]);
    }

    public function test_500_has_no_automatic_retry_and_explicit_recovery_preserves_original_body_and_version(): void
    {
        $sdk = $this->sdk([$this->error(500, 'internal_error'), $this->json(['data' => ['id' => self::CALENDAR, 'version' => 7]], ['Idempotent-Replayed' => 'true'])]);
        try {
            $sdk->serviceLevel->updateServiceCalendar($this->calendar(self::CALENDAR, 6), self::KEY);
            self::fail('A 500 must leave the write unconfirmed.');
        } catch (ServiceLevelWriteUnconfirmed $error) {
            self::assertCount(1, $this->history);
            self::assertInstanceOf(ErrorThrowable::class, $error->cause);
            $receipt = $sdk->serviceLevel->recover($error->intent);
        }
        self::assertCount(2, $this->history);
        self::assertSame((string) $this->history[0]['request']->getBody(), (string) $this->history[1]['request']->getBody());
        self::assertSame(self::KEY, $this->history[1]['request']->getHeaderLine('Idempotency-Key'));
        self::assertSame(7, $receipt->object->data->version);
        self::assertSame('true', $receipt->headers['Idempotent-Replayed'][0]);
    }

    public function test_timeout_exposes_original_intent_without_redispatch(): void
    {
        $sdk = $this->sdk([new ConnectException('Timed out after dispatch.', new Request('PUT', 'https://sdk.invalid/v1/crm/service-calendars'))]);
        try {
            $sdk->serviceLevel->updateServiceCalendar($this->calendar(), self::KEY);
            self::fail('Timeout must be unconfirmed.');
        } catch (ServiceLevelWriteUnconfirmed $error) {
            self::assertSame(self::KEY, $error->intent->idempotencyKey);
            self::assertSame('updateServiceCalendar', $error->intent->operation);
            self::assertNull($error->intent->expectedResultId);
            self::assertInstanceOf(ConnectException::class, $error->cause);
        }
        self::assertCount(1, $this->history);
    }

    public function test_recovery_freezes_effective_company_and_version_even_when_hooks_change(): void
    {
        $sdk = $this->sdk([$this->error(500, 'internal_error'), $this->json(['data' => ['id' => self::CALENDAR, 'version' => 7]])]);
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new class(self::CALENDAR, self::TICKET) implements \Factuarea\Sdk\Hooks\BeforeRequestHook
        {
            private int $calls = 0;

            public function __construct(private string $originalProfile, private string $otherProfile)
            {
            }

            public function beforeRequest(\Factuarea\Sdk\Hooks\BeforeRequestContext $context, RequestInterface $request): RequestInterface
            {
                $this->calls++;

                return $request->withHeader('Factuarea-Version', $this->calls === 1 ? '2026-09-01' : '2026-10-01')
                    ->withHeader('X-Active-Profile', $this->calls === 1 ? $this->originalProfile : $this->otherProfile);
            }
        });
        try {
            $sdk->serviceLevel->updateServiceCalendar($this->calendar(self::CALENDAR, 6), self::KEY);
            self::fail('Unconfirmed result required.');
        } catch (ServiceLevelWriteUnconfirmed $error) {
            $sdk->serviceLevel->recover($error->intent);
        }
        self::assertCount(2, $this->history);
        self::assertSame('2026-09-01', $this->history[1]['request']->getHeaderLine('Factuarea-Version'));
        self::assertSame(self::CALENDAR, $this->history[1]['request']->getHeaderLine('X-Active-Profile'));
    }

    public function test_after_error_hook_cannot_synthesize_confirmation_or_retry_a_write(): void
    {
        $sdk = $this->sdk([$this->error(500, 'internal_error')]);
        $hook = new class implements \Factuarea\Sdk\Hooks\AfterErrorHook
        {
            public int $calls = 0;

            public function afterError(\Factuarea\Sdk\Hooks\AfterErrorContext $context, ?\Psr\Http\Message\ResponseInterface $response, ?\Throwable $error): \Factuarea\Sdk\Hooks\ErrorResponseContext
            {
                $this->calls++;

                return new \Factuarea\Sdk\Hooks\ErrorResponseContext(new Response(200, ['Content-Type' => 'application/json'], '{}'), null);
            }
        };
        $sdk->sdkConfiguration->hooks->registerAfterErrorHook($hook);
        try {
            $sdk->serviceLevel->updateServiceCalendar($this->calendar(), self::KEY);
            self::fail('A hook must never synthesize a receipt.');
        } catch (ServiceLevelWriteUnconfirmed) {
            self::assertSame(0, $hook->calls);
        }
        self::assertCount(1, $this->history);
    }

    public function test_native_unconfirmed_receipt_409_is_distinct_from_a_cas_rejection(): void
    {
        $sdk = $this->sdk([$this->error(409, 'crm_version_conflict', 'service_level_result_unconfirmed')]);
        try {
            $sdk->serviceLevel->updateServiceCalendar($this->calendar(), self::KEY);
            self::fail('Original reservation is unconfirmed.');
        } catch (ServiceLevelWriteUnconfirmed $error) {
            self::assertSame(409, $error->statusCode);
            self::assertInstanceOf(ErrorThrowable::class, $error->cause);
            self::assertSame('service_level_result_unconfirmed', $error->cause->container->error->subcode);
        }
        self::assertCount(1, $this->history);
    }

    /** @return array<string,array{array<string,mixed>}> */
    public static function invalidReceipts(): array
    {
        return [
            'missing version' => [['id' => self::CALENDAR]],
            'string version' => [['id' => self::CALENDAR, 'version' => '7']],
            'float version' => [['id' => self::CALENDAR, 'version' => 7.5]],
            'queued result' => [['status' => 'queued']],
            'foreign identity' => [['id' => self::CYCLE, 'version' => 7]],
            'private legacy field' => [['uuid' => self::CALENDAR, 'version' => 7]],
            'queued with identity' => [['id' => self::CALENDAR, 'version' => 7, 'status' => 'queued']],
        ];
    }

    /** @param array<string,mixed> $data */
    #[DataProvider('invalidReceipts')]
    public function test_http_200_without_a_valid_original_receipt_is_never_confirmed(array $data): void
    {
        $sdk = $this->sdk([$this->json(['data' => $data])]);
        try {
            $sdk->serviceLevel->updateServiceCalendar($this->calendar(self::CALENDAR, 6), self::KEY);
            self::fail('Invalid receipt must never confirm a write.');
        } catch (ServiceLevelWriteUnconfirmed $error) {
            self::assertSame(200, $error->statusCode);
            self::assertSame(6, json_decode($error->intent->bodyJson, true, flags: JSON_THROW_ON_ERROR)['expected_version']);
        }
        self::assertCount(1, $this->history);
    }

    /** @return array<string,array{int,string,?string}> */
    public static function rejections(): array
    {
        return [
            'authentication' => [401, 'missing_api_key', null],
            'scope' => [403, 'insufficient_scope', null],
            'tenant hidden' => [404, 'resource_not_found', null],
            'CAS' => [409, 'crm_version_conflict', 'service_level_version_conflict'],
            'key reused' => [409, 'idempotency_key_reused', null],
            'validation' => [422, 'parameter_invalid', null],
            'admission' => [429, 'rate_limit_exceeded', null],
        ];
    }

    #[DataProvider('rejections')]
    public function test_native_errors_remain_typed_and_are_not_retried(int $status, string $code, ?string $subcode): void
    {
        $sdk = $this->sdk([$this->error($status, $code, $subcode)]);
        try {
            $sdk->serviceLevel->updateServiceCalendar($this->calendar(), self::KEY);
            self::fail('Native error required.');
        } catch (ErrorThrowable $error) {
            self::assertSame($code, $error->container->error->code);
            self::assertSame($subcode, $error->container->error->subcode);
            self::assertSame('req_servicelevel_fixture', $error->container->error->requestId);
            self::assertSame($status, $error->container->rawResponse->getStatusCode());
        }
        self::assertCount(1, $this->history);
    }

    public function test_page_pagination_is_native_and_read_projection_keeps_exact_microseconds(): void
    {
        $calendar = ['id' => self::CALENDAR, 'version' => 6, 'name' => 'Servicio', 'timezone' => 'UTC', 'mode' => 'always_open', 'windows' => [], 'holidays' => [], 'exceptions' => []];
        $sdk = $this->sdk([
            $this->json(['data' => ['items' => [$calendar], 'total' => 3, 'page' => 2, 'per_page' => 1]]),
            $this->json(['data' => ['subject_id' => self::TICKET, 'items' => [self::cycle()], 'total' => 1, 'page' => 1, 'per_page' => 25]]),
            $this->json(['data' => self::statusFixture()]),
        ]);
        $calendars = $sdk->serviceLevel->listServiceCalendars(2, 1)->object->data;
        self::assertSame(2, $calendars->page);
        self::assertSame(3, $calendars->total);
        self::assertInstanceOf(Components\ServiceCalendar::class, $calendars->items[0]);
        self::assertSame('page=2&per_page=1', $this->history[0]['request']->getUri()->getQuery());
        $history = $sdk->serviceLevel->getServiceSlaHistory(self::TICKET)->object->data;
        self::assertSame(self::TICKET, $history->subjectId);
        self::assertInstanceOf(Components\ServiceSlaCycle::class, $history->items[0]);
        self::assertSame(self::POLICY, $history->items[0]->configurations[0]->targets->policyId);
        self::assertNull($history->items[0]->clocks->firstResponse->messageId);
        self::assertSame('/v1/crm/service-tickets/'.self::TICKET.'/sla/history', $this->history[1]['request']->getUri()->getPath());
        $status = $sdk->serviceLevel->getServiceSlaStatus(self::TICKET)->object->data;
        self::assertSame(123456789012345, $status->clocks->firstResponse->elapsedMicroseconds);
        self::assertSame('not_started', $status->clocks->nextResponse->state);
        self::assertSame('/v1/crm/service-tickets/'.self::TICKET.'/sla', $this->history[2]['request']->getUri()->getPath());
        foreach ($this->history as $entry) {
            self::assertSame('GET', $entry['request']->getMethod());
            self::assertSame('', (string) $entry['request']->getBody());
            self::assertFalse($entry['request']->hasHeader('Idempotency-Key'));
        }
    }

    public function test_recovery_rejects_another_credential_before_sending(): void
    {
        $sdk = $this->sdk([$this->error(500, 'internal_error')]);
        try {
            $sdk->serviceLevel->updateServiceCalendar($this->calendar(), self::KEY);
            self::fail('Unconfirmed result required.');
        } catch (ServiceLevelWriteUnconfirmed $error) {
            $intent = $error->intent;
        }
        $other = $this->sdk([]);
        $other->sdkConfiguration->securitySource = fn () => new Components\Security(bearerAuth: 'fact_test_other_fixture');
        try {
            $other->serviceLevel->recover($intent);
            self::fail('Another credential must not redispatch the original intent.');
        } catch (InvalidArgumentException) {
            self::assertCount(0, $this->history);
        }
    }

    public function test_request_validation_cannot_generate_or_replace_missing_keys_or_ids(): void
    {
        $sdk = $this->sdk([]);
        try {
            $sdk->serviceLevel->configureServiceSla(self::TICKET, $this->configure(), '');
            self::fail('Explicit original key required.');
        } catch (InvalidArgumentException) {
            self::assertCount(0, $this->history);
        }
        try {
            $sdk->serviceLevel->getServiceSlaStatus('123');
            self::fail('Native UUIDv7 required.');
        } catch (InvalidArgumentException) {
            self::assertCount(0, $this->history);
        }
    }

    /** @return array<string,mixed> */
    private static function cycle(): array
    {
        $target = ['minutes' => 30, 'at_risk_minutes' => null];
        $configuration = ['effective_at' => '2026-10-09T10:00:00.000001Z', 'targets' => ['policy_id' => self::POLICY, 'version' => 1,
            'clocks' => ['first_response' => $target, 'next_response' => $target, 'resolution' => $target], 'pause_on_waiting_customer' => ['next_response']], 'calendar' => null];
        $clock = ['started_at' => null, 'stopped_at' => null, 'satisfied' => false, 'message_id' => null, 'configuration' => null];

        return ['id' => self::CYCLE, 'subject_id' => self::TICKET, 'version' => 7, 'cycle_number' => 1, 'previous_cycle_id' => null,
            'opened_at' => '2026-10-09T10:00:00.000001Z', 'observed_at' => '2026-10-09T11:00:00.000001Z', 'terminal_at' => null,
            'clocks' => ['first_response' => $clock, 'next_response' => $clock, 'resolution' => $clock], 'response_history' => [],
            'configurations' => [$configuration], 'pauses' => [], 'messages' => []];
    }

    /** @return array<string,mixed> */
    private static function statusFixture(): array
    {
        $clock = ['started_at' => '2026-10-09T10:00:00.000001Z', 'stopped_at' => null, 'satisfied' => false, 'paused' => false,
            'elapsed_microseconds' => 123456789012345, 'target_minutes' => 30, 'at_risk_minutes' => null, 'policy_id' => self::POLICY,
            'policy_version' => 1, 'elapsed_calendar_version' => null, 'deadline' => '2026-10-09T10:30:00.000001Z', 'deadline_is_provisional' => false, 'state' => 'breached'];

        return ['id' => self::CYCLE, 'subject_id' => self::TICKET, 'version' => 7, 'cycle_number' => 1, 'policy_id' => self::POLICY, 'policy_version' => 1,
            'calendar_id' => null, 'calendar_version' => null, 'as_of' => '2026-10-09T11:00:00.000001Z', 'terminal' => false, 'state' => 'breached',
            'public_customer_messages' => 0, 'public_agent_responses' => 0, 'next_response_history' => [],
            'clocks' => ['first_response' => $clock, 'next_response' => [...$clock, 'started_at' => null, 'state' => 'not_started'], 'resolution' => $clock]];
    }

    /** @param array<string,mixed> $body
     * @param array<string,string> $headers */
    private function json(array $body, array $headers = []): Response
    {
        return new Response(200, ['Content-Type' => 'application/json', ...$headers], json_encode($body, JSON_THROW_ON_ERROR));
    }

    private function error(int $status, string $code, ?string $subcode = null): Response
    {
        $type = match ($status) {
            401 => 'authentication_error', 403 => 'permission_error', 429 => 'rate_limit_error', 500 => 'api_error', default => 'invalid_request_error',
        };

        return new Response($status, ['Content-Type' => 'application/json'], json_encode(['error' => ['type' => $type, 'code' => $code,
            'subcode' => $subcode, 'message' => 'Rechazo nativo.', 'request_id' => 'req_servicelevel_fixture']], JSON_THROW_ON_ERROR));
    }
}
