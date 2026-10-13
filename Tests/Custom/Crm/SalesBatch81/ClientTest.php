<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\SalesBatch81;

use Factuarea\Sdk\Custom\Crm\Documents\Omitted;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\Client as SalesClient;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\ConflictFailure;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\CreateActivityRequest;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\CreateLeadRequest;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\CreateOpportunityRequest;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\CrmFailure;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\LeadPatchRequest;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\PermissionFailure;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\RateLimitFailure;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\SalesResult;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\ValidationFailure;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\WriteUnconfirmed;
use Factuarea\Sdk\Custom\FactuareaClient;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Models\Components\Security;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

// The installed optimized classmap predates these manual Source files. Generation remains Root's work.
require_once __DIR__.'/../../../../src/Custom/Crm/Documents/Omitted.php';
foreach (['OAuthBearerClient', 'CredentialContext', 'OriginalIntent', 'OriginalReceipt'] as $class) {
    require_once __DIR__.'/../../../../src/Custom/Crm/OAuthOriginals/'.$class.'.php';
}
foreach (['Wire', 'CreateLeadRequest', 'CreateOpportunityRequest', 'CreateActivityRequest', 'LeadPatchRequest', 'WriteIntent', 'CrmFailure',
    'ValidationFailure', 'ConflictFailure', 'PermissionFailure', 'RateLimitFailure', 'WriteUnconfirmed', 'SalesResult', 'LeadPage', 'Client'] as $class) {
    require_once __DIR__.'/../../../../src/Custom/Crm/SalesBatch81/'.$class.'.php';
}
require_once __DIR__.'/../../../../examples/CrmWebsite.php';
require_once __DIR__.'/../../../../examples/CrmJourney.php';

final class ClientTest extends TestCase
{
    private const LEAD = '01900000-0000-7000-8000-000000000001';
    private const OPPORTUNITY = '01900000-0000-7000-8000-000000000002';
    private const PIPELINE = '01900000-0000-7000-8000-000000000003';
    private const STAGE = '01900000-0000-7000-8000-000000000004';
    private const ACTIVITY = '01900000-0000-7000-8000-000000000005';
    private const EFFECT = '01900000-0000-7000-8000-000000000006';
    private array $history = [];
    private function client(array $replies, bool $oauth = true, string|\Closure $credential = 'native-token'): SalesClient
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler($replies));
        $stack->push(Middleware::history($this->history));
        $http = new Client(['handler' => $stack]);
        $sdk = $oauth ? FactuareaClient::createOAuth($credential, 'https://sdk.invalid/v1', $http)
            : Factuarea::builder()->setServerUrl('https://sdk.invalid/v1')->setSecurity(new Security(bearerAuth: $credential))->setClient($http)->build();

        return FactuareaClient::crmSales($sdk);
    }
    private static function reply(array $data, int $status = 201, array $headers = []): Response
    {
        return new Response($status, ['Content-Type' => 'application/json', 'X-Request-Id' => 'req_native'] + $headers, json_encode(['data' => $data], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE));
    }
    private static function lead(): array
    {
        return ['id' => self::LEAD, 'version' => 1, 'name' => 'Àngela 山', 'notes' => null, 'status' => 'future_native_status'];
    }
    public function test_native_journey_uses_server_identities_decimal_strings_and_exact_owner_routes(): void
    {
        $client = $this->client([self::reply(self::lead()), self::reply(['id' => self::OPPORTUNITY, 'version' => 3, 'amount' => '9007199254740993.0001', 'currency' => 'EUR']),
            self::reply(['activity_id' => self::ACTIVITY, 'effect_id' => self::EFFECT, 'state' => 'confirmed', 'activity' => null])]);
        $lead = $client->dispatch($client->prepareLead(new CreateLeadRequest(name: 'Àngela 山', notes: null), 'lead-original'));
        $opportunity = $client->dispatch($client->prepareOpportunity(new CreateOpportunityRequest('Native', self::PIPELINE, self::STAGE, '9007199254740993.0001', 'EUR', leadId: $lead->id), 'opportunity-original'));
        $activity = $client->dispatch($client->prepareActivity(new CreateActivityRequest('call', 'Follow up', 'opportunity', $opportunity->id, 'Europe/Madrid', dueAt: '2026-10-11T09:00:00Z'), 'activity-original'));
        self::assertSame('9007199254740993.0001', $opportunity->field('amount'));
        self::assertSame(self::ACTIVITY, $activity->id);
        self::assertTrue($activity->confirmed);
        self::assertCount(3, $this->history);
        foreach (['/v1/crm/leads', '/v1/crm/opportunity', '/v1/crm/crmactivity'] as $i => $path) {
            $event = $this->history[$i];
            self::assertSame($path, $event['request']->getUri()->getPath());
            self::assertSame('POST', $event['request']->getMethod());
            self::assertSame('Bearer native-token', $event['request']->getHeaderLine('Authorization'));
            self::assertFalse($event['options']['allow_redirects']);
            self::assertFalse($event['request']->hasHeader('X-API-Key'));
            $wire = json_decode((string) $event['request']->getBody(), true, 64, JSON_THROW_ON_ERROR);
            foreach (['id', 'effect_id', 'company_id', 'actor_id', 'authorization_version'] as $forbidden) {
                self::assertArrayNotHasKey($forbidden, $wire);
            }
        }
        self::assertSame($lead->id, json_decode((string) $this->history[1]['request']->getBody(), true)['lead_id']);
        self::assertSame($opportunity->id, json_decode((string) $this->history[2]['request']->getBody(), true)['target']['id']);
    }
    public function test_omitted_patch_fields_remain_absent_and_explicit_null_is_not_dropped(): void
    {
        $request = new LeadPatchRequest(4, notes: null);
        self::assertSame(['expected_version' => 4, 'notes' => null], $request->toArray());
        $client = $this->client([self::reply(self::lead(), 200)]);
        $intent = $client->prepareLeadPatch(self::LEAD, $request, 'patch-original');
        $result = $client->dispatch($intent);
        self::assertNull($result->field('notes'));
        self::assertSame(Omitted::Value, $result->field('company_name'));
        self::assertSame('future_native_status', $result->field('status'));
        self::assertSame('PATCH', $this->history[0]['request']->getMethod());
        self::assertSame('{"expected_version":4,"notes":null}', (string) $this->history[0]['request']->getBody());
        $this->expectException(\LogicException::class);
        $client->recover($intent);
    }
    public function test_timeout_retains_first_wire_for_one_get_original_without_another_write(): void
    {
        $client = $this->client([new ConnectException('secret token and body', new Request('POST', 'https://sdk.invalid')), new Response(200, ['Content-Type' => 'application/json'], '{}')]);
        $intent = $client->prepareLead(new CreateLeadRequest(name: 'Àngela 山', notes: null), 'raw original key');
        try {
            $client->dispatch($intent);
            self::fail();
        } catch (WriteUnconfirmed $error) {
            self::assertTrue($error->unconfirmed);
            self::assertStringNotContainsString('secret', $error->getMessage());
            self::assertNull($error->getPrevious());
        }
        try {
            $client->recover($intent);
            self::fail();
        } catch (CrmFailure $error) {
            self::assertSame('invalid_native_original', $error->machineCode);
        }
        self::assertCount(2, $this->history);
        self::assertSame('GET', $this->history[1]['request']->getMethod());
        self::assertSame('/v1/crm/leads/create/original', $this->history[1]['request']->getUri()->getPath());
        self::assertSame('original_body_sha256='.hash('sha256', $intent->firstBody), $this->history[1]['request']->getUri()->getQuery());
        self::assertSame('raw original key', $this->history[1]['request']->getHeaderLine('Idempotency-Key'));
        self::assertSame('', (string) $this->history[1]['request']->getBody());
        self::assertSame($intent->firstBody, (string) $this->history[0]['request']->getBody());
        $this->expectException(\LogicException::class);
        $client->dispatch($intent);
    }
    public function test_confirmed_original_preserves_original_response_bytes_and_current_opaque_c7(): void
    {
        $body = ' {"data":{"id":"'.self::OPPORTUNITY.'","version":2,"amount":"12.3000","title":null}} ';
        $first = ' { "title": "Native", "amount": "12.3000", "contact_id": null } ';
        $original = new \Factuarea\Sdk\Custom\Crm\OAuthOriginals\OriginalIntent('opportunityCreate', 'original-key', $first);
        $wire = ['data' => ['state' => 'confirmed', 'original_body_sha256' => hash('sha256', $first),
            'original_response' => ['status' => 201, 'body' => $body]], 'meta' => ['authorization_version' => 'opaque-current-C7']];
        $client = $this->client([new Response(200, ['Content-Type' => 'application/json'], json_encode($wire, JSON_THROW_ON_ERROR))]);
        $receipt = $client->recoverOriginal($original);
        self::assertSame($body, $receipt->originalBody);
        self::assertSame('opaque-current-C7', $receipt->authorizationVersion);
        self::assertSame('12.3000', SalesResult::decode('opportunity', $receipt->originalBody, $receipt->originalStatus, null)->field('amount'));
        self::assertCount(1, $this->history);
        self::assertSame('GET', $this->history[0]['request']->getMethod());
        self::assertSame('/v1/crm/opportunity/create/original', $this->history[0]['request']->getUri()->getPath());
        self::assertSame('original_body_sha256='.hash('sha256', $first), $this->history[0]['request']->getUri()->getQuery());
    }
    public function test_typed_original_read_after_lost_response_uses_exact_first_body_hash(): void
    {
        $first = '{"name":"Native","source":"manual"}';
        $receipt = ['data' => ['state' => 'confirmed', 'original_body_sha256' => hash('sha256', $first),
            'original_response' => ['status' => 201, 'body' => json_encode(['data' => self::lead()], JSON_THROW_ON_ERROR)]], 'meta' => ['authorization_version' => 'opaque_current']];
        $client = $this->client([new Response(503), new Response(200, ['Content-Type' => 'application/json'], json_encode($receipt, JSON_THROW_ON_ERROR))]);
        $intent = $client->prepareLead(new CreateLeadRequest(name: 'Native'), 'key');
        self::assertSame($first, $intent->firstBody);
        try {
            $client->dispatch($intent);
            self::fail();
        } catch (WriteUnconfirmed) {
        }
        $result = $client->recover($intent);
        self::assertSame('confirmed', $result->state);
        self::assertSame('opaque_current', $result->authorizationVersion);
        self::assertCount(2, $this->history);
        self::assertSame('POST', $this->history[0]['request']->getMethod());
        self::assertSame('GET', $this->history[1]['request']->getMethod());
    }
    public static function errors(): array
    {
        return [[422, ValidationFailure::class, 'parameter_invalid', 'field_invalid'], [409, ConflictFailure::class, 'resource_conflict', 'version_conflict'],
            [403, PermissionFailure::class, 'crm_access_denied', 'scope'], [429, RateLimitFailure::class, 'rate_limit_exceeded', 'too_many_requests'],
            [409, WriteUnconfirmed::class, 'crm_effect_unconfirmed', 'crm_original_unconfirmed'], [503, WriteUnconfirmed::class, 'dependency_unavailable', 'provider_unavailable']];
    }
    #[DataProvider('errors')]
    public function test_errors_are_typed_redacted_and_single_dispatch(int $status, string $type, string $code, string $subcode): void
    {
        $client = $this->client([new Response($status, ['Content-Type' => 'application/json', 'X-Request-Id' => 'req_current', 'Retry-After' => '17'],
            json_encode(['error' => ['type' => 'native', 'code' => $code, 'subcode' => $subcode, 'param' => 'notes', 'request_id' => 'req_body', 'message' => 'private PII and secret bearer token']], JSON_THROW_ON_ERROR)), self::reply(self::lead())]);
        $intent = $client->prepareLead(new CreateLeadRequest(name: 'Native'), 'key');
        try {
            $client->dispatch($intent);
            self::fail();
        } catch (CrmFailure $error) {
            self::assertInstanceOf($type, $error);
            self::assertSame($code, $error->machineCode);
            self::assertSame($subcode, $error->subcode);
            self::assertSame('req_current', $error->requestId);
            self::assertSame('17', $error->retryAfter);
            self::assertSame('notes', $error->param);
            self::assertStringNotContainsString('private', $error->getMessage());
            self::assertNull($error->getPrevious());
            self::assertCount(1, $this->history);
        }
        $this->expectException(\LogicException::class);
        $client->dispatch($intent);
    }
    public function test_error_envelope_request_id_is_used_when_response_header_is_absent(): void
    {
        $client = $this->client([new Response(422, ['Content-Type' => 'application/json'], json_encode([
            'error' => ['code' => 'parameter_invalid', 'request_id' => 'req_body_fallback'],
        ], JSON_THROW_ON_ERROR))]);

        try {
            $client->dispatch($client->prepareLead(new CreateLeadRequest(name: 'Native'), 'key'));
            self::fail();
        } catch (ValidationFailure $error) {
            self::assertSame('req_body_fallback', $error->requestId);
            self::assertCount(1, $this->history);
        }
    }
    public function test_current_revocation_after_dispatch_retains_uncertainty_without_retry(): void
    {
        $client = $this->client([new Response(403, ['Content-Type' => 'application/json'], json_encode(['error' => ['code' => 'crm_context_revoked', 'subcode' => 'revoked']], JSON_THROW_ON_ERROR))]);
        try {
            $client->dispatch($client->prepareLead(new CreateLeadRequest(name: 'Native'), 'key'));
            self::fail();
        } catch (PermissionFailure $error) {
            self::assertTrue($error->unconfirmed);
            self::assertCount(1, $this->history);
        }
    }
    public function test_server_unconfirmed_activity_does_not_advance_journey(): void
    {
        $client = $this->client([self::reply(['state' => 'pending', 'activity_id' => self::ACTIVITY, 'effect_id' => self::EFFECT, 'activity' => null])]);
        $this->expectException(WriteUnconfirmed::class);
        $client->dispatch($client->prepareActivity(new CreateActivityRequest('note', 'Native', 'lead', self::LEAD, 'UTC'), 'key'));
    }
    public function test_malformed_success_is_unconfirmed_and_not_retried(): void
    {
        $client = $this->client([self::reply(['id' => self::OPPORTUNITY, 'version' => 2, 'amount' => 12.3]), self::reply(self::lead())]);
        try {
            $client->dispatch($client->prepareOpportunity(new CreateOpportunityRequest('Native', self::PIPELINE, self::STAGE, '12.3000', 'EUR', leadId: self::LEAD), 'key'));
            self::fail();
        } catch (WriteUnconfirmed $error) {
            self::assertSame('invalid_native_response', $error->machineCode);
            self::assertCount(1, $this->history);
        }
    }
    public function test_masked_lead_confirmation_retains_identity_without_fabricating_version(): void
    {
        $client = $this->client([self::reply(['id' => self::LEAD, 'confirmed' => true, 'representation_available' => false])]);
        $result = $client->dispatch($client->prepareLead(new CreateLeadRequest(phone: '+34600000000'), 'key'));
        self::assertNull($result->version);
        self::assertSame(Omitted::Value, $result->field('name'));
        self::assertTrue($result->confirmed);
    }
    public function test_native_list_traverses_all_pages_without_email_matching_or_pii_logging(): void
    {
        $client = $this->client([self::reply(['data' => [self::lead()], 'has_more' => true, 'next_cursor' => 'opaque+cursor'], 200),
            self::reply(['data' => [['id' => self::OPPORTUNITY, 'version' => 1]], 'has_more' => false, 'next_cursor' => null], 200)]);
        $all = iterator_to_array($client->leads(2, 10));
        self::assertCount(2, $all);
        self::assertCount(2, $this->history);
        self::assertSame('limit=10', $this->history[0]['request']->getUri()->getQuery());
        self::assertSame('limit=10&cursor=opaque%2Bcursor', $this->history[1]['request']->getUri()->getQuery());
        foreach ($this->history as $event) {
            self::assertSame('GET', $event['request']->getMethod());
            self::assertFalse($event['request']->hasHeader('Idempotency-Key'));
        }
    }
    public function test_repeated_cursor_is_a_failure_instead_of_silent_first_page_completion(): void
    {
        $page = self::reply(['data' => [], 'has_more' => true, 'next_cursor' => 'repeat'], 200);
        $client = $this->client([$page, $page]);
        try {
            iterator_to_array($client->leads(5));
            self::fail();
        } catch (CrmFailure $error) {
            self::assertSame('pagination_conflict', $error->machineCode);
            self::assertCount(2, $this->history);
        }
    }
    public function test_explicit_page_budget_exhaustion_is_visible(): void
    {
        $client = $this->client([self::reply(['data' => [], 'has_more' => true, 'next_cursor' => 'next'], 200)]);
        try {
            iterator_to_array($client->leads(1));
            self::fail();
        } catch (CrmFailure $error) {
            self::assertSame('pagination_budget_exhausted', $error->machineCode);
            self::assertCount(1, $this->history);
        }
    }
    public function test_original_intent_cannot_move_to_another_client_or_credential(): void
    {
        $one = $this->client([self::reply(self::lead())], false, 'fact_test_company_a');
        $intent = $one->prepareLead(new CreateLeadRequest(name: 'Native'), 'key');
        $two = $this->client([self::reply(self::lead())], false, 'fact_test_company_b');
        try {
            $two->dispatch($intent);
            self::fail();
        } catch (\LogicException) {
            self::assertCount(0, $this->history);
        }
    }
    public function test_explicit_oauth_refresh_only_changes_next_original_get_credential(): void
    {
        $token = 'original-native-token';
        $first = '{"name":"Native","source":"manual"}';
        $client = $this->client([new Response(503), new Response(200, ['Content-Type' => 'application/json'], json_encode(['data' => ['state' => 'missing', 'original_body_sha256' => hash('sha256', $first), 'original_response' => null],
            'meta' => ['authorization_version' => 'current-C7']], JSON_THROW_ON_ERROR))], true, static function () use (&$token): string {
                return $token;
            });
        $intent = $client->prepareLead(new CreateLeadRequest(name: 'Native'), 'key');
        try {
            $client->dispatch($intent);
        } catch (WriteUnconfirmed) {
        }
        $token = 'actual-native-descendant';
        self::assertSame('missing', $client->recover($intent)->state);
        self::assertSame('Bearer original-native-token', $this->history[0]['request']->getHeaderLine('Authorization'));
        self::assertSame('Bearer actual-native-descendant', $this->history[1]['request']->getHeaderLine('Authorization'));
        self::assertCount(2, $this->history);
    }
    public function test_server_website_example_persists_the_original_before_one_write_without_exposing_credentials(): void
    {
        $journal = [];
        $client = $this->client([function ($request) use (&$journal): Response {
            self::assertCount(1, $journal);
            self::assertSame('leadCreate', $journal[0][0]);
            self::assertSame((string) $request->getBody(), $journal[0][2]);

            return self::reply(self::lead());
        }]);
        $result = \Factuarea\Sdk\Examples\SalesBatch81\submitWebsiteLead($client, ['name' => 'Native', 'email' => null], 'website-original',
            static function (string $step, string $key, string $wire) use (&$journal): void {
                $journal[] = [$step, $key, $wire];
            });
        self::assertSame(self::LEAD, $result->id);
        self::assertCount(1, $this->history);
        self::assertSame('{"name":"Native","email":null,"source":"manual"}', $journal[0][2]);
        $source = file_get_contents(__DIR__.'/../../../../examples/CrmWebsite.php');
        self::assertStringContainsString("getenv('FACTUAREA_API_KEY')", $source);
        self::assertStringNotContainsString('echo ', $source);
        self::assertStringNotContainsString('error_log(', $source);
    }
    public function test_composable_journey_example_journals_three_first_wires_and_stops_on_scope_rejection(): void
    {
        $journal = [];
        $client = $this->client([self::reply(self::lead()), new Response(403, ['Content-Type' => 'application/json'],
            json_encode(['error' => ['code' => 'crm_access_denied', 'subcode' => 'scope']], JSON_THROW_ON_ERROR)), self::reply(self::lead())]);
        try {
            \Factuarea\Sdk\Examples\SalesBatch81\journey($client, new CreateLeadRequest(name: 'Native'), self::PIPELINE, self::STAGE, '12.3000', 'EUR',
                'Native opportunity', 'Follow up', 'UTC', ['leadCreate' => 'first-lead', 'opportunityCreate' => 'first-opportunity', 'activityCreate' => 'first-activity'],
                static function (string $step, string $key, string $wire) use (&$journal): void {
                    $journal[] = [$step, $key, $wire];
                });
            self::fail();
        } catch (PermissionFailure) {
            self::assertCount(2, $journal);
            self::assertCount(2, $this->history);
            self::assertSame(['leadCreate', 'opportunityCreate'], array_column($journal, 0));
            self::assertSame(self::LEAD, json_decode($journal[1][2], true)['lead_id']);
            self::assertSame('12.3000', json_decode($journal[1][2], true)['amount']);
        }
    }
    public function test_composable_journey_example_compiles_and_returns_all_three_canonical_results(): void
    {
        $client = $this->client([self::reply(self::lead()), self::reply(['id' => self::OPPORTUNITY, 'version' => 2, 'amount' => '12.3000']),
            self::reply(['activity_id' => self::ACTIVITY, 'effect_id' => self::EFFECT, 'state' => 'confirmed', 'activity' => null])]);
        $journal = [];
        $results = \Factuarea\Sdk\Examples\SalesBatch81\journey($client, new CreateLeadRequest(name: 'Native'), self::PIPELINE, self::STAGE, '12.3000', 'EUR',
            'Native opportunity', 'Follow up', 'UTC', ['leadCreate' => 'first-lead', 'opportunityCreate' => 'first-opportunity', 'activityCreate' => 'first-activity'],
            static function (string $step, string $key, string $wire) use (&$journal): void {
                $journal[] = [$step, $key, $wire];
            });
        self::assertSame(['lead', 'opportunity', 'activity'], array_keys($results));
        self::assertCount(3, $journal);
        self::assertCount(3, $this->history);
        self::assertSame(self::ACTIVITY, $results['activity']->id);
    }
    public static function recoveryStates(): array
    {
        return [['pending'], ['in_progress'], ['unconfirmed'], ['missing']];
    }
    #[DataProvider('recoveryStates')]
    public function test_durable_nonconfirmed_original_never_announces_success_or_emits_a_write(string $state): void
    {
        $first = ' { "name":"Native", "notes":null } ';
        $client = $this->client([new Response(200, ['Content-Type' => 'application/json'], json_encode(['data' => ['state' => $state,
            'original_body_sha256' => hash('sha256', $first), 'original_response' => null], 'meta' => ['authorization_version' => 'opaque_current']], JSON_THROW_ON_ERROR))]);
        $original = new \Factuarea\Sdk\Custom\Crm\OAuthOriginals\OriginalIntent('leadCreate', 'raw key', $first);
        $result = $client->recoverOriginal($original);
        self::assertSame($state, $result->state);
        self::assertNull($result->originalBody);
        self::assertNull($result->originalStatus);
        self::assertCount(1, $this->history);
        self::assertSame('GET', $this->history[0]['request']->getMethod());
    }
    public function test_wrong_body_hash_in_original_fails_closed_after_one_read(): void
    {
        $client = $this->client([new Response(200, ['Content-Type' => 'application/json'], json_encode(['data' => ['state' => 'missing',
            'original_body_sha256' => str_repeat('f', 64), 'original_response' => null], 'meta' => ['authorization_version' => 'opaque_current']], JSON_THROW_ON_ERROR))]);
        try {
            $client->recoverOriginal(new \Factuarea\Sdk\Custom\Crm\OAuthOriginals\OriginalIntent('leadCreate', 'key', '{}'));
            self::fail();
        } catch (CrmFailure $error) {
            self::assertSame('invalid_native_original', $error->machineCode);
            self::assertCount(1, $this->history);
        }
    }
    public function test_native_rfc_email_validation_remains_server_owned_without_filter_var_coercion(): void
    {
        $email = 'Àngela@example.com';
        $client = $this->client([self::reply(self::lead()), new Response(422, ['Content-Type' => 'application/json'],
            json_encode(['error' => ['code' => 'parameter_invalid', 'subcode' => 'email_invalid', 'param' => 'email']], JSON_THROW_ON_ERROR))]);
        $lead = $client->prepareLead(new CreateLeadRequest(email: $email), 'email-create');
        $client->dispatch($lead);
        self::assertSame($email, json_decode((string) $this->history[0]['request']->getBody(), true)['email']);
        try {
            $client->dispatch($client->prepareLeadPatch(self::LEAD, new LeadPatchRequest(1, email: $email), 'email-patch'));
            self::fail();
        } catch (ValidationFailure $error) {
            self::assertSame('email', $error->param);
            self::assertCount(2, $this->history);
        }
    }
    public function test_weak_php_callers_cannot_coerce_float_money_before_dto_or_first_journey_write(): void
    {
        $client = $this->client([self::reply(self::lead())]);
        $journal = [];
        try {
            // Internal reflection invocation reproduces weak caller scalar coercion even in this strict test file.
            (new \ReflectionClass(CreateOpportunityRequest::class))->newInstanceArgs(['Native', self::PIPELINE, self::STAGE, 1.2, 'EUR', self::LEAD]);
            self::fail();
        } catch (\InvalidArgumentException) {
            self::assertCount(0, $this->history);
        }
        try {
            (new \ReflectionFunction('Factuarea\\Sdk\\Examples\\SalesBatch81\\journey'))->invokeArgs([
                $client, new CreateLeadRequest(name: 'Native'), self::PIPELINE, self::STAGE, 1.2, 'EUR', 'Native opportunity', 'Follow up', 'UTC',
                ['leadCreate' => 'first-lead', 'opportunityCreate' => 'first-opportunity', 'activityCreate' => 'first-activity'],
                static function (string $step, string $key, string $wire) use (&$journal): void {
                    $journal[] = [$step, $key, $wire];
                },
            ]);
            self::fail();
        } catch (\InvalidArgumentException) {
            self::assertCount(0, $journal);
            self::assertCount(0, $this->history);
        }
    }
    public static function invalidInputs(): array
    {
        return [
            ['empty-lead', static fn () => new CreateLeadRequest(notes: 'only notes')],
            ['float', static fn () => new CreateOpportunityRequest('Native', self::PIPELINE, self::STAGE, 1.2, 'EUR', leadId: self::LEAD)],
            ['numeric-uuid', static fn () => new CreateOpportunityRequest('Native', '12', self::STAGE, '1.0000', 'EUR', leadId: self::LEAD)],
            ['missing-target', static fn () => new CreateOpportunityRequest('Native', self::PIPELINE, self::STAGE, '1.0000', 'EUR')],
            ['cas-zero', static fn () => new LeadPatchRequest(0, notes: null)],
            ['timezone', static fn () => new CreateActivityRequest('note', 'Native', 'lead', self::LEAD, 'unknown')],
            ['invalid-date', static fn () => new CreateActivityRequest('note', 'Native', 'lead', self::LEAD, 'UTC', dueAt: '2026-02-30T09:00:00Z')],
        ];
    }
    #[DataProvider('invalidInputs')]
    public function test_invalid_native_input_fails_before_http(string $case, \Closure $construct): void
    {
        try {
            $construct();
            self::fail($case);
        } catch (\InvalidArgumentException|\TypeError) {
            self::assertTrue(true);
        }
    }
}
