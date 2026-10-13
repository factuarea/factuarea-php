<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\CrmMetaNativeBatch80;

use Factuarea\Sdk\Custom\CrmMetaNativeBatch80\Client as MetaClient;
use Factuarea\Sdk\Custom\CrmMetaNativeBatch80\Contract;
use Factuarea\Sdk\Custom\CrmMetaNativeBatch80\MetaException;
use Factuarea\Sdk\Custom\CrmMetaNativeBatch80\RequestIntent;
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

// Frozen optimized classmap predates only these new handwritten Source classes.
require_once __DIR__.'/../../../src/Custom/Crm/OAuthOriginals/OAuthBearerClient.php';
foreach (['Contract', 'RequestIntent', 'Response', 'MetaException', 'Client'] as $class) {
    require_once __DIR__.'/../../../src/Custom/CrmMetaNativeBatch80/'.$class.'.php';
}

final class ClientTest extends TestCase
{
    private const CONNECTION = '01900000-0000-7000-8000-000000000001';
    private const FORM = '01900000-0000-7000-8000-000000000002';
    private const CREDENTIAL = '01900000-0000-4000-8000-000000000003';
    private const PAGE = '90071992547409931234567890123456';
    private const STATE = '01900000-0000-7000-8000-000000000004';
    private array $history = [];
    private function client(array $replies, bool $oauth = true, ?\Closure $source = null): MetaClient
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler($replies));
        $stack->push(Middleware::history($this->history));
        $http = new Client(['handler' => $stack]);
        $sdk = $oauth ? FactuareaClient::createOAuth($source ?? 'actual-native-token', 'https://sdk.invalid/v1', $http) : Factuarea::builder()->setServerUrl('https://sdk.invalid/v1')->setSecurity(new Security(bearerAuth: 'fact_test_actual'))->setClient($http)->build();

        return FactuareaClient::meta($sdk);
    }
    private static function mapping(): array
    {
        return [(object) ['remote_name' => 'Àngela 山', 'target' => 'name', 'type' => 'text', 'field_version' => null, 'required' => true, 'options' => new \stdClass()]];
    }
    private static function inspect(): array
    {
        return ['credential_uuid' => self::CREDENTIAL, 'remote_form_id' => '12345678901234567890123456789012'];
    }
    private static function publicationInput(): array
    {
        return self::inspect() + ['mapping' => self::mapping(), 'expected_mapping_version' => null, 'expected_publication_version' => PHP_INT_MAX - 1, 'fields_snapshot_checksum' => str_repeat('a', 64), 'confirmed' => true];
    }
    private static function publication(): array
    {
        return ['status' => 'mapping_published', 'connection_uuid' => self::CONNECTION, 'credential_uuid' => self::CREDENTIAL, 'form_uuid' => self::FORM, 'page_id' => self::PAGE, 'remote_form_id' => self::inspect()['remote_form_id'], 'mapping_version' => 4, 'publication_version' => PHP_INT_MAX - 1, 'fields_snapshot_checksum' => str_repeat('a', 64), 'mapping' => self::mapping()];
    }
    private static function inspection(): array
    {
        return ['status' => 'mapping_inspected', 'connection_uuid' => self::CONNECTION, 'credential_uuid' => self::CREDENTIAL, 'form_uuid' => self::FORM, 'page_id' => self::PAGE, 'remote_form_id' => self::inspect()['remote_form_id'], 'app_id' => '123', 'responsible_uuid' => self::CREDENTIAL, 'remote_field_names' => ['Àngela 山'], 'publication_version' => 9, 'authorization_version' => 2, 'configuration_version' => 3, 'fields_snapshot_checksum' => str_repeat('a', 64), 'expected_mapping_version' => null, 'expected_consent_version' => null, 'destination' => ['basic_fields' => ['name', 'notes'], 'required_basic_fields' => ['name'], 'allowed_field_ids' => [self::FORM], 'required_field_ids' => [], 'fields_snapshot' => (object) ['z' => [(object) ['version' => 7]], 'a' => (object) ['required' => true]]], 'app_review' => ['available' => false, 'reason' => 'app_review_evidence_unavailable']];
    }
    private static function reply(mixed $wire, int $status = 200, array $headers = []): Response
    {
        return new Response($status, ['Content-Type' => 'application/json', 'X-Request-Id' => 'native-request'] + $headers, json_encode($wire, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
    public function test_seven_native_routes_stay_independent_with_real_unavailable_review(): void
    {
        $sdk = $this->client([
            self::reply(['authorization_url' => 'https://www.facebook.com/dialog/oauth', 'state' => self::STATE]),
            self::reply(['status' => 'credentials_installed', 'connection_uuid' => self::CONNECTION, 'credential_uuid' => self::CREDENTIAL, 'page_id' => self::PAGE, 'scopes' => ['ads_management', 'leads_retrieval', 'pages_manage_ads', 'pages_manage_metadata', 'pages_read_engagement', 'pages_show_list']]),
            self::reply(['data' => ['options' => [['connection_uuid' => self::CONNECTION, 'credential_uuid' => self::CREDENTIAL, 'page_id' => self::PAGE, 'page_name' => null]]]]),
            self::reply(['data' => self::publication()]), self::reply(['data' => self::inspection()]), self::reply(['data' => self::publication()]), self::reply(['error' => 'meta_oauth_unavailable'], 503),
        ]);
        self::assertSame(self::STATE, $sdk->prepareCrmMetaOAuth(['page_id' => self::PAGE, 'confirmed' => true])->data->state);
        self::assertCount(1, $this->history);
        self::assertSame(['ads_management', 'leads_retrieval', 'pages_manage_ads', 'pages_manage_metadata', 'pages_read_engagement', 'pages_show_list'], $sdk->completeCrmMetaOAuth(['state' => self::STATE, 'code' => 'actual-human-code'])->data->scopes);
        $sdk->listCrmMetaConnectionOptions();
        $sdk->getCrmMetaMapping(self::CONNECTION, self::FORM);
        $inspect = $sdk->inspectCrmMetaMapping(self::CONNECTION, self::FORM, self::inspect());
        self::assertSame(['z', 'a'], array_keys(get_object_vars($inspect->data->data->destination->fields_snapshot)));
        self::assertFalse($inspect->data->data->app_review->available);
        self::assertNull($inspect->data->data->expected_mapping_version);
        self::assertSame((string) self::reply(['data' => self::inspection()])->getBody(), $inspect->rawBody);
        $sdk->publishCrmMetaMapping(self::CONNECTION, self::FORM, self::publicationInput());
        try {
            $sdk->publishCrmMetaConsent(self::CONNECTION, ['credential_uuid' => self::CREDENTIAL, 'grants' => [(object) ['stream' => self::FORM, 'actions' => ['read', 'ingest']]], 'expected_consent_version' => null, 'confirmed' => true]);
            self::fail();
        } catch (MetaException $e) {
            self::assertSame(503, $e->status);
            self::assertSame('unavailable', $e->reason);
            self::assertFalse($e->unconfirmed);
        }
        self::assertCount(7, $this->history);
        $suffixes = ['/oauth/prepare', '/oauth/complete', '/options', '/'.self::CONNECTION.'/mappings/'.self::FORM, '/'.self::CONNECTION.'/mappings/'.self::FORM.'/inspect', '/'.self::CONNECTION.'/mappings/'.self::FORM, '/'.self::CONNECTION.'/consent'];
        foreach ($this->history as $i => $event) {
            $req = $event['request'];
            self::assertSame('/v1/crm/connections/meta-lead-ads'.$suffixes[$i], $req->getUri()->getPath());
            self::assertSame('', $req->getUri()->getQuery());
            self::assertSame('Bearer actual-native-token', $req->getHeaderLine('Authorization'));
            self::assertFalse($req->hasHeader('Idempotency-Key'));
            self::assertFalse($req->hasHeader('X-API-Key'));
            self::assertFalse($event['options']['allow_redirects']);
        }
        self::assertSame('', (string) $this->history[2]['request']->getBody());
        self::assertStringContainsString('"options":{}', (string) $this->history[5]['request']->getBody());
    }
    public function test_exact_first_wire_and_current_provider_are_retained_without_reconstruction(): void
    {
        $token = 'native-parent';
        $sdk = $this->client([self::reply(['authorization_url' => 'https://www.facebook.com/dialog/oauth', 'state' => self::STATE])], true, static function () use (&$token): string {
            return $token;
        });
        $raw = " \n{\"confirmed\":true,\"page_id\":\"".self::PAGE."\"}\n";
        $intent = new RequestIntent('prepareOAuth', $raw);
        $token = 'authentic-current-descendant';
        $sdk->execute($intent);
        self::assertSame($raw, (string) $this->history[0]['request']->getBody());
        self::assertSame('Bearer authentic-current-descendant', $this->history[0]['request']->getHeaderLine('Authorization'));
        self::assertSame($raw, $intent->firstWire);
        $this->expectException(\LogicException::class);
        $sdk->execute($intent);
    }
    public static function rejections(): array
    {
        return [[401, 'permission_missing'], [409, 'mapping_conflict'], [429, 'rate_limited'], [503, 'unconfirmed'], [307, 'unavailable']];
    }
    #[DataProvider('rejections')]
    public function test_native_errors_never_retry_or_leak_secret(int $status, string $reason): void
    {
        $sdk = $this->client([self::reply(['error' => 'meta_oauth_'.$reason, 'provider_body' => 'secret-code-and-state'], $status, ['Retry-After' => '7', 'Location' => 'https://foreign.invalid'])]);
        try {
            $sdk->completeCrmMetaOAuth(['state' => self::STATE, 'code' => 'secret-code-and-state']);
            self::fail();
        } catch (MetaException $e) {
            self::assertSame($status, $e->status);
            self::assertSame($reason, $e->reason);
            self::assertSame(7, $e->retryAfter);
            self::assertSame('native-request', $e->requestId);
            self::assertStringNotContainsString('secret-code-and-state', $e->getMessage());
            self::assertNull($e->getPrevious());
        } self::assertCount(1, $this->history);
    }
    public function test_network_unknown_completion_and_inspect_query_are_distinct_and_single_dispatch(): void
    {
        foreach (['completeOAuth', 'inspect'] as $action) {
            $sdk = $this->client([new ConnectException('secret-code-and-state', new Request('POST', 'https://sdk.invalid'))]);
            try {
                $sdk->execute($action === 'completeOAuth' ? RequestIntent::fromBody($action, ['state' => self::STATE, 'code' => 'secret-code-and-state']) : RequestIntent::fromBody($action, self::inspect(), self::CONNECTION, self::FORM));
                self::fail();
            } catch (MetaException $e) {
                self::assertSame($action === 'completeOAuth' ? 'unconfirmed' : 'transport_failure', $e->reason);
                self::assertSame($action === 'completeOAuth', $e->unconfirmed);
                self::assertNull($e->getPrevious());
            } self::assertCount(1, $this->history);
        }
    }
    public function test_actual_api_key_stays_an_api_key_without_oauth_disguise(): void
    {
        $this->client([self::reply(['data' => ['options' => []]])], false)->listCrmMetaConnectionOptions();
        self::assertSame('Bearer fact_test_actual', $this->history[0]['request']->getHeaderLine('Authorization'));
        self::assertCount(1, $this->history);
    }
    public static function invalidInputs(): array
    {
        $p = self::publicationInput();

        return [[array_replace($p, ['confirmed' => false])], [array_replace($p, ['expected_mapping_version' => 0])], [array_replace($p, ['expected_publication_version' => PHP_INT_MAX])], [$p + ['company_id' => self::CONNECTION]], [array_replace($p, ['mapping' => [(object) array_replace((array) self::mapping()[0], ['options' => []])]])], [array_replace($p, ['mapping' => [self::mapping()[0], self::mapping()[0]]])]];
    }
    #[DataProvider('invalidInputs')]
    public function test_closed_inputs_reject_fake_cas_context_or_array_options_before_http(array $body): void
    {
        $sdk = $this->client([]);
        try {
            $sdk->publishCrmMetaMapping(self::CONNECTION, self::FORM, $body);
            self::fail();
        } catch (\InvalidArgumentException) {
            self::assertCount(0, $this->history);
        }
    }
    public function test_foreign_bindings_or_invented_app_review_are_rejected_as_query_failures(): void
    {
        foreach ([array_replace(self::inspection(), ['credential_uuid' => self::CONNECTION]), array_replace(self::inspection(), ['form_uuid' => self::CONNECTION]), array_replace(self::inspection(), ['app_review' => ['available' => true, 'reason' => 'approved']])] as $bad) {
            $sdk = $this->client([self::reply(['data' => $bad])]);
            try {
                $sdk->inspectCrmMetaMapping(self::CONNECTION, self::FORM, self::inspect());
                self::fail();
            } catch (MetaException $e) {
                self::assertSame('invalid_native_response', $e->reason);
                self::assertFalse($e->unconfirmed);
            } self::assertCount(1, $this->history);
        }
    }
    public function test_contract_has_actual_operation_ids_scopes_and_query_carriers(): void
    {
        self::assertSame(['prepareCrmMetaOAuth', 'completeCrmMetaOAuth', 'listCrmMetaConnectionOptions', 'getCrmMetaMapping', 'inspectCrmMetaMapping', 'publishCrmMetaMapping', 'publishCrmMetaConsent'], array_column(Contract::ROUTES, 2));
        self::assertSame('crm_connections.configure', Contract::OPERATION);
        self::assertSame('crm_connections:write', Contract::FINE_SCOPE);
        self::assertSame('crm_connections.write', Contract::OAUTH_SCOPE);
        self::assertFalse(Contract::ROUTES['inspect'][3]);
    }
    public function test_native_byte_limit_and_actual_meta_permission_union_are_required(): void
    {
        $sdk = $this->client([self::reply(['status' => 'credentials_installed', 'connection_uuid' => self::CONNECTION, 'credential_uuid' => self::CREDENTIAL, 'page_id' => self::PAGE, 'scopes' => ['leads_retrieval']])]);
        try {
            $sdk->completeCrmMetaOAuth(['state' => self::STATE, 'code' => str_repeat('é', 4097)]);
            self::fail();
        } catch (\InvalidArgumentException) {
            self::assertCount(0, $this->history);
        }
        try {
            $sdk->completeCrmMetaOAuth(['state' => self::STATE, 'code' => 'actual-code']);
            self::fail();
        } catch (MetaException $e) {
            self::assertSame('invalid_native_response', $e->reason);
            self::assertTrue($e->unconfirmed);
        } self::assertCount(1, $this->history);
    }
    public function test_actual_c7_codes_are_preserved_and_unknown_provider_strings_are_closed(): void
    {
        foreach (['crm_access_denied' => 'crm_access_denied', 'secret_code_and_state' => 'native_rejection'] as $code => $reason) {
            $sdk = $this->client([self::reply(['error' => ['code' => $code, 'message' => 'secret_code_and_state']], 403)]);
            try {
                $sdk->listCrmMetaConnectionOptions();
                self::fail();
            } catch (MetaException $e) {
                self::assertSame(403, $e->status);
                self::assertSame($reason, $e->reason);
                self::assertStringNotContainsString('secret_code_and_state', $e->getMessage());
            } self::assertCount(1, $this->history);
        }
    }
}
