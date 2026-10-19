<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Tests\Custom\Crm;

use PHPUnit\Framework\TestCase;

/** Keeps native operation request bodies wired to the shared CRM JSON transport. */
final class CrmContractTest extends TestCase
{
    public function test_each_generated_operation_forwards_only_its_typed_body_to_the_shared_transport(): void
    {
        $root = dirname(__DIR__, 3);
        $operations = $this->json($root.'/spec/crm-operations.json');

        foreach ($operations as $operation) {
            $methodName = $operation['phpMethod'] ?? $operation['operationId'];
            $source = file_get_contents($root.'/src/Custom/Crm/'.$operation['group'].'.php');
            self::assertIsString($source, 'Generated CRM API group is missing: '.$operation['group']);
            $pattern = '/public function '.preg_quote($methodName, '/'). '\s*\(.*?\): CrmResponse\s*\{(.*?)(?=\n    public function |\n})/s';
            self::assertSame(1, preg_match($pattern, $source, $matches), $operation['operationId'].' must have a concrete API method.');

            $requestClass = 'Factuarea\\Sdk\\Custom\\Crm\\Model\\'.$operation['request'];
            self::assertTrue(class_exists($requestClass), $operation['operationId'].' request model must be loadable.');
            $body = array_key_exists('body', $requestClass::FIELDS) ? '$request->body' : 'null';
            self::assertStringContainsString(
                ', $query, '.$body.', ',
                $matches[1],
                $operation['operationId'].' must forward its schema body, or no body, to the transport.',
            );
        }

        $transport = file_get_contents($root.'/src/Custom/Crm/Transport.php');
        self::assertIsString($transport);
        self::assertStringContainsString('$body->validate();', $transport);
        self::assertStringContainsString(
            '$body === null ? null : json_encode($body, JSON_THROW_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION)',
            $transport,
            'The shared transport must encode typed CRM bodies without numeric precision loss.',
        );
    }

    /** @return list<array<string, mixed>> */
    private function json(string $path): array
    {
        $contents = file_get_contents($path);
        self::assertIsString($contents, 'Required native CRM contract file is missing: '.$path);
        $decoded = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($decoded);

        return $decoded;
    }
}
