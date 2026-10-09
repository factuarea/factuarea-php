<?php

/** Read-only Source metadata. No application bootstrap, database or credential access. */
declare(strict_types=1);

$backend = $argv[1] ?? null;
if (! is_string($backend) || ! is_dir($backend.'/app')) {
    throw new RuntimeException('Supply the authoritative app/backend directory.');
}
require dirname(__DIR__).'/vendor/autoload.php';
spl_autoload_register(static function (string $class) use ($backend): void {
    if (str_starts_with($class, 'App\\')) {
        $path = $backend.'/app/'.str_replace('\\', '/', substr($class, 4)).'.php';
        if (is_file($path)) {
            require $path;
        }
    }
});

$manifests = [
    'App\\BusinessContact\\Infrastructure\\Crm\\CrmCapabilityManifest',
    'App\\Lead\\Infrastructure\\Crm\\CrmCapabilityManifest',
    'App\\Pipeline\\Infrastructure\\Crm\\CrmCapabilityManifest',
];
$operations = [];
$sources = [];
foreach ($manifests as $manifestType) {
    $reflection = crmManifestReflection($manifestType);
    $file = $reflection->getFileName();
    if (! is_string($file)) {
        throw new RuntimeException('The owner manifest source cannot be located.');
    }
    $sources[] = ['path' => substr($file, strlen(rtrim($backend, '/')) + 1), 'sha256' => hash_file('sha256', $file)];
    foreach ($reflection->getMethod('definitions')->invoke($reflection->newInstance()) as $definition) {
        foreach ($definition->operations as $operation) {
            $public = array_filter($operation->surfaces, static fn ($surface): bool => $surface->channel === 'v1' && $surface->exclusionReason === null && $surface->routeName !== null);
            if ($public === []) {
                continue;
            }
            if (! class_exists($operation->handlerClass)) {
                throw new RuntimeException('An operation handler is missing.');
            }
            $handler = new ReflectionClass($operation->handlerClass);
            $interfaces = $handler->getInterfaceNames();
            $command = $handler->implementsInterface('App\\Shared\\Application\\Bus\\Command\\CommandHandlerInterface');
            $query = $handler->implementsInterface('App\\Shared\\Application\\Bus\\Query\\QueryHandlerInterface');
            if ($command === $query) {
                throw new RuntimeException('The owner handler must implement exactly one command/query contract.');
            }
            $operations[$operation->key] = ['handler_class' => $operation->handlerClass, 'effect' => $command, 'interfaces' => $interfaces];
        }
    }
}
ksort($operations);
echo json_encode(['classification' => 'source-handler-interfaces', 'sources' => $sources, 'operations' => $operations], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n";

/** @return ReflectionClass<object> */
function crmManifestReflection(string $name): ReflectionClass
{
    if (! class_exists($name)) {
        throw new RuntimeException('The canonical owner manifest cannot be loaded.');
    }

    return new ReflectionClass($name);
}
