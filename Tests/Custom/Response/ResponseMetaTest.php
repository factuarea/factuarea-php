<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Response;

use Factuarea\Sdk\Custom\Request\RequestOverrides;
use Factuarea\Sdk\Custom\Request\RequestOverridesClient;
use Factuarea\Sdk\Custom\Response\ResponseMeta;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Models\Components\Security;
use Factuarea\Sdk\Models\Errors\APIException;
use Factuarea\Sdk\Models\Operations\PublicApiV1InvoicesPdfRequest;
use Factuarea\Sdk\Utils\Retry\RetryConfigNone;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * How the generated SDK surfaces the binary PDF operation and the statuses the
 * pinned spec does not declare for it (202 JSON, 503 with Retry-After), and how
 * {@see ResponseMeta} reads them. Measured against
 * `GET /companies/{company}/invoices/{invoice}/pdf`.
 */
final class ResponseMetaTest extends TestCase
{
    /** Company axis of the v1 contract: the company `id` travels in the URL. */
    private const COMPANY = '019e0000-0000-7000-8000-0000000000c0';

    /** @var list<array{request: RequestInterface}> */
    private array $history = [];

    private function sdk(Response ...$responses): Factuarea
    {
        return $this->sdkWith(null, ...$responses);
    }

    private function sdkWith(?RetryConfigNone $retry, Response ...$responses): Factuarea
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));

        $builder = Factuarea::builder()
            ->setSecurity(new Security(bearerAuth: 'fact_test_x'))
            ->setClient(new RequestOverridesClient(new Client(['handler' => $stack])));
        if ($retry !== null) {
            $builder->setRetryConfig($retry);
        }

        return $builder->build();
    }

    private function pdf(Factuarea $sdk): mixed
    {
        return $sdk->invoices->publicApiV1InvoicesPdf(new PublicApiV1InvoicesPdfRequest(company: self::COMPANY, invoice: 'inv-1'));
    }

    public function test_binary_200_exposes_bytes_and_etag(): void
    {
        $sdk = $this->sdk(new Response(200, ['Content-Type' => 'application/pdf', 'ETag' => '"v1"'], '%PDF-1.7 fake'));

        $response = $this->pdf($sdk);

        $this->assertSame('%PDF-1.7 fake', $response->bytes);
        $meta = ResponseMeta::fromResponse($response->rawResponse);
        $this->assertSame(200, $meta->statusCode);
        $this->assertSame('"v1"', $meta->etag);
        $this->assertNull($meta->retryAfter);
    }

    public function test_304_without_body_is_a_typed_response_and_keeps_the_etag(): void
    {
        $sdk = $this->sdk(new Response(304, ['ETag' => '"v1"']));

        $response = $this->pdf($sdk);

        $this->assertSame(304, $response->statusCode);
        $this->assertNull($response->bytes);
        $this->assertSame('"v1"', ResponseMeta::fromResponse($response->rawResponse)->etag);
    }

    public function test_202_json_not_declared_by_the_spec_reaches_the_caller_as_api_exception_with_its_body(): void
    {
        $sdk = $this->sdk(new Response(202, ['Content-Type' => 'application/json', 'Retry-After' => '3'], '{"status":"queued"}'));

        try {
            $this->pdf($sdk);
            $this->fail('Expected an APIException for the undeclared 202.');
        } catch (APIException $e) {
            $this->assertSame('Unknown status code received', $e->getMessage());
            $meta = ResponseMeta::fromException($e);
            $this->assertSame(202, $meta->statusCode);
            $this->assertSame(3, $meta->retryAfter);
            $this->assertSame(['status' => 'queued'], $meta->json());
        }
    }

    public function test_503_with_retry_after_surfaces_when_retries_are_disabled_for_the_call(): void
    {
        // A per-call Options::setRetryConfig() is ignored by the generated code (its
        // `else` branch rebuilds the default backoff), so retries are disabled at SDK level.
        $sdk = $this->sdkWith(new RetryConfigNone(), new Response(503, ['Content-Type' => 'application/json', 'Retry-After' => '30'], '{"error":{"code":"pdf_unavailable"}}'));

        try {
            $this->pdf($sdk);
            $this->fail('Expected an APIException for the 503.');
        } catch (APIException $e) {
            $meta = ResponseMeta::fromException($e);
            $this->assertSame(503, $meta->statusCode);
            $this->assertSame(30, $meta->retryAfter);
            $this->assertSame('pdf_unavailable', $meta->json()['error']['code']);
        }
        $this->assertCount(1, $this->history);
    }

    public function test_sends_if_match_and_if_none_match_headers_the_spec_does_not_declare(): void
    {
        $sdk = $this->sdk(new Response(304, ['ETag' => '"v2"']));

        RequestOverrides::run(fn () => $this->pdf($sdk), headers: ['If-Match' => '"v2"']);

        $this->assertSame('"v2"', $this->history[0]['request']->getHeaderLine('If-Match'));
    }

    public function test_retry_after_accepts_http_dates_and_ignores_garbage(): void
    {
        $date = gmdate('D, d M Y H:i:s \G\M\T', time() + 120);
        $meta = ResponseMeta::fromResponse(new Response(503, ['Retry-After' => $date]));
        $this->assertGreaterThan(100, $meta->retryAfter);
        $this->assertLessThanOrEqual(120, $meta->retryAfter);

        $this->assertNull(ResponseMeta::fromResponse(new Response(503, ['Retry-After' => 'soon']))->retryAfter);
        $this->assertNull(ResponseMeta::fromResponse(new Response(503))->retryAfter);
    }

    public function test_json_is_null_for_empty_or_invalid_bodies(): void
    {
        $this->assertNull((new ResponseMeta(304, null, null, ''))->json());
        $this->assertNull((new ResponseMeta(502, null, null, '<html>'))->json());
    }
}
