<?php

declare(strict_types=1);

namespace Factuarea\Sdk\NativeCrm134;

use Factuarea\Sdk\Custom\Crm\Transport;
use Factuarea\Sdk\Factuarea;

/** Explicit Native149 Source selection; it does not activate backend capabilities. */
final readonly class SourceClient
{
    public StageAdministration $stageOperations;
    public Stages\UpdateClient $stages;
    public BulkOperations $bulkOperations;
    public Bulk\ApplyClient $ticketBulk;
    public Library $library;
    public Library\RestoreOriginalClient $restoreOriginal;

    private function __construct(Factuarea $sdk)
    {
        $directory = dirname(__DIR__, 2).'/spec/';
        $contents = file_get_contents($directory.'crm-native134-provenance.json');
        if ($contents === false) {
            throw new \LogicException('The private Native149 Source provenance is missing.');
        }
        $provenance = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        if (! is_array($provenance)) {
            throw new \LogicException('The private Native149 provenance must be an object.');
        }
        $profile = $provenance['source_profile'] ?? [];
        if (($provenance['group'] ?? null) !== 149 || ($profile['name'] ?? null) !== 'crm-source-contracts'
            || ($profile['published'] ?? null) !== false || ($profile['ratified'] ?? null) !== false
            || ($profile['delivery_enabled'] ?? null) !== false || ($provenance['published'] ?? null) !== false
            || ($provenance['ratified'] ?? null) !== false || ($provenance['delivery_enabled'] ?? null) !== false
            || ($provenance['capabilities'] ?? null) !== []) {
            throw new \LogicException('This entry point requires the explicit unreleased Native149 Source profile.');
        }
        foreach (['schemas' => 'schema_cache_sha256', 'consumer-manifest' => 'consumer_manifest_sha256'] as $file => $field) {
            $expected = $provenance[$field] ?? null;
            $actual = hash_file('sha256', $directory.'crm-native134-'.$file.'.json');
            if (! is_string($expected) || $actual === false || ! hash_equals($expected, $actual)) {
                throw new \LogicException('The private Native149 input must retain its exact exported bytes and native selection.');
            }
        }
        // Full native export is a private generator input; installed consumers use the pinned schema cache.
        $nativeInput = $directory.'crm-native134-source.json';
        $sourceHash = $provenance['artifact_sha256'] ?? null;
        $cache = json_decode((string) file_get_contents($directory.'crm-native134-schemas.json'), true, 512, JSON_THROW_ON_ERROR);
        if (! is_string($sourceHash) || ! preg_match('/^[a-f0-9]{64}$/D', $sourceHash)
            || ($cache['x-native-source-sha256'] ?? null) !== $sourceHash
            || (is_file($nativeInput) && ! hash_equals($sourceHash, (string) hash_file('sha256', $nativeInput)))) {
            throw new \LogicException('The native generation provenance must match the installed schema cache.');
        }
        $transport = new Transport($sdk->sdkConfiguration, currentSecurity: true);
        $this->stageOperations = new StageAdministration($transport);
        $this->stages = new Stages\UpdateClient($this->stageOperations);
        $this->bulkOperations = new BulkOperations($transport);
        $this->ticketBulk = new Bulk\ApplyClient($this->bulkOperations);
        $this->library = new Library($transport);
        $this->restoreOriginal = new Library\RestoreOriginalClient($this->library);
    }

    public static function privateContract(Factuarea $sdk): self
    {
        return new self($sdk);
    }
}
