<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Native139;

use Factuarea\Sdk\Custom\Crm\Transport;
use Factuarea\Sdk\Factuarea;

/** Explicit private Source profile. Selecting it does not publish a contract or enable backend capabilities. */
final readonly class SourceClient
{
    public ContactPeople $contactPeople;
    public Leads $leads;
    public Pipelines $pipelines;
    public KnowledgeBase $knowledgeBase;
    public PublicHelpCenter $publicHelpCenter;
    public Opportunities $opportunities;
    public Activities $activities;
    public Documents $documents;
    public TaskAgenda $agenda;
    public SpreadsheetImports\ApplyOriginalClient $imports;
    public NativeEntityExportClient $entityExports;

    private function __construct(Factuarea $sdk)
    {
        $directory = dirname(__DIR__, 4).'/spec/';
        $contents = file_get_contents($directory.'crm-native139-provenance.json');
        if ($contents === false) {
            throw new \LogicException('The private native Source provenance is missing.');
        }
        $provenance = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        $sourceHash = hash_file('sha256', $directory.'crm-native139-source.json');
        $schemaHash = hash_file('sha256', $directory.'crm-native139-schemas.json');
        if ($sourceHash === false || $schemaHash === false
            || ! hash_equals($provenance['artifact_sha256'], $sourceHash)
            || ! hash_equals($provenance['schema_cache_sha256'], $schemaHash)) {
            throw new \LogicException('The private native contract must retain its exact exported bytes.');
        }
        $profile = $provenance['source_profile'] ?? [];
        if (($profile['name'] ?? null) !== 'crm-source-contracts' || ($profile['published'] ?? null) !== false
            || ($profile['ratified'] ?? null) !== false || ($profile['delivery_enabled'] ?? null) !== false
            || ($provenance['published'] ?? null) !== false || ($provenance['ratified'] ?? null) !== false
            || ($provenance['delivery_enabled'] ?? null) !== false || ($provenance['capabilities'] ?? null) !== []) {
            throw new \LogicException('This entry point only consumes the explicit unreleased Source profile.');
        }
        $transport = new Transport($sdk->sdkConfiguration, currentSecurity: true);
        $this->contactPeople = new ContactPeople($transport);
        $this->leads = new Leads($transport);
        $this->pipelines = new Pipelines($transport);
        $this->knowledgeBase = new KnowledgeBase($transport);
        $this->publicHelpCenter = new PublicHelpCenter($transport);
        $this->opportunities = new Opportunities($transport);
        $this->activities = new Activities($transport);
        $this->documents = new Documents($transport);
        $this->agenda = new TaskAgenda($transport);
        $this->imports = new SpreadsheetImports\ApplyOriginalClient($transport, new SpreadsheetImports($transport));
        $this->entityExports = new NativeEntityExportClient($transport);
    }

    public static function privateContract(Factuarea $sdk): self
    {
        return new self($sdk);
    }
}
