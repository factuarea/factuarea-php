# TaxCatalog

## Overview

### Available Operations

* [publicApiV1TaxCatalogShow](#publicapiv1taxcatalogshow) - Retrieve the tax catalog

## publicApiV1TaxCatalogShow

Return, in a single document, the Spanish tax knowledge you need to build a compliant invoicing form: indirect tax regimes (IVA, IGIC, IPSI) with their legal rates and their VeriFactu L1 code, header-level operation regimes with the legal wording each one requires, exemption causes with their AEAT code, legal wording and article of the Spanish VAT Act, the system IRPF withholding rates and the closed matrix of legal VAT/equivalence-surcharge pairs. It replaces the hardcoded table every integration ends up maintaining by hand.

The catalog carries no data of the authenticated company: two different companies receive byte-identical bodies for the same language, and `retention_rates` never includes the custom taxes a company creates through `POST /v1/taxes`. Withholding rates are published in POSITIVE, so apply them as a deduction from the taxable base.

This is the catalog of what the platform supports, NOT an exhaustive normative list of every regime, exemption or withholding rate Spanish law defines. Use it to know what you can send to this API; do not read it as tax advice or as a substitute for the legislation.

Every entry carries its `label` (and, in the two normative blocks, its `description`) in Spanish, English and Catalan at once. `Accept-Language` only picks the language reported in `primary_language`; it never filters the payload, so one cached document is enough to render a multilingual selector. The response is cacheable: it ships `ETag` and a public `Cache-Control`, and sending the validator back in `If-None-Match` returns 304 with no body. Two languages produce two different `ETag`s, because the negotiated language travels inside the body.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.tax-catalog.show" method="get" path="/tax-catalog" example="success" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->taxCatalog->publicApiV1TaxCatalogShow(
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                        | Type                                                                                                                                                                                                                                                                                                                                                                                             | Required                                                                                                                                                                                                                                                                                                                                                                                         | Description                                                                                                                                                                                                                                                                                                                                                                                      | Example                                                                                                                                                                                                                                                                                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1TaxCatalogShowResponse](../../Models/Operations/PublicApiV1TaxCatalogShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 429       | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |