<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm;

use Factuarea\Sdk\Custom\Crm\CrmClient;
use Factuarea\Sdk\Custom\Crm\CrmApiException;
use Factuarea\Sdk\Custom\Crm\CrmResponse;
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
use PHPUnit\Framework\TestCase;

/** Keeps the generated consumer surface tied to the native frozen OpenAPI contract. */
final class CrmOperationInventoryContractTest extends TestCase
{
    public function test_every_native_openapi_operation_has_one_typed_generated_sdk_method(): void
    {
        $root = dirname(__DIR__, 3);
        $openApi = $this->json($root.'/spec/crm-openapi.json');
        $effects = $this->json($root.'/spec/crm-effects.json')['operations'];
        $generated = $this->json($root.'/spec/crm-operations.json');
        $expected = [];

        foreach ($openApi['paths'] as $path => $pathItem) {
            foreach ($pathItem as $verb => $operation) {
                if (! in_array(strtolower($verb), ['get', 'post', 'put', 'patch', 'delete'], true)
                    || ! isset($operation['x-crm-operation'])) {
                    continue;
                }

                $operationId = $operation['operationId'];
                $key = $operation['x-crm-operation'];
                $group = $this->groupFor($key);
                $phpMethod = $this->phpMethod($operationId);
                $entry = [
                    'operationId' => $operationId,
                    'method' => strtoupper($verb),
                    'path' => $path,
                    'group' => $group,
                    'request' => $this->pascal($operationId).'Parameters',
                    'response' => $this->pascal($operationId).'Result',
                    'scope' => $operation['x-required-scope'] ?? null,
                    'key' => $key,
                    'effect' => $effects[$key]['effect'] ?? null,
                ];
                if ($phpMethod !== $operationId) {
                    $entry['phpMethod'] = $phpMethod;
                }
                $expected[] = $entry;
            }
        }

        $sort = static function (array $operations): array {
            usort($operations, static fn (array $left, array $right): int => strcmp($left['operationId'], $right['operationId']));

            return $operations;
        };
        self::assertSame($sort($expected), $sort($generated), 'The generated operation map must match every real native OpenAPI operation.');
        self::assertNotEmpty($expected, 'The frozen OpenAPI contract must contain native CRM operations.');

        $clientProperties = (new \ReflectionClass(CrmClient::class))->getProperties();
        $exposedGroups = [];
        foreach ($clientProperties as $property) {
            $type = $property->getType();
            if ($type instanceof \ReflectionNamedType && ! $type->isBuiltin()) {
                $exposedGroups[$type->getName()] = true;
            }
        }

        foreach ($expected as $operation) {
            $groupClass = 'Factuarea\\Sdk\\Custom\\Crm\\'.$operation['group'];
            self::assertArrayHasKey($groupClass, $exposedGroups, 'The CRM client must expose '.$operation['group'].'.');

            $api = new \ReflectionClass($groupClass);
            $methodName = $operation['phpMethod'] ?? $operation['operationId'];
            self::assertTrue($api->hasMethod($methodName), $operation['operationId'].' must be callable on its generated API group.');
            $method = $api->getMethod($methodName);
            $parameters = $method->getParameters();
            self::assertCount(1, $parameters, $operation['operationId'].' must accept one typed request DTO.');
            self::assertSame(
                'Factuarea\\Sdk\\Custom\\Crm\\Model\\'.$operation['request'],
                $parameters[0]->getType()?->getName(),
                $operation['operationId'].' request DTO must match the frozen schema.',
            );
            self::assertSame(CrmResponse::class, $method->getReturnType()?->getName());

            $requestClass = 'Factuarea\\Sdk\\Custom\\Crm\\Model\\'.$operation['request'];
            $responseClass = 'Factuarea\\Sdk\\Custom\\Crm\\Model\\'.$operation['response'];
            self::assertTrue(is_subclass_of($requestClass, WireModel::class), $operation['operationId'].' request model must be generated.');
            self::assertTrue(is_subclass_of($responseClass, WireModel::class), $operation['operationId'].' response model must be generated.');

            $source = file_get_contents($root.'/src/Custom/Crm/'.$operation['group'].'.php');
            self::assertIsString($source);
            $pattern = '/public function '.preg_quote($methodName, '/').'\s*\(.*?\): CrmResponse\s*\{(.*?)(?=\n    public function |\n})/s';
            self::assertSame(1, preg_match($pattern, $source, $matches), $operation['operationId'].' must have a concrete generated implementation.');
            $body = $matches[1];
            self::assertStringContainsString("request('".$operation['method']."'", $body, $operation['operationId'].' HTTP verb drifted.');
            self::assertStringContainsString(var_export(ltrim($operation['path'], '/'), true), $body, $operation['operationId'].' route drifted.');
            self::assertStringContainsString("'".$operation['operationId']."'", $body, $operation['operationId'].' telemetry identity drifted.');
            self::assertStringContainsString('Model\\'.$operation['response'].'::class', $body, $operation['operationId'].' response binding drifted.');
            self::assertSame(
                $effects[$operation['key']]['effect'],
                $operation['effect'],
                $operation['operationId'].' effect classification must come from the native owner snapshot.',
            );
        }
    }

    public function test_crm_clients_keep_their_configured_authentication_isolated(): void
    {
        $firstHistory = [];
        $secondHistory = [];
        $first = $this->client('fact_test_first_client', $firstHistory);
        $second = $this->client('fact_test_second_client', $secondHistory);

        foreach ([$first, $second] as $crm) {
            try {
                $crm->leads->crmListLeads(new \Factuarea\Sdk\Custom\Crm\Model\CrmListLeadsParameters());
                self::fail('The mocked scope denial should prevent a successful CRM response.');
            } catch (CrmApiException) {
                // The request reached its own mock handler; no SDK client state is shared.
            }
        }

        self::assertCount(1, $firstHistory);
        self::assertCount(1, $secondHistory);
        self::assertSame('Bearer fact_test_first_client', $firstHistory[0]['request']->getHeaderLine('Authorization'));
        self::assertSame('Bearer fact_test_second_client', $secondHistory[0]['request']->getHeaderLine('Authorization'));
        self::assertNotSame(
            $firstHistory[0]['request']->getHeaderLine('Authorization'),
            $secondHistory[0]['request']->getHeaderLine('Authorization'),
        );
    }

    /** @return array<string, mixed> */
    private function json(string $path): array
    {
        $contents = file_get_contents($path);
        self::assertIsString($contents, 'Required native CRM contract file is missing: '.$path);
        $decoded = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($decoded);

        return $decoded;
    }

    private function groupFor(string $key): string
    {
        return match (true) {
            str_starts_with($key, 'crm_contact_people.') => 'ContactPeople',
            str_starts_with($key, 'crm_leads.') => 'Leads',
            str_starts_with($key, 'crm_pipelines.') => 'Pipelines',
            str_starts_with($key, 'knowledge_articles.') => 'KnowledgeBase',
            str_starts_with($key, 'public_help_centers.') => 'PublicHelpCenter',
            default => self::fail('OpenAPI exported an operation outside the approved native CRM owners: '.$key),
        };
    }

    private function phpMethod(string $operationId): string
    {
        if (preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/D', $operationId) === 1) {
            return $operationId;
        }

        return lcfirst($this->pascal($operationId));
    }

    private function pascal(string $value): string
    {
        return implode('', array_map(
            static fn (string $part): string => ucfirst($part),
            array_filter(preg_split('/[^A-Za-z0-9]+/', $value) ?: [], static fn (string $part): bool => $part !== ''),
        ));
    }

    /** @param list<array<string, mixed>> $history */
    private function client(string $apiKey, array &$history): CrmClient
    {
        $stack = HandlerStack::create(new MockHandler([
            new Response(403, ['Content-Type' => 'application/json'], json_encode([
                'error' => ['type' => 'authorization_error', 'code' => 'insufficient_scope', 'message' => 'fixture'],
            ], JSON_THROW_ON_ERROR)),
        ]));
        $stack->push(Middleware::history($history));
        $sdk = Factuarea::builder()
            ->setSecurity(new Security(bearerAuth: $apiKey))
            ->setClient(new IdempotencyClient(new Client(['handler' => $stack])))
            ->setRetryConfig(new RetryConfigNone())
            ->build();

        return new CrmClient($sdk);
    }
}
