<?php

/** Sandbox CLI example. API keys stay in the server process environment. */
declare(strict_types=1);

use Factuarea\Sdk\Custom\Crm\CrmClient;
use Factuarea\Sdk\Custom\Crm\Model\CrmCreateLeadParameters;
use Factuarea\Sdk\Custom\Crm\Model\CrmCreateLeadRequest;
use Factuarea\Sdk\Custom\Crm\Model\CrmListLeadsParameters;
use Factuarea\Sdk\Custom\FactuareaClient;

require dirname(__DIR__).'/vendor/autoload.php';

$key = getenv('FACTUAREA_API_KEY');
if (! is_string($key) || ! str_starts_with($key, 'fact_test_')) {
    throw new RuntimeException('Configure a fact_test_ sandbox API key in the host environment.');
}
$crm = new CrmClient(FactuareaClient::create($key));

// The credential binds company, actor and environment. Never send company_id.
foreach ($crm->leads->crmListLeadsItems(new CrmListLeadsParameters(limit: 25), maxPages: 10) as $lead) {
    printf("%s\n", $lead->id);
}

// Creation is explicit. Persist the key with this intent before issuing the call.
if (($argv[1] ?? '') === 'create') {
    $originalKey = getenv('CRM_ORIGINAL_IDEMPOTENCY_KEY');
    if (! is_string($originalKey) || $originalKey === '') {
        throw new RuntimeException('Persist and configure CRM_ORIGINAL_IDEMPOTENCY_KEY for this intent.');
    }
    $result = $crm->leads->crmCreateLead(new CrmCreateLeadParameters(
        body: new CrmCreateLeadRequest(name: 'Sandbox SDK example'),
        idempotencyKey: $originalKey,
    ));
    // A successful result can be a minimal confirmed receipt after access changes.
    printf("%s\n", $result->body->data->id);
}
