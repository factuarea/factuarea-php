<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\ServiceFormReferences;

use Factuarea\Sdk\Custom\Crm\CrmClient;
use Factuarea\Sdk\Custom\Crm\CrmApiException;
use Factuarea\Sdk\Custom\Crm\ServiceFormReferences\References;
use Factuarea\Sdk\Custom\Crm\ServiceFormReferences\ReferencesResult;
use Factuarea\Sdk\Custom\Crm\ServiceFormReferences\RequiredPaths;
use Factuarea\Sdk\Custom\Crm\ServiceFormReferences\ServiceFormReferencesClient;
use Factuarea\Sdk\Custom\Crm\Transport;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyClient;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyHook;
use Factuarea\Sdk\Custom\Version\FactuareaVersionHook;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Hooks\BeforeRequestContext;
use Factuarea\Sdk\Hooks\BeforeRequestHook;
use Factuarea\Sdk\Models\Components\Security;
use Factuarea\Sdk\Utils\Retry\RetryConfigBackoff;
use Factuarea\Sdk\Utils\Retry\RetryConfigNone;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/** Actual SDK/Guzzle offline boundary only, with no Company issuer or backend fixture shortcut. */
final class NativeTransportTest extends TestCase
{
    /** @var \ArrayObject<int,array{request:RequestInterface,response:?ResponseInterface,error:?\Throwable,options:array<string,mixed>}> */
    private \ArrayObject $history;
    /** @var \ArrayObject<int,string> */
    private \ArrayObject $operations;
    private Factuarea $sdk;
    /** @param list<Response> $responses */
    private function client(array $responses, bool $retry = false): ServiceFormReferencesClient
    {
        $this->history = new \ArrayObject();
        $this->operations = new \ArrayObject();
        $stack = HandlerStack::create(new MockHandler($responses));
        $history = $this->history;
        $stack->push(Middleware::history($history));
        $this->sdk = Factuarea::builder()->setServerUrl('https://native-references.fixture.invalid/v1')
            ->setSecurity(new Security(bearerAuth: 'native_service_form_reference_fixture'))
            ->setClient(new IdempotencyClient(new Client(['handler' => $stack])))
            ->setRetryConfig($retry ? new RetryConfigBackoff(1, 1, 1.0, 10, true) : new RetryConfigNone())->build();
        $this->sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new FactuareaVersionHook());
        $this->sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new IdempotencyHook());
        $this->sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new class($this->operations) implements BeforeRequestHook
        {
            /** @param \ArrayObject<int,string> $operations */
            public function __construct(private \ArrayObject $operations)
            {
            }
            public function beforeRequest(BeforeRequestContext $context, RequestInterface $request): RequestInterface
            {
                $this->operations->append($context->operationID);

                return $request;
            }
        });

        return (new CrmClient($this->sdk))->serviceFormReferences(new RequiredPaths('crm/capture/service-form-references'))
            ->withHeaders(CodecTest::USER4, '2026-10-01');
    }
    private static function response(?References $references = null): Response
    {
        return new Response(200, ['Content-Type' => 'application/json', 'Cache-Control' => 'private, no-store'], (new ReferencesResult($references ?? CodecTest::references()))->toJson());
    }
    private static function error(int $status): Response
    {
        return new Response($status, ['Content-Type' => 'application/json', 'Retry-After' => '7'], json_encode(['error' => ['type' => 'invalid_request_error',
            'code' => 'parameter_invalid', 'message' => 'Actual reference owner denied the query', 'param' => 'calendars', 'request_id' => 'req_native_references']], JSON_THROW_ON_ERROR));
    }
    private function request(int $index): RequestInterface
    {
        $record = $this->history[$index] ?? null;
        if ($record === null) {
            throw new \LogicException('The native request was not recorded.');
        }

return $record['request'];
    }

    public function test_native_get_uses_same_factory_security_hooks_closed_empty_request_and_no_generated_key(): void
    {
        $client = $this->client([self::response()]);
        $result = $client->getServiceFormReferences();
        self::assertSame(200, $result->statusCode());
        self::assertNull($result->idempotencyKey);
        self::assertSame('private, no-store', $result->rawResponse->getHeaderLine('Cache-Control'));
        self::assertSame(8, $result->body->data->calendars[0]->version);
        self::assertSame(CodecTest::USER4, $result->body->data->responsibleUsers[0]->id);
        self::assertSame(['crm_capture_forms.references'], $this->operations->getArrayCopy());
        $request = $this->request(0);
        self::assertSame('GET', $request->getMethod());
        self::assertSame('/v1/crm/capture/service-form-references', $request->getUri()->getPath());
        self::assertSame('', $request->getUri()->getQuery());
        self::assertSame('', (string) $request->getBody());
        self::assertSame('Bearer native_service_form_reference_fixture', $request->getHeaderLine('Authorization'));
        self::assertSame(CodecTest::USER4, $request->getHeaderLine('X-Active-Profile'));
        self::assertSame('2026-10-01', $request->getHeaderLine('Factuarea-Version'));
        self::assertFalse($request->hasHeader('Idempotency-Key'));
        self::assertCount(1, $this->history);
    }
    public function test_each_get_observes_current_owner_collections_and_c7_without_caching_a_grant(): void
    {
        $current = new References([], [], [], [], 'native:C7-revised');
        $client = $this->client([self::response(), self::response($current)]);
        $old = $client->getServiceFormReferences()->body->data;
        $fresh = $client->getServiceFormReferences()->body->data;
        self::assertSame('actual-current:C7', $old->authorizationVersion);
        self::assertSame('native:C7-revised', $fresh->authorizationVersion);
        self::assertCount(1, $old->calendars);
        self::assertSame([], $fresh->calendars);
        self::assertSame([], $fresh->responsibleUsers);
        self::assertCount(2, $this->history);
        self::assertSame('GET', $this->request(1)->getMethod());
    }
    /** @return iterable<string,array{int}> */
    public static function deniedReads(): iterable
    {
        yield 'revoked' => [403];
        yield 'current snapshot changed' => [409];
        yield 'owner validation' => [422];
        yield 'rate' => [429];
        yield 'required producer unavailable' => [503];
    }
    #[DataProvider('deniedReads')]
    public function test_native_errors_are_preserved_instead_of_returning_empty_eligible_options(int $status): void
    {
        $client = $this->client([self::error($status)]);
        try {
            $client->getServiceFormReferences();
            self::fail('The current owner did not admit this query.');
        } catch (CrmApiException $error) {
            self::assertSame($status, $error->statusCode);
            self::assertSame('7', $error->retryAfter);
            self::assertSame('calendars', $error->param);
            self::assertSame('req_native_references', $error->requestId);
        }
        self::assertCount(1, $this->history);
        self::assertSame('GET', $this->request(0)->getMethod());
    }
    public function test_current_mask_omission_is_rejected_without_a_calendar_or_responsible_fallback(): void
    {
        $values = CodecTest::references()->toArray();
        unset($values['calendars']);
        $client = $this->client([new Response(200, ['Content-Type' => 'application/json'], json_encode(['data' => $values], JSON_THROW_ON_ERROR))]);
        $this->expectException(\InvalidArgumentException::class);
        $client->getServiceFormReferences();
    }
    public function test_older_reference_identity_does_not_silence_current_revocation(): void
    {
        $client = $this->client([self::response(), self::error(403)]);
        self::assertSame(CodecTest::CALENDAR, $client->getServiceFormReferences()->body->data->calendars[0]->id);
        try {
            $client->getServiceFormReferences();
            self::fail('Current revocation must not return a cached eligible identity.');
        } catch (CrmApiException $error) {
            self::assertSame(403, $error->statusCode);
        }
        self::assertCount(2, $this->history);
    }
    public function test_existing_configured_get_retry_policy_is_retained_without_a_mutation_key(): void
    {
        $client = $this->client([self::error(503), self::response()], true);
        self::assertSame('actual-current:C7', $client->getServiceFormReferences()->body->data->authorizationVersion);
        self::assertCount(2, $this->history);
        foreach ([0, 1] as $index) {
            self::assertSame('GET', $this->request($index)->getMethod());
            self::assertFalse($this->request($index)->hasHeader('Idempotency-Key'));
        }
    }
    public function test_required_path_and_native_header_overrides_cannot_add_context_query_or_fabricated_authority(): void
    {
        $client = $this->client([]);
        foreach (['https://remote.fixture.invalid', '/crm/path', 'crm/path?company_id=42', 'crm/{tenant}', 'crm/../path', 'crm/./path'] as $path) {
            try {
                new RequiredPaths($path);
                self::fail('The protected native path is relative and has no query or target placeholder.');
            } catch (\InvalidArgumentException $error) {
                self::assertNotSame('', $error->getMessage());
            }
        }
        try {
            $client->withHeaders("profile\r\nAuthorization: other");
            self::fail('Header injection is invalid.');
        } catch (\InvalidArgumentException $error) {
            self::assertNotSame('', $error->getMessage());
        }
        try {
            new ServiceFormReferencesClient(new Transport($this->sdk->sdkConfiguration), new RequiredPaths('crm/capture/service-form-references'), ['Authorization-Version' => 'fabricated']);
            self::fail('Only actual native profile/version override headers are accepted.');
        } catch (\InvalidArgumentException $error) {
            self::assertNotSame('', $error->getMessage());
        }
        self::assertCount(0, $this->history);
    }
}
