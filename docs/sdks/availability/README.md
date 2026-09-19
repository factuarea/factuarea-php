# Storefront.Availability

## Overview

### Available Operations

* [publicApiV1StorefrontAvailabilityBulkResolve](#publicapiv1storefrontavailabilitybulkresolve) - Resolve storefront availability in bulk
* [publicApiV1StorefrontAvailabilityShow](#publicapiv1storefrontavailabilityshow) - Retrieve storefront availability

## publicApiV1StorefrontAvailabilityBulkResolve

Ask for the availability of a whole cart in ONE request, up to the declared ceiling of 50 selections, with the same selection shape as the price resolution. It is authenticated with the publishable storefront key that travels in the browser of an anonymous shopper. Every entry comes back with its index and either its availability or an error code with its message, so a line that points at an article of another company is reported as not found with its index while the others answer normally. Sending more selections than the ceiling is rejected with a validation error and resolves nothing. The figure and the rules are the ones of the single read: physical minus committed by live reservations, no per-warehouse breakdown and no internal quantity. It is a read that travels as a POST and needs no idempotency key.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.availability.bulk_resolve" method="post" path="/companies/{company}/storefront/availability/bulk-resolve" example="api_key_revoked" -->
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

$body = new Components\BulkResolveStorefrontAvailabilityRequest(
    selections: [],
);

$response = $sdk->storefront->availability->publicApiV1StorefrontAvailabilityBulkResolve(
    company: 'Parker Inc',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.availability.bulk_resolve" method="post" path="/companies/{company}/storefront/availability/bulk-resolve" example="invalid_api_key" -->
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

$body = new Components\BulkResolveStorefrontAvailabilityRequest(
    selections: [],
);

$response = $sdk->storefront->availability->publicApiV1StorefrontAvailabilityBulkResolve(
    company: 'Mueller, Lind and Green',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.availability.bulk_resolve" method="post" path="/companies/{company}/storefront/availability/bulk-resolve" example="missing_api_key" -->
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

$body = new Components\BulkResolveStorefrontAvailabilityRequest(
    selections: [],
);

$response = $sdk->storefront->availability->publicApiV1StorefrontAvailabilityBulkResolve(
    company: 'Abshire, Sanford and Conn',
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
| `body`                                                                                                                                                                                                                                                                                                                                                                                           | [Components\BulkResolveStorefrontAvailabilityRequest](../../Models/Components/BulkResolveStorefrontAvailabilityRequest.md)                                                                                                                                                                                                                                                                       | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1StorefrontAvailabilityBulkResolveResponse](../../Models/Operations/PublicApiV1StorefrontAvailabilityBulkResolveResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1StorefrontAvailabilityShow

Get the units of an article a shopper can still buy. The figure published is the PHYSICAL quantity MINUS the quantity COMMITTED by live reservations, and never the physical balance: publishing the physical balance oversells exactly what other carts are already holding. A reservation that has expired commits nothing, even if nobody has released it yet. It is authenticated with the publishable storefront key that travels in the browser of an anonymous shopper, and the answer has FOUR fields and not one more — the article, the variant when there is one, the available total and whether the article is tracked by stock. There is NO per-warehouse breakdown, no physical quantity, no committed quantity, no reorder point, no cost and no supplier: the breakdown is the operation of the seller, it would reveal how many sites they have and how much sits in each, and no shopper page needs it to decide whether three units can be bought. When the article is not tracked by stock the available total comes back empty, and that is not a selling limit. This is the SAME read the order creation checks against, so a quantity that was enough here and is not enough at checkout means stock or reservations moved in between, never that two figures disagree.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.availability.show" method="get" path="/companies/{company}/storefront/availability" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1StorefrontAvailabilityShowRequest(
    company: 'Welch Group',
    productId: 'e75c6557-71d3-43d6-88c0-ed08639fb3f1',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->storefront->availability->publicApiV1StorefrontAvailabilityShow(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                          | Type                                                                                                                               | Required                                                                                                                           | Description                                                                                                                        |
| ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                         | [Operations\PublicApiV1StorefrontAvailabilityShowRequest](../../Models/Operations/PublicApiV1StorefrontAvailabilityShowRequest.md) | :heavy_check_mark:                                                                                                                 | The request object to use for the request.                                                                                         |

### Response

**[?Operations\PublicApiV1StorefrontAvailabilityShowResponse](../../Models/Operations/PublicApiV1StorefrontAvailabilityShowResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |