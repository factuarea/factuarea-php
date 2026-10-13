<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Examples\SalesBatch81;

use Factuarea\Sdk\Custom\Crm\SalesBatch81\Client;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\CreateActivityRequest;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\CreateLeadRequest;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\CreateOpportunityRequest;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\SalesResult;

/** Composable explicit steps. Pipeline/stage and three original keys are adopted from actual host/owner facts.
 * A rejection or unconfirmed response stops immediately. The host resumes only the failed step after reading its original.
 * No customer conversion, fiscal promotion, automatic search/matching or Opportunity won transition is implied.
 * $persist(step,key,exactFirstWire) is the host's private durable journal, not a new SDK ledger or a logger.
 * @return array{lead:SalesResult,opportunity:SalesResult,activity:SalesResult}
 */
function journey(Client $client, CreateLeadRequest $leadRequest, string $pipelineId, string $stageId, mixed $amount, string $currency,
    string $opportunityTitle, string $activityTitle, string $timezone, array $originalKeys, callable $persist): array
{
    if (!is_string($amount)) {
        throw new \InvalidArgumentException('The native journey amount must be an original decimal string.');
    }
    $steps = ['leadCreate', 'opportunityCreate', 'activityCreate'];
    if (count($originalKeys) !== 3 || array_diff(array_keys($originalKeys), $steps) !== []) {
        throw new \InvalidArgumentException('Three explicit original step keys are required.');
    }
    foreach ($originalKeys as $key) {
        if (!is_string($key) || preg_match('/^[\x20-\x7e]{1,255}$/D', $key) !== 1 || trim($key) !== $key) {
            throw new \InvalidArgumentException('Retain each exact original step key.');
        }
    }
    $leadIntent = $client->prepareLead($leadRequest, $originalKeys['leadCreate']);
    $persist('leadCreate', $leadIntent->key, $leadIntent->firstBody);
    $lead = $client->dispatch($leadIntent);
    $opportunityIntent = $client->prepareOpportunity(new CreateOpportunityRequest($opportunityTitle, $pipelineId, $stageId, $amount, $currency, leadId: $lead->id), $originalKeys['opportunityCreate']);
    $persist('opportunityCreate', $opportunityIntent->key, $opportunityIntent->firstBody);
    $opportunity = $client->dispatch($opportunityIntent);
    $activityIntent = $client->prepareActivity(new CreateActivityRequest('call', $activityTitle, 'opportunity', $opportunity->id, $timezone), $originalKeys['activityCreate']);
    $persist('activityCreate', $activityIntent->key, $activityIntent->firstBody);
    $activity = $client->dispatch($activityIntent);

    return ['lead' => $lead, 'opportunity' => $opportunity, 'activity' => $activity];
}
