<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Examples\SalesBatch81;

use Factuarea\Sdk\Custom\Crm\Documents\Omitted;
use Factuarea\Sdk\Custom\Crm\OAuthOriginals\OriginalIntent;
use Factuarea\Sdk\Custom\Crm\OAuthOriginals\OriginalReceipt;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\Client;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\CreateLeadRequest;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\SalesResult;
use Factuarea\Sdk\Custom\FactuareaClient;
use Factuarea\Sdk\Factuarea;

/** Server only: do not export this environment secret into browser bundles, HTML or form fields. */
function websiteSdk(): Factuarea
{
    $secret = getenv('FACTUAREA_API_KEY');
    if (!is_string($secret) || $secret === '') {
        throw new \RuntimeException('The server credential is unavailable.');
    }

    return FactuareaClient::create($secret);
}

/** Host authenticates/validates its form and persists the exact original journal before a single HTTP dispatch.
 * $persist receives step, original key and exact first wire; it must store privately and durably, without logging PII.
 * The original key comes from the host's existing submission intent; never generate a replacement on timeout.
 */
function submitWebsiteLead(Client $client, array $form, string $originalKey, callable $persist): SalesResult
{
    if (array_diff(array_keys($form), ['name', 'email', 'phone']) !== []) {
        throw new \InvalidArgumentException('Unsupported website form fields.');
    }
    $value = static function (string $field) use ($form): string|null|Omitted {
        if (!array_key_exists($field, $form)) {
            return Omitted::Value;
        }
        if ($form[$field] !== null && !is_string($form[$field])) {
            throw new \InvalidArgumentException('Invalid website field type.');
        }

        return $form[$field];
    };
    $intent = $client->prepareLead(new CreateLeadRequest(name: $value('name'), email: $value('email'), phone: $value('phone')), $originalKey);
    $persist('leadCreate', $intent->key, $intent->firstBody);

    return $client->dispatch($intent);
}

/** After a lost response or a process restart, read the original under the actual current credential. No write occurs. */
function recoverWebsiteLead(Client $client, string $originalKey, #[\SensitiveParameter] string $firstWire): OriginalReceipt
{
    return $client->recoverOriginal(new OriginalIntent('leadCreate',$originalKey,$firstWire));
}
