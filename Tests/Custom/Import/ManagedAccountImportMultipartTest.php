<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Import;

use Factuarea\Sdk\Custom\Idempotency\IdempotencyHook;
use Factuarea\Sdk\Custom\Version\FactuareaVersionHook;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Models\Components\AccountsFile;
use Factuarea\Sdk\Models\Components\ManagedAccountImportV1Request;
use Factuarea\Sdk\Models\Components\Security;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * The bulk import of managed accounts (`POST /v1/accounts/imports`) takes a
 * `multipart/form-data` body whose file field is `accounts_file`. Same mocked
 * Guzzle transport as {@see \Factuarea\Sdk\Tests\Custom\Client\HttpBehaviourTest}:
 * no real network, the outgoing request is captured and inspected.
 */
final class ManagedAccountImportMultipartTest extends TestCase
{
    /** @var list<array{request: RequestInterface}> */
    private array $history = [];

    private function sdkWith(MockHandler $mock): Factuarea
    {
        $this->history = [];
        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($this->history));

        $sdk = Factuarea::builder()
            ->setSecurity(new Security(bearerAuth: 'fact_live_secret123'))
            ->setClient(new Client(['handler' => $stack]))
            ->build();
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new FactuareaVersionHook());
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new IdempotencyHook());

        return $sdk;
    }

    public function test_the_import_is_sent_as_multipart_with_the_accounts_file_field(): void
    {
        $csv = "name,tax_id,mode\nAcme SL,B12345678,managed_account\n";
        $mock = new MockHandler([
            new Response(422, ['Content-Type' => 'application/json'], (string) json_encode(['error' => [
                'type' => 'invalid_request_error',
                'code' => 'import_file_invalid',
                'message' => 'El fichero no es válido.',
                'request_id' => 'req_abc123',
            ]])),
        ]);
        $sdk = $this->sdkWith($mock);

        try {
            $sdk->managedAccounts->imports->publicApiV1ManagedAccountsImportsCreate(
                new ManagedAccountImportV1Request(
                    accountsFile: new AccountsFile(fileName: 'accounts.csv', content: $csv),
                ),
                idempotencyKey: IdempotencyHook::uuidV4(),
            );
        } catch (\Throwable) {
            // expected: only the captured outgoing request matters here
        }

        $this->assertNotEmpty($this->history);
        $request = $this->history[array_key_last($this->history)]['request'];

        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('/v1/accounts/imports', $request->getUri()->getPath());
        $this->assertStringStartsWith('multipart/form-data', $request->getHeaderLine('Content-Type'));

        $body = (string) $request->getBody();
        $this->assertStringContainsString('name="accounts_file"', $body);
        $this->assertStringContainsString('filename="accounts.csv"', $body);
        $this->assertStringContainsString($csv, $body);
    }
}
