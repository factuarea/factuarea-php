<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\PurchaseScans;

use Factuarea\Sdk\Custom\Idempotency\IdempotencyHook;
use Factuarea\Sdk\Custom\Version\FactuareaVersionHook;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Components\Security;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * `purchase_scans.create` (`POST /purchase_scans`) — uploads N originals as
 * `multipart/form-data`. Written against a mocked Guzzle transport (same
 * pattern as {@see \Factuarea\Sdk\Tests\Custom\Client\HttpBehaviourTest}), so
 * it does not require the SDK to actually be regenerated to be authored, but
 * it DOES require it to be regenerated (task 4.6, Speakeasy) to run: today
 * `Factuarea\Sdk\PurchaseScans` and `Factuarea\Sdk\Models\Components\UploadPurchaseScansRequest`
 * do not exist yet.
 *
 * Reconciled against the real task 4.6 regeneration (Speakeasy, `php`
 * target): the file-part wrapper is named plain `Components\Files`
 * (`fileName`/`content` properties, `multipartForm:file=true,name=files[]`
 * on the parent `files` array), not the hypothesized
 * `UploadPurchaseScansRequestFiles`. Everything else in the original
 * hypothesis held: class `PurchaseScans`, method
 * `publicApiV1PurchaseScansCreate(Components\UploadPurchaseScansRequest $body, string $idempotencyKey, ...)`,
 * and `multipartArrayFormat: standard` DOES make Speakeasy emit one
 * multipart part per array item under the same field name natively — no
 * `src/Custom/` helper needed (evidence for task 4.7).
 */
final class PurchaseScansUploadTest extends TestCase
{
    /** @var list<array{request: RequestInterface}> */
    private array $history = [];

    private function sdkWith(MockHandler $mock): Factuarea
    {
        $this->history = [];
        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($this->history));
        $guzzle = new Client(['handler' => $stack]);

        $sdk = Factuarea::builder()
            ->setSecurity(new Security(bearerAuth: 'fact_test_secret123'))
            ->setClient($guzzle)
            ->build();
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new FactuareaVersionHook());
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new IdempotencyHook());

        return $sdk;
    }

    private function lastRequest(): RequestInterface
    {
        return $this->history[array_key_last($this->history)]['request'];
    }

    private function successEnvelope(): string
    {
        $spec = json_decode(
            (string) file_get_contents(__DIR__.'/../../../spec/openapi.json'),
            true,
            flags: JSON_THROW_ON_ERROR,
        );
        $envelope = $spec['paths']['/purchase_scans']['post']['responses']['202']
            ['content']['application/json']['examples']['success']['value'];
        $first = $envelope['data']['accepted'][0];
        $first['original_filename'] = 'factura-1.pdf';
        $first['item_index'] = 0;
        $second = $first;
        $second['id'] = '019e0000-0000-7000-8000-000000000043';
        $second['original_filename'] = 'factura-2.pdf';
        $second['item_index'] = 1;
        $envelope['data']['accepted'] = [$first, $second];
        $envelope['data']['rejected'] = [];
        $envelope['data']['idempotent_replay'] = false;

        return json_encode($envelope, JSON_THROW_ON_ERROR);
    }

    public function test_uploads_two_files_under_the_frozen_field_name_with_an_idempotency_key(): void
    {
        $mock = new MockHandler([
            new Response(202, ['Content-Type' => 'application/json'], $this->successEnvelope()),
        ]);
        $sdk = $this->sdkWith($mock);

        $body = new Components\UploadPurchaseScansRequest(files: [
            new Components\Files(fileName: 'factura-1.pdf', content: '%PDF-1 primer fichero'),
            new Components\Files(fileName: 'factura-2.pdf', content: '%PDF-1 segundo fichero'),
        ]);
        $idempotencyKey = IdempotencyHook::uuidV4();

        $response = $sdk->purchaseScans->publicApiV1PurchaseScansCreate($body, $idempotencyKey);

        $request = $this->lastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertStringEndsWith('/purchase_scans', (string) $request->getUri());
        $this->assertStringStartsWith('multipart/form-data', $request->getHeaderLine('Content-Type'));
        $this->assertSame($idempotencyKey, $request->getHeaderLine('Idempotency-Key'));

        $raw = (string) $request->getBody();
        $fileParts = preg_match_all('/name="files\[\]"/', $raw);
        $this->assertSame(2, $fileParts, 'Expected exactly two multipart parts under the frozen field name files[].');
        $this->assertStringContainsString('factura-1.pdf', $raw);
        $this->assertStringContainsString('factura-2.pdf', $raw);

        // The 202 envelope must deserialize into the response.
        $this->assertSame(202, $response->statusCode);
        $this->assertNotNull($response->object);
        $this->assertNotNull($response->object->data);
    }
}
