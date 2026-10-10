<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm;

use Factuarea\Sdk\Custom\Crm\CrmClient;
use Factuarea\Sdk\Custom\Crm\Model;
use Factuarea\Sdk\Custom\Crm\UnconfirmedMutationException;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyClient;
use Factuarea\Sdk\Custom\Version\FactuareaVersionHook;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Models\Components\Security;
use Factuarea\Sdk\Models\Errors\ErrorThrowable;
use Factuarea\Sdk\Utils\Retry\RetryConfigNone;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CrmHttpTest extends TestCase
{
    private const ID = '019a4afd-5000-7000-8000-000000000001';

    private const SECOND = '019a4afd-5000-7000-8000-000000000002';

    /** @var list<array<string, mixed>> */
    private array $history = [];

    /** @param list<Response|\Throwable> $responses */
    private function client(array $responses): CrmClient
    {
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));
        $sdk = Factuarea::builder()
            ->setSecurity(new Security(bearerAuth: 'fact_test_fixture'))
            ->setClient(new IdempotencyClient(new Client(['handler' => $stack])))
            ->setRetryConfig(new RetryConfigNone())
            ->build();
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new FactuareaVersionHook());

        return new CrmClient($sdk);
    }

    /** @param array<string, mixed> $body @param array<string, string> $headers */
    private function response(array $body, int $status = 200, array $headers = []): Response
    {
        return new Response($status, ['Content-Type' => 'application/json'] + $headers, json_encode($body, JSON_THROW_ON_ERROR));
    }

    /** @return array<string, mixed> */
    private function receipt(): array
    {
        return ['data' => ['id' => self::ID, 'confirmed' => true, 'representation_available' => false]];
    }

    public function test_create_and_original_replay_preserve_receipt_key_auth_and_api_version(): void
    {
        $client = $this->client([
            $this->response($this->receipt(), 201),
            $this->response($this->receipt(), 200, ['Idempotent-Replayed' => 'true']),
        ]);
        $request = new Model\CrmCreateLeadParameters(
            body: new Model\CrmCreateLeadRequest(name: 'SDK fixture'),
            idempotencyKey: 'intent-original-1',
        );
        $first = $client->leads->crmCreateLead($request);
        $replay = $client->leads->crmCreateLead($request);
        self::assertInstanceOf(Model\LeadConfirmedReceipt::class, $first->body->data);
        self::assertSame(self::ID, $first->body->data->id);
        self::assertSame(self::ID, $replay->body->data->id);
        self::assertSame('intent-original-1', $replay->idempotencyKey);
        self::assertFalse($first->replayed());
        self::assertTrue($replay->replayed());
        self::assertSame(201, $first->statusCode());
        foreach ($this->history as $entry) {
            self::assertSame('Bearer fact_test_fixture', $entry['request']->getHeaderLine('Authorization'));
            self::assertSame(FactuareaVersionHook::DEFAULT_VERSION, $entry['request']->getHeaderLine('Factuarea-Version'));
            self::assertSame('intent-original-1', $entry['request']->getHeaderLine('Idempotency-Key'));
            self::assertSame('/v1/crm/leads', $entry['request']->getUri()->getPath());
            self::assertSame(['name' => 'SDK fixture'], json_decode((string) $entry['request']->getBody(), true));
        }
    }

    public function test_people_and_pipeline_creation_use_real_routes_and_decimal_strings(): void
    {
        $client = $this->client([$this->response($this->receipt(), 201), $this->response($this->receipt(), 201)]);
        $person = $client->contactPeople->crmContactPeopleCreate(new Model\CrmContactPeopleCreateParameters(
            body: new Model\CreateContactPersonV1Request(kind: 'person', name: 'SDK person'),
            idempotencyKey: 'person-original',
        ));
        $pipeline = $client->pipelines->createPipeline(new Model\CreatePipelineParameters(
            body: new Model\PipelineCreatePipelineRequest(
                name: 'SDK sales', visibility: 'company', teamIds: [],
                initialStage: new Model\PipelineCreatePipelineRequestInitialStage(name: 'New', probability: '10.25', definitionVersion: 0),
            ),
            idempotencyKey: 'pipeline-original',
        ));
        self::assertInstanceOf(Model\CrmContactPersonConfirmedReceipt::class, $person->body->data);
        self::assertInstanceOf(Model\PipelineConfirmedReceipt::class, $pipeline->body->data);
        self::assertSame('/v1/crm/contact-people', $this->history[0]['request']->getUri()->getPath());
        self::assertSame('/v1/crm/pipelines', $this->history[1]['request']->getUri()->getPath());
        $body = json_decode((string) $this->history[1]['request']->getBody(), true);
        self::assertSame('10.25', $body['initial_stage']['probability']);
        self::assertArrayNotHasKey('id', $body);
    }

    public function test_patch_preserves_omission_null_and_exact_money(): void
    {
        $client = $this->client([$this->response($this->receipt())]);
        $client->leads->crmUpdateLead(new Model\CrmUpdateLeadParameters(
            lead: self::ID,
            body: new Model\CrmUpdateLeadRequest(
                expectedVersion: 3, email: null,
                estimatedValue: new Model\CrmUpdateLeadRequestEstimatedValue(amount: '9007199254740993.0100', currency: 'EUR'),
                metadata: (object) ['integer' => 1],
            ),
            idempotencyKey: 'update-original',
        ));
        $request = $this->history[0]['request'];
        $body = json_decode((string) $request->getBody(), true);
        self::assertSame('PATCH', $request->getMethod());
        self::assertSame('/v1/crm/leads/'.self::ID, $request->getUri()->getPath());
        self::assertArrayNotHasKey('name', $body);
        self::assertArrayHasKey('email', $body);
        self::assertNull($body['email']);
        self::assertSame(3, $body['expected_version']);
        self::assertSame('9007199254740993.0100', $body['estimated_value']['amount']);
        self::assertSame(1, $body['metadata']['integer']);
    }

    public function test_foreign_tenant_and_server_context_cannot_be_injected_in_a_dto(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Model\CrmCreateLeadRequest::fromArray(['name' => 'SDK fixture', 'company_id' => 99, 'sandbox' => false]);
    }

    /** @return iterable<string, array{int, string, string}> */
    public static function errors(): iterable
    {
        yield 'scope' => [403, 'authorization_error', 'insufficient_scope'];
        yield 'opaque tenant' => [404, 'not_found_error', 'resource_not_found'];
        yield 'CAS' => [409, 'conflict_error', 'crm_version_conflict'];
        yield 'validation' => [422, 'invalid_request_error', 'parameter_invalid'];
        yield 'rate limit' => [429, 'rate_limit_error', 'rate_limit_exceeded'];
    }

    #[DataProvider('errors')]
    public function test_native_errors_keep_status_code_request_id_and_retry_after(int $status, string $type, string $code): void
    {
        $client = $this->client([$this->response(['error' => [
            'type' => $type, 'code' => $code, 'message' => 'Native CRM error', 'request_id' => 'req_crm_fixture',
        ]], $status, ['Retry-After' => '5'])]);
        try {
            $client->leads->crmFindLead(new Model\CrmFindLeadParameters(lead: self::ID));
            self::fail('The native typed error was expected.');
        } catch (ErrorThrowable $error) {
            self::assertSame($code, $error->container->error->code);
            self::assertSame('req_crm_fixture', $error->container->error->requestId);
            self::assertSame($status, $error->container->rawResponse->getStatusCode());
            self::assertSame('5', $error->container->rawResponse->getHeaderLine('Retry-After'));
            self::assertStringNotContainsString('fact_test_fixture', (string) $error);
            self::assertCount(1, $this->history);
        }
    }

    public function test_server_failure_on_write_keeps_native_error_and_does_not_repeat_effect(): void
    {
        $client = $this->client([$this->response(['error' => [
            'type' => 'api_error', 'code' => 'internal_error', 'message' => 'Native failure',
        ]], 500), $this->response($this->receipt(), 201)]);
        try {
            $client->leads->crmCreateLead(new Model\CrmCreateLeadParameters(
                body: new Model\CrmCreateLeadRequest(name: 'SDK fixture'), idempotencyKey: 'original-failed',
            ));
            self::fail('The native error was expected.');
        } catch (ErrorThrowable $error) {
            self::assertSame(500, $error->container->rawResponse->getStatusCode());
            self::assertCount(1, $this->history);
            self::assertSame('original-failed', $this->history[0]['request']->getHeaderLine('Idempotency-Key'));
        }
    }

    public function test_transport_failure_is_unconfirmed_and_retains_the_original_key(): void
    {
        $client = $this->client([new ConnectException('timeout', new Request('POST', 'https://api.factuarea.com/v1/crm/leads')),
            $this->response($this->receipt(), 201)]);
        try {
            $client->leads->crmCreateLead(new Model\CrmCreateLeadParameters(
                body: new Model\CrmCreateLeadRequest(name: 'SDK fixture'), idempotencyKey: 'original-timeout',
            ));
            self::fail('The unconfirmed result was expected.');
        } catch (UnconfirmedMutationException $error) {
            self::assertSame('unconfirmed', $error->outcome);
            self::assertSame('original-timeout', $error->idempotencyKey);
            self::assertSame('crmCreateLead', $error->operationId);
            self::assertCount(1, $this->history);
        }
    }

    public function test_uuid_v7_and_decimal_contracts_reject_invalid_input_before_http(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Model\CrmFindLeadParameters(lead: '00000000-0000-4000-8000-000000000001');
    }

    public function test_lead_cursor_pages_keep_opaque_cursor_and_hydrate_typed_items(): void
    {
        $item = ['id' => self::ID, 'version' => 1, 'name' => 'Lead A', 'company_name' => null, 'reasons' => ['exact_email']];
        $next = 'opaque/+token==:not-an-id';
        $client = $this->client([
            $this->response(['data' => ['data' => [$item], 'has_more' => true, 'next_cursor' => $next]]),
            $this->response(['data' => ['data' => [array_replace($item, ['id' => self::SECOND])], 'has_more' => false, 'next_cursor' => null]]),
        ]);
        $request = new Model\CrmFindLeadDuplicatesParameters(lead: self::ID, limit: 1);
        $items = iterator_to_array($client->leads->crmFindLeadDuplicatesItems($request));
        self::assertCount(2, $items);
        self::assertInstanceOf(Model\LeadDuplicate::class, $items[0]);
        self::assertSame(self::SECOND, $items[1]->id);
        parse_str($this->history[1]['request']->getUri()->getQuery(), $query);
        self::assertSame($next, $query['cursor']);
        self::assertSame('1', $query['limit']);
        self::assertSame(\Factuarea\Sdk\Custom\Crm\Omitted::Value, $request->cursor);
    }

    public function test_people_pages_use_page_metadata_and_never_invent_a_cursor_parameter(): void
    {
        $item = ['id' => self::ID, 'kind' => 'person', 'name' => 'Person A', 'version' => 1, 'status' => 'active'];
        $client = $this->client([
            $this->response(['data' => [$item], 'meta' => ['total' => 2, 'current_page' => 1, 'per_page' => 1, 'last_page' => 2]]),
            $this->response(['data' => [array_replace($item, ['id' => self::SECOND])], 'meta' => ['total' => 2, 'current_page' => 2, 'per_page' => 1, 'last_page' => 2]]),
        ]);
        $items = iterator_to_array($client->contactPeople->crmContactPeopleIndexItems(new Model\CrmContactPeopleIndexParameters(perPage: 1)));
        self::assertCount(2, $items);
        self::assertInstanceOf(Model\CrmContactPersonListItem::class, $items[0]);
        parse_str($this->history[1]['request']->getUri()->getQuery(), $query);
        self::assertSame('2', $query['page']);
        self::assertSame('1', $query['per_page']);
        self::assertArrayNotHasKey('cursor', $query);
        self::assertArrayNotHasKey('starting_after', $query);
    }

    public function test_pipeline_nested_items_pages_and_decimal_projection_are_preserved(): void
    {
        $stage = ['id' => self::SECOND, 'name' => 'New', 'position' => 0, 'status' => 'active', 'probability' => '10.25',
            'stale_after_days' => null, 'required_field_ids' => [], 'definition_version' => 0, 'revision' => 1];
        $item = ['id' => self::ID, 'name' => 'Sales', 'status' => 'active', 'visibility' => 'company', 'team_ids' => [],
            'version' => 1, 'require_override_reason' => false, 'require_loss_reason' => false, 'stages' => [$stage], 'loss_reasons' => []];
        $client = $this->client([
            $this->response(['data' => ['items' => [$item], 'has_more' => true, 'next_cursor' => 'pipeline-cursor']]),
            $this->response(['data' => ['items' => [], 'has_more' => false, 'next_cursor' => null]]),
        ]);
        $items = iterator_to_array($client->pipelines->listPipelinesItems(new Model\ListPipelinesParameters(limit: 1)));
        self::assertCount(1, $items);
        self::assertInstanceOf(Model\Pipeline::class, $items[0]);
        self::assertSame('10.25', $items[0]->stages[0]->probability);
        parse_str($this->history[1]['request']->getUri()->getQuery(), $query);
        self::assertSame('pipeline-cursor', $query['cursor']);
    }

    public function test_repeated_server_cursor_stops_with_an_explicit_error(): void
    {
        $page = $this->response(['data' => ['data' => [], 'has_more' => true, 'next_cursor' => 'repeated']]);
        $client = $this->client([$page, $page]);
        $this->expectException(\UnexpectedValueException::class);
        iterator_to_array($client->leads->crmFindLeadDuplicatesItems(new Model\CrmFindLeadDuplicatesParameters(lead: self::ID)));
    }

    public function test_page_limit_never_silently_truncates_a_listing(): void
    {
        $client = $this->client([$this->response(['data' => ['data' => [], 'has_more' => true, 'next_cursor' => 'next']])]);
        $this->expectException(\OverflowException::class);
        iterator_to_array($client->leads->crmFindLeadDuplicatesItems(new Model\CrmFindLeadDuplicatesParameters(lead: self::ID), maxPages: 1));
    }

    public function test_read_only_post_preview_is_not_an_unconfirmed_effect(): void
    {
        $client = $this->client([new ConnectException('timeout', new Request('POST', 'https://api.factuarea.com/v1/crm/leads/'.self::ID.'/conversion-preview'))]);
        $this->expectException(ConnectException::class);
        $client->leads->crmPreviewLeadConversion(new Model\CrmPreviewLeadConversionParameters(
            lead: self::ID,
            body: new Model\CrmPreviewLeadConversionRequest(strategy: 'link_existing', personId: self::SECOND,
                businessContactId: self::SECOND, roleAction: 'preserve'),
            idempotencyKey: 'preview-original',
        ));
    }

    public function test_typed_lead_filters_are_retained_across_native_cursor_pages(): void
    {
        $client = $this->client([
            $this->response(['data' => ['data' => [], 'has_more' => true, 'next_cursor' => 'filter-bound-cursor']]),
            $this->response(['data' => ['data' => [], 'has_more' => false, 'next_cursor' => null]]),
        ]);
        $filters = (new Model\LeadFilters(status: 'open', scoreMin: 40))->toJson();
        iterator_to_array($client->leads->crmListLeadsItems(new Model\CrmListLeadsParameters(filters: $filters, sort: '-score', limit: 25)));
        foreach ($this->history as $entry) {
            parse_str($entry['request']->getUri()->getQuery(), $query);
            self::assertSame($filters, $query['filters']);
            self::assertSame('-score', $query['sort']);
            self::assertSame('25', $query['limit']);
        }
    }

    public function test_scope_revocation_between_pages_propagates_without_a_false_success(): void
    {
        $client = $this->client([
            $this->response(['data' => ['data' => [], 'has_more' => true, 'next_cursor' => 'before-revocation']]),
            $this->response(['error' => ['type' => 'authorization_error', 'code' => 'insufficient_scope',
                'message' => 'Revoked scope', 'request_id' => 'req_revoked']], 403),
        ]);
        try {
            iterator_to_array($client->leads->crmListLeadsItems(new Model\CrmListLeadsParameters()));
            self::fail('The second page must retain the current scope denial.');
        } catch (ErrorThrowable $error) {
            self::assertSame('insufficient_scope', $error->container->error->code);
            self::assertCount(2, $this->history);
        }
    }

    public function test_empty_erasure_preview_body_keeps_the_native_json_object(): void
    {
        $client = $this->client([$this->response(['error' => ['type' => 'authorization_error', 'code' => 'insufficient_scope', 'message' => 'Read denied']], 403)]);
        try {
            $client->leads->crmPreviewLeadErasure(new Model\CrmPreviewLeadErasureParameters(
                lead: self::ID, body: new Model\CrmPreviewLeadErasureRequest(), idempotencyKey: 'erasure-preview-original',
            ));
            self::fail('The native scope denial was expected.');
        } catch (ErrorThrowable) {
            self::assertSame('{}', (string) $this->history[0]['request']->getBody());
            self::assertSame('POST', $this->history[0]['request']->getMethod());
        }
    }

    public function test_native_active_profile_and_version_overrides_are_explicit_headers(): void
    {
        $client = $this->client([$this->response(['error' => ['type' => 'not_found_error', 'code' => 'profile_not_found', 'message' => 'Opaque profile']], 404)]);
        try {
            $client->leads->crmFindLead(new Model\CrmFindLeadParameters(
                lead: self::ID, xActiveProfile: self::SECOND, factuareaVersion: '2026-10-01',
            ));
            self::fail('The native managed-profile denial was expected.');
        } catch (ErrorThrowable $error) {
            self::assertSame('profile_not_found', $error->container->error->code);
            $request = $this->history[0]['request'];
            self::assertSame(self::SECOND, $request->getHeaderLine('X-Active-Profile'));
            self::assertSame('2026-10-01', $request->getHeaderLine('Factuarea-Version'));
            self::assertSame('Bearer fact_test_fixture', $request->getHeaderLine('Authorization'));
            self::assertSame('', (string) $request->getBody());
            self::assertSame('', $request->getUri()->getQuery());
        }
    }
    public function test_native_json_object_keys_and_empty_array_shapes_survive_response_and_pagination(): void
    {
        $fixture = [
            'id' => self::ID, 'version' => 1, 'status' => 'open', 'stage' => 'inbox', 'name' => 'Lead JSON shapes',
            'email' => null, 'phone' => null, 'mobile' => null,
            'contact_data' => ['metadata' => (object) ['0' => 'value']],
            'owner_id' => null, 'team_id' => null, 'contact_id' => null, 'person_id' => null,
            'first_attribution' => new \stdClass(), 'last_attribution' => new \stdClass(),
            'source' => 'manual', 'source_term_id' => null, 'score' => 0, 'rules_version' => 'v1',
            'score_factors' => [], 'score_policy_id' => null, 'score_policy_version' => null,
            'score_evaluated_at' => '2026-10-09T00:00:00Z', 'consent' => [],
            'typed_fields' => new \stdClass(), 'typed_conflicts' => [], 'typed_projection' => null,
            'next_action' => ['status' => 'none', 'activity_id' => null, 'type' => null, 'title' => null, 'due_at' => null, 'reason' => null],
            'created_at' => '2026-10-09T00:00:00Z', 'updated_at' => '2026-10-09T00:00:00Z', 'erased_at' => null, 'routing' => null,
        ];
        $client = $this->client([
            $this->response(['data' => $fixture]),
            $this->response(['data' => ['data' => [$fixture], 'has_more' => true, 'next_cursor' => 'json-shapes']]),
            $this->response(['data' => ['data' => [array_replace($fixture, ['id' => self::SECOND, 'contact_data' => ['metadata' => []]])], 'has_more' => false, 'next_cursor' => null]]),
        ]);
        $lead = $client->leads->crmFindLead(new Model\CrmFindLeadParameters(lead: self::ID))->body->data;
        self::assertInstanceOf(\stdClass::class, $lead->contactData->metadata);
        self::assertSame('value', $lead->contactData->metadata->{'0'});
        self::assertStringContainsString('"metadata":{"0":"value"}', $lead->toJson());
        $items = iterator_to_array($client->leads->crmListLeadsItems(new Model\CrmListLeadsParameters(limit: 1)));
        self::assertCount(2, $items);
        self::assertSame('value', $items[0]->contactData->metadata->{'0'});
        self::assertSame([], $items[1]->contactData->metadata);
        self::assertSame('{"metadata":[]}', $items[1]->contactData->toJson());
        self::assertSame('{"metadata":{}}', (new Model\LeadContactData(metadata: new \stdClass()))->toJson());
    }

    public function test_get_without_retries_preserves_the_native_connection_exception(): void
    {
        $failure = new ConnectException('read timeout', new Request('GET', 'https://fixture.invalid'));
        $client = $this->client([$failure]);
        try {
            $client->leads->crmListLeads(new Model\CrmListLeadsParameters());
            self::fail('Expected the original read transport failure.');
        } catch (ConnectException $exception) {
            self::assertSame($failure, $exception);
            self::assertCount(1, $this->history);
        }
    }

}
