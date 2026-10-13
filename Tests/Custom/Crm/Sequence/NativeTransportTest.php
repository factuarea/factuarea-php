<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\Sequence;

use Factuarea\Sdk\Custom\Crm\CrmClient;
use Factuarea\Sdk\Custom\Crm\CrmApiException;
use Factuarea\Sdk\Custom\Crm\CrmUnconfirmedException;
use Factuarea\Sdk\Custom\Crm\Model\CrmSearchKnowledgeArticlesParameters;
use Factuarea\Sdk\Custom\Crm\Sequence\OriginalIntent;
use Factuarea\Sdk\Custom\Crm\Sequence\RequiredPaths;
use Factuarea\Sdk\Custom\Crm\Sequence\SequenceClient;
use Factuarea\Sdk\Custom\Crm\Sequence\UnconfirmedControlException;
use Factuarea\Sdk\Custom\Crm\Sequence\UnconfirmedWriteException;
use Factuarea\Sdk\Custom\Crm\UnconfirmedMutationException;
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
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class NativeTransportTest extends TestCase
{
    private const ID = '019a4afd-5000-7000-8000-000000000001';
    private const SUBJECT = '019a4afd-5000-7000-8000-000000000002';
    private const ENROLMENT = '019a4afd-5000-7000-8000-000000000003';

    /** @var \ArrayObject<int,array{request:RequestInterface,response:?ResponseInterface,error:?\Throwable,options:array<string,mixed>}> */
    private \ArrayObject $history;
    /** @var \ArrayObject<int,string> */
    private \ArrayObject $operations;
    private MockHandler $mock;
    private CrmClient $crm;

    /** Actual native Routes/v1.php supplied explicitly, never defaults or publication metadata. */
    private function paths(): RequiredPaths
    {
        return new RequiredPaths('crm/crmsequence', 'crm/crmsequence/{sequence}/preview', 'crm/crmsequence',
            'crm/crmsequence/{sequence}', 'crm/crmsequence/{sequence}/enrolments', 'crm/crmsequence/original',
            'crm/crmsequence/enrolments/original', 'crm/crmsequence/enrolments/{enrolment}/pause',
            'crm/crmsequence/enrolments/{enrolment}/resume', 'crm/crmsequence/enrolments/{enrolment}/cancel');
    }

    /** @param list<Response|\Throwable> $responses */
    private function client(array $responses, string $token = 'fact_sequence_native_fixture'): SequenceClient
    {
        $this->history = new \ArrayObject();
        $this->operations = new \ArrayObject();
        $this->mock = new MockHandler($responses);
        $stack = HandlerStack::create($this->mock);
        $history = $this->history;
        $stack->push(Middleware::history($history));
        $sdk = Factuarea::builder()->setSecurity(new Security(bearerAuth: $token))
            ->setClient(new IdempotencyClient(new Client(['handler' => $stack, 'headers' => ['Idempotency-Key' => 'default-key-must-never-replace-native-intent']])))
            ->setRetryConfig(new RetryConfigBackoff(1, 1, 1.0, 10, true))->build();
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new FactuareaVersionHook());
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
        $this->crm = new CrmClient($sdk);

        return $this->crm->sequences($this->paths());
    }

    private function request(int $index): RequestInterface
    {
        $record = $this->history[$index];
        self::assertNotNull($record, 'The configured native HTTP client must have received this request.');

        return $record['request'];
    }

    private function saveWire(int $version = 0): string
    {
        return '{ "definition": {"timezone":"Europe/Madrid","ambiguous_offset":"later","allowed_windows":[{"weekday":1,"start_minute":0,"end_minute":1440}],"steps":[{"type":"wait","wait_seconds":60,"recipe_id":null}],"stop_on_closure":true,"purpose":"crm_sequence","channel":"email","stop_conditions":[]}, "expected_version" : '.$version.' }';
    }

    /** @return array<string,mixed> */
    private function schedule(int $version = 1): array
    {
        return ['id' => self::ID, 'version' => $version, 'authorization_version' => 'current-C7', 'sandbox' => true,
            'state' => 'active', 'owner_id' => '019a4afd-5000-4000-8000-000000000009', 'team_id' => null, 'fields' => (object) []];
    }

    /** @return array<string,mixed> */
    private function enrolment(): array
    {
        return ['id' => self::ENROLMENT, 'version' => 1, 'authorization_version' => 'current-C7', 'sandbox' => true,
            'sequence_id' => self::ID, 'subject_type' => 'lead', 'subject_id' => self::SUBJECT, 'generation' => 1,
            'definition_version' => 2, 'state' => 'active', 'current_step' => 0, 'due_at' => null,
            'stop_reason' => null, 'fields' => (object) []];
    }

    /** @param array<string,mixed> $data */
    private function response(array $data): Response
    {
        return new Response(200, ['Content-Type' => 'application/json'], json_encode(['data' => $data], JSON_THROW_ON_ERROR));
    }

    private function error(int $status, string $type, string $code): Response
    {
        return new Response($status, ['Content-Type' => 'application/json', 'Retry-After' => '5'],
            json_encode(['error' => ['type' => $type, 'code' => $code, 'message' => 'Native Sequence error',
                'param' => 'expected_version', 'request_id' => 'req_sequence_fixture']], JSON_THROW_ON_ERROR));
    }

    public function test_original_save_and_enrol_preserve_bytes_keys_and_server_allocated_ids(): void
    {
        $client = $this->client([$this->response($this->schedule()), $this->response($this->schedule(2)), $this->response($this->enrolment())]);
        $key = str_repeat('opaque-original-', 22);
        $create = OriginalIntent::save($this->saveWire(), $key);
        self::assertSame(self::ID, $client->save($create)->body->data->id);
        $save = OriginalIntent::save($this->saveWire(1), 'persisted-save-key', self::ID);
        self::assertSame(2, $client->save($save)->body->data->version);
        $enrol = OriginalIntent::enrol(self::ID, '{ "subject_id" : "'.self::SUBJECT.'", "expected_definition_version":2,"subject_type":"lead" }', 'persisted-enrol-key');
        self::assertSame(self::ENROLMENT, $client->enrol($enrol)->body->data->id);
        foreach ([$create, $save, $enrol] as $index => $intent) {
            $request = $this->request($index);
            self::assertSame('POST', $request->getMethod());
            self::assertSame($intent->originalWire, (string) $request->getBody());
            self::assertSame($intent->originalKey, $request->getHeaderLine('Idempotency-Key'));
            self::assertSame('Bearer fact_sequence_native_fixture', $request->getHeaderLine('Authorization'));
            self::assertNotSame('', $request->getHeaderLine('Factuarea-Version'));
        }
        self::assertSame('/v1/crm/crmsequence', $this->request(0)->getUri()->getPath());
        self::assertSame('/v1/crm/crmsequence/'.self::ID, $this->request(1)->getUri()->getPath());
        self::assertSame('/v1/crm/crmsequence/'.self::ID.'/enrolments', $this->request(2)->getUri()->getPath());
    }

    public function test_lost_ack_preserves_intent_and_recovers_only_by_get_with_original_key(): void
    {
        $failure = new ConnectException('Lost acknowledgement', new Request('POST', 'https://fixture.invalid/v1/crm/crmsequence'));
        $client = $this->client([$failure, $this->response($this->schedule(1))]);
        $intent = OriginalIntent::save($this->saveWire(), 'persisted-lost-ack-key');
        try {
            $client->save($intent);
            self::fail('The original write should remain unconfirmed.');
        } catch (UnconfirmedWriteException $error) {
            self::assertSame($intent, $error->originalIntent);
            $native = $error->getPrevious();
            self::assertInstanceOf(UnconfirmedMutationException::class, $native);
            self::assertSame($intent->originalKey, $native->idempotencyKey);
            self::assertSame($failure, $native->getPrevious());
        }
        self::assertCount(1, $this->history);
        self::assertSame(1, $client->originalSave($intent->originalKey)->body->data->version);
        self::assertCount(2, $this->history);
        $read = $this->request(1);
        self::assertSame('GET', $read->getMethod());
        self::assertSame('/v1/crm/crmsequence/original', $read->getUri()->getPath());
        self::assertSame('', $read->getUri()->getQuery());
        self::assertSame('', (string) $read->getBody());
        self::assertSame($intent->originalKey, $read->getHeaderLine('Idempotency-Key'));
        self::assertSame('automation.crm_sequence_save_original', $this->operations[1]);
    }

    public function test_native_list_preview_and_enrol_original_are_get_only_closed_projections(): void
    {
        $client = $this->client([$this->response(['items' => [], 'enrolments' => [], 'resource' => 'enrolments', 'next_cursor' => null, 'visible_count' => 0]),
            $this->response(['sequence_id' => self::ID, 'definition_version' => 2, 'subject_id' => self::SUBJECT, 'timezone' => 'Europe/Madrid',
                'steps' => [['step_index' => 0, 'type' => 'wait', 'due_at' => '2026-10-12T08:00:00+00:00', 'recipe_id' => null]]]),
            $this->response($this->enrolment())]);
        $filters = '{"resource":"enrolments","sequence_id":"'.self::ID.'"}';
        self::assertSame(0, $client->list($filters, 7, 'opaque_cursor')->body->data->visible_count);
        self::assertSame(self::SUBJECT, $client->preview(self::ID, 'lead', self::SUBJECT, '2026-10-12T09:00:00+01:00')->body->data->subject_id);
        self::assertSame(self::ENROLMENT, $client->originalEnrol('persisted-enrol-key')->body->data->id);
        parse_str($this->request(0)->getUri()->getQuery(), $query);
        self::assertSame(['filters' => $filters, 'limit' => '7', 'cursor' => 'opaque_cursor'], $query);
        foreach ($this->history as $record) {
            self::assertSame('GET', $record['request']->getMethod());
            self::assertSame('', (string) $record['request']->getBody());
        }
        self::assertSame('/v1/crm/crmsequence/enrolments/original', $this->request(2)->getUri()->getPath());
        self::assertSame('', $this->request(2)->getUri()->getQuery());
        self::assertSame('persisted-enrol-key', $this->request(2)->getHeaderLine('Idempotency-Key'));
        self::assertSame('automation.crm_sequence_enrol_original', $this->operations[2]);
    }

    public function test_controls_return_void_204_without_hook_default_or_generated_keys(): void
    {
        $client = $this->client([new Response(204), new Response(204), new Response(204)]);
        $client->pause(self::ENROLMENT, 3);
        $client->resume(self::ENROLMENT, 4);
        $client->cancel(self::ENROLMENT, 5);
        self::assertCount(3, $this->history);
        foreach (['pause', 'resume', 'cancel'] as $index => $action) {
            $request = $this->request($index);
            self::assertSame('PATCH', $request->getMethod());
            self::assertSame('/v1/crm/crmsequence/enrolments/'.self::ENROLMENT.'/'.$action, $request->getUri()->getPath());
            self::assertFalse($request->hasHeader('Idempotency-Key'));
            self::assertSame('Bearer fact_sequence_native_fixture', $request->getHeaderLine('Authorization'));
            self::assertNotSame('', $request->getHeaderLine('Factuarea-Version'));
            $wire = ['expected_version' => $index + 3];
            if ($action === 'pause') {
                $wire['reason'] = 'manual';
            }
            self::assertSame($wire, json_decode((string) $request->getBody(), true, 32, JSON_THROW_ON_ERROR));
        }
    }

    public function test_a_control_network_failure_is_not_retried_or_given_a_fake_original_key(): void
    {
        $failure = new ConnectException('Lost control acknowledgement', new Request('PATCH', 'https://fixture.invalid/v1/crm/crmsequence/enrolments/'.self::ENROLMENT.'/pause'));
        $client = $this->client([$failure, new Response(204)]);
        try {
            $client->pause(self::ENROLMENT, 3);
            self::fail('The control should remain unconfirmed.');
        } catch (UnconfirmedControlException $error) {
            self::assertSame('pause', $error->action);
            self::assertSame(self::ENROLMENT, $error->enrolmentId);
            self::assertSame(3, $error->expectedVersion);
            self::assertSame($failure, $error->getPrevious());
        }
        self::assertCount(1, $this->history);
        self::assertCount(1, $this->mock);
        self::assertFalse($this->request(0)->hasHeader('Idempotency-Key'));
    }

    public function test_native_conflict_keeps_sanitized_error_facts_and_original_key(): void
    {
        $client = $this->client([$this->error(409, 'conflict_error', 'crm_version_conflict')]);
        $intent = OriginalIntent::save($this->saveWire(2), 'persisted-conflict-key', self::ID);
        try {
            $client->save($intent);
            self::fail('The native conflict must be preserved.');
        } catch (CrmApiException $error) {
            self::assertSame('crm_version_conflict', $error->machineCode);
            self::assertSame('expected_version', $error->param);
            self::assertSame('req_sequence_fixture', $error->requestId);
            self::assertSame(409, $error->statusCode);
            self::assertSame('5', $error->retryAfter);
        }
        self::assertSame($intent->originalKey, $this->request(0)->getHeaderLine('Idempotency-Key'));
        self::assertCount(1, $this->history);
    }

    public function test_native_server_failure_keeps_exact_original_intent_and_never_resends(): void
    {
        $client = $this->client([$this->error(500, 'api_error', 'internal_error'), $this->response($this->schedule())]);
        $intent = OriginalIntent::save($this->saveWire(), 'persisted-server-failure-key');
        try {
            $client->save($intent);
            self::fail('The native server error must retain an unconfirmed original intent.');
        } catch (UnconfirmedWriteException $error) {
            self::assertSame($intent, $error->originalIntent);
            $native = $error->getPrevious();
            self::assertInstanceOf(CrmUnconfirmedException::class, $native);
            self::assertSame('internal_error', $native->machineCode);
            self::assertSame(500, $native->statusCode);
            self::assertSame('req_sequence_fixture', $native->requestId);
        }
        self::assertCount(1, $this->history);
        self::assertCount(1, $this->mock);
        self::assertSame($intent->originalWire, (string) $this->request(0)->getBody());
    }

    public function test_an_unreadable_success_preserves_the_original_intent_for_explicit_recovery(): void
    {
        $client = $this->client([$this->response(['id' => self::ID])]);
        $intent = OriginalIntent::save($this->saveWire(), 'persisted-unreadable-key');
        try {
            $client->save($intent);
            self::fail('A partial response cannot confirm the original effect.');
        } catch (UnconfirmedWriteException $error) {
            self::assertSame($intent, $error->originalIntent);
            self::assertInstanceOf(\InvalidArgumentException::class, $error->getPrevious());
        }
        self::assertCount(1, $this->history);
    }

    public function test_public_factory_preserves_existing_group_and_client_credential_isolation(): void
    {
        $one = $this->client([$this->response($this->schedule()), $this->response(['items' => [], 'total' => 0, 'page' => 1, 'per_page' => 25])], 'fact_company_one_fixture');
        $one->save(OriginalIntent::save($this->saveWire(), 'company-one-key'));
        self::assertSame(0, $this->crm->knowledgeBase->crmSearchKnowledgeArticles(new CrmSearchKnowledgeArticlesParameters(page: 1))->body->data->total);
        foreach ($this->history as $record) {
            self::assertSame('Bearer fact_company_one_fixture', $record['request']->getHeaderLine('Authorization'));
        }
        $two = $this->client([$this->response($this->schedule())], 'fact_company_two_fixture');
        $two->originalSave('company-two-key');
        self::assertSame('Bearer fact_company_two_fixture', $this->request(0)->getHeaderLine('Authorization'));
        self::assertSame('company-two-key', $this->request(0)->getHeaderLine('Idempotency-Key'));
    }
}
