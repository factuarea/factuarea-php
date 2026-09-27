<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\PurchaseScans;

use Factuarea\Sdk\Custom\Idempotency\IdempotencyClient;
use Factuarea\Sdk\Custom\Version\FactuareaVersionHook;
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
 * `purchase_scans.source` (`GET /purchase_scans/{purchase_scan}/source`) —
 * downloads the original file. The response has no `application/json` variant
 * on 200 (`anchors/contract.md` §"Las 13 operaciones": `application/pdf,
 * image/jpeg, image/png`), mirroring the existing binary-download precedent
 * `Gallery::publicApiV1ProductsGalleryDownload`, which exposes the body as raw
 * `bytes` on the response object.
 *
 * Reconciled against the real task 4.6 regeneration: the response exposes one
 * nullable bytes property PER content-type variant declared in the spec
 * (`twoHundredApplicationPdfBytes`, `twoHundredImageJpegBytes`,
 * `twoHundredImagePngBytes`), not a single `bytes` property as originally
 * hypothesized — Speakeasy names each 200 media-type branch individually
 * rather than collapsing them. The method name and single-path-parameter
 * signature matched the original hypothesis exactly.
 */
final class PurchaseScansSourceTest extends TestCase
{
    /** @var list<array{request: RequestInterface}> */
    private array $history = [];

    private function sdkWith(MockHandler $mock): Factuarea
    {
        $this->history = [];
        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($this->history));
        $guzzle = new IdempotencyClient(new Client(['handler' => $stack]));

        $sdk = Factuarea::builder()
            ->setSecurity(new Security(bearerAuth: 'fact_test_secret123'))
            ->setClient($guzzle)
            ->build();
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new FactuareaVersionHook());

        return $sdk;
    }

    private function lastRequest(): RequestInterface
    {
        return $this->history[array_key_last($this->history)]['request'];
    }

    private function errorEnvelope(string $type, string $code, string $message): string
    {
        return (string) json_encode(['error' => [
            'type' => $type,
            'code' => $code,
            'message' => $message,
            'request_id' => 'req_abc123',
        ]]);
    }

    public function test_exposes_the_original_pdf_bytes_unchanged(): void
    {
        $knownBytes = "%PDF-1.4\n% bytes conocidos del original\n%%EOF";
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/pdf'], $knownBytes),
        ]);
        $sdk = $this->sdkWith($mock);

        $response = $sdk->purchaseScans->publicApiV1PurchaseScansSource('019e0000-0000-7000-8000-000000000042');

        $this->assertSame('GET', $this->lastRequest()->getMethod());
        $this->assertStringEndsWith(
            '/purchase_scans/019e0000-0000-7000-8000-000000000042/source',
            (string) $this->lastRequest()->getUri(),
        );
        $this->assertSame(200, $response->statusCode);
        $this->assertSame($knownBytes, $response->twoHundredApplicationPdfBytes);
    }

    public function test_a_404_with_an_error_envelope_throws_a_typed_exception(): void
    {
        $mock = new MockHandler([
            new Response(404, ['Content-Type' => 'application/json'], $this->errorEnvelope(
                'invalid_request_error',
                'purchase_scan_not_found',
                'Escaneo de compra no encontrado.',
            )),
        ]);
        $sdk = $this->sdkWith($mock);

        try {
            $sdk->purchaseScans->publicApiV1PurchaseScansSource('019e0000-0000-7000-8000-000000000099');
            $this->fail('Expected a typed ErrorThrowable for a 404.');
        } catch (ErrorThrowable $e) {
            $this->assertSame('purchase_scan_not_found', $e->container->error->code);
            $this->assertSame('req_abc123', $e->container->error->requestId);
        }
    }
}
