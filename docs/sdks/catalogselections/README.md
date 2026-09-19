# Storefront.CatalogSelections

## Overview

### Available Operations

* [publicApiV1StorefrontCatalogSelectionsResolve](#publicapiv1storefrontcatalogselectionsresolve) - Resolve a storefront catalog selection

## publicApiV1StorefrontCatalogSelectionsResolve

Resolve a shopper selection — the article, the variant, the presentation, the configuration and the option values chosen so far — and get back the resolved target and whether that combination CAN BE OFFERED, before asking for its price or putting it in a cart. It is authenticated with the publishable storefront key that travels in the browser of an anonymous shopper. It is a read that travels as a POST because the selection does not fit in a query string: nothing is created, nothing is modified, no idempotency key is needed and its rate-limit family is the read one. A combination that cannot be offered is NOT an error: it comes back with 200, with the offerable flag set to false and the reason code that explains it, because the answer to a legitimate question is an answer and the rejection belongs to the operations that confirm. The response also says which option groups are still pending, whether the selection is complete and whether the company resolves its configurable catalog by exact list — where a combination that was never declared cannot be offered — or by open cross, where only the declared restrictions rule it out. A reference that belongs to another company answers as not found.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.catalog_selections.resolve" method="post" path="/companies/{company}/storefront/catalog-selections/resolve" example="api_key_revoked" -->
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

$body = new Components\ResolveStorefrontCatalogSelectionRequest(
    productId: '7214ad55-aed9-42f3-bd9f-4338845672c9',
);

$response = $sdk->storefront->catalogSelections->publicApiV1StorefrontCatalogSelectionsResolve(
    company: 'Kuphal - Streich',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.catalog_selections.resolve" method="post" path="/companies/{company}/storefront/catalog-selections/resolve" example="invalid_api_key" -->
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

$body = new Components\ResolveStorefrontCatalogSelectionRequest(
    productId: '7214ad55-aed9-42f3-bd9f-4338845672c9',
);

$response = $sdk->storefront->catalogSelections->publicApiV1StorefrontCatalogSelectionsResolve(
    company: 'Marquardt, Huels and Braun',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.catalog_selections.resolve" method="post" path="/companies/{company}/storefront/catalog-selections/resolve" example="missing_api_key" -->
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

$body = new Components\ResolveStorefrontCatalogSelectionRequest(
    productId: '7214ad55-aed9-42f3-bd9f-4338845672c9',
);

$response = $sdk->storefront->catalogSelections->publicApiV1StorefrontCatalogSelectionsResolve(
    company: 'Volkman - Simonis',
    body: $body,
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
| `company`                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `body`                                                                                                                                                                                                                                                                                                                                                                                           | [Components\ResolveStorefrontCatalogSelectionRequest](../../Models/Components/ResolveStorefrontCatalogSelectionRequest.md)                                                                                                                                                                                                                                                                       | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1StorefrontCatalogSelectionsResolveResponse](../../Models/Operations/PublicApiV1StorefrontCatalogSelectionsResolveResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |