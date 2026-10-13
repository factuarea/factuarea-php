<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm;
use Factuarea\Sdk\Custom\FactuareaClient;
use Factuarea\Sdk\Custom\Crm\OAuthOriginals\CredentialContext;
use Factuarea\Sdk\Custom\Crm\OAuthOriginals\OriginalIntent;
use Factuarea\Sdk\Custom\Crm\OAuthOriginals\OriginalReceipt;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Models\Components\Security;
use Factuarea\Sdk\Models\Errors\APIException;
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
use UnexpectedValueException;
use LogicException;
use Factuarea\Sdk\Custom\Crm\OAuthOriginals\OAuthBearerClient;

// The frozen vendor classmap predates this Source delta; normal package install regenerates it.
foreach (['CredentialContext', 'OriginalIntent', 'OriginalReceipt', 'Transport', 'OAuthBearerClient'] as $file) {
    require_once __DIR__.'/../../../src/Custom/Crm/OAuthOriginals/'.$file.'.php';
}
final class OAuthOriginalsTest extends TestCase
{
    private const UUID = '01900000-0000-7000-8000-000000000001';
    private const FIRST = " \n{\"amount\":\"999999999999999999.99\",\"name\":\"Àngela 山\",\"optional\":null}\n";
    private const ORIGINAL = " { \"data\": { \"amount\": \"999999999999999999.99\", \"optional\": null } }\n";
    private array $history = [];
    private function client(array $responses): Client
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));

        return new Client(['handler' => $stack]);
    }
    private function receipt(string $state = 'confirmed', ?string $digest = null): Response
    {
        return new Response(200, ['Content-Type' => 'application/json'], json_encode(['data' => ['state' => $state,
            'original_body_sha256' => $digest ?? hash('sha256', self::FIRST),
            'original_response' => $state === 'confirmed' ? ['status' => 201, 'body' => self::ORIGINAL] : null],
            'meta' => ['authorization_version' => 'current-native-revision']], JSON_THROW_ON_ERROR));
    }
    public static function routes(): array
    {
        return [['leadCreate', '/v1/crm/leads/create/original', null], ['opportunityCreate', '/v1/crm/opportunity/create/original', null],
            ['opportunityStage', '/v1/crm/opportunity/'.self::UUID.'/stage/original', self::UUID], ['activityCreate', '/v1/crm/crmactivity/create/original', null]];
    }
    #[DataProvider('routes')]
    public function test_four_gets_preserve_exact_first_wire_body_raw_key_and_original_response(string $operation, string $path, ?string $opportunity): void
    {
        $sdk = FactuareaClient::createOAuth('native-access-token', 'https://sdk.invalid/v1', $this->client([$this->receipt()]));
        $intent = new OriginalIntent($operation, 'raw original key', self::FIRST, $opportunity);
        $receipt = FactuareaClient::oauthOriginals($sdk)->recover($intent);
        self::assertSame('confirmed', $receipt->state);
        self::assertSame(self::ORIGINAL, $receipt->originalBody);
        self::assertSame(self::FIRST, $intent->firstBody);
        self::assertSame(201, $receipt->originalStatus);
        self::assertCount(1, $this->history);
        $request = $this->history[0]['request'];
        self::assertSame('GET', $request->getMethod());
        self::assertSame('', (string) $request->getBody());
        self::assertSame($path, $request->getUri()->getPath());
        self::assertSame('original_body_sha256='.hash('sha256', self::FIRST), $request->getUri()->getQuery());
        self::assertSame('raw original key', $request->getHeaderLine('Idempotency-Key'));
        self::assertSame('Bearer native-access-token', $request->getHeaderLine('Authorization'));
        self::assertFalse($request->hasHeader('X-API-Key'));
        self::assertFalse($this->history[0]['options']['allow_redirects']);
    }
    public function test_credential_context_uses_real_oauth_route_and_native_scopes(): void
    {
        $sdk = FactuareaClient::createOAuth('native-access-token', 'https://sdk.invalid/v1', $this->client([new Response(200, ['Content-Type' => 'application/json'], json_encode(['data' => [
            'company_id' => self::UUID, 'credential_id' => self::UUID, 'client_id' => self::UUID, 'environment' => 'test',
            'scopes' => [CredentialContext::FINE_SCOPE, 'contacts:delete', 'invoices:send', 'recurring_invoices:transition', '*'], 'authorization_version' => 'native-current']], JSON_THROW_ON_ERROR))]));
        $context = FactuareaClient::oauthOriginals($sdk)->credentialContext();
        self::assertSame('test', $context->environment);
        self::assertSame([CredentialContext::FINE_SCOPE, 'contacts:delete', 'invoices:send', 'recurring_invoices:transition', '*'], $context->scopes);
        self::assertSame('crm_access_context.show', CredentialContext::OPERATION);
        self::assertSame('crm_access_context.read', CredentialContext::OAUTH_SCOPE);
        self::assertSame('/v1/crm/credential-context', $this->history[0]['request']->getUri()->getPath());
    }
    public function test_refresh_provider_supplies_current_descendant_without_replaying_any_write(): void
    {
        $token = 'issued-parent';
        $sdk = FactuareaClient::createOAuth(static function () use (&$token): string {
            return $token;
        }, 'https://sdk.invalid/v1', $this->client([$this->receipt('missing'), $this->receipt()]));
        $transport = FactuareaClient::oauthOriginals($sdk);
        $intent = new OriginalIntent('leadCreate', 'original-key', self::FIRST);
        self::assertSame('missing', $transport->recover($intent)->state);
        $token = 'authentic-descendant';
        self::assertSame('confirmed', $transport->recover($intent)->state);
        self::assertCount(2, $this->history);
        self::assertSame('Bearer issued-parent', $this->history[0]['request']->getHeaderLine('Authorization'));
        self::assertSame('Bearer authentic-descendant', $this->history[1]['request']->getHeaderLine('Authorization'));
        foreach ($this->history as $event) {
            self::assertSame('GET', $event['request']->getMethod());
        }
    }
    public function test_native_api_key_singleton_recovery_remains_distinct_from_oauth_context(): void
    {
        $sdk = Factuarea::builder()->setServerUrl('https://sdk.invalid/v1')->setSecurity(new Security(bearerAuth: 'fact_test_actual_key'))
            ->setClient($this->client([$this->receipt('missing')]))->build();
        $transport = FactuareaClient::oauthOriginals($sdk);
        self::assertSame('missing', $transport->recover(new OriginalIntent('leadCreate', 'key', self::FIRST))->state);
        self::assertSame('Bearer fact_test_actual_key', $this->history[0]['request']->getHeaderLine('Authorization'));
        $this->expectException(InvalidArgumentException::class);
        $transport->credentialContext();
    }
    public function test_oauth_factory_rejects_an_api_key_as_an_oauth_token(): void
    {
        $this->expectException(InvalidArgumentException::class);
        FactuareaClient::createOAuth('fact_test_actual_key');
    }
    public function test_failures_are_one_get_with_no_retry_or_mutation(): void
    {
        foreach ([new Response(401, ['Content-Type' => 'application/json'], '{}'), new Response(503, ['Content-Type' => 'application/json'], '{}'), new Response(307, ['Location' => 'https://other.invalid']), new ConnectException('network loss', new Request('GET', 'https://sdk.invalid'))] as $failure) {
            $sdk = FactuareaClient::createOAuth('native-token', 'https://sdk.invalid/v1', $this->client([$failure, $this->receipt()]));
            try {
                FactuareaClient::oauthOriginals($sdk)->recover(new OriginalIntent('leadCreate', 'key', self::FIRST));
                self::fail('The original read must fail.');
            } catch (APIException|ConnectException $error) {
                self::assertCount(1, $this->history);
                self::assertSame('GET', $this->history[0]['request']->getMethod());
            }
        }
    }
    public function test_nonconfirmed_states_are_returned_without_invented_confirmation(): void
    {
        foreach (['pending', 'in_progress', 'unconfirmed', 'missing'] as $state) {
            $sdk = FactuareaClient::createOAuth('native-token', 'https://sdk.invalid/v1', $this->client([$this->receipt($state)]));
            $receipt = FactuareaClient::oauthOriginals($sdk)->recover(new OriginalIntent('activityCreate', 'key', self::FIRST));
            self::assertSame($state, $receipt->state);
            self::assertNull($receipt->originalStatus);
            self::assertNull($receipt->originalBody);
            self::assertCount(1, $this->history);
        }
    }
    public function test_foreign_body_hash_is_rejected(): void
    {
        $sdk = FactuareaClient::createOAuth('native-token', 'https://sdk.invalid/v1', $this->client([$this->receipt('confirmed', str_repeat('f', 64))]));
        $this->expectException(UnexpectedValueException::class);
        FactuareaClient::oauthOriginals($sdk)->recover(new OriginalIntent('leadCreate', 'key', self::FIRST));
    }
    public function test_nonconfirmed_receipt_with_original_response_is_rejected(): void
    {
        $intent = new OriginalIntent('leadCreate', 'key', self::FIRST);
        $this->expectException(UnexpectedValueException::class);
        OriginalReceipt::fromWire(['data' => ['state' => 'missing', 'original_body_sha256' => $intent->originalBodySha256, 'original_response' => ['status' => 201, 'body' => '{}']], 'meta' => ['authorization_version' => 'v']], $intent);
    }
    public function test_dispatch_boundary_resolves_fresh_token_and_blocks_same_request_mutation_replay(): void
    {
        $token = 'issued-parent';
        $client = new OAuthBearerClient($this->client([new Response(503), $this->receipt()]), static function () use (&$token): Security {
            return new Security(bearerAuth: $token);
        });
        $request = new Request('POST', 'https://sdk.invalid/v1/crm/leads', ['Idempotency-Key' => 'first-key'], self::FIRST);
        self::assertSame(503, $client->send($request, ['http_errors' => false, 'headers' => ['Authorization' => 'Bearer stale-build-token']])->getStatusCode());
        $token = 'authentic-descendant';
        try {
            $client->send($request);
            self::fail('Repeated OAuth mutation dispatched.');
        } catch (LogicException) {
            self::assertCount(1, $this->history);
        }
        $client->send(new Request('GET', 'https://sdk.invalid/v1/crm/leads/create/original'));
        self::assertSame('Bearer issued-parent', $this->history[0]['request']->getHeaderLine('Authorization'));
        self::assertSame(self::FIRST, (string) $this->history[0]['request']->getBody());
        self::assertSame('Bearer authentic-descendant', $this->history[1]['request']->getHeaderLine('Authorization'));
        self::assertFalse($this->history[0]['options']['allow_redirects']);
    }
    public function test_normalized_key_cannot_silently_change_original_identity(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new OriginalIntent('leadCreate', ' key ', self::FIRST);
    }
    #[DataProvider('routes')]
    public function test_named_native_original_getters_preserve_exact_wire_hash_and_key(string $operation, string $path, ?string $opportunity): void
    {
        $sdk = FactuareaClient::createOAuth('native-access-token', 'https://sdk.invalid/v1', $this->client([$this->receipt()]));
        $transport = FactuareaClient::oauthOriginals($sdk);
        $receipt = match ($operation) {
            'leadCreate' => $transport->getLeadCreateOriginal('raw key', self::FIRST),
            'opportunityCreate' => $transport->getOpportunityCreateOriginal('raw key', self::FIRST),
            'opportunityStage' => $transport->getOpportunityStageOriginal($opportunity, 'raw key', self::FIRST),
            'activityCreate' => $transport->getActivityCreateOriginal('raw key', self::FIRST),
        };
        self::assertSame('confirmed', $receipt->state);
        self::assertCount(1, $this->history);
        self::assertSame('GET', $this->history[0]['request']->getMethod());
        self::assertSame($path, $this->history[0]['request']->getUri()->getPath());
        self::assertSame('original_body_sha256='.hash('sha256', self::FIRST), $this->history[0]['request']->getUri()->getQuery());
        self::assertSame('raw key', $this->history[0]['request']->getHeaderLine('Idempotency-Key'));
    }
}
