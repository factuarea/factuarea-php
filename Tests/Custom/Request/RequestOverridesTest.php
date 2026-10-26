<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Request;

use Factuarea\Sdk\Custom\Idempotency\IdempotencyClient;
use Factuarea\Sdk\Custom\Request\RequestOverrides;
use Factuarea\Sdk\Custom\Request\RequestOverridesClient;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Models\Components\Security;
use Factuarea\Sdk\Models\Components\UpdateProjectV1Request;
use Factuarea\Sdk\Models\Components\UpdateSeriesRequest;
use Factuarea\Sdk\Models\Operations\PublicApiV1ProjectsUpdateRequest;
use Factuarea\Sdk\Models\Operations\PublicApiV1SeriesUpdateRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

final class RequestOverridesTest extends TestCase
{
    /** Company axis of the v1 contract: the company `id` travels in the URL. */
    private const COMPANY = '019e0000-0000-7000-8000-0000000000c0';

    /** @var list<array{request: RequestInterface}> */
    private array $history = [];

    private function sdk(Response ...$responses): Factuarea
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));
        $client = new RequestOverridesClient(new IdempotencyClient(new Client(['handler' => $stack])));

        return Factuarea::builder()->setSecurity(new Security(bearerAuth: 'fact_test_x'))->setClient($client)->build();
    }

    private function body(int $index = 0): string
    {
        return (string) $this->history[$index]['request']->getBody();
    }

    private function updateSeries(Factuarea $sdk, UpdateSeriesRequest $body): void
    {
        try {
            $sdk->series->publicApiV1SeriesUpdate(new PublicApiV1SeriesUpdateRequest(company: self::COMPANY, series: 's1', body: $body));
        } catch (\Throwable) {
            // The response is irrelevant: only the outgoing request is measured.
        }
    }

    public function test_without_a_scope_the_request_is_untouched(): void
    {
        $sdk = $this->sdk(new Response(200, ['Content-Type' => 'application/json'], '{}'));
        $this->updateSeries($sdk, new UpdateSeriesRequest(name: 'B2B'));

        $this->assertSame('{"name":"B2B"}', $this->body());
    }

    public function test_clears_only_the_named_fields_and_keeps_assigned_ones(): void
    {
        $sdk = $this->sdk(new Response(200, ['Content-Type' => 'application/json'], '{}'));
        RequestOverrides::run(
            fn () => $this->updateSeries($sdk, new UpdateSeriesRequest(name: 'B2B')),
            clear: ['code'],
        );

        $this->assertSame('{"name":"B2B","code":null}', $this->body());
        $this->assertSame('application/json', $this->history[0]['request']->getHeaderLine('Content-Type'));
    }

    public function test_an_empty_body_still_carries_the_cleared_field(): void
    {
        $sdk = $this->sdk(new Response(200, ['Content-Type' => 'application/json'], '{}'));
        RequestOverrides::run(
            fn () => $this->updateSeries($sdk, new UpdateSeriesRequest()),
            clear: ['name'],
        );

        $this->assertSame('{"name":null}', $this->body());
    }

    public function test_supports_nested_paths(): void
    {
        $sdk = $this->sdk(new Response(200, ['Content-Type' => 'application/json'], '{}'));
        RequestOverrides::run(function () use ($sdk): void {
            try {
                $sdk->projects->publicApiV1ProjectsUpdate(
                    new PublicApiV1ProjectsUpdateRequest(company: self::COMPANY, project: 'p1', body: new UpdateProjectV1Request(name: 'P')),
                );
            } catch (\Throwable) {
            }
        }, clear: ['business.logo_id']);

        $this->assertSame('{"name":"P","business":{"logo_id":null}}', $this->body());
    }

    public function test_refuses_to_clear_a_field_that_also_has_a_value(): void
    {
        $sdk = $this->sdk(new Response(200, ['Content-Type' => 'application/json'], '{}'));

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('already sends a value');
        RequestOverrides::run(
            fn () => $sdk->series->publicApiV1SeriesUpdate(
                new PublicApiV1SeriesUpdateRequest(company: self::COMPANY, series: 's1', body: new UpdateSeriesRequest(name: 'B2B')),
            ),
            clear: ['name'],
        );
    }

    public function test_adds_headers_and_replaces_same_named_ones(): void
    {
        $sdk = $this->sdk(new Response(200, ['Content-Type' => 'application/json'], '{}'));
        RequestOverrides::run(
            fn () => $this->updateSeries($sdk, new UpdateSeriesRequest(name: 'B2B')),
            headers: ['If-Match' => '"abc"', 'idempotency-key' => 'fixed-key'],
        );

        $request = $this->history[0]['request'];
        $this->assertSame('"abc"', $request->getHeaderLine('If-Match'));
        $this->assertSame('fixed-key', $request->getHeaderLine('Idempotency-Key'));
    }

    public function test_the_scope_ends_with_the_callback_even_when_it_throws(): void
    {
        try {
            RequestOverrides::run(static fn () => throw new \RuntimeException('boom'), clear: ['name']);
        } catch (\RuntimeException) {
        }
        $this->assertNull(RequestOverrides::current());

        $sdk = $this->sdk(new Response(200, ['Content-Type' => 'application/json'], '{}'));
        $this->updateSeries($sdk, new UpdateSeriesRequest(name: 'B2B'));
        $this->assertSame('{"name":"B2B"}', $this->body());
    }

    public function test_applies_to_every_retry_of_the_call(): void
    {
        $sdk = $this->sdk(
            new Response(500, ['Content-Type' => 'application/json', 'Retry-After-Ms' => '0'], '{}'),
            new Response(200, ['Content-Type' => 'application/json'], '{}'),
        );
        RequestOverrides::run(
            fn () => $this->updateSeries($sdk, new UpdateSeriesRequest(name: 'B2B')),
            clear: ['code'],
        );

        $this->assertCount(2, $this->history);
        $this->assertSame($this->body(0), $this->body(1));
        $this->assertSame('{"name":"B2B","code":null}', $this->body(1));
    }

    public function test_rejects_invalid_paths(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        RequestOverrides::run(static fn () => null, clear: ['a..b']);
    }
}
