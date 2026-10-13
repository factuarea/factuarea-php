<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\VerticalPacks;

use Factuarea\Sdk\Custom\Crm\CrmClient;
use Factuarea\Sdk\Custom\Crm\CrmApiException;
use Factuarea\Sdk\Custom\Crm\Transport;
use Factuarea\Sdk\Custom\Crm\VerticalPacks\OriginalIntent;
use Factuarea\Sdk\Custom\Crm\VerticalPacks\OriginalReceipt;
use Factuarea\Sdk\Custom\Crm\VerticalPacks\Pack;
use Factuarea\Sdk\Custom\Crm\VerticalPacks\Plan;
use Factuarea\Sdk\Custom\Crm\VerticalPacks\PreviewRequest;
use Factuarea\Sdk\Custom\Crm\VerticalPacks\RequiredPaths;
use Factuarea\Sdk\Custom\Crm\VerticalPacks\UndoRequest;
use Factuarea\Sdk\Custom\Crm\VerticalPacks\UnconfirmedWriteException;
use Factuarea\Sdk\Custom\Crm\VerticalPacks\VerticalPackClient;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyClient;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyHook;
use Factuarea\Sdk\Custom\Version\FactuareaVersionHook;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Hooks\BeforeRequestContext;
use Factuarea\Sdk\Hooks\BeforeRequestHook;
use Factuarea\Sdk\Models\Components\Security;
use Factuarea\Sdk\Models\Errors\APIException;
use Factuarea\Sdk\Utils\Retry\RetryConfigBackoff;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/** Offline actual SDK transport and HTTP71 contract; no server admission or mounted route is claimed. */
final class NativeTransportTest extends TestCase
{
    private const KEY = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
    private const PROFILE = '019a4afd-7100-4000-8000-000000000007';
    /** @var \ArrayObject<int,array<string,mixed>> */
    private \ArrayObject $history;
    /** @var \ArrayObject<int,string> */
    private \ArrayObject $operations;
    private MockHandler $mock;
    private Factuarea $sdk;

    private static function paths(): RequiredPaths
    {
        return new RequiredPaths('crm/configuration/packs', 'crm/configuration/packs/{pack}/preview',
            'crm/configuration/pack-plans/{plan}/read', 'crm/configuration/pack-plans/{plan}/conflicts',
            'crm/configuration/pack-plans/{plan}/apply', 'crm/configuration/pack-installations/{installation}/undo',
            'crm/configuration/pack-original-receipts/read');
    }
    /** @param list<Response|\Throwable> $responses */
    private function client(array $responses): VerticalPackClient
    {
        $this->history = new \ArrayObject();
        $this->operations = new \ArrayObject();
        $this->mock = new MockHandler($responses);
        $stack = HandlerStack::create($this->mock);
        $history = $this->history;
        $stack->push(Middleware::history($history));
        $this->sdk = Factuarea::builder()->setServerUrl('https://native-packs.fixture.invalid/v1')
            ->setSecurity(new Security(bearerAuth: 'fact_native_packs_fixture'))
            ->setClient(new IdempotencyClient(new Client(['handler' => $stack, 'headers' => ['Idempotency-Key' => 'unrelated-default', 'X-Native-Default' => 'retained']])))
            ->setRetryConfig(new RetryConfigBackoff(1, 1, 1.0, 10, true))->build();
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

        return (new CrmClient($this->sdk))->verticalPacks(self::paths())->withHeaders(self::PROFILE, '2026-10-01');
    }
    /** @param array<string,mixed>|null $data
     * @param array<string,string> $headers */
    private static function response(?array $data, int $status = 200, array $headers = []): Response
    {
        return new Response($status, ['Content-Type' => 'application/json'] + $headers, json_encode(['data' => $data], JSON_THROW_ON_ERROR));
    }
    private static function error(int $status): Response
    {
        return new Response($status, ['Content-Type' => 'application/json', 'Retry-After' => '3'], json_encode(['error' => [
            'type' => 'invalid_request_error', 'code' => 'parameter_invalid', 'message' => 'Native plan cannot be admitted',
            'param' => 'plan_token', 'request_id' => 'req_vertical_pack_native']], JSON_THROW_ON_ERROR));
    }
    private function request(int $index): RequestInterface
    {
        $entry = $this->history[$index];
        self::assertIsArray($entry);
        $request = $entry['request'] ?? null;
        self::assertInstanceOf(RequestInterface::class, $request);

        return $request;
    }
    private function option(int $index, string $name): mixed
    {
        $entry = $this->history[$index];
        self::assertIsArray($entry);
        $options = $entry['options'] ?? null;
        self::assertIsArray($options);

        return $options[$name] ?? null;
    }

    private static function intent(): OriginalIntent
    {
        return OriginalIntent::apply(CodecTest::plan(), CodecTest::applyWire(), self::KEY);
    }
    /** @return array<string,mixed> */
    private static function undoReceipt(): array
    {
        $data = CodecTest::receipt()->toArray();
        $data['id'] = CodecTest::TARGET;
        $data['operation'] = 'undo';
        $data['target_id'] = CodecTest::INSTALLATION;
        self::assertInstanceOf(\stdClass::class, $data['installation']);
        $data['installation']->receipt_id = CodecTest::TARGET;
        $data['installation']->version = 13;

        return $data;
    }

    public function test_all_seven_native_v1_operations_use_actual_paths_envelopes_and_hooks(): void
    {
        $plan = CodecTest::plan();
        $applied = CodecTest::receipt();
        $pack = new Pack(CodecTest::PACK, 3, 'professional_services', CodecTest::text(), CodecTest::text(), false, null);
        $client = $this->client([self::response(['items' => [$pack->jsonSerialize()], 'next_cursor' => null]),
            self::response($plan->toArray()), self::response($plan->toArray()), self::response(['items' => [], 'preview_hash' => $plan->previewHash]),
            self::response($applied->toArray()), self::response(self::undoReceipt()), self::response($applied->toArray(), 200, ['Idempotent-Replayed' => 'true'])]);
        self::assertSame(CodecTest::PACK, $client->listCrmPacks('professional_services')->body->data->items[0]->id);
        self::assertSame($plan->planToken, $client->previewCrmPack(CodecTest::PACK, new PreviewRequest(3, false))->body->data->planToken);
        self::assertSame($plan->previewHash, $client->getCrmPackPlan($plan)->body->data->previewHash);
        self::assertSame([], $client->getCrmPackConflicts($plan)->body->data->items);
        $intent = self::intent();
        self::assertSame(CodecTest::RECEIPT, $client->applyCrmPack($intent)->body->data->id);
        $undoWire = ' '.(new UndoRequest(12, CodecTest::RECEIPT, true))->toJson()." \n";
        $undo = OriginalIntent::undo($applied, $undoWire, str_repeat('b', 64));
        self::assertSame(13, $client->undoCrmPackInstallation($undo)->body->data->installation->version);
        $original = $client->getOriginalCrmPackReceipt($intent);
        self::assertSame(CodecTest::RECEIPT, $original->body->receipt?->id);
        self::assertTrue($original->replayed());
        self::assertNull($original->idempotencyKey);
        self::assertSame(['crmConfigurationListPacks', 'crmConfigurationPreviewPack', 'crmConfigurationGetPackPlan',
            'crmConfigurationGetPackConflicts', 'crmConfigurationApplyPack', 'crmConfigurationUndoPackInstallation', 'crmConfigurationGetOriginalPackReceipt'], $this->operations->getArrayCopy());
        $paths = ['/v1/crm/configuration/packs', '/v1/crm/configuration/packs/'.CodecTest::PACK.'/preview',
            '/v1/crm/configuration/pack-plans/'.CodecTest::PLAN.'/read', '/v1/crm/configuration/pack-plans/'.CodecTest::PLAN.'/conflicts',
            '/v1/crm/configuration/pack-plans/'.CodecTest::PLAN.'/apply', '/v1/crm/configuration/pack-installations/'.CodecTest::INSTALLATION.'/undo',
            '/v1/crm/configuration/pack-original-receipts/read'];
        foreach ($paths as $index => $path) {
            $request = $this->request($index);
            self::assertSame($path, $request->getUri()->getPath());
            self::assertSame($index === 0 ? 'GET' : 'POST', $request->getMethod());
            self::assertSame('Bearer fact_native_packs_fixture', $request->getHeaderLine('Authorization'));
            self::assertSame(self::PROFILE, $request->getHeaderLine('X-Active-Profile'));
            self::assertSame('2026-10-01', $request->getHeaderLine('Factuarea-Version'));
            if ($index > 0) {
                self::assertFalse($this->option($index, 'allow_redirects'));
                self::assertSame('', $request->getUri()->getQuery());
            }
        }
        self::assertSame('sector=professional_services', $this->request(0)->getUri()->getQuery());
        self::assertSame($intent->originalWire, (string) $this->request(4)->getBody());
        self::assertSame(self::KEY, $this->request(4)->getHeaderLine('Idempotency-Key'));
        self::assertSame($undoWire, (string) $this->request(5)->getBody());
        self::assertSame(str_repeat('b', 64), $this->request(5)->getHeaderLine('Idempotency-Key'));
        foreach ([1, 2, 3, 6] as $index) {
            self::assertFalse($this->request($index)->hasHeader('Idempotency-Key'));
            self::assertSame('retained', $this->request($index)->getHeaderLine('X-Native-Default'));
        }
        $recovery = json_decode((string) $this->request(6)->getBody(), true, 32, JSON_THROW_ON_ERROR);
        self::assertSame(['operation' => 'apply', 'target_id' => CodecTest::PLAN, 'original_key' => self::KEY, 'original_wire' => $intent->originalWire], $recovery);
    }

    public function test_ambiguous_write_keeps_same_original_intent_and_never_resends_or_recovers_automatically(): void
    {
        $client = $this->client([new ConnectException('Offline native boundary', new Request('POST', 'https://native-packs.fixture.invalid')), self::response(CodecTest::receipt()->toArray())]);
        $intent = self::intent();
        try {
            $client->applyCrmPack($intent);
            self::fail('No original receipt was confirmed.');
        } catch (UnconfirmedWriteException $error) {
            self::assertSame($intent, $error->originalIntent);
            self::assertSame($intent->originalWire, $error->originalIntent->originalWire);
        }
        self::assertCount(1, $this->history);
        self::assertCount(1, $this->mock);
    }
    /** @return iterable<string,array{int}> */
    public static function definiteErrors(): iterable
    {
        yield 'revoked' => [403];
        yield 'C7 TTL hash CAS conflict' => [409];
        yield 'closed field' => [422];
    }
    #[DataProvider('definiteErrors')]
    public function test_definite_native_errors_retain_parameter_request_id_headers_and_do_not_retry(int $status): void
    {
        $client = $this->client([self::error($status), self::response(CodecTest::receipt()->toArray())]);
        try {
            $client->applyCrmPack(self::intent());
            self::fail('Native admission failed.');
        } catch (CrmApiException $error) {
            self::assertSame('plan_token', $error->param);
            self::assertSame('req_vertical_pack_native', $error->requestId);
            self::assertSame($status, $error->statusCode);
            self::assertSame('3', $error->retryAfter);
        }
        self::assertCount(1, $this->history);
        self::assertCount(1, $this->mock);
    }
    /** @return iterable<string,array{int}> */
    public static function unknownCompletions(): iterable
    {
        yield 'created' => [201];
        yield 'accepted' => [202];
        yield 'other area control' => [204];
        yield 'redirect' => [307];
        yield 'missing original producer' => [503];
    }
    #[DataProvider('unknownCompletions')]
    public function test_only_native200_confirmed_receipt_can_confirm_the_write(int $status): void
    {
        $client = $this->client([$status === 503 ? self::error(503) : self::response(CodecTest::receipt()->toArray(), $status, ['Location' => 'https://must-not-follow.fixture.invalid']), self::response(CodecTest::receipt()->toArray())]);
        try {
            $client->applyCrmPack(self::intent());
            self::fail('The write completion is unknown.');
        } catch (UnconfirmedWriteException $error) {
            self::assertSame(self::KEY, $error->originalIntent->originalKey);
        }
        self::assertCount(1, $this->history);
        self::assertCount(1, $this->mock);
    }
    /** @return iterable<string,array{string}> */
    public static function queryCases(): iterable
    {
        foreach (['preview', 'plan', 'conflicts', 'recover'] as $case) {
            yield $case => [$case];
        }
    }
    #[DataProvider('queryCases')]
    public function test_post_queries_never_auto_key_retry_or_reserve_even_with_native_optional_hooks(string $case): void
    {
        $client = $this->client([self::error(503), self::response(null)]);
        try {
            match ($case) {
                'preview' => $client->previewCrmPack(CodecTest::PACK, new PreviewRequest(3, false)),
                'plan' => $client->getCrmPackPlan(CodecTest::plan()), 'conflicts' => $client->getCrmPackConflicts(CodecTest::plan()),
                'recover' => $client->getOriginalCrmPackReceipt(self::intent()), default => throw new \LogicException('Bad fixture case.')
            };
            self::fail('The native query producer is unavailable.');
        } catch (CrmApiException $error) {
            self::assertSame(503, $error->statusCode);
        }
        self::assertCount(1, $this->history);
        self::assertCount(1, $this->mock);
        self::assertFalse($this->request(0)->hasHeader('Idempotency-Key'));
        self::assertFalse($this->option(0, 'allow_redirects'));
    }
    public function test_original_absence_stays_null_without_dispatch_and_escaped_wrapper_preserves_maximum_raw_wire(): void
    {
        $wire = CodecTest::applyWire();
        $wire .= str_repeat("\n", 65536 - strlen($wire));
        $intent = OriginalIntent::retained('apply', CodecTest::PLAN, $wire, self::KEY);
        $client = $this->client([self::response(null)]);
        self::assertNull($client->getOriginalCrmPackReceipt($intent)->body->receipt);
        self::assertCount(1, $this->history);
        $wrapper = (string) $this->request(0)->getBody();
        self::assertGreaterThan(65536, strlen($wrapper));
        self::assertLessThanOrEqual(262144, strlen($wrapper));
        $decoded = json_decode($wrapper, true, 32, JSON_THROW_ON_ERROR);
        self::assertIsArray($decoded);
        self::assertSame($wire, $decoded['original_wire']);
        self::assertFalse($this->request(0)->hasHeader('Idempotency-Key'));
    }
    public function test_original_recovery_retains_first_server_receipt_when_catalogue_head_advances(): void
    {
        $client = $this->client([self::response(['items' => [(new Pack(CodecTest::PACK, 99, 'professional_services', CodecTest::text(), CodecTest::text(), false, null))->jsonSerialize()], 'next_cursor' => null]),
            self::response(CodecTest::receipt()->toArray())]);
        self::assertSame(99, $client->listCrmPacks()->body->data->items[0]->version);
        $original = $client->getOriginalCrmPackReceipt(self::intent());
        self::assertInstanceOf(\Factuarea\Sdk\Custom\Crm\VerticalPacks\Receipt::class, $original->body->receipt);
        self::assertSame(CodecTest::RECEIPT, $original->body->receipt->id);
        self::assertSame(3, $original->body->receipt->installation->packVersion);
        self::assertSame(12, $original->body->receipt->installation->version);
        self::assertCount(2, $this->history);
    }
    public function test_read_plan_keeps_token_out_of_url_and_rejects_replacement_without_minting_another(): void
    {
        $plan = CodecTest::plan();
        $replacement = $plan->toArray();
        $replacement['plan_token'] = 'replacement-token';
        $client = $this->client([self::response($replacement)]);
        try {
            $client->getCrmPackPlan($plan);
            self::fail('The original token cannot be replaced.');
        } catch (\UnexpectedValueException $error) {
            self::assertNotSame('', $error->getMessage());
        }
        self::assertStringNotContainsString($plan->planToken, (string) $this->request(0)->getUri());
        self::assertSame(['plan_token' => $plan->planToken], json_decode((string) $this->request(0)->getBody(), true, 32, JSON_THROW_ON_ERROR));
        self::assertCount(1, $this->history);
    }
    public function test_conflict_read_rejects_choices_from_another_native_hash(): void
    {
        $client = $this->client([self::response(['items' => CodecTest::plan(true)->toArray()['items'], 'preview_hash' => str_repeat('f', 64)])]);
        $this->expectException(\UnexpectedValueException::class);
        $client->getCrmPackConflicts(CodecTest::plan(true));
    }
    public function test_foreign_receipt_cannot_confirm_original_write_or_read(): void
    {
        $receipt = CodecTest::receipt()->toArray();
        $receipt['target_id'] = CodecTest::TARGET;
        $client = $this->client([self::response($receipt), self::response($receipt)]);
        try {
            $client->applyCrmPack(self::intent());
            self::fail('The original write was not confirmed.');
        } catch (UnconfirmedWriteException $error) {
            self::assertSame(self::KEY, $error->originalIntent->originalKey);
        }
        $this->expectException(\UnexpectedValueException::class);
        $client->getOriginalCrmPackReceipt(self::intent());
    }
    public function test_transport_rejects_unknown_operation_or_query_key_before_the_http_boundary(): void
    {
        $this->client([]);
        $transport = new Transport($this->sdk->sdkConfiguration);
        foreach ([['inventedOperation', null], ['crmConfigurationGetOriginalPackReceipt', self::KEY]] as [$operation, $key]) {
            try {
                $transport->requestVerticalPackJson(self::paths()->recover, $operation, OriginalReceipt::class, '{}', $key);
                self::fail('Unknown operation or new query key cannot be sent.');
            } catch (\InvalidArgumentException $error) {
                self::assertNotSame('', $error->getMessage());
            }
        }
        self::assertCount(0, $this->history);
    }
    public function test_paths_and_headers_are_required_and_cannot_inject_url_tokens_or_new_privileges(): void
    {
        $client = $this->client([]);
        foreach (['https://external.fixture.invalid', 'crm/packs?plan_token=opaque', 'crm/../packs', '/crm/packs'] as $list) {
            try {
                new RequiredPaths($list, 'packs/{pack}', 'plans/{plan}', 'plans/{plan}', 'plans/{plan}', 'installations/{installation}', 'original/read');
                self::fail('Supply a relative mounted native path.');
            } catch (\InvalidArgumentException $error) {
                self::assertNotSame('', $error->getMessage());
            }
        }
        try {
            $client->withHeaders("profile\r\nAuthorization: changed");
            self::fail('Header injection is invalid.');
        } catch (\InvalidArgumentException $error) {
            self::assertNotSame('', $error->getMessage());
        }
        try {
            $client->listCrmPacks(null, 'invented-cursor');
            self::fail('The native catalogue has no further cursor.');
        } catch (\InvalidArgumentException $error) {
            self::assertNotSame('', $error->getMessage());
        }
        self::assertCount(0, $this->history);
    }

    public function test_native_catalogue7_current_cas_does_not_replace_first_original_receipt(): void
    {
        $pack = new Pack(CodecTest::PACK, 99, 'professional_services', CodecTest::text(), CodecTest::text(), false,
            new \Factuarea\Sdk\Custom\Crm\VerticalPacks\Installation(CodecTest::INSTALLATION, 88, CodecTest::TARGET, CodecTest::PACK, 4));
        $client = $this->client([self::response(['items' => [$pack->jsonSerialize()], 'next_cursor' => null]), self::response(CodecTest::receipt()->toArray())]);
        $head = $client->listCrmPacks()->body->data->items[0];
        self::assertFalse($head->supportsDemo);
        self::assertInstanceOf(\Factuarea\Sdk\Custom\Crm\VerticalPacks\Installation::class, $head->installation);
        self::assertSame(88, $head->installation->version);
        self::assertSame(CodecTest::TARGET, $head->installation->receiptId);
        $original = $client->getOriginalCrmPackReceipt(self::intent())->body->receipt;
        self::assertInstanceOf(\Factuarea\Sdk\Custom\Crm\VerticalPacks\Receipt::class, $original);
        self::assertSame(CodecTest::RECEIPT, $original->id);
        self::assertSame(12, $original->installation->version);
        self::assertSame(3, $original->installation->packVersion);
        self::assertCount(2, $this->history);
        self::assertSame('GET', $this->request(0)->getMethod());
        self::assertSame('POST', $this->request(1)->getMethod());
        $body = json_decode((string) $this->request(1)->getBody(), true, 32, JSON_THROW_ON_ERROR);
        self::assertIsArray($body);
        self::assertSame(CodecTest::applyWire(), $body['original_wire']);
        self::assertSame(self::KEY, $body['original_key']);
        self::assertFalse($this->request(1)->hasHeader('Idempotency-Key'));
    }

}
