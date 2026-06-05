<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Idempotency;

use Factuarea\Sdk\Custom\Idempotency\IdempotencyHook;
use Factuarea\Sdk\Hooks\BeforeRequestContext;
use Factuarea\Sdk\Hooks\HookContext;
use Factuarea\Sdk\SDKConfiguration;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class IdempotencyHookTest extends TestCase
{
    private IdempotencyHook $hook;

    private BeforeRequestContext $context;

    protected function setUp(): void
    {
        $this->hook = new IdempotencyHook();
        $this->context = new BeforeRequestContext(new HookContext(
            new SDKConfiguration(),
            'https://api.factuarea.com/v1',
            'public-api.v1.invoices.create',
            null,
            static fn () => null,
        ));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function mutatingMethods(): array
    {
        return [
            'POST' => ['POST'],
            'PUT' => ['PUT'],
            'PATCH' => ['PATCH'],
            'DELETE' => ['DELETE'],
        ];
    }

    #[DataProvider('mutatingMethods')]
    public function test_adds_an_idempotency_key_to_mutating_requests(string $method): void
    {
        $request = new Request($method, 'https://api.factuarea.com/v1/invoices');

        $result = $this->hook->beforeRequest($this->context, $request);

        $this->assertTrue($result->hasHeader('Idempotency-Key'));
        $key = $result->getHeaderLine('Idempotency-Key');
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/',
            $key,
        );
    }

    public function test_does_not_add_a_key_to_get_requests(): void
    {
        $request = new Request('GET', 'https://api.factuarea.com/v1/invoices');

        $result = $this->hook->beforeRequest($this->context, $request);

        $this->assertFalse($result->hasHeader('Idempotency-Key'));
    }

    public function test_does_not_overwrite_a_caller_provided_key(): void
    {
        $request = (new Request('POST', 'https://api.factuarea.com/v1/invoices'))
            ->withHeader('Idempotency-Key', 'my-explicit-key');

        $result = $this->hook->beforeRequest($this->context, $request);

        $this->assertSame('my-explicit-key', $result->getHeaderLine('Idempotency-Key'));
    }

    public function test_generated_keys_are_unique(): void
    {
        $keys = [];
        for ($i = 0; $i < 100; $i++) {
            $keys[] = IdempotencyHook::uuidV4();
        }

        $this->assertCount(100, array_unique($keys));
    }

    public function test_generated_key_fits_the_api_length_bounds(): void
    {
        $key = IdempotencyHook::uuidV4();
        $this->assertGreaterThanOrEqual(1, strlen($key));
        $this->assertLessThanOrEqual(64, strlen($key));
    }
}
