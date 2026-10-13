<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\NativeContractBatch84;

use Factuarea\Sdk\Custom\Crm\Documents\Omitted;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\Client;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\CreateActivityRequest;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\CreateLeadRequest;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\CreateOpportunityRequest;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\LeadPatchRequest;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\Wire;
use Factuarea\Sdk\Models\Components\Security;
use Factuarea\Sdk\SDKConfiguration;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use TypeError;

// The existing optimized Composer classmap predates the handwritten CRM layer.
// Load the same real SDK Source; do not change the shared dependency installation.
require_once dirname(__DIR__, 4).'/src/Custom/Crm/Documents/Omitted.php';
foreach (['OAuthBearerClient', 'CredentialContext', 'OriginalIntent', 'OriginalReceipt'] as $class) {
    require_once dirname(__DIR__, 4).'/src/Custom/Crm/OAuthOriginals/'.$class.'.php';
}
foreach (['Wire', 'CreateLeadRequest', 'CreateOpportunityRequest', 'CreateActivityRequest', 'LeadPatchRequest', 'WriteIntent', 'CrmFailure',
    'ValidationFailure', 'ConflictFailure', 'PermissionFailure', 'RateLimitFailure', 'WriteUnconfirmed', 'SalesResult', 'LeadPage', 'Client'] as $class) {
    require_once dirname(__DIR__, 4).'/src/Custom/Crm/SalesBatch81/'.$class.'.php';
}

final class RequestBoundaryTest extends TestCase
{
    private const UUID_V7 = '019a0000-0000-7abc-8def-abcdef012345';

    private const UUID_V4 = '019a0000-0000-4abc-8def-abcdef012345';

    private function client(array &$history): Client
    {
        $history = [];
        $response = new Response(200, ['Content-Type' => 'application/json'], json_encode(['data' => ['id' => self::UUID_V7, 'confirmed' => true, 'representation_available' => false]], JSON_THROW_ON_ERROR));
        $stack = HandlerStack::create(new MockHandler([$response]));
        $stack->push(Middleware::history($history));
        $config = new SDKConfiguration();
        $config->client = new HttpClient(['handler' => $stack]);
        $config->serverUrl = 'https://boundary.invalid/v1';
        $config->securitySource = fn (): Security => new Security(bearerAuth: 'fact_test_boundary_fixture');

        return new Client($config);
    }

    public function test_lead_identity_preserves_canonical_values_null_and_omission(): void
    {
        $owner = new CreateLeadRequest(name: 'Anonymous fixture', ownerId: self::UUID_V7, teamId: null);
        self::assertSame(['name' => 'Anonymous fixture', 'owner_id' => self::UUID_V7, 'team_id' => null, 'source' => 'manual'], $owner->toArray());
        self::assertSame(Omitted::Value, $owner->notes);

        $team = new CreateLeadRequest(name: 'Anonymous fixture', teamId: self::UUID_V7);
        self::assertSame(['name' => 'Anonymous fixture', 'team_id' => self::UUID_V7, 'source' => 'manual'], $team->toArray());
    }

    #[DataProvider('invalidLeadIdentities')]
    public function test_lead_rejects_identities_the_original_form_request_rejects(string $field, string $uuid): void
    {
        $this->expectException(InvalidArgumentException::class);
        new CreateLeadRequest(...['name' => 'Anonymous fixture', $field => $uuid]);
    }

    public static function invalidLeadIdentities(): iterable
    {
        yield 'owner UUID v4' => ['ownerId', self::UUID_V4];
        yield 'owner uppercase UUID v7' => ['ownerId', strtoupper(self::UUID_V7)];
        yield 'team UUID v4' => ['teamId', self::UUID_V4];
        yield 'team uppercase UUID v7' => ['teamId', strtoupper(self::UUID_V7)];
    }

    public function test_generic_uuid_and_other_sales_requests_keep_their_distinct_contracts(): void
    {
        $uppercaseV7 = strtoupper(self::UUID_V7);
        self::assertSame(self::UUID_V4, Wire::uuid(self::UUID_V4, true));
        self::assertSame($uppercaseV7, Wire::uuid($uppercaseV7));

        $opportunity = new CreateOpportunityRequest('Anonymous fixture', $uppercaseV7, $uppercaseV7, '9007199254740993.0001', 'EUR', leadId: $uppercaseV7, ownerId: self::UUID_V4);
        self::assertSame(self::UUID_V4, $opportunity->toArray()['owner_id']);
        self::assertSame($uppercaseV7, $opportunity->toArray()['pipeline_id']);
        self::assertSame('9007199254740993.0001', $opportunity->toArray()['amount']);

        $activity = new CreateActivityRequest('call', 'Anonymous fixture', 'lead', $uppercaseV7, 'Europe/Madrid', ownerId: self::UUID_V4);
        self::assertSame(self::UUID_V4, $activity->toArray()['owner_id']);
        self::assertSame($uppercaseV7, $activity->toArray()['target']['id']);
    }

    #[DataProvider('validLeadVersions')]
    public function test_lead_cas_boundaries_remain_exact_json_integers(int $version): void
    {
        $request = new LeadPatchRequest($version, notes: null);
        self::assertSame(['expected_version' => $version, 'notes' => null], $request->toArray());
        self::assertSame(['expected_version' => $version, 'notes' => null], json_decode(Wire::encode($request->toArray()), true, 64, JSON_THROW_ON_ERROR));
    }

    public static function validLeadVersions(): iterable
    {
        yield 'first version' => [1];
        yield 'maximum unsigned 32-bit version' => [4294967295];
    }

    #[DataProvider('invalidLeadVersions')]
    public function test_lead_rejects_versions_outside_its_original_unsigned_range(int $version): void
    {
        $this->expectException(InvalidArgumentException::class);
        new LeadPatchRequest($version);
    }

    public static function invalidLeadVersions(): iterable
    {
        yield 'zero' => [0];
        yield 'negative' => [-1];
        yield 'first overflow' => [4294967296];
        yield 'signed platform maximum belongs to no lead CAS' => [PHP_INT_MAX];
    }

    #[DataProvider('nonIntegerLeadVersions')]
    public function test_lead_cas_keeps_its_strict_integer_input_type(mixed $version): void
    {
        $this->expectException(TypeError::class);
        new LeadPatchRequest($version);
    }

    public static function nonIntegerLeadVersions(): iterable
    {
        yield 'numeric string' => ['1'];
        yield 'decimal number' => [1.0];
        yield 'boolean' => [true];
        yield 'null' => [null];
    }

    #[DataProvider('invalidLeadRoutes')]
    public function test_lead_route_is_rejected_before_creating_or_dispatching_an_intent(string $uuid): void
    {
        $history = [];
        $client = $this->client($history);
        try {
            $client->prepareLeadPatch($uuid, new LeadPatchRequest(1), 'boundary-original');
            self::fail('The original Lead route validator rejects this identity.');
        } catch (InvalidArgumentException) {
            self::assertSame([], $history);
        }
    }

    public static function invalidLeadRoutes(): iterable
    {
        yield 'UUID v4' => [self::UUID_V4];
        yield 'uppercase UUID v7' => [strtoupper(self::UUID_V7)];
        yield 'numeric identity' => ['17'];
    }

    public function test_canonical_lead_route_dispatches_one_fake_request_with_exact_body(): void
    {
        $history = [];
        $client = $this->client($history);
        $intent = $client->prepareLeadPatch(self::UUID_V7, new LeadPatchRequest(1, notes: null), 'boundary-original');
        self::assertSame([], $history);

        $result = $client->dispatch($intent);
        self::assertTrue($result->confirmed);
        self::assertSame(self::UUID_V7, $result->id);
        self::assertCount(1, $history);
        $request = $history[0]['request'];
        self::assertSame('PATCH', $request->getMethod());
        self::assertSame('/v1/crm/leads/'.self::UUID_V7, $request->getUri()->getPath());
        self::assertSame('{"expected_version":1,"notes":null}', (string) $request->getBody());
        self::assertSame('boundary-original', $request->getHeaderLine('Idempotency-Key'));
        self::assertFalse($history[0]['options']['allow_redirects']);
    }
}
