<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\TaskTimeBilling;

use Factuarea\Sdk\Custom\Crm\CrmClient;
use Factuarea\Sdk\Custom\Crm\CrmApiException;
use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\TaskTimeBilling\EntrySelection;
use Factuarea\Sdk\Custom\Crm\TaskTimeBilling\OriginalIntent;
use Factuarea\Sdk\Custom\Crm\TaskTimeBilling\PageParameters;
use Factuarea\Sdk\Custom\Crm\TaskTimeBilling\PreviewRequest;
use Factuarea\Sdk\Custom\Crm\TaskTimeBilling\RecoveryRequest;
use Factuarea\Sdk\Custom\Crm\TaskTimeBilling\RequiredPaths;
use Factuarea\Sdk\Custom\Crm\TaskTimeBilling\TaskTimeBillingClient;
use Factuarea\Sdk\Custom\Crm\TaskTimeBilling\UnconfirmedWriteException;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyClient;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyHook;
use Factuarea\Sdk\Custom\Version\FactuareaVersionHook;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Hooks\BeforeRequestContext;
use Factuarea\Sdk\Hooks\BeforeRequestHook;
use Factuarea\Sdk\Models\Components\Security;
use Factuarea\Sdk\Utils\Retry\RetryConfigBackoff;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

final class NativeTransportTest extends TestCase
{
    private const TICKET = '019a4afd-5000-7000-8000-000000000001';
    private const TASK = '019a4afd-5000-7000-8000-000000000002';
    private const BINDING = '019a4afd-5000-7000-8000-000000000003';
    private const EFFECT = '019a4afd-5000-7000-8000-000000000004';
    private const ENTRY = '019a4afd-5000-7000-8000-000000000005';
    private const HISTORICAL = '019a4afd-5000-4000-8000-000000000006';

    /** @var \ArrayObject<int,array<string,mixed>> */
    private \ArrayObject $history;
    /** @var \ArrayObject<int,string> */
    private \ArrayObject $operations;
    private MockHandler $mock;

    /** @param list<Response|\Throwable> $responses */
    private function client(array $responses): TaskTimeBillingClient
    {
        $this->history = new \ArrayObject();
        $this->operations = new \ArrayObject();
        $this->mock = new MockHandler($responses);
        $stack = HandlerStack::create($this->mock);
        $history = $this->history;
        $stack->push(Middleware::history($history));
        $sdk = Factuarea::builder()->setServerUrl('https://native-task-time.fixture.invalid/v1')
            ->setSecurity(new Security(bearerAuth: 'fact_native_task_time_fixture'))
            ->setClient(new IdempotencyClient(new Client(['handler' => $stack, 'headers' => ['Idempotency-Key' => 'unrelated-default', 'X-Native-Default' => 'retained']])))
            ->setRetryConfig(new RetryConfigBackoff(1, 1, 1.0, 10, true))->build();
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new FactuareaVersionHook());
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new IdempotencyHook());
        $operations = $this->operations;
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new class($operations) implements BeforeRequestHook
        {
            /** @param \ArrayObject<int,string> $operations */
            public function __construct(private \ArrayObject $operations)
            {
            }
            public function beforeRequest(BeforeRequestContext $context, RequestInterface $request): RequestInterface
            {
                $this->operations[] = $context->operationID;

                return $request;
            }
        });
        $prefix = 'crm/service-tickets/{ticket}/';
        $paths = new RequiredPaths($prefix.'tasks/link', $prefix.'tasks/unlink', $prefix.'tasks', $prefix.'billable-time',
            $prefix.'timesheet', $prefix.'time-invoice-preview', $prefix.'task-time/link/original-receipt', $prefix.'task-time/unlink/original-receipt');

        return (new CrmClient($sdk))->taskTimeBilling($paths)->withHeaders(self::HISTORICAL, '2026-10-01');
    }

    private function wire(bool $unlink = false): string
    {
        return '{ "task_id" : "'.self::TASK.'", "expected_ticket_version":3,"expected_task_version":4,"confirmed":true'
            .($unlink ? ',"binding_id":"'.self::BINDING.'","expected_binding_version":2' : '').' }';
    }

    /** @return array<string,mixed> */
    private function receipt(string $action = 'link', int $bindingVersion = 2): array
    {
        return ['ticket_id' => self::TICKET, 'ticket_version' => 4, 'authorization_version' => 'current-C7', 'status' => 'confirmed',
            'effect_id' => self::EFFECT, 'operation' => 'customer_service.task_time_'.$action, 'confirmed_at' => '2026-10-10T10:00:00Z',
            'result' => ['binding' => ['id' => self::BINDING, 'task_id' => self::TASK, 'version' => $bindingVersion, 'archived_at' => $action === 'unlink' ? '2026-10-10T10:00:00Z' : null]]];
    }

    /** @param array<string,mixed> $data */
    private function response(array $data, int $status = 200, array $headers = []): Response
    {
        return new Response($status, ['Content-Type' => 'application/json'] + $headers, json_encode(['data' => $data], JSON_THROW_ON_ERROR));
    }

    /** @return array<string,mixed> */
    private function base(): array
    {
        return ['ticket_id' => self::TICKET, 'ticket_version' => 8, 'authorization_version' => 'new-C7'];
    }

    /** @return array<string,mixed> */
    private function preview(): array
    {
        return $this->base() + ['preview' => ['contact' => ['id' => self::HISTORICAL, 'name' => 'Native contact'],
            'lines' => [['description' => 'Native hours', 'quantity' => '0.33333333', 'unit' => 'h', 'unit_price' => '99999999999.99', 'amount' => '33333332999.99']],
            'total_seconds' => 1200, 'total_hours' => '0.33333333', 'time_entry_ids' => [self::ENTRY], 'warnings' => ['Current native review required'],
            'totals' => ['subtotal' => '33333332999.99', 'vat' => '0.00', 'surcharge' => '0.00', 'retention' => '0.00', 'total' => '33333332999.99', 'vat_rate' => null, 'surcharge_rate' => null, 'retention_rate' => null]]];
    }

    private function request(int $index): RequestInterface
    {
        return $this->history[$index]['request'];
    }

    public function test_all_eight_actual_v1_operations_and_native_hooks(): void
    {
        $entry = ['id' => self::ENTRY, 'task_id' => self::TASK, 'invoice_id' => null, 'ended_at' => null];
        $client = $this->client([$this->response($this->receipt()), $this->response($this->receipt('unlink')),
            $this->response($this->base() + ['revision' => 'tasks-revision', 'tasks' => [['id' => self::BINDING, 'task_id' => self::TASK]]]),
            $this->response($this->base() + ['revision' => 'billable-revision', 'entries' => [$entry], 'next_cursor' => null]),
            $this->response($this->base() + ['revision' => 'timesheet-revision', 'entries' => [$entry], 'next_cursor' => null]),
            $this->response($this->preview()), $this->response($this->receipt()), $this->response($this->receipt('unlink'))]);
        $client->linkServiceTicketTask(OriginalIntent::link(self::TICKET, $this->wire(), 'native opaque key'));
        $client->unlinkServiceTicketTask(OriginalIntent::unlink(self::TICKET, $this->wire(true), 'native-unlink-key'));
        $tasks = $client->listServiceTicketTasks(self::TICKET);
        $billable = $client->getServiceBillableTime(self::TICKET, new PageParameters(null, 25));
        $timesheet = $client->getServiceTimesheet(self::TICKET, new PageParameters(str_repeat('a', 64).':'.self::ENTRY, 20));
        $preview = $client->previewServiceTimeInvoice(self::TICKET, new PreviewRequest(self::TASK, 3, 4, [new EntrySelection(self::ENTRY, str_repeat('b', 64))], 'single_line'));
        $client->getOriginalServiceTicketTaskLinkReceipt(self::TICKET, new RecoveryRequest('link', self::TASK, 3, 4, self::BINDING, self::EFFECT));
        $client->getOriginalServiceTicketTaskUnlinkReceipt(self::TICKET, new RecoveryRequest('unlink', self::TASK, 3, 4, self::BINDING, self::EFFECT, 2));
        self::assertSame(['linkServiceTicketTask', 'unlinkServiceTicketTask', 'listServiceTicketTasks', 'getServiceBillableTime', 'getServiceTimesheet', 'previewServiceTimeInvoice', 'getOriginalServiceTicketTaskLinkReceipt', 'getOriginalServiceTicketTaskUnlinkReceipt'], $this->operations->getArrayCopy());
        $suffixes = ['tasks/link', 'tasks/unlink', 'tasks', 'billable-time', 'timesheet', 'time-invoice-preview', 'task-time/link/original-receipt', 'task-time/unlink/original-receipt'];
        foreach ($suffixes as $index => $suffix) {
            $request = $this->request($index);
            self::assertSame('/v1/crm/service-tickets/'.self::TICKET.'/'.$suffix, $request->getUri()->getPath());
            self::assertSame(in_array($index, [2, 3, 4], true) ? 'GET' : 'POST', $request->getMethod());
            self::assertSame('Bearer fact_native_task_time_fixture', $request->getHeaderLine('Authorization'));
            self::assertSame(self::HISTORICAL, $request->getHeaderLine('X-Active-Profile'));
            self::assertSame('2026-10-01', $request->getHeaderLine('Factuarea-Version'));
        }
        self::assertSame($this->wire(), (string) $this->request(0)->getBody());
        self::assertSame('native opaque key', $this->request(0)->getHeaderLine('Idempotency-Key'));
        self::assertSame(Omitted::Value, $tasks->body->data->tasks[0]->version);
        self::assertSame(Omitted::Value, $billable->body->data->totalBillableClosedSeconds);
        self::assertSame(Omitted::Value, $timesheet->body->data->totalClosedSeconds);
        self::assertNull($timesheet->body->data->entries[0]->invoiceId);
        self::assertSame('33333332999.99', $preview->body->data->preview->totals->total);
        self::assertSame('99999999999.99', $preview->body->data->preview->lines[0]->unitPrice);
        self::assertSame('limit=25', $this->request(3)->getUri()->getQuery());
        foreach ([5, 6, 7] as $index) {
            self::assertFalse($this->request($index)->hasHeader('Idempotency-Key'));
            self::assertSame('retained', $this->request($index)->getHeaderLine('X-Native-Default'));
            self::assertFalse($this->history[$index]['options']['allow_redirects']);
        }
    }

    public function test_recovery_retains_older_original_after_current_head_advances(): void
    {
        $client = $this->client([$this->response($this->base() + ['revision' => 'new-head', 'tasks' => [['id' => self::BINDING, 'task_id' => self::TASK, 'version' => 9]]]),
            $this->response($this->receipt('link', 2), 200, ['Idempotent-Replayed' => 'true'])]);
        self::assertSame(9, $client->listServiceTicketTasks(self::TICKET)->body->data->tasks[0]->version);
        $r = $client->getOriginalServiceTicketTaskLinkReceipt(self::TICKET, new RecoveryRequest('link', self::TASK, 3, 4, self::BINDING, self::EFFECT));
        self::assertSame(2, $r->body->data->result->binding->version);
        self::assertSame(self::EFFECT, $r->body->data->effectId);
        self::assertTrue($r->replayed());
        self::assertNull($r->idempotencyKey);
        self::assertCount(2, $this->history);
        self::assertStringNotContainsString('original_wire_body', (string) $this->request(1)->getBody());
    }

    public function test_ambiguous_connection_does_not_resend_or_invent_recovery_identity(): void
    {
        $failure = new ConnectException('Native offline connection failure', new Request('POST', 'https://native-task-time.fixture.invalid'));
        $client = $this->client([$failure, $this->response($this->receipt())]);
        $intent = OriginalIntent::link(self::TICKET, $this->wire(), 'unchanged-native-key');
        try {
            $client->linkServiceTicketTask($intent);
            self::fail('Connection failure cannot confirm the write.');
        } catch (UnconfirmedWriteException $e) {
            self::assertSame($intent, $e->originalIntent);
            self::assertSame($this->wire(), $e->originalIntent->originalWire);
            self::assertNull($e->originalIntent->bindingId);
        }
        self::assertCount(1, $this->history);
        self::assertCount(1, $this->mock);
    }

    /** @return iterable<string,array{int}> */
    public static function unknownCompletions(): iterable
    {
        yield 'accepted is not native completion' => [202];
        yield 'created is not native completion' => [201];
        yield 'sequence control status is not Task receipt' => [204];
    }

    #[DataProvider('unknownCompletions')]
    public function test_task_write_needs200_rather_than_other_areas204(int $status): void
    {
        $client = $this->client([$status === 204 ? new Response(204) : $this->response($this->receipt(), $status)]);
        $this->expectException(UnconfirmedWriteException::class);
        $client->linkServiceTicketTask(OriginalIntent::link(self::TICKET, $this->wire(), 'native-key'));
    }

    /** @return iterable<string,array{int}> */
    public static function definiteErrors(): iterable
    {
        yield 'revoked' => [403];
        yield 'CAS' => [409];
        yield 'field' => [422];
        yield 'rate limited' => [429];
    }

    #[DataProvider('definiteErrors')]
    public function test_native_client_errors_keep_typed_param_and_request_id(int $status): void
    {
        $error = new Response($status, ['Content-Type' => 'application/json', 'Retry-After' => '3'], json_encode(['error' => ['type' => 'invalid_request_error', 'code' => 'parameter_invalid', 'message' => 'Native field error', 'param' => 'expected_binding_version', 'request_id' => 'req_task_time_native']], JSON_THROW_ON_ERROR));
        $client = $this->client([$error, $this->response($this->receipt())]);
        try {
            $client->linkServiceTicketTask(OriginalIntent::link(self::TICKET, $this->wire(), 'native-key'));
            self::fail('The native error must be preserved.');
        } catch (CrmApiException $e) {
            self::assertSame('expected_binding_version', $e->param);
            self::assertSame('req_task_time_native', $e->requestId);
            self::assertSame($status, $e->statusCode);
            self::assertSame('3', $e->retryAfter);
        }
        self::assertCount(1, $this->history);
    }

    public function test_a_receipt_for_another_task_cannot_confirm_an_effect(): void
    {
        $data = $this->receipt();
        $data['result']['binding']['task_id'] = self::ENTRY;
        $client = $this->client([$this->response($data)]);
        $this->expectException(UnconfirmedWriteException::class);
        $client->linkServiceTicketTask(OriginalIntent::link(self::TICKET, $this->wire(), 'native-key'));
    }

    public function test_recovery_cannot_substitute_another_effect_or_current_head(): void
    {
        $data = $this->receipt();
        $data['effect_id'] = self::ENTRY;
        $client = $this->client([$this->response($data)]);
        $this->expectException(\UnexpectedValueException::class);
        $client->getOriginalServiceTicketTaskLinkReceipt(self::TICKET, new RecoveryRequest('link', self::TASK, 3, 4, self::BINDING, self::EFFECT));
    }

    public function test_query_post_connection_failure_does_not_become_another_write(): void
    {
        $client = $this->client([new ConnectException('Offline readonly failure', new Request('POST', 'https://native-task-time.fixture.invalid')), $this->response($this->receipt())]);
        try {
            $client->getOriginalServiceTicketTaskLinkReceipt(self::TICKET, new RecoveryRequest('link', self::TASK, 3, 4, self::BINDING, self::EFFECT));
            self::fail('Recovery transport failure cannot confirm an original receipt.');
        } catch (ConnectException) {
            self::assertCount(1, $this->history);
            self::assertFalse($this->request(0)->hasHeader('Idempotency-Key'));
        }
    }

    public function test_query_post_does_not_follow_redirect_and_resend_known_tuple(): void
    {
        $client = $this->client([new Response(307, ['Location' => 'https://native-task-time.fixture.invalid/redirect']), $this->response($this->receipt())]);
        try {
            $client->getOriginalServiceTicketTaskLinkReceipt(self::TICKET, new RecoveryRequest('link', self::TASK, 3, 4, self::BINDING, self::EFFECT));
            self::fail('Redirect cannot reissue a native recovery POST.');
        } catch (\Factuarea\Sdk\Models\Errors\APIException $e) {
            self::assertSame(307, $e->statusCode);
            self::assertCount(1, $this->history);
            self::assertCount(1, $this->mock);
        }
    }

    public function test_masked_preview_totals_preserve_null_and_omission(): void
    {
        $null = $this->preview();
        $null['preview']['totals'] = null;
        $omitted = $this->preview();
        unset($omitted['preview']['totals']);
        $client = $this->client([$this->response($null), $this->response($omitted)]);
        $input = new PreviewRequest(self::TASK, 3, 4, [new EntrySelection(self::ENTRY, str_repeat('a', 64))], 'per_task');
        self::assertNull($client->previewServiceTimeInvoice(self::TICKET, $input)->body->data->preview->totals);
        self::assertSame(Omitted::Value, $client->previewServiceTimeInvoice(self::TICKET, $input)->body->data->preview->totals);
    }

    public function test_numeric_money_response_is_rejected_without_float_coercion(): void
    {
        $data = $this->preview();
        $data['preview']['lines'][0]['unit_price'] = 19.5;
        $client = $this->client([$this->response($data)]);
        $this->expectException(\InvalidArgumentException::class);
        $client->previewServiceTimeInvoice(self::TICKET, new PreviewRequest(self::TASK, 3, 4, [new EntrySelection(self::ENTRY, str_repeat('a', 64))], 'per_task'));
    }
}
