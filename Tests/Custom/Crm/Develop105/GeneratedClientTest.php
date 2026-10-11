<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm\Develop105;

use Factuarea\Sdk\Custom\Crm\CrmClient;
use Factuarea\Sdk\Custom\Crm\Model;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyClient;
use Factuarea\Sdk\Custom\Version\FactuareaVersionHook;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Models\Components\Security;
use Factuarea\Sdk\Models\Errors\ErrorThrowable;
use Factuarea\Sdk\Utils\Retry\RetryConfigNone;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/** Fake HTTP only. These cases do not activate a CRM capability or execute a real API. */
final class GeneratedClientTest extends TestCase
{
    public function test_generated_post_query_uses_each_clients_current_auth_without_an_invented_effect_key(): void
    {
        $historyA = [];
        $historyB = [];
        $clientA = $this->client('fact_test_source_A', [$this->response(['data' => [
            'accepted_values' => new \stdClass, 'conflicts' => [], 'definitions_version' => null,
        ]])], $historyA);
        $clientB = $this->client('fact_test_source_B', [$this->response(['error' => [
            'type' => 'authorization_error', 'code' => 'insufficient_scope', 'message' => 'Read denied',
            'request_id' => 'req_source105',
        ]], 403)], $historyB);
        $request = new Model\CrmContactPeopleNormalizeParameters(
            body: new Model\PreviewContactNormalizationV1Request(kind: 'person', name: 'Source fixture'));

        $result = $clientA->contactPeople->crmContactPeopleNormalize($request);
        self::assertNull($result->idempotencyKey);
        self::assertNull($result->body->data->definitionsVersion);
        self::assertSame([], $result->body->data->conflicts);
        try {
            $clientB->contactPeople->crmContactPeopleNormalize($request);
            self::fail('The second credential must retain its native scope denial.');
        } catch (ErrorThrowable $error) {
            self::assertSame('insufficient_scope', $error->container->error->code);
            self::assertSame('req_source105', $error->container->error->requestId);
            self::assertSame(403, $error->container->rawResponse->getStatusCode());
            self::assertStringNotContainsString('fact_test_source_', (string) $error);
        }
        foreach ([[$historyA, 'fact_test_source_A'], [$historyB, 'fact_test_source_B']] as [$history, $key]) {
            self::assertCount(1, $history);
            $http = $history[0]['request'];
            self::assertSame('POST', $http->getMethod());
            self::assertSame('/v1/crm/contact-people/normalization-preview', $http->getUri()->getPath());
            self::assertSame('Bearer '.$key, $http->getHeaderLine('Authorization'));
            self::assertSame(FactuareaVersionHook::DEFAULT_VERSION, $http->getHeaderLine('Factuarea-Version'));
            self::assertFalse($http->hasHeader('Idempotency-Key'));
            self::assertFalse($history[0]['options']['allow_redirects']);
            self::assertSame(['kind' => 'person', 'name' => 'Source fixture'],
                json_decode((string) $http->getBody(), true, 512, JSON_THROW_ON_ERROR));
        }
    }

    public function test_a_native_cursor_equal_to_the_initial_resume_position_stops_before_requesting_it_again(): void
    {
        $cursor = 'native/+resume==:opaque';
        $history = [];
        $client = $this->client('fact_test_cursor', [$this->response(['data' => [
            'data' => [], 'has_more' => true, 'next_cursor' => $cursor,
        ]])], $history);
        $request = new Model\CrmListLeadsParameters(cursor: $cursor, limit: 25);
        try {
            iterator_to_array($client->leads->crmListLeadsItems($request));
            self::fail('An unchanged native resume cursor cannot advance the listing.');
        } catch (\UnexpectedValueException $error) {
            self::assertSame('CRM pagination repeated its cursor or page.', $error->getMessage());
        }
        self::assertCount(1, $history);
        parse_str($history[0]['request']->getUri()->getQuery(), $query);
        self::assertSame($cursor, $query['cursor']);
        self::assertSame('25', $query['limit']);
        self::assertSame($cursor, $request->cursor);
    }

    /** @param list<Response> $responses @param list<array<string,mixed>> $history */
    private function client(string $key, array $responses, array &$history): CrmClient
    {
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($history));
        $sdk = Factuarea::builder()->setSecurity(new Security(bearerAuth: $key))
            ->setClient(new IdempotencyClient(new Client(['handler' => $stack,
                'headers' => ['Idempotency-Key' => 'unrelated-client-default']])))
            ->setRetryConfig(new RetryConfigNone())->build();
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new FactuareaVersionHook);

        return new CrmClient($sdk);
    }

    /** @param array<string,mixed> $body */
    private function response(array $body, int $status = 200): Response
    {
        return new Response($status, ['Content-Type' => 'application/json'], json_encode($body, JSON_THROW_ON_ERROR));
    }
}
