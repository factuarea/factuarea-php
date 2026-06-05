<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Version;

use Factuarea\Sdk\Custom\Version\FactuareaVersionHook;
use Factuarea\Sdk\Hooks\BeforeRequestContext;
use Factuarea\Sdk\Hooks\HookContext;
use Factuarea\Sdk\SDKConfiguration;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class FactuareaVersionHookTest extends TestCase
{
    private BeforeRequestContext $context;

    protected function setUp(): void
    {
        $this->context = new BeforeRequestContext(new HookContext(
            new SDKConfiguration(),
            'https://api.factuarea.com/v1',
            'public-api.v1.invoices.list',
            null,
            static fn () => null,
        ));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function methods(): array
    {
        return [
            'GET' => ['GET'],
            'POST' => ['POST'],
            'PUT' => ['PUT'],
            'PATCH' => ['PATCH'],
            'DELETE' => ['DELETE'],
        ];
    }

    #[DataProvider('methods')]
    public function test_pins_the_factuarea_version_header_on_every_method(string $method): void
    {
        $hook = new FactuareaVersionHook();
        $request = new Request($method, 'https://api.factuarea.com/v1/invoices');

        $result = $hook->beforeRequest($this->context, $request);

        $this->assertSame(
            FactuareaVersionHook::DEFAULT_VERSION,
            $result->getHeaderLine('Factuarea-Version'),
        );
    }

    public function test_default_version_is_the_frozen_spec_date(): void
    {
        $this->assertSame('2026-06-04', FactuareaVersionHook::DEFAULT_VERSION);
        $this->assertMatchesRegularExpression(
            '/^\d{4}-\d{2}-\d{2}$/',
            FactuareaVersionHook::DEFAULT_VERSION,
        );
    }

    public function test_does_not_overwrite_a_caller_provided_version(): void
    {
        $hook = new FactuareaVersionHook();
        $request = (new Request('GET', 'https://api.factuarea.com/v1/invoices'))
            ->withHeader('Factuarea-Version', '2099-01-01');

        $result = $hook->beforeRequest($this->context, $request);

        $this->assertSame('2099-01-01', $result->getHeaderLine('Factuarea-Version'));
    }

    public function test_honours_a_custom_pinned_version(): void
    {
        $hook = new FactuareaVersionHook('2026-12-31');
        $request = new Request('POST', 'https://api.factuarea.com/v1/invoices');

        $result = $hook->beforeRequest($this->context, $request);

        $this->assertSame('2026-12-31', $result->getHeaderLine('Factuarea-Version'));
    }
}
