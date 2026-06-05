<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Client;

use Factuarea\Sdk\Custom\Idempotency\IdempotencyHook;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Models\Components\Security;
use Factuarea\Sdk\Models\Errors\ErrorThrowable;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * Verifies the SDK's HTTP-level runtime behaviour (auth, retries, idempotency,
 * typed errors) against a mocked Guzzle transport — no real network.
 *
 * The client is assembled exactly the way {@see \Factuarea\Sdk\Custom\FactuareaClient}
 * does (Bearer auth + IdempotencyHook), but with the Guzzle handler swapped for
 * a MockHandler so we can script responses and capture outgoing requests.
 */
final class HttpBehaviourTest extends TestCase
{
    /** @var list<array{request: RequestInterface}> */
    private array $history = [];

    private function sdkWith(MockHandler $mock, string $apiKey = 'fact_test_secret123'): Factuarea
    {
        $this->history = [];
        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($this->history));
        $guzzle = new Client(['handler' => $stack]);

        // Build the SDK the way FactuareaClient::create does (security wiring via the
        // generated builder + IdempotencyHook), but inject the mocked transport.
        $sdk = Factuarea::builder()
            ->setSecurity(new Security(bearerAuth: $apiKey))
            ->setClient($guzzle)
            ->build();
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new IdempotencyHook());

        return $sdk;
    }

    private function lastRequest(): RequestInterface
    {
        return $this->history[array_key_last($this->history)]['request'];
    }

    private function errorEnvelope(string $type, string $code, string $message, array $extra = []): string
    {
        return (string) json_encode(['error' => array_merge([
            'type' => $type,
            'code' => $code,
            'message' => $message,
            'request_id' => 'req_abc123',
        ], $extra)]);
    }

    public function test_sends_the_api_key_as_a_bearer_token(): void
    {
        $mock = new MockHandler([
            new Response(401, ['Content-Type' => 'application/json'], $this->errorEnvelope(
                'authentication_error',
                'missing_api_key',
                'Falta la clave de API.',
            )),
        ]);
        $sdk = $this->sdkWith($mock, 'fact_test_my_key');

        try {
            $sdk->account->publicApiV1AccountShow();
        } catch (\Throwable) {
            // expected — we only care about the outgoing Authorization header
        }

        $this->assertSame('Bearer fact_test_my_key', $this->lastRequest()->getHeaderLine('Authorization'));
    }

    public function test_maps_a_422_to_a_typed_error_with_request_id(): void
    {
        $mock = new MockHandler([
            new Response(422, ['Content-Type' => 'application/json'], $this->errorEnvelope(
                'invalid_request_error',
                'parameter_invalid',
                'El campo client_id es obligatorio.',
                ['param' => 'client_id'],
            )),
        ]);
        $sdk = $this->sdkWith($mock);

        try {
            $sdk->invoices->publicApiV1InvoicesBulkDelete(
                new \Factuarea\Sdk\Models\Components\BulkDeleteInvoicesV1Request(ids: ['inv_1']),
            );
            $this->fail('Expected a typed ErrorThrowable.');
        } catch (ErrorThrowable $e) {
            $error = $e->container->error;
            $this->assertSame('parameter_invalid', $error->code);
            $this->assertSame('client_id', $error->param);
            $this->assertSame('req_abc123', $error->requestId);
            $this->assertSame('invalid_request_error', $error->type->value);
        }
    }

    public function test_typed_error_message_does_not_leak_the_api_key(): void
    {
        $mock = new MockHandler([
            new Response(403, ['Content-Type' => 'application/json'], $this->errorEnvelope(
                'permission_error',
                'insufficient_scope',
                'Permisos insuficientes.',
            )),
        ]);
        $sdk = $this->sdkWith($mock, 'fact_live_super_secret_key_value');

        try {
            $sdk->invoices->publicApiV1InvoicesBulkDelete(
                new \Factuarea\Sdk\Models\Components\BulkDeleteInvoicesV1Request(ids: ['inv_1']),
            );
            $this->fail('Expected a typed ErrorThrowable.');
        } catch (ErrorThrowable $e) {
            $this->assertStringNotContainsString('fact_live_super_secret_key_value', $e->getMessage());
            $this->assertStringNotContainsString('fact_live_super_secret_key_value', (string) $e);
        }
    }

    public function test_retries_on_500_then_succeeds_and_reuses_the_same_idempotency_key(): void
    {
        $mock = new MockHandler([
            new Response(500, ['Content-Type' => 'application/json'], $this->errorEnvelope(
                'api_error',
                'internal_error',
                'Error interno.',
            )),
            new Response(500, ['Content-Type' => 'application/json'], $this->errorEnvelope(
                'api_error',
                'internal_error',
                'Error interno.',
            )),
            // Third attempt: a typed 422 so we stop without needing a full success body.
            new Response(422, ['Content-Type' => 'application/json'], $this->errorEnvelope(
                'invalid_request_error',
                'parameter_invalid',
                'stop here',
            )),
        ]);
        $sdk = $this->sdkWith($mock);

        try {
            $sdk->invoices->publicApiV1InvoicesBulkDelete(
                new \Factuarea\Sdk\Models\Components\BulkDeleteInvoicesV1Request(ids: ['inv_1']),
            );
        } catch (ErrorThrowable) {
            // expected on the final attempt
        }

        $this->assertCount(3, $this->history, 'Expected two retries after the initial attempt.');

        $keys = array_map(
            static fn (array $entry): string => $entry['request']->getHeaderLine('Idempotency-Key'),
            $this->history,
        );
        $this->assertNotSame('', $keys[0], 'A mutating request must carry an Idempotency-Key.');
        $this->assertSame($keys[0], $keys[1], 'Retries must reuse the same Idempotency-Key.');
        $this->assertSame($keys[1], $keys[2], 'Retries must reuse the same Idempotency-Key.');
    }

    public function test_does_not_retry_on_a_422(): void
    {
        $mock = new MockHandler([
            new Response(422, ['Content-Type' => 'application/json'], $this->errorEnvelope(
                'invalid_request_error',
                'parameter_invalid',
                'no retry',
            )),
        ]);
        $sdk = $this->sdkWith($mock);

        try {
            $sdk->invoices->publicApiV1InvoicesBulkDelete(
                new \Factuarea\Sdk\Models\Components\BulkDeleteInvoicesV1Request(ids: ['inv_1']),
            );
        } catch (ErrorThrowable) {
            // expected
        }

        $this->assertCount(1, $this->history, 'A 422 is a client error and must not be retried.');
    }
}
