# Products.BaseUnitReplacement

## Overview

### Available Operations

* [publicApiV1ProductsBaseUnitReplacementPreview](#publicapiv1productsbaseunitreplacementpreview) - Preview a base unit replacement
* [publicApiV1ProductsBaseUnitReplacementCreate](#publicapiv1productsbaseunitreplacementcreate) - Replace a product by changing its base unit

## publicApiV1ProductsBaseUnitReplacementPreview

Compute, without writing anything, what replacing a product with one of a different base unit would do, and mint the `impact_token` that confirms it.
- Requires `products:write` because the token authorises an irreversible write.
- Send `target_base_unit`, `equivalence` and a `keep` or `retire` decision per presentation.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.products.base_unit_replacement.preview" method="post" path="/companies/{company}/products/{product}/base-unit-replacement/preview" example="factuarea_version_invalid_format" -->
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

$body = new Components\PreviewProductBaseUnitReplacementRequest(
    targetBaseUnit: Components\PreviewProductBaseUnitReplacementRequestTargetBaseUnit::Hur,
);

$response = $sdk->products->baseUnitReplacement->publicApiV1ProductsBaseUnitReplacementPreview(
    company: '5845d350-ecc4-4e64-af48-ae629c0c9d9b',
    product: 'f9b46318-e3ba-4aeb-a2f8-27535a578898',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.base_unit_replacement.preview" method="post" path="/companies/{company}/products/{product}/base-unit-replacement/preview" example="missing_api_key" -->
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

$body = new Components\PreviewProductBaseUnitReplacementRequest(
    targetBaseUnit: Components\PreviewProductBaseUnitReplacementRequestTargetBaseUnit::Hur,
);

$response = $sdk->products->baseUnitReplacement->publicApiV1ProductsBaseUnitReplacementPreview(
    company: '81803810-6bd9-48fc-8c70-b22c08fb256d',
    product: '91d154aa-c223-4f9e-93ae-7868b8aa2f21',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.products.base_unit_replacement.preview" method="post" path="/companies/{company}/products/{product}/base-unit-replacement/preview" example="retired_active_profile_header" -->
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

$body = new Components\PreviewProductBaseUnitReplacementRequest(
    targetBaseUnit: Components\PreviewProductBaseUnitReplacementRequestTargetBaseUnit::Hur,
);

$response = $sdk->products->baseUnitReplacement->publicApiV1ProductsBaseUnitReplacementPreview(
    company: 'd9fe95a7-0b89-4e49-ba63-d62e409a57f2',
    product: '048ca944-22d4-4075-9b73-0a1f6b7764c4',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.products.base_unit_replacement.preview" method="post" path="/companies/{company}/products/{product}/base-unit-replacement/preview" example="success" -->
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

$body = new Components\PreviewProductBaseUnitReplacementRequest(
    targetBaseUnit: Components\PreviewProductBaseUnitReplacementRequestTargetBaseUnit::Hur,
);

$response = $sdk->products->baseUnitReplacement->publicApiV1ProductsBaseUnitReplacementPreview(
    company: '845d350e-cc4e-464f-b48a-e629c0c9d9ba',
    product: '9b46318e-3baa-4eb2-af82-7535a578898a',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.products.base_unit_replacement.preview" method="post" path="/companies/{company}/products/{product}/base-unit-replacement/preview" example="unsupported_api_version" -->
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

$body = new Components\PreviewProductBaseUnitReplacementRequest(
    targetBaseUnit: Components\PreviewProductBaseUnitReplacementRequestTargetBaseUnit::Hur,
);

$response = $sdk->products->baseUnitReplacement->publicApiV1ProductsBaseUnitReplacementPreview(
    company: '98180381-06bd-498f-bcc7-0b22c08fb256',
    product: '191d154a-ac22-43f9-8e3a-e7868b8aa2f2',
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
| `body`                                                                                                                                                                                                                                                                                                                                                                       | [Components\PreviewProductBaseUnitReplacementRequest](../../Models/Components/PreviewProductBaseUnitReplacementRequest.md)                                                                                                                                                                                                                                                   | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | N/A                                                                                                                                                                                                                                                                                                                                                                          |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1ProductsBaseUnitReplacementPreviewResponse](../../Models/Operations/PublicApiV1ProductsBaseUnitReplacementPreviewResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ProductsBaseUnitReplacementCreate

Replace a product by a NEW one with a different base unit; returns 201 with the new product (`replaces` points at the old one).
- Irreversible: `Idempotency-Key` is mandatory and the old product is deactivated for good.
- Requires the `impact_token` of a current preview (stale: 422 `base_unit_replacement_preview_stale`).

### Example Usage: created

<!-- UsageSnippet language="php" operationID="public-api.v1.products.base_unit_replacement.create" method="post" path="/companies/{company}/products/{product}/base-unit-replacement" example="created" -->
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

$request = new Operations\PublicApiV1ProductsBaseUnitReplacementCreateRequest(
    company: '76d712d7-7d66-487b-b73d-93c10a09662e',
    product: '551c6af3-775d-4450-993e-89abf5e5e083',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReplaceProductBaseUnitRequest(
        targetBaseUnit: Components\ReplaceProductBaseUnitRequestTargetBaseUnit::Mlt,
        impactToken: '<value>',
    ),
);

$response = $sdk->products->baseUnitReplacement->publicApiV1ProductsBaseUnitReplacementCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.products.base_unit_replacement.create" method="post" path="/companies/{company}/products/{product}/base-unit-replacement" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1ProductsBaseUnitReplacementCreateRequest(
    company: '4480c111-3277-46d7-812d-77d6687b73d9',
    product: 'c10a0966-2ed5-451c-a6af-3775d45093e8',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReplaceProductBaseUnitRequest(
        targetBaseUnit: Components\ReplaceProductBaseUnitRequestTargetBaseUnit::Mlt,
        impactToken: '<value>',
    ),
);

$response = $sdk->products->baseUnitReplacement->publicApiV1ProductsBaseUnitReplacementCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.base_unit_replacement.create" method="post" path="/companies/{company}/products/{product}/base-unit-replacement" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1ProductsBaseUnitReplacementCreateRequest(
    company: '1db262d8-c1f4-4f5b-8e99-e35b6108e9ce',
    product: '3afe2290-d948-461a-9457-fd7bfb15aafa',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReplaceProductBaseUnitRequest(
        targetBaseUnit: Components\ReplaceProductBaseUnitRequestTargetBaseUnit::Mlt,
        impactToken: '<value>',
    ),
);

$response = $sdk->products->baseUnitReplacement->publicApiV1ProductsBaseUnitReplacementCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.products.base_unit_replacement.create" method="post" path="/companies/{company}/products/{product}/base-unit-replacement" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1ProductsBaseUnitReplacementCreateRequest(
    company: '8d53183c-6c07-458d-8327-b79e7ce3aeb4',
    product: 'fa30c721-3ed0-404f-878c-972d5ebe26b9',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReplaceProductBaseUnitRequest(
        targetBaseUnit: Components\ReplaceProductBaseUnitRequestTargetBaseUnit::Mlt,
        impactToken: '<value>',
    ),
);

$response = $sdk->products->baseUnitReplacement->publicApiV1ProductsBaseUnitReplacementCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.products.base_unit_replacement.create" method="post" path="/companies/{company}/products/{product}/base-unit-replacement" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1ProductsBaseUnitReplacementCreateRequest(
    company: 'abf5e5e0-8361-4db2-862d-8c1f4f5be99e',
    product: '5b6108e9-ce03-4afe-b229-0d94861a457f',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReplaceProductBaseUnitRequest(
        targetBaseUnit: Components\ReplaceProductBaseUnitRequestTargetBaseUnit::Mlt,
        impactToken: '<value>',
    ),
);

$response = $sdk->products->baseUnitReplacement->publicApiV1ProductsBaseUnitReplacementCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                        | Type                                                                                                                                             | Required                                                                                                                                         | Description                                                                                                                                      |
| ------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                                       | [Operations\PublicApiV1ProductsBaseUnitReplacementCreateRequest](../../Models/Operations/PublicApiV1ProductsBaseUnitReplacementCreateRequest.md) | :heavy_check_mark:                                                                                                                               | The request object to use for the request.                                                                                                       |

### Response

**[?Operations\PublicApiV1ProductsBaseUnitReplacementCreateResponse](../../Models/Operations/PublicApiV1ProductsBaseUnitReplacementCreateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |