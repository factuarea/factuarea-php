<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\InstalledBatch82;

use Factuarea\Sdk\Custom\Crm\Documents\Omitted;
use Factuarea\Sdk\Custom\Crm\OAuthOriginals\OriginalIntent;
use Factuarea\Sdk\Custom\Crm\OAuthOriginals\OriginalReceipt;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\Client as SalesClient;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\ConflictFailure;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\CreateActivityRequest;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\CreateLeadRequest;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\CreateOpportunityRequest;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\CrmFailure;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\LeadPatchRequest;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\PermissionFailure;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\RateLimitFailure;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\SalesResult;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\ValidationFailure;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\WriteUnconfirmed;
use Factuarea\Sdk\Custom\FactuareaClient;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyClient;
use Factuarea\Sdk\Factuarea;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class InstalledConsumerTest extends TestCase
{
    private const LEAD = '01900000-0000-7000-8000-000000000011';
    private const OPPORTUNITY = '01900000-0000-7000-8000-000000000012';
    private const PIPELINE = '01900000-0000-7000-8000-000000000013';
    private const STAGE = '01900000-0000-7000-8000-000000000014';
    private const ACTIVITY = '01900000-0000-7000-8000-000000000015';
    private const EFFECT = '01900000-0000-7000-8000-000000000016';
    private const PRIVATE_MARKER = 'private-form-value-not-for-output';

    private function fixture(array $replies, string|\Closure $token = 'installed-native-token'): object
    {
        $fixture = new \stdClass;
        $fixture->history = [];
        $stack = HandlerStack::create(new MockHandler($replies));
        $stack->push(Middleware::history($fixture->history));
        $fixture->http = new Client(['handler' => $stack]);
        $fixture->sdk = FactuareaClient::createOAuth($token, 'https://installed.invalid/v1', $fixture->http);
        $fixture->sales = FactuareaClient::crmSales($fixture->sdk);

        return $fixture;
    }

    private static function reply(array $data, int $status = 201): Response
    {
        return new Response($status, ['Content-Type' => 'application/json', 'X-Request-Id' => 'req_installed'],
            json_encode(['data' => $data], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE));
    }

    private static function lead(): array
    {
        return ['id' => self::LEAD, 'version' => 1, 'name' => self::PRIVATE_MARKER, 'notes' => null, 'status' => 'future_native_status'];
    }

    private static function original(string $wire, string $state, ?string $body = null): Response
    {
        return new Response(200, ['Content-Type' => 'application/json'], json_encode(['data' => ['state' => $state,
            'original_body_sha256' => hash('sha256', $wire), 'original_response' => $body === null ? null : ['status' => 201, 'body' => $body]],
            'meta' => ['authorization_version' => 'opaque_current_C7']], JSON_THROW_ON_ERROR));
    }

    public function test_authoritative_composer_autoload_resolves_actual_imports_inside_the_installed_package(): void
    {
        $loader = $GLOBALS['factuareaInstalledBatch82Loader'];
        self::assertTrue($loader->isClassMapAuthoritative());
        self::assertFalse(is_link(FACTUAREA_INSTALLED_BATCH82_PACKAGE));
        foreach ([Factuarea::class, FactuareaClient::class, SalesClient::class, CreateLeadRequest::class, CreateOpportunityRequest::class,
            CreateActivityRequest::class, LeadPatchRequest::class, SalesResult::class, CrmFailure::class, ValidationFailure::class,
            ConflictFailure::class, PermissionFailure::class, RateLimitFailure::class, WriteUnconfirmed::class, OriginalIntent::class,
            OriginalReceipt::class, Omitted::class, \Factuarea\Sdk\Custom\CrmMetaNativeBatch80\Client::class] as $class) {
            self::assertTrue(class_exists($class) || enum_exists($class));
            $file = (new \ReflectionClass($class))->getFileName();
            self::assertStringStartsWith(FACTUAREA_INSTALLED_BATCH82_PACKAGE.'/src/', $file);
            self::assertArrayHasKey($class, $loader->getClassMap());
            self::assertSame(realpath($file), realpath($loader->findFile($class)));
        }
        foreach (['websiteSdk', 'submitWebsiteLead', 'recoverWebsiteLead', 'journey'] as $function) {
            $file = (new \ReflectionFunction('Factuarea\\Sdk\\Examples\\SalesBatch81\\'.$function))->getFileName();
            self::assertStringStartsWith(FACTUAREA_INSTALLED_BATCH82_PACKAGE.'/examples/', $file);
        }
    }

    public function test_installed_working_tree_package_matches_every_frozen_native_sdk_file(): void
    {
        $handoff = json_decode(file_get_contents(FACTUAREA_INSTALLED_BATCH82_HANDOFF), true, 512, JSON_THROW_ON_ERROR);
        $prefix = $handoff['repo']['root'].'/';
        $count = 0;
        foreach ($handoff['repo']['source_pins'] as $pin) {
            if (!str_starts_with($pin['absolute_path'], $prefix)) {
                continue;
            }
            $relative = substr($pin['absolute_path'], strlen($prefix));
            self::assertSame($pin['sha256'], hash_file('sha256', FACTUAREA_INSTALLED_BATCH82_PACKAGE.'/'.$relative), $relative);
            $count++;
        }
        self::assertSame(20, $count);
    }

    public function test_installed_server_website_factory_reads_env_and_journals_before_one_actual_sdk_dispatch(): void
    {
        $previous = getenv('FACTUAREA_API_KEY');
        putenv('FACTUAREA_API_KEY=fact_test_private_installed_batch82');
        $fixture = $this->fixture([self::reply(self::lead())]);
        $journal = [];
        ob_start();
        try {
            $sdk = \Factuarea\Sdk\Examples\SalesBatch81\websiteSdk();
            self::assertInstanceOf(Factuarea::class, $sdk);
            $transport = new IdempotencyClient($fixture->http);
            $sdk->sdkConfiguration->client = $transport;
            $sdk->sdkConfiguration->defaultClient = $transport;
            $sales = FactuareaClient::crmSales($sdk);
            $result = \Factuarea\Sdk\Examples\SalesBatch81\submitWebsiteLead($sales, ['name' => self::PRIVATE_MARKER, 'email' => null], 'website original',
                static function (string $step, string $key, string $wire) use (&$journal, $fixture): void {
                    self::assertCount(0, $fixture->history);
                    $journal[] = [$step, $key, $wire];
                });
            self::assertSame(self::LEAD, $result->id);
        } finally {
            $output = ob_get_clean();
            putenv($previous === false ? 'FACTUAREA_API_KEY' : 'FACTUAREA_API_KEY='.$previous);
        }
        self::assertSame('', $output);
        self::assertCount(1, $fixture->history);
        self::assertCount(1, $journal);
        $request = $fixture->history[0]['request'];
        self::assertSame('Bearer fact_test_private_installed_batch82', $request->getHeaderLine('Authorization'));
        self::assertSame('website original', $request->getHeaderLine('Idempotency-Key'));
        self::assertSame($journal[0][2], (string) $request->getBody());
        self::assertSame('POST', $request->getMethod());
        self::assertStringEndsWith('/v1/crm/leads', $request->getUri()->getPath());
        self::assertNotSame('', $request->getHeaderLine('Factuarea-Version'));
    }

    public function test_website_missing_server_credential_has_no_output_or_transport(): void
    {
        $previous = getenv('FACTUAREA_API_KEY');
        putenv('FACTUAREA_API_KEY');
        ob_start();
        try {
            \Factuarea\Sdk\Examples\SalesBatch81\websiteSdk();
            self::fail();
        } catch (\RuntimeException $error) {
            self::assertSame('The server credential is unavailable.', $error->getMessage());
        } finally {
            $output = ob_get_clean();
            putenv($previous === false ? 'FACTUAREA_API_KEY' : 'FACTUAREA_API_KEY='.$previous);
        }
        self::assertSame('', $output);
    }

    public function test_host_journal_failure_prevents_the_installed_website_http_write(): void
    {
        $fixture = $this->fixture([self::reply(self::lead())]);
        try {
            \Factuarea\Sdk\Examples\SalesBatch81\submitWebsiteLead($fixture->sales, ['name' => self::PRIVATE_MARKER], 'first-key',
                static function (): void {
                    throw new \RuntimeException('private_journal_unavailable');
                });
            self::fail();
        } catch (\RuntimeException $error) {
            self::assertSame('private_journal_unavailable', $error->getMessage());
            self::assertCount(0, $fixture->history);
        }
    }

    public function test_website_unknown_form_fields_fail_before_journal_or_http(): void
    {
        $fixture = $this->fixture([self::reply(self::lead())]);
        $journal = [];
        try {
            \Factuarea\Sdk\Examples\SalesBatch81\submitWebsiteLead($fixture->sales, ['name' => self::PRIVATE_MARKER, 'company_id' => self::LEAD], 'first-key',
                static function () use (&$journal): void {
                    $journal[] = true;
                });
            self::fail();
        } catch (\InvalidArgumentException) {
            self::assertSame([], $journal);
            self::assertCount(0, $fixture->history);
        }
    }

    public function test_installed_journey_composes_native_server_identities_and_journals_each_first_wire_without_output(): void
    {
        $fixture = $this->fixture([self::reply(self::lead()), self::reply(['id' => self::OPPORTUNITY, 'version' => 2, 'amount' => '12.3000']),
            self::reply(['activity_id' => self::ACTIVITY, 'effect_id' => self::EFFECT, 'state' => 'confirmed', 'activity' => null])]);
        $journal = [];
        ob_start();
        try {
            $result = \Factuarea\Sdk\Examples\SalesBatch81\journey($fixture->sales, new CreateLeadRequest(name: self::PRIVATE_MARKER, email: null),
                self::PIPELINE, self::STAGE, '12.3000', 'EUR', 'Native opportunity', 'Follow up', 'UTC',
                ['leadCreate' => 'lead-key', 'opportunityCreate' => 'opportunity-key', 'activityCreate' => 'activity-key'],
                static function (string $step, string $key, string $wire) use (&$journal, $fixture): void {
                    self::assertCount(count($journal), $fixture->history);
                    $journal[] = [$step, $key, $wire];
                });
        } finally {
            $output = ob_get_clean();
        }
        self::assertSame('', $output);
        self::assertSame(self::LEAD, $result['lead']->id);
        self::assertSame(self::OPPORTUNITY, $result['opportunity']->id);
        self::assertSame(self::ACTIVITY, $result['activity']->id);
        self::assertCount(3, $fixture->history);
        self::assertSame(['/v1/crm/leads', '/v1/crm/opportunity', '/v1/crm/crmactivity'],
            array_map(static fn (array $item): string => $item['request']->getUri()->getPath(), $fixture->history));
        foreach ($fixture->history as $index => $item) {
            self::assertSame($journal[$index][1], $item['request']->getHeaderLine('Idempotency-Key'));
            self::assertSame($journal[$index][2], (string) $item['request']->getBody());
        }
        self::assertSame(self::LEAD, json_decode($journal[1][2], true)['lead_id']);
        self::assertSame('12.3000', json_decode($journal[1][2], true)['amount']);
        self::assertSame(self::OPPORTUNITY, json_decode($journal[2][2], true)['target']['id']);
        self::assertSame(self::EFFECT, $result['activity']->field('effect_id'));
    }

    public function test_installed_journey_stops_after_current_scope_rejection_without_activity_or_pii_output(): void
    {
        $fixture = $this->fixture([self::reply(self::lead()), new Response(403, ['Content-Type' => 'application/json'],
            json_encode(['error' => ['code' => 'crm_access_denied', 'subcode' => 'scope', 'message' => self::PRIVATE_MARKER]], JSON_THROW_ON_ERROR))]);
        $journal = [];
        ob_start();
        try {
            \Factuarea\Sdk\Examples\SalesBatch81\journey($fixture->sales, new CreateLeadRequest(name: self::PRIVATE_MARKER),
                self::PIPELINE, self::STAGE, '12.3000', 'EUR', 'Native opportunity', 'Follow up', 'UTC',
                ['leadCreate' => 'lead-key', 'opportunityCreate' => 'opportunity-key', 'activityCreate' => 'activity-key'],
                static function (string $step, string $key, string $wire) use (&$journal): void {
                    $journal[] = [$step, $key, $wire];
                });
            self::fail();
        } catch (PermissionFailure $error) {
            self::assertStringNotContainsString(self::PRIVATE_MARKER, $error->getMessage().json_encode($error));
            self::assertNull($error->getPrevious());
        } finally {
            $output = ob_get_clean();
        }
        self::assertSame('', $output);
        self::assertCount(2, $journal);
        self::assertCount(2, $fixture->history);
    }

    public function test_two_installed_clients_keep_credentials_and_original_intent_separate(): void
    {
        $one = $this->fixture([self::reply(self::lead())], 'native-client-one');
        $two = $this->fixture([self::reply(self::lead())], 'native-client-two');
        $intent = $one->sales->prepareLead(new CreateLeadRequest(name: self::PRIVATE_MARKER), 'client-one-key');
        try {
            $two->sales->dispatch($intent);
            self::fail();
        } catch (\LogicException) {
            self::assertCount(0, $two->history);
        }
        $one->sales->dispatch($intent);
        $two->sales->dispatch($two->sales->prepareLead(new CreateLeadRequest(name: 'Other'), 'client-two-key'));
        self::assertSame('Bearer native-client-one', $one->history[0]['request']->getHeaderLine('Authorization'));
        self::assertSame('Bearer native-client-two', $two->history[0]['request']->getHeaderLine('Authorization'));
        self::assertSame('client-one-key', $one->history[0]['request']->getHeaderLine('Idempotency-Key'));
        self::assertSame('client-two-key', $two->history[0]['request']->getHeaderLine('Idempotency-Key'));
    }

    public static function typedRejections(): array
    {
        return [[422, ValidationFailure::class, 'parameter_invalid', 'email_invalid'],
            [409, ConflictFailure::class, 'crm_version_conflict', 'version'],
            [403, PermissionFailure::class, 'crm_access_denied', 'scope'],
            [429, RateLimitFailure::class, 'rate_limit_exceeded', 'throttled'],
            [409, WriteUnconfirmed::class, 'crm_effect_unconfirmed', 'crm_original_unconfirmed'],
            [503, WriteUnconfirmed::class, 'native_unavailable', 'unavailable']];
    }

    #[DataProvider('typedRejections')]
    public function test_installed_typed_error_is_safe_and_consumes_only_one_dispatch(int $status, string $type, string $code, string $subcode): void
    {
        $fixture = $this->fixture([new Response($status, ['Content-Type' => 'application/json', 'X-Request-Id' => 'req_safe', 'Retry-After' => '120'],
            json_encode(['error' => ['code' => $code, 'subcode' => $subcode, 'param' => 'email', 'message' => self::PRIVATE_MARKER,
                'raw_token' => 'native-client-secret']], JSON_THROW_ON_ERROR))], 'native-client-secret');
        $intent = $fixture->sales->prepareLead(new CreateLeadRequest(name: self::PRIVATE_MARKER), 'private-first-key');
        ob_start();
        try {
            $fixture->sales->dispatch($intent);
            self::fail();
        } catch (CrmFailure $error) {
            self::assertInstanceOf($type, $error);
            self::assertSame($status, $error->status);
            self::assertSame('req_safe', $error->requestId);
            self::assertSame('120', $error->retryAfter);
            self::assertSame($code, $error->machineCode);
            self::assertNull($error->getPrevious());
            $render = $error->getMessage().json_encode($error);
            foreach ([self::PRIVATE_MARKER, 'native-client-secret', 'private-first-key', $intent->firstBody] as $private) {
                self::assertStringNotContainsString($private, $render);
            }
        } finally {
            $output = ob_get_clean();
        }
        self::assertSame('', $output);
        try {
            $fixture->sales->dispatch($intent);
            self::fail();
        } catch (\LogicException) {
            self::assertCount(1, $fixture->history);
        }
    }

    public function test_installed_website_recovers_durable_original_after_lost_response_without_write_retry(): void
    {
        $first = '{"name":"'.self::PRIVATE_MARKER.'","email":null,"source":"manual"}';
        $body = ' { "data": {"id":"'.self::LEAD.'","version":1,"amount":"12.3000"} } ';
        $fixture = $this->fixture([new ConnectException('private-provider-diagnostic', new Request('POST', 'https://installed.invalid')),
            self::original($first, 'confirmed', $body)]);
        $journal = [];
        ob_start();
        try {
            \Factuarea\Sdk\Examples\SalesBatch81\submitWebsiteLead($fixture->sales, ['name' => self::PRIVATE_MARKER, 'email' => null], 'original durable key',
                static function (string $step, string $key, string $wire) use (&$journal): void {
                    $journal[] = [$step, $key, $wire];
                });
            self::fail();
        } catch (WriteUnconfirmed $error) {
            self::assertNull($error->getPrevious());
            self::assertStringNotContainsString('private-provider-diagnostic', $error->getMessage());
            $receipt = \Factuarea\Sdk\Examples\SalesBatch81\recoverWebsiteLead($fixture->sales, $journal[0][1], $journal[0][2]);
            self::assertSame('confirmed', $receipt->state);
            self::assertSame($body, $receipt->originalBody);
            self::assertSame(201, $receipt->originalStatus);
            self::assertSame('opaque_current_C7', $receipt->authorizationVersion);
        } finally {
            $output = ob_get_clean();
        }
        self::assertSame('', $output);
        self::assertCount(2, $fixture->history);
        $read = $fixture->history[1]['request'];
        self::assertSame('GET', $read->getMethod());
        self::assertSame('/v1/crm/leads/create/original', $read->getUri()->getPath());
        self::assertSame('original durable key', $read->getHeaderLine('Idempotency-Key'));
        self::assertSame('', (string) $read->getBody());
        parse_str($read->getUri()->getQuery(), $query);
        self::assertSame(['original_body_sha256' => hash('sha256', $first)], $query);
    }

    public function test_private_host_journal_recovers_the_installed_original_after_recreating_the_client(): void
    {
        $journalPath = tempnam(FACTUAREA_INSTALLED_BATCH82_PROOF, 'private-host-journal-');
        self::assertIsString($journalPath);
        $first = '{"name":"'.self::PRIVATE_MARKER.'","email":null,"source":"manual"}';
        $body = ' {"data":{"id":"'.self::LEAD.'","confirmed":true,"representation_available":false}} ';
        $writer = $this->fixture([new Response(503)], 'native-original-credential');
        try {
            try {
                \Factuarea\Sdk\Examples\SalesBatch81\submitWebsiteLead($writer->sales, ['name' => self::PRIVATE_MARKER, 'email' => null], 'persisted-original-key',
                    static function (string $step, string $key, string $wire) use ($journalPath, $writer): void {
                        self::assertCount(0, $writer->history);
                        file_put_contents($journalPath, json_encode(['step' => $step, 'key' => $key, 'first_wire' => $wire], JSON_THROW_ON_ERROR));
                        chmod($journalPath, 0600);
                    });
                self::fail();
            } catch (WriteUnconfirmed) {
                self::assertCount(1, $writer->history);
            }
            self::assertSame(0600, fileperms($journalPath) & 0777);
            $saved = json_decode(file_get_contents($journalPath), true, 512, JSON_THROW_ON_ERROR);
            self::assertSame(['step', 'key', 'first_wire'], array_keys($saved));
            self::assertSame($first, $saved['first_wire']);
            // The original intent object is not transferred; a new installed client reads the private durable journal.
            $reader = $this->fixture([self::original($saved['first_wire'], 'confirmed', $body)], 'native-current-credential');
            $receipt = \Factuarea\Sdk\Examples\SalesBatch81\recoverWebsiteLead($reader->sales, $saved['key'], $saved['first_wire']);
            self::assertSame($body, $receipt->originalBody);
            self::assertSame('opaque_current_C7', $receipt->authorizationVersion);
            self::assertCount(1, $reader->history);
            self::assertSame('GET', $reader->history[0]['request']->getMethod());
            self::assertSame('persisted-original-key', $reader->history[0]['request']->getHeaderLine('Idempotency-Key'));
            self::assertSame('Bearer native-current-credential', $reader->history[0]['request']->getHeaderLine('Authorization'));
        } finally {
            unlink($journalPath);
        }
    }
    public static function nonconfirmedStates(): array
    {
        return [['pending'], ['in_progress'], ['unconfirmed'], ['missing']];
    }

    #[DataProvider('nonconfirmedStates')]
    public function test_installed_recovery_never_turns_nonconfirmed_history_into_a_new_write(string $state): void
    {
        $first = ' {"name":"Native","notes":null} ';
        $fixture = $this->fixture([self::original($first, $state)]);
        $receipt = \Factuarea\Sdk\Examples\SalesBatch81\recoverWebsiteLead($fixture->sales, 'history-key', $first);
        self::assertSame($state, $receipt->state);
        self::assertNull($receipt->originalBody);
        self::assertNull($receipt->originalStatus);
        self::assertCount(1, $fixture->history);
        self::assertSame('GET', $fixture->history[0]['request']->getMethod());
    }

    public function test_installed_original_rejects_a_foreign_first_body_hash_after_one_read(): void
    {
        $fixture = $this->fixture([self::original('{"foreign":true}', 'missing')]);
        try {
            \Factuarea\Sdk\Examples\SalesBatch81\recoverWebsiteLead($fixture->sales, 'history-key', '{}');
            self::fail();
        } catch (CrmFailure $error) {
            self::assertSame('invalid_native_original', $error->machineCode);
            self::assertCount(1, $fixture->history);
            self::assertSame('GET', $fixture->history[0]['request']->getMethod());
        }
    }

    public function test_installed_decimal_omitted_null_and_forward_fields_keep_native_types(): void
    {
        $request = new CreateOpportunityRequest('Native', self::PIPELINE, self::STAGE, '12.3000', 'EUR', leadId: self::LEAD, ownerId: null);
        self::assertSame('12.3000', $request->amount);
        self::assertNull($request->toArray()['owner_id']);
        self::assertArrayNotHasKey('team_id', $request->toArray());
        try {
            (new \ReflectionClass(CreateOpportunityRequest::class))->newInstanceArgs(['Native', self::PIPELINE, self::STAGE, 1.2, 'EUR', self::LEAD]);
            self::fail();
        } catch (\InvalidArgumentException) {
            self::assertTrue(true);
        }
        $fixture = $this->fixture([self::reply(self::lead())]);
        $result = $fixture->sales->dispatch($fixture->sales->prepareLead(new CreateLeadRequest(name: 'Native'), 'native-key'));
        self::assertSame('future_native_status', $result->field('status'));
        self::assertNull($result->field('notes'));
        self::assertSame(Omitted::Value, $result->field('email'));
    }

    public function test_installed_transport_does_not_follow_a_mutation_redirect(): void
    {
        $fixture = $this->fixture([new Response(302, ['Location' => 'https://installed.invalid/redirect']), self::reply(self::lead())]);
        try {
            $fixture->sales->dispatch($fixture->sales->prepareLead(new CreateLeadRequest(name: 'Native'), 'native-key'));
            self::fail();
        } catch (WriteUnconfirmed) {
            self::assertCount(1, $fixture->history);
            self::assertFalse($fixture->history[0]['options']['allow_redirects']);
        }
    }
}
