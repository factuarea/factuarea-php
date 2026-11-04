# Products.Configurations

## Overview

### Available Operations

* [publicApiV1ProductsConfigurationsList](#publicapiv1productsconfigurationslist) - List product commercial combinations
* [publicApiV1ProductsConfigurationsImpactPreview](#publicapiv1productsconfigurationsimpactpreview) - Preview the impact of restricting a catalog

## publicApiV1ProductsConfigurationsList

List the commercial combinations (variant x presentation x option values) of a product, each with its `signature`, own price and state.
- An EMPTY list means LEGACY mode (any cross is accepted); once one exists, the list is an allow-list.
- Cursor-paginated (`limit`, `starting_after`); another company's product returns 404 `product_not_found`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.configurations.list" method="get" path="/companies/{company}/products/{product}/configurations" example="success" -->
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
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1ProductsConfigurationsListRequest(
    company: 'Gerlach - Gutkowski',
    product: 'Electronic Fresh Chips',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->products->configurations->publicApiV1ProductsConfigurationsList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                          | Type                                                                                                                               | Required                                                                                                                           | Description                                                                                                                        |
| ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                         | [Operations\PublicApiV1ProductsConfigurationsListRequest](../../Models/Operations/PublicApiV1ProductsConfigurationsListRequest.md) | :heavy_check_mark:                                                                                                                 | The request object to use for the request.                                                                                         |

### Response

**[?Operations\PublicApiV1ProductsConfigurationsListResponse](../../Models/Operations/PublicApiV1ProductsConfigurationsListResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ProductsConfigurationsImpactPreview

Compute what would be lost if the commercial combinations of a product were replaced, and mint the `impact_token` that confirms that exact change.
- A read that persists nothing, but requires `products:write`.
- `configurations` is the COMPLETE list that stays active; an empty list retires the allow-list.
- Send the token back when emptying `configurations`.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.products.configurations.impact_preview" method="post" path="/companies/{company}/products/{product}/configurations/impact-preview" example="factuarea_version_invalid_format" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\PreviewCatalogConfigurationImpactRequest(
    configurations: [],
);

$response = $sdk->products->configurations->publicApiV1ProductsConfigurationsImpactPreview(
    company: '2e2fc5a7-0730-4c73-be62-9a8eb6ffd62b',
    product: 'fca30174-e758-4ecd-b9c7-8207ae6ed1bf',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: insufficient_scope

<!-- UsageSnippet language="php" operationID="public-api.v1.products.configurations.impact_preview" method="post" path="/companies/{company}/products/{product}/configurations/impact-preview" example="insufficient_scope" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\PreviewCatalogConfigurationImpactRequest(
    configurations: [],
);

$response = $sdk->products->configurations->publicApiV1ProductsConfigurationsImpactPreview(
    company: '22d19ff2-7cbb-4ae2-9e68-c95e49e9221b',
    product: 'Luxurious Wooden Tuna',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.configurations.impact_preview" method="post" path="/companies/{company}/products/{product}/configurations/impact-preview" example="missing_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\PreviewCatalogConfigurationImpactRequest(
    configurations: [],
);

$response = $sdk->products->configurations->publicApiV1ProductsConfigurationsImpactPreview(
    company: 'Marquardt - Wilkinson',
    product: 'Ergonomic Concrete Soap',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.products.configurations.impact_preview" method="post" path="/companies/{company}/products/{product}/configurations/impact-preview" example="retired_active_profile_header" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\PreviewCatalogConfigurationImpactRequest(
    configurations: [],
);

$response = $sdk->products->configurations->publicApiV1ProductsConfigurationsImpactPreview(
    company: '22d19ff2-7cbb-4ae2-9e68-c95e49e9221b',
    product: '88938458-88ab-435a-98ec-1a397968c370',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.products.configurations.impact_preview" method="post" path="/companies/{company}/products/{product}/configurations/impact-preview" example="success" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\PreviewCatalogConfigurationImpactRequest(
    configurations: [],
);

$response = $sdk->products->configurations->publicApiV1ProductsConfigurationsImpactPreview(
    company: 'Champlin - Stamm',
    product: 'Elegant Steel Ball',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.products.configurations.impact_preview" method="post" path="/companies/{company}/products/{product}/configurations/impact-preview" example="unsupported_api_version" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\PreviewCatalogConfigurationImpactRequest(
    configurations: [],
);

$response = $sdk->products->configurations->publicApiV1ProductsConfigurationsImpactPreview(
    company: 'a0d0d05c-0238-4b95-8c52-ef39ac655e6e',
    product: 'aa0013c7-3fe1-4d98-bd4f-66169e042900',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `product`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the product.                                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `body`                                                                                                                                                                                                                                                                                                                                                                       | [Components\PreviewCatalogConfigurationImpactRequest](../../Models/Components/PreviewCatalogConfigurationImpactRequest.md)                                                                                                                                                                                                                                                   | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | N/A                                                                                                                                                                                                                                                                                                                                                                          |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1ProductsConfigurationsImpactPreviewResponse](../../Models/Operations/PublicApiV1ProductsConfigurationsImpactPreviewResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |