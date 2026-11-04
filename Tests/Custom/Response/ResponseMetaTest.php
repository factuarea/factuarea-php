<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Response;

use Factuarea\Sdk\Custom\Request\RequestOverrides;
use Factuarea\Sdk\Custom\Request\RequestOverridesClient;
use Factuarea\Sdk\Custom\Response\ResponseMeta;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Models\Components\Security;
use Factuarea\Sdk\Models\Errors\APIException;
use Factuarea\Sdk\Models\Errors\ErrorThrowable;
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
 * How the generated SDK surfaces the binary PDF operation and its other statuses
 * (202 JSON while it renders, 503 with Retry-After), and how {@see ResponseMeta}
 * reads them. Measured against `GET /companies/{company}/invoices/{invoice}/pdf`;
 * a status an operation does not declare, against
 * `GET /companies/{company}/invoices/{invoice}`.
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

    public function test_202_json_is_a_typed_response_whose_raw_response_keeps_retry_after(): void
    {
        $body = ['data' => ['id' => '0199aaaa-0000-7000-8000-000000000001', 'object' => 'invoice_pdf', 'status' => 'pending', 'pdf_url' => 'https://api.factuarea.com/v1/x',
            'pdf_output_state' => ['state' => 'queued', 'format' => 'a4', 'variant' => 'official', 'retry_after' => 3]]];
        $sdk = $this->sdk(new Response(202, ['Content-Type' => 'application/json', 'Retry-After' => '3'], (string) json_encode($body)));

        $response = $this->pdf($sdk);

        $this->assertSame(202, $response->statusCode);
        $this->assertNull($response->bytes);
        $meta = ResponseMeta::fromResponse($response->rawResponse);
        $this->assertSame(202, $meta->statusCode);
        $this->assertSame(3, $meta->retryAfter);
        $this->assertSame('queued', $meta->json()['data']['pdf_output_state']['state']);
    }

    public function test_a_status_the_operation_does_not_declare_reaches_the_caller_as_api_exception_with_its_body(): void
    {
        $sdk = $this->sdk(new Response(202, ['Content-Type' => 'application/json', 'Retry-After' => '3'], '{"status":"queued"}'));

        try {
            $sdk->invoices->publicApiV1InvoicesShow(self::COMPANY, 'inv-1');
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
        $error = '{"error":{"type":"service_unavailable_error","code":"pdf_artifact_not_ready","message":"x"}}';
        $sdk = $this->sdkWith(new RetryConfigNone(), new Response(503, ['Content-Type' => 'application/json', 'Retry-After' => '30'], $error));

        try {
            $this->pdf($sdk);
            $this->fail('Expected a typed error for the 503.');
        } catch (ErrorThrowable $e) {
            $this->assertSame('pdf_artifact_not_ready', $e->container->error->code);
            $this->assertNotNull($e->container->rawResponse);
            $meta = ResponseMeta::fromResponse($e->container->rawResponse);
            $this->assertSame(503, $meta->statusCode);
            $this->assertSame(30, $meta->retryAfter);
            $this->assertSame('pdf_artifact_not_ready', $meta->json()['error']['code']);
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
