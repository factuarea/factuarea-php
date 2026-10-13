<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm;
use Factuarea\Sdk\Custom\FactuareaClient;
use Factuarea\Sdk\Custom\Crm\WhatsApp\Transport;
use Factuarea\Sdk\Custom\Crm\WhatsApp\SendUnconfirmed;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyClient;
use Factuarea\Sdk\Custom\Version\FactuareaVersionHook;
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
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
foreach (['Preparation', 'SendIntent', 'DeliveryReceipt', 'OriginalReceipt', 'SendUnconfirmed', 'Transport'] as $file) {
    require_once __DIR__.'/../../../src/Custom/Crm/WhatsApp/'.$file.'.php';
}
require_once __DIR__.'/../../../src/Custom/Crm/OAuthOriginals/OAuthBearerClient.php';
final class WhatsAppTest extends TestCase
{
    private const C = '01900000-0000-7000-8000-000000000001';
    private const M = '01900000-0000-7000-8000-000000000002';
    private const E = '01900000-0000-7000-8000-000000000003';
    private array $history = [];
    private function p(): array
    {
        return ['conversation_id' => self::C, 'message_id' => self::M, 'effect_id' => self::E, 'expected_version' => 3, 'message_version' => 4, 'purpose' => 'contact_request', 'sender' => ['channel' => 'whatsapp', 'phone' => '+34611111111', 'name' => null], 'recipient' => ['channel' => 'whatsapp', 'phone' => '+34622222222', 'name' => 'Àngela 山', 'kind' => 'to'], 'body' => 'Hola 山', 'template' => null, 'preparation_token' => 'sealed-server-token', 'snapshot_digest' => str_repeat('a', 64), 'authorization_version' => 'current-C7'];
    }
    private function response(mixed $data, int $status = 200): Response
    {
        return new Response($status, ['Content-Type' => 'application/json', 'X-Request-Id' => 'native-request'], json_encode(['data' => $data], JSON_THROW_ON_ERROR));
    }
    private function client(array $responses): array
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));
        $http = new Client(['handler' => $stack]);
        $sdk = Factuarea::builder()->setClient(new IdempotencyClient($http))->setSecurity(new Security(bearerAuth: 'fact_test_actual'))->setServerUrl('https://native.invalid/v1')->build();
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new FactuareaVersionHook());

        return [$sdk, FactuareaClient::whatsapp($sdk), $http];
    }
    private function receipt(string $state = 'confirmed'): array
    {
        return ['status' => $state, 'provider_message_id' => $state === 'confirmed' ? 'wamid-native' : null, 'submitted_at' => $state === 'confirmed' ? '2026-10-10T11:00:00Z' : null, 'reason_code' => $state === 'confirmed' ? null : 'native_reason'];
    }
    public function test_exact_native_prepare_send_original_preserve_wire_without_generated_key_or_retry(): void
    {
        $p = $this->p();
        $raw = " \n".json_encode(['preparation_token' => $p['preparation_token'], 'template' => null, 'purpose' => 'contact_request', 'message_version' => 4, 'expected_version' => 3, 'effect_id' => self::E, 'conversation_id' => self::C], JSON_THROW_ON_ERROR)."\n";
        $r = $this->receipt();
        [$sdk,$transport] = $this->client([$this->response($p), $this->response($r), $this->response(['state' => 'confirmed', 'conversation_id' => self::C, 'message_id' => self::M, 'effect_id' => self::E, 'original_wire_hash' => hash('sha256', $raw), 'preparation_digest' => $p['snapshot_digest'], 'receipt' => $r])]);
        $intent = $transport->prepareSend($transport->prepareCrmWhatsAppMessage(self::C, self::M, 'contact_request'), $raw);
        self::assertSame($raw, $intent->firstWire);
        self::assertSame('confirmed', $transport->sendCrmWhatsAppMessage($intent)->status);
        self::assertSame('confirmed', $transport->getCrmWhatsAppMessageOriginal($intent)->data['state']);
        self::assertCount(3, $this->history);
        self::assertSame(['GET', 'POST', 'GET'], array_map(fn ($x) => $x['request']->getMethod(), $this->history));
        self::assertSame($raw, (string) $this->history[1]['request']->getBody());
        parse_str($this->history[2]['request']->getUri()->getQuery(), $query);
        self::assertSame(['original_wire' => $raw], $query);
        foreach ($this->history as $h) {
            self::assertSame('Bearer fact_test_actual', $h['request']->getHeaderLine('Authorization'));
            self::assertFalse($h['request']->hasHeader('Idempotency-Key'));
            self::assertFalse($h['request']->hasHeader('X-API-Key'));
            self::assertFalse($h['options']['allow_redirects']);
        }
        $this->expectException(\LogicException::class);
        $transport->sendCrmWhatsAppMessage($intent);
    }
    public static function unknowns(): array
    {
        return [['network'], ['503'], ['pending']];
    }
    #[DataProvider('unknowns')]
    public function test_unconfirmed_effect_is_retained_without_replay(string $kind): void
    {
        $failure = $kind === 'network' ? new ConnectException('timeout', new Request('POST', 'https://native.invalid')) : ($kind === '503' ? $this->response([], 503) : $this->response($this->receipt('pending')));
        [$sdk,$transport] = $this->client([$this->response($this->p()), $failure]);
        $intent = $transport->prepareSend($transport->prepareCrmWhatsAppMessage(self::C, self::M, 'contact_request'));
        try {
            $transport->sendCrmWhatsAppMessage($intent);
            self::fail('Expected unconfirmed.');
        } catch (SendUnconfirmed $e) {
            self::assertSame($intent, $e->intent);
        }
        self::assertCount(2, $this->history);
        try {
            $transport->sendCrmWhatsAppMessage($intent);
            self::fail('Repeated send.');
        } catch (\LogicException) {
        } self::assertCount(2, $this->history);
        $this->expectException(\LogicException::class);
        $transport->prepareCrmWhatsAppMessage(self::C, self::M, 'contact_request');
    }
    public static function rejects(): array
    {
        return [[401], [403], [409], [422], [429]];
    }
    #[DataProvider('rejects')]
    public function test_http_rejection_keeps_status_and_never_retries(int $status): void
    {
        [$sdk,$transport] = $this->client([$this->response($this->p()), $this->response([], $status)]);
        $intent = $transport->prepareSend($transport->prepareCrmWhatsAppMessage(self::C, self::M, 'contact_request'));
        try {
            $transport->sendCrmWhatsAppMessage($intent);
            self::fail('Expected rejection.');
        } catch (APIException $e) {
            self::assertSame($status, $e->statusCode);
        }self::assertCount(2, $this->history);
    }
    public function test_current_api_key_change_and_oauth_unavailable_fail_before_http(): void
    {
        [$sdk,$transport,$http] = $this->client([$this->response($this->p())]);
        $intent = $transport->prepareSend($transport->prepareCrmWhatsAppMessage(self::C, self::M, 'contact_request'));
        $sdk->sdkConfiguration->securitySource = fn () => new Security(bearerAuth: 'fact_test_other');
        try {
            $transport->sendCrmWhatsAppMessage($intent);
            self::fail('Credential changed.');
        } catch (\InvalidArgumentException) {
        }self::assertCount(1, $this->history);
        $oauth = FactuareaClient::createOAuth('native-oauth', 'https://native.invalid/v1', $http);
        $this->expectException(\InvalidArgumentException::class);
        FactuareaClient::whatsapp($oauth)->prepareCrmWhatsAppMessage(self::C, self::M, 'contact_request');
    }
    public function test_revalidation_keeps_same_token_effect_and_rejects_mutated_preparation(): void
    {
        [$sdk,$transport] = $this->client([$this->response($this->p()), $this->response($this->p()), $this->response(array_replace($this->p(), ['effect_id' => self::M]))]);
        $p = $transport->prepareCrmWhatsAppMessage(self::C, self::M, 'contact_request');
        $transport->prepareCrmWhatsAppMessage(self::C, self::M, 'contact_request', previous: $p);
        parse_str($this->history[1]['request']->getUri()->getQuery(), $query);
        self::assertSame($p->data['preparation_token'], $query['preparation_token']);
        $this->expectException(\InvalidArgumentException::class);
        $transport->prepareCrmWhatsAppMessage(self::C, self::M, 'contact_request', previous: $p);
    }
    public static function states(): array
    {
        return [['not_found'], ['unconfirmed'], ['failed']];
    }
    #[DataProvider('states')]
    public function test_factual_original_states_only_issue_get(string $state): void
    {
        $p = $this->p();
        $wire = json_encode(array_intersect_key($p, array_flip(['conversation_id', 'effect_id', 'expected_version', 'message_version', 'purpose', 'template', 'preparation_token'])), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        [$sdk,$transport] = $this->client([$this->response($p), $this->response(['state' => $state, 'conversation_id' => self::C, 'message_id' => self::M, 'effect_id' => self::E, 'original_wire_hash' => hash('sha256', $wire), 'preparation_digest' => $p['snapshot_digest'], 'receipt' => $state === 'not_found' ? null : $this->receipt($state)])]);
        $intent = $transport->prepareSend($transport->prepareCrmWhatsAppMessage(self::C, self::M, 'contact_request'));
        self::assertSame($state, $transport->getCrmWhatsAppMessageOriginal($intent)->data['state']);
        self::assertSame(['GET', 'GET'], array_map(fn ($x) => $x['request']->getMethod(), $this->history));
    }
    public function test_native_short_e164_and_long_template_name_preserve_exact_selection(): void
    {
        $template = ['id' => '123', 'name' => str_repeat('a', 512), 'language' => 'es_ES', 'category' => 'UTILITY', 'parameters' => ['Hola 山']];
        $p = $this->p();
        $p['sender']['phone'] = '+123456';
        $p['template'] = $template;
        [$sdk,$transport] = $this->client([$this->response($p)]);
        $prepared = $transport->prepareCrmWhatsAppMessage(self::C, self::M, 'contact_request', $template);
        self::assertSame($template, $prepared->data['template']);
        parse_str($this->history[0]['request']->getUri()->getQuery(), $query);
        self::assertSame($template, json_decode($query['template'], true, 32, JSON_THROW_ON_ERROR));
        self::assertSame($template, json_decode($transport->prepareSend($prepared)->firstWire, true, 32, JSON_THROW_ON_ERROR)['template']);
    }
    public function test_durable_original_restore_only_permits_get_even_before_saved_dispatch(): void
    {
        [$sdk,$transport] = $this->client([$this->response($this->p())]);
        $intent = $transport->prepareSend($transport->prepareCrmWhatsAppMessage(self::C, self::M, 'contact_request'));
        $checkpoint = json_decode(json_encode($transport->exportIntent($intent), JSON_THROW_ON_ERROR), true, 64, JSON_THROW_ON_ERROR);
        self::assertFalse($checkpoint['dispatched']);
        [$restarted,$reader] = $this->client([$this->response(['state' => 'not_found', 'conversation_id' => self::C, 'message_id' => self::M, 'effect_id' => self::E, 'original_wire_hash' => $intent->originalWireHash, 'preparation_digest' => $this->p()['snapshot_digest'], 'receipt' => null])]);
        $restored = $reader->restoreOriginal($checkpoint);
        self::assertSame('not_found', $reader->getCrmWhatsAppMessageOriginal($restored)->data['state']);
        try {
            $reader->sendCrmWhatsAppMessage($restored);
            self::fail('Restored SEND allowed.');
        } catch (\LogicException) {
        } self::assertCount(1, $this->history);
        self::assertSame('GET', $this->history[0]['request']->getMethod());
        $restarted->sdkConfiguration->securitySource = fn () => new Security(bearerAuth: 'fact_test_other');
        $this->expectException(\InvalidArgumentException::class);
        $reader->restoreOriginal($checkpoint);
    }
}
