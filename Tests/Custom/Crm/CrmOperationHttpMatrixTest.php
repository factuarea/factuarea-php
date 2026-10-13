<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm;

use Factuarea\Sdk\Custom\Crm\CrmClient;
use Factuarea\Sdk\Custom\Crm\CrmPermissionException;
use Factuarea\Sdk\Custom\Crm\Schema;
use Factuarea\Sdk\Custom\Idempotency\IdempotencyClient;
use Factuarea\Sdk\Custom\Version\FactuareaVersionHook;
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Hooks\BeforeRequestContext;
use Factuarea\Sdk\Hooks\BeforeRequestHook;
use Factuarea\Sdk\Models\Components\Security;
use Factuarea\Sdk\Utils\Retry\RetryConfigNone;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/** Exercises the full native operation map through the configured offline HTTP transport. */
final class CrmOperationHttpMatrixTest extends TestCase
{
    private const API_KEY = 'fact_test_crm_operation_matrix';

    private const UUID = '019a4afd-5000-7000-8000-000000000001';

    public function test_every_native_operation_reaches_the_fake_transport_once_with_its_contract_identity(): void
    {
        $root = dirname(__DIR__, 3);
        $operations = $this->json($root.'/spec/crm-operations.json');
        $responses = [];
        foreach ($operations as $_operation) {
            $responses[] = new Response(
                403,
                ['Content-Type' => 'application/json', 'X-Request-Id' => 'req_operation_matrix'],
                json_encode(['error' => [
                    'type' => 'authorization_error',
                    'code' => 'insufficient_scope',
                    'message' => 'offline scope fixture',
                    'request_id' => 'req_operation_matrix',
                ]], JSON_THROW_ON_ERROR),
            );
        }
        $history = [];
        $operationIds = new \ArrayObject;
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($history));
        $sdk = Factuarea::builder()
            ->setServerUrl('https://api.fixture.invalid/v1')
            ->setSecurity(new Security(bearerAuth: self::API_KEY))
            ->setClient(new IdempotencyClient(new Client(['handler' => $stack])))
            ->setRetryConfig(new RetryConfigNone)
            ->build();
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new FactuareaVersionHook);
        $sdk->sdkConfiguration->hooks->registerBeforeRequestHook(new class($operationIds) implements BeforeRequestHook
        {
            public function __construct(private \ArrayObject $operationIds)
            {
            }

            public function beforeRequest(BeforeRequestContext $context, RequestInterface $request): RequestInterface
            {
                $this->operationIds->append($context->operationID);

                return $request;
            }
        });
        $crm = new CrmClient($sdk);
        $expectedKeys = [];
        $groups = [
            'ContactPeople' => 'contactPeople',
            'Leads' => 'leads',
            'Pipelines' => 'pipelines',
            'KnowledgeBase' => 'knowledgeBase',
            'PublicHelpCenter' => 'publicHelpCenter',
        ];

        foreach ($operations as $operation) {
            $requestClass = 'Factuarea\\Sdk\\Custom\\Crm\\Model\\'.$operation['request'];
            try {
                $requestValues = $this->sampleObject($requestClass::SCHEMA);
                $request = $requestClass::fromArray($requestValues);
            } catch (\Throwable $failure) {
                throw new \LogicException($operation['operationId'].': could not build a valid request fixture.', previous: $failure);
            }
            $expectedKeys[] = $requestValues['idempotency_key'] ?? null;
            $api = $crm->{$groups[$operation['group']]};
            $method = $operation['phpMethod'] ?? $operation['operationId'];

            try {
                $api->{$method}($request);
                self::fail($operation['operationId'].' should be rejected by the offline scope fixture.');
            } catch (CrmPermissionException $exception) {
                self::assertSame(403, $exception->statusCode);
                self::assertSame('req_operation_matrix', $exception->requestId);
            }
        }

        self::assertSame(array_column($operations, 'operationId'), iterator_to_array($operationIds));
        self::assertCount(count($operations), $history, 'Every operation must issue exactly one HTTP request.');

        foreach ($operations as $index => $operation) {
            $request = $history[$index]['request'];
            self::assertSame('Bearer '.self::API_KEY, $request->getHeaderLine('Authorization'));
            self::assertSame(FactuareaVersionHook::DEFAULT_VERSION, $request->getHeaderLine('Factuarea-Version'));
            self::assertSame($operation['method'], $request->getMethod(), $operation['operationId']);
            self::assertSame('/v1'.$this->path($operation['path']), $request->getUri()->getPath(), $operation['operationId']);
            self::assertSame($expectedKeys[$index] ?? '', $request->getHeaderLine('Idempotency-Key'), $operation['operationId']);
        }
    }

    /** @param array<string, mixed> $schema @return array<string, mixed> */
    private function sampleObject(array $schema): array
    {
        $schema = $this->resolve($schema);
        $properties = $schema['properties'] ?? [];
        $required = $schema['required'] ?? [];
        $allOf = $schema['allOf'] ?? [];

        foreach ($allOf as $branch) {
            if (isset($branch['if'])) {
                continue;
            }
            $branch = $this->resolve($branch);
            $properties = array_replace($properties, $branch['properties'] ?? []);
            $required = array_values(array_unique(array_merge($required, $branch['required'] ?? [])));
        }

        $values = [];
        foreach ($required as $name) {
            if (isset($properties[$name])) {
                $values[$name] = $this->sample($properties[$name]);
            }
        }

        foreach ($allOf as $branch) {
            if (! isset($branch['if'])) {
                $values = $this->merge($values, $this->sample($branch));

                continue;
            }
            $selected = $this->schemaMatches($branch['if'], $values) ? ($branch['then'] ?? null) : ($branch['else'] ?? null);
            if (is_array($selected)) {
                $values = $this->satisfyBranch($values, $selected, $properties);
            }
        }

        foreach (['anyOf', 'oneOf'] as $kind) {
            if (! isset($schema[$kind])) {
                continue;
            }
            foreach ($schema[$kind] as $variant) {
                $sample = $this->sampleVariant($variant);
                $candidate = is_array($sample) ? $this->merge($values, $sample)
                    : ($values === [] ? $sample : $values);
                if ($this->schemaMatches($variant, $candidate) && $this->schemaMatches($schema, $candidate)) {
                    $values = $candidate;
                    break;
                }
            }
        }

        foreach ($allOf as $branch) {
            if (! isset($branch['if'])) {
                continue;
            }
            if ($this->schemaMatches($branch['if'], $values)) {
                $selected = $branch['then'] ?? null;
            } else {
                $selected = $branch['else'] ?? null;
            }
            if (is_array($selected) && ! $this->schemaMatches($selected, $values)) {
                $values = $this->satisfyBranch($values, $selected, $properties);
            }
        }

        if (count($values) < ($schema['minProperties'] ?? 0)) {
            foreach ($properties as $name => $property) {
                if (! array_key_exists($name, $values)) {
                    $values[$name] = $this->sample($property);
                    if (count($values) >= $schema['minProperties']) {
                        break;
                    }
                }
            }
        }

        return $values;
    }

    /** @param array<string, mixed> $values @param array<string, mixed> $branch @param array<string, mixed> $properties @return array<string, mixed> */
    private function satisfyBranch(array $values, array $branch, array $properties): array
    {
        foreach ($branch['properties'] ?? [] as $name => $property) {
            if (is_array($property) && (array_key_exists('const', $property) || isset($property['enum']))) {
                $values[$name] = $this->sample($property);
            }
        }
        foreach ($branch['required'] ?? [] as $name) {
            if (! array_key_exists($name, $values)) {
                $property = $branch['properties'][$name] ?? $properties[$name] ?? null;
                if (is_array($property)) {
                    $values[$name] = $this->sample($property);
                }
            }
        }

        return $values;
    }

    /** @param array<string, mixed> $schema */
    private function sampleVariant(array $schema): mixed
    {
        $schema = $this->resolve($schema);
        if (isset($schema['oneOf']) || isset($schema['anyOf'])) {
            return $this->sample($schema);
        }

        if (($schema['type'] ?? null) === 'object' || isset($schema['properties']) || isset($schema['allOf'])) {
            $values = $this->sampleObject($schema);
            foreach ($schema['properties'] ?? [] as $name => $property) {
                if (! array_key_exists($name, $values)) {
                    $values[$name] = $this->sample($property);
                }
            }

            return $values;
        }

        return $this->sample($schema);
    }

    /** @param array<string, mixed> $schema */
    private function sample(array $schema): mixed
    {
        $schema = $this->resolve($schema);
        if (array_key_exists('const', $schema)) {
            return $schema['const'];
        }
        if (isset($schema['enum']) && is_array($schema['enum']) && $schema['enum'] !== []) {
            return $schema['enum'][0];
        }
        if (isset($schema['oneOf']) || isset($schema['anyOf'])) {
            $kind = isset($schema['oneOf']) ? 'oneOf' : 'anyOf';
            $objectShape = ($schema['type'] ?? null) === 'object'
                || isset($schema['properties']) || isset($schema['allOf']);
            $base = $objectShape ? $this->sampleObject($schema) : [];
            foreach ($schema[$kind] as $variant) {
                $sample = $this->sampleVariant($variant);
                $candidate = is_array($sample) ? $this->merge($base, $sample)
                    : ($base === [] ? $sample : $base);
                if ($this->schemaMatches($variant, $candidate) && $this->schemaMatches($schema, $candidate)) {
                    return $candidate;
                }
            }

            throw new \LogicException('Cannot build a request fixture for native schema '.($schema['title'] ?? '(unnamed variant)').'.');
        }

        $types = $schema['type'] ?? null;
        if (is_array($types)) {
            $types = array_values(array_filter($types, static fn (mixed $type): bool => $type !== 'null'))[0] ?? 'null';
        }
        if ($types === 'null') {
            return null;
        }
        if ($types === 'object' || isset($schema['properties']) || isset($schema['allOf'])) {
            $object = $this->sampleObject($schema);

            // In PHP, [] encodes as a JSON array even where the wire schema
            // requires an empty object (for example a closed resolutions map).
            return $object === [] ? (object) [] : $object;
        }
        if ($types === 'array') {
            $minimum = (int) ($schema['minItems'] ?? 0);
            $items = $schema['items'] ?? [];
            $values = [];
            for ($index = 0; $index < $minimum; $index++) {
                $values[] = $this->sample($items);
            }

            return $values;
        }
        if ($types === 'integer') {
            return max(0, (int) ($schema['minimum'] ?? 1));
        }
        if ($types === 'number') {
            return max(0, (float) ($schema['minimum'] ?? 1));
        }
        if ($types === 'boolean') {
            return false;
        }
        if ($types === 'string' || $types === null) {
            return $this->sampleString($schema);
        }

        throw new \LogicException('Cannot build a request fixture for a native CRM schema type.');
    }

    /** @param array<string, mixed> $schema */
    private function sampleString(array $schema): string
    {
        if (isset($schema['format'])) {
            $formatted = match ($schema['format']) {
                'uuid' => self::UUID,
                'date' => '2026-10-13',
                'date-time' => '2026-10-13T12:00:00Z',
                'email' => 'crm@example.test',
                'uri' => 'https://example.test/crm',
                default => null,
            };
            if ($formatted !== null) {
                return $formatted;
            }
        }

        $pattern = $schema['pattern'] ?? '';
        $value = match ($pattern) {
            '^[ -~]+$' => 'crm-original-key-1',
            '^\\S' => 'x',
            '\\S' => 'x',
            'Z$' => '2026-10-13T12:00:00Z',
            '^[^\\x00-\\x1f\\x7f]+$(?![\\s\\S])' => 'x',
            '^[^\\u0000-\\u0008\\u000b\\u000c\\u000e-\\u001f\\u007f]*$' => '',
            '^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$' => self::UUID,
            '^[a-z0-9]+(?:-[a-z0-9]+)*$' => 'crm-fixture',
            '^[a-z][a-z0-9_-]{0,63}$' => 'a',
            '^[a-z][a-z0-9_]{1,99}$' => 'aa',
            '^[0-9a-f]{64}$' => str_repeat('a', 64),
            '^[A-Z]{3}$' => 'EUR',
            '^(0|[1-9][0-9]*)(\\.[0-9]+)?$' => '1.00',
            '^(?:0|[1-9][0-9]*)(?:\\.[0-9]{1,4})?$' => '1.00',
            '^(?:100(?:\\.0{1,2})?|(?:0|[1-9][0-9]?)(?:\\.[0-9]{1,2})?)$' => '1.00',
            default => str_repeat('x', max(1, (int) ($schema['minLength'] ?? 1))),
        };

        return $value;
    }

    /** @param array<string, mixed> $schema */
    private function resolve(array $schema): array
    {
        if (isset($schema['$ref']) && is_string($schema['$ref'])) {
            return Schema::definition(substr($schema['$ref'], strlen('#/components/schemas/')));
        }

        return $schema;
    }

    /** @param array<string, mixed> $schema @param array<string, mixed> $value */
    private function schemaMatches(array $schema, mixed $value): bool
    {
        try {
            Schema::validate($schema, $value);

            return true;
        } catch (\InvalidArgumentException) {
            return false;
        }
    }

    /** @param array<string, mixed> $left @param array<string, mixed> $right @return array<string, mixed> */
    private function merge(array $left, mixed $right): array
    {
        if (! is_array($right)) {
            return $left;
        }

        foreach ($right as $key => $value) {
            if (isset($left[$key]) && is_array($left[$key]) && is_array($value)) {
                $left[$key] = $this->merge($left[$key], $value);
            } else {
                $left[$key] = $value;
            }
        }

        return $left;
    }

    private function path(string $path): string
    {
        return preg_replace_callback('/\{([^}]+)\}/', static fn (array $matches): string => rawurlencode(self::UUID), $path) ?? $path;
    }

    /** @return list<array<string, mixed>> */
    private function json(string $path): array
    {
        $contents = file_get_contents($path);
        self::assertIsString($contents, 'The native CRM operation inventory must exist.');
        $decoded = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($decoded);

        return $decoded;
    }
}
