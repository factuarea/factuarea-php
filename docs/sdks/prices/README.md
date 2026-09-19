# Storefront.Prices

## Overview

### Available Operations

* [publicApiV1StorefrontPricesBulkResolve](#publicapiv1storefrontpricesbulkresolve) - Resolve storefront prices in bulk
* [publicApiV1StorefrontPricesResolve](#publicapiv1storefrontpricesresolve) - Resolve a storefront price

## publicApiV1StorefrontPricesBulkResolve

Resolve the price of a whole cart in ONE request, up to the declared ceiling of 50 selections, with the same selection shape as the single resolution. It is authenticated with the publishable storefront key that travels in the browser of an anonymous shopper. Resolving line by line would be one request per line from the browser of EVERY shopper, and the admission budget of the lane counts them all, so this is the operation to build the cart on. Failures are reported BY INDEX of the input: every entry comes back with its index and either its price or an error code with its message, so one unresolvable target does not invalidate the rest of the cart and the order of the answer matches the order you sent. Sending more selections than the ceiling is rejected with a validation error and resolves nothing. As in the single resolution, the price is the server's and what you get is a preview: the order line is priced again at creation.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.prices.bulk_resolve" method="post" path="/companies/{company}/storefront/prices/bulk-resolve" example="api_key_revoked" -->
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

$body = new Components\BulkResolveStorefrontPricesRequest(
    selections: [
        new Components\BulkResolveStorefrontPricesRequestSelection(
            productId: 'f9436625-4bfe-433e-813c-21101a7c6f16',
            quantity: 6367.43,
        ),
    ],
);

$response = $sdk->storefront->prices->publicApiV1StorefrontPricesBulkResolve(
    company: 'Prohaska LLC',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.prices.bulk_resolve" method="post" path="/companies/{company}/storefront/prices/bulk-resolve" example="invalid_api_key" -->
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

$body = new Components\BulkResolveStorefrontPricesRequest(
    selections: [
        new Components\BulkResolveStorefrontPricesRequestSelection(
            productId: 'f9436625-4bfe-433e-813c-21101a7c6f16',
            quantity: 6367.43,
        ),
    ],
);

$response = $sdk->storefront->prices->publicApiV1StorefrontPricesBulkResolve(
    company: 'Koch, Langworth and Wolf',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.prices.bulk_resolve" method="post" path="/companies/{company}/storefront/prices/bulk-resolve" example="missing_api_key" -->
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

$body = new Components\BulkResolveStorefrontPricesRequest(
    selections: [
        new Components\BulkResolveStorefrontPricesRequestSelection(
            productId: 'f9436625-4bfe-433e-813c-21101a7c6f16',
            quantity: 6367.43,
        ),
    ],
);

$response = $sdk->storefront->prices->publicApiV1StorefrontPricesBulkResolve(
    company: 'Larkin, Brown and Robel',
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
| `body`                                                                                                                                                                                                                                                                                                                                                                                           | [Components\BulkResolveStorefrontPricesRequest](../../Models/Components/BulkResolveStorefrontPricesRequest.md)                                                                                                                                                                                                                                                                                   | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1StorefrontPricesBulkResolveResponse](../../Models/Operations/PublicApiV1StorefrontPricesBulkResolveResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1StorefrontPricesResolve

Resolve the price of ONE shopper selection with the SAME pricing engine the merchant uses, so the storefront never has to compute an amount of its own. It is authenticated with the publishable storefront key that travels in the browser of an anonymous shopper. You get the unit price, the currency, the unit code and the unit semantics applied, the quantity, the applicable tax and the line total, as decimal strings with the scale of the sales family. What you deliberately do NOT get is which price list won, the source amount, the breakdown of option adjustments or the name of the resolver: those are commercial data of the seller and no shopper needs them to buy. Read this before you charge anything: THE PRICE IS ALWAYS THE SERVER'S, and what this operation returns is a PREVIEW. The amount an order line freezes is resolved again on the server when the order is created and any amount present in a request body is ignored, so between the preview and the order the amount can legitimately change if the catalog or the price list changed — which is what the cart revalidation is for. It is a read that travels as a POST because the selection does not fit in a query string: it creates nothing and needs no idempotency key.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.prices.resolve" method="post" path="/companies/{company}/storefront/prices/resolve" example="api_key_revoked" -->
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

$body = new Components\ResolveStorefrontPriceRequest(
    productId: 'ec8378d3-0201-48b6-98f3-2a0677714381',
    quantity: 5767.76,
);

$response = $sdk->storefront->prices->publicApiV1StorefrontPricesResolve(
    company: 'Quitzon, Metz and Reichel',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.prices.resolve" method="post" path="/companies/{company}/storefront/prices/resolve" example="invalid_api_key" -->
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

$body = new Components\ResolveStorefrontPriceRequest(
    productId: 'ec8378d3-0201-48b6-98f3-2a0677714381',
    quantity: 5767.76,
);

$response = $sdk->storefront->prices->publicApiV1StorefrontPricesResolve(
    company: 'Witting - Wisoky',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.prices.resolve" method="post" path="/companies/{company}/storefront/prices/resolve" example="missing_api_key" -->
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

$body = new Components\ResolveStorefrontPriceRequest(
    productId: 'ec8378d3-0201-48b6-98f3-2a0677714381',
    quantity: 5767.76,
);

$response = $sdk->storefront->prices->publicApiV1StorefrontPricesResolve(
    company: 'Boehm, Hirthe and Greenholt',
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
| `body`                                                                                                                                                                                                                                                                                                                                                                                           | [Components\ResolveStorefrontPriceRequest](../../Models/Components/ResolveStorefrontPriceRequest.md)                                                                                                                                                                                                                                                                                             | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1StorefrontPricesResolveResponse](../../Models/Operations/PublicApiV1StorefrontPricesResolveResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |