<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm;

use Factuarea\Sdk\Custom\Crm\CrmApiException;
use Factuarea\Sdk\Custom\Crm\CrmClient;
use Factuarea\Sdk\Custom\Crm\CrmConflictException;
use Factuarea\Sdk\Custom\Crm\CrmPermissionException;
use Factuarea\Sdk\Custom\Crm\CrmRateLimitException;
use Factuarea\Sdk\Custom\Crm\CrmValidationException;
use Factuarea\Sdk\Custom\Crm\Model;
use Factuarea\Sdk\Custom\Crm\Omitted;
use Factuarea\Sdk\Custom\Crm\WireModel;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyClient;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Models\Components\Security;
use Factuarea\Sdk\Utils\Retry\RetryConfigNone;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/** Offline consumer contracts; field admission and tenant isolation remain native server responsibilities. */
final class CrmProjectionSafetyContractTest extends TestCase
{
    private const ID = '019a4afd-5000-7000-8000-000000000001';

    private const SECRET = 'private_projection_fixture/secret';

    /** @var list<array<string, mixed>> */
    private array $history = [];

    public function test_contact_list_roundtrip_keeps_omission_null_empty_values_and_masked_typed_fields_distinct(): void
    {
        $omitted = $this->person();
        $explicit = array_replace($omitted, [
            'id' => '019a4afd-5000-7000-8000-000000000002',
            'job_title' => null, 'contact_id' => null, 'origin' => null,
            'channels' => [], 'addresses' => [], 'tags' => [], 'relationships' => [],
            'access' => new \stdClass(),
            'typed_fields' => ['values' => new \stdClass(), 'definitions_version' => null],
        ]);
        $payload = $this->page([$omitted, $explicit]);
        $client = $this->client($this->response($payload));
        $result = $client->contactPeople->crmContactPeopleIndex(new Model\CrmContactPeopleIndexParameters())->body;

        self::assertInstanceOf(Model\CrmContactPersonListItem::class, $result->data[0]);
        foreach (['jobTitle', 'contactId', 'origin', 'channels', 'addresses', 'tags', 'relationships', 'access', 'typedFields'] as $property) {
            self::assertSame(Omitted::Value, $result->data[0]->{$property}, $property.' must remain absent.');
        }
        self::assertSame($omitted, $result->data[0]->toArray());
        self::assertNull($result->data[1]->jobTitle);
        self::assertNull($result->data[1]->contactId);
        self::assertNull($result->data[1]->origin);
        self::assertSame([], $result->data[1]->channels);
        foreach (['ownerId', 'teamId', 'restriction', 'allowedUserIds'] as $property) {
            self::assertSame(Omitted::Value, $result->data[1]->access->{$property}, 'An empty access projection must not grant defaults.');
        }
        self::assertSame('{}', $result->data[1]->access->toJson());
        self::assertInstanceOf(Model\CrmContactPersonListItemTypedFieldsVariant2::class, $result->data[1]->typedFields);
        self::assertNull($result->data[1]->typedFields->definitionsVersion);
        self::assertSame('{}', $result->data[1]->typedFields->values->toJson());
        self::assertEquals(json_decode(json_encode($payload, JSON_THROW_ON_ERROR)), json_decode($result->toJson()));
        self::assertCount(1, $this->history);
        self::assertSame('', $this->history[0]['request']->getUri()->getQuery());
    }

    /** @return iterable<string, array{array<string, mixed>}> */
    public static function privateProjectionFields(): iterable
    {
        yield 'internal UUID alias' => [['uuid' => self::SECRET]];
        yield 'tenant context' => [['company_id' => self::SECRET]];
        yield 'claim token' => [['claim_token' => self::SECRET]];
        yield 'fabricated permission' => [['access' => ['can_read_fiscal' => self::SECRET]]];
        yield 'private typed value' => [['typed_fields' => ['values' => ['private_value' => self::SECRET], 'definitions_version' => null]]];
    }

    /** @param array<string, mixed> $fields */
    #[DataProvider('privateProjectionFields')]
    public function test_closed_contact_response_rejects_private_fields_without_echoing_their_values(array $fields): void
    {
        $client = $this->client($this->response($this->page([array_replace($this->person(), $fields)])));

        try {
            $client->contactPeople->crmContactPeopleIndex(new Model\CrmContactPeopleIndexParameters());
            self::fail('The response contained a field outside the native contact projection.');
        } catch (\InvalidArgumentException $failure) {
            self::assertNotSame('', $failure->getMessage());
            self::assertStringNotContainsString(self::SECRET, $failure->getMessage());
            self::assertCount(1, $this->history);
        }
    }

    /** @return iterable<string, array{string, string, class-string<WireModel>, array<string, mixed>, int, string, string, class-string<CrmApiException>}> */
    public static function supportedReadFailures(): iterable
    {
        yield 'lead authentication' => ['leads', 'crmFindLead', Model\CrmFindLeadParameters::class,
            ['lead' => self::ID], 401, 'authentication_error', 'invalid_api_key', CrmPermissionException::class];
        yield 'contact scope' => ['contactPeople', 'crmContactPeopleShow', Model\CrmContactPeopleShowParameters::class,
            ['person' => self::ID], 403, 'authorization_error', 'insufficient_scope', CrmPermissionException::class];
        yield 'opaque article' => ['knowledgeBase', 'crmGetKnowledgeArticle', Model\CrmGetKnowledgeArticleParameters::class,
            ['article' => self::ID], 404, 'not_found_error', 'resource_not_found', CrmPermissionException::class];
        yield 'pipeline conflict' => ['pipelines', 'listPipelines', Model\ListPipelinesParameters::class,
            [], 409, 'conflict_error', 'crm_version_conflict', CrmConflictException::class];
        yield 'history validation' => ['leads', 'crmGetLeadHistory', Model\CrmGetLeadHistoryParameters::class,
            ['lead' => self::ID], 422, 'invalid_request_error', 'parameter_invalid', CrmValidationException::class];
        yield 'contact rate limit' => ['contactPeople', 'crmContactPeopleIndex', Model\CrmContactPeopleIndexParameters::class,
            [], 429, 'rate_limit_error', 'rate_limit_exceeded', CrmRateLimitException::class];
        yield 'read server failure' => ['leads', 'crmFindLead', Model\CrmFindLeadParameters::class,
            ['lead' => self::ID], 500, 'api_error', 'internal_error', CrmApiException::class];
    }

    /** @param class-string<WireModel> $requestType @param array<string, mixed> $parameters @param class-string<CrmApiException> $expected */
    #[DataProvider('supportedReadFailures')]
    public function test_supported_read_operations_expose_typed_bounded_errors_without_retaining_private_payloads(
        string $group,
        string $operation,
        string $requestType,
        array $parameters,
        int $status,
        string $type,
        string $code,
        string $expected,
    ): void {
        $client = $this->client($this->response(['error' => [
            'type' => $type, 'code' => $code, 'message' => self::SECRET,
            'subcode' => 'native_policy_denied', 'param' => 'expected_version',
            'request_id' => 'req_payload_fixture', 'doc_url' => 'https://fixture.invalid/'.self::SECRET,
        ]], $status, ['X-Request-Id' => 'req_header_fixture', 'Retry-After' => '7']));

        try {
            $client->{$group}->{$operation}($requestType::fromArray($parameters));
            self::fail('The native rejection must propagate as a typed CRM failure.');
        } catch (CrmApiException $failure) {
            self::assertSame($expected, $failure::class);
            self::assertSame($status, $failure->statusCode);
            self::assertSame($code, $failure->machineCode);
            self::assertSame('native_policy_denied', $failure->subcode);
            self::assertSame('expected_version', $failure->param);
            self::assertSame('req_header_fixture', $failure->requestId);
            self::assertSame('7', $failure->retryAfter);
            self::assertFalse($failure->unconfirmed, 'A read rejection must not become an unconfirmed mutation.');
            $this->assertSanitized($failure);
            self::assertCount(1, $this->history);
            self::assertSame('GET', $this->history[0]['request']->getMethod());
        }
    }

    public function test_invalid_machine_facts_and_headers_are_dropped_with_safe_request_id_fallback(): void
    {
        $client = $this->client($this->response(['error' => [
            'type' => 'invalid_request_error', 'code' => self::SECRET,
            'message' => self::SECRET, 'subcode' => self::SECRET, 'param' => self::SECRET,
            'request_id' => 'req_payload_fixture',
        ]], 422, ['X-Request-Id' => 'Bearer '.self::SECRET, 'Retry-After' => self::SECRET]));

        try {
            $client->leads->crmFindLead(new Model\CrmFindLeadParameters(lead: self::ID));
            self::fail('The native validation failure must propagate.');
        } catch (CrmValidationException $failure) {
            self::assertSame('native_rejection', $failure->machineCode);
            self::assertNull($failure->subcode);
            self::assertNull($failure->param);
            self::assertNull($failure->retryAfter);
            self::assertSame('req_payload_fixture', $failure->requestId);
            $this->assertSanitized($failure);
            self::assertCount(1, $this->history);
        }
    }

    private function assertSanitized(CrmApiException $failure): void
    {
        self::assertNull($failure->getPrevious());
        foreach (['rawResponse', 'container', 'docUrl', 'details', 'errors', 'idempotencyKey'] as $property) {
            self::assertObjectNotHasProperty($property, $failure);
        }
        $stored = json_encode(get_object_vars($failure), JSON_THROW_ON_ERROR);
        foreach ([self::SECRET, 'fact_test_projection_fixture', 'https://fixture.invalid'] as $private) {
            self::assertStringNotContainsString($private, $failure->getMessage());
            self::assertStringNotContainsString($private, $stored);
        }
    }

    /** @return array<string, mixed> */
    private function person(): array
    {
        return ['id' => self::ID, 'kind' => 'person', 'name' => 'Projection fixture', 'version' => 3, 'status' => 'active'];
    }

    /** @param list<array<string, mixed>> $items @return array<string, mixed> */
    private function page(array $items): array
    {
        return ['data' => $items, 'meta' => ['total' => count($items), 'current_page' => 1, 'per_page' => 25, 'last_page' => 1]];
    }

    private function client(Response $response): CrmClient
    {
        $stack = HandlerStack::create(new MockHandler([$response]));
        $stack->push(Middleware::history($this->history));
        $sdk = Factuarea::builder()
            ->setSecurity(new Security(bearerAuth: 'fact_test_projection_fixture'))
            ->setClient(new IdempotencyClient(new Client(['handler' => $stack])))
            ->setRetryConfig(new RetryConfigNone())
            ->build();

        return new CrmClient($sdk);
    }

    /** @param array<string, mixed> $body @param array<string, string> $headers */
    private function response(array $body, int $status = 200, array $headers = []): Response
    {
        return new Response($status, ['Content-Type' => 'application/json'] + $headers, json_encode($body, JSON_THROW_ON_ERROR));
    }
}
