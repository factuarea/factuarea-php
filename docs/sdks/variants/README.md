# Products.Variants

## Overview

### Available Operations

* [publicApiV1ProductsVariantsCreate](#publicapiv1productsvariantscreate) - Create a product variant
* [publicApiV1ProductsVariantsList](#publicapiv1productsvariantslist) - List product variants
* [publicApiV1ProductsVariantsDelete](#publicapiv1productsvariantsdelete) - Delete a product variant
* [publicApiV1ProductsVariantsUpdate](#publicapiv1productsvariantsupdate) - Update a product variant

## publicApiV1ProductsVariantsCreate

Create a variant under a product with its own SKU, barcode, optional price override and stock settings.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.products.variants.create" method="post" path="/companies/{company}/products/{product}/variants" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1ProductsVariantsCreateRequest(
    company: '1fba5c9c-423c-4c50-9a0b-2e5e68e61b4e',
    product: 'd743b388-062a-4f8d-a262-0091c41be28e',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateProductVariantRequest(
        name: '<value>',
        manageStock: false,
        stock: 3658.66,
    ),
);

$response = $sdk->products->variants->publicApiV1ProductsVariantsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.variants.create" method="post" path="/companies/{company}/products/{product}/variants" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1ProductsVariantsCreateRequest(
    company: 'Rolfson and Sons',
    product: 'Refined Soft Sausages',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateProductVariantRequest(
        name: '<value>',
        manageStock: false,
        stock: 3658.66,
    ),
);

$response = $sdk->products->variants->publicApiV1ProductsVariantsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.products.variants.create" method="post" path="/companies/{company}/products/{product}/variants" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1ProductsVariantsCreateRequest(
    company: 'a5aa7ccb-6ea4-4871-a3c5-d5d41880d3bb',
    product: '0b3beb10-5122-429f-afc4-27c37eed149a',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateProductVariantRequest(
        name: '<value>',
        manageStock: false,
        stock: 3658.66,
    ),
);

$response = $sdk->products->variants->publicApiV1ProductsVariantsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.products.variants.create" method="post" path="/companies/{company}/products/{product}/variants" example="success" -->
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

$request = new Operations\PublicApiV1ProductsVariantsCreateRequest(
    company: 'Hilll, Nienow and Nitzsche',
    product: 'Oriental Plastic Hat',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateProductVariantRequest(
        name: '<value>',
        manageStock: false,
        stock: 3658.66,
    ),
);

$response = $sdk->products->variants->publicApiV1ProductsVariantsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.products.variants.create" method="post" path="/companies/{company}/products/{product}/variants" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1ProductsVariantsCreateRequest(
    company: 'e17e892a-90f0-426d-8dac-90bed5e481d7',
    product: '7d3dabb2-b8b4-4eef-824b-496f186395b9',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateProductVariantRequest(
        name: '<value>',
        manageStock: false,
        stock: 3658.66,
    ),
);

$response = $sdk->products->variants->publicApiV1ProductsVariantsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1ProductsVariantsCreateRequest](../../Models/Operations/PublicApiV1ProductsVariantsCreateRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1ProductsVariantsCreateResponse](../../Models/Operations/PublicApiV1ProductsVariantsCreateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ProductsVariantsList

List the variants configured for a product, including optional price and stock overrides.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.variants.list" method="get" path="/companies/{company}/products/{product}/variants" example="success" -->
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

$request = new Operations\PublicApiV1ProductsVariantsListRequest(
    company: 'Littel - Berge',
    product: 'Ergonomic Metal Ball',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->products->variants->publicApiV1ProductsVariantsList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                              | Type                                                                                                                   | Required                                                                                                               | Description                                                                                                            |
| ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                             | [Operations\PublicApiV1ProductsVariantsListRequest](../../Models/Operations/PublicApiV1ProductsVariantsListRequest.md) | :heavy_check_mark:                                                                                                     | The request object to use for the request.                                                                             |

### Response

**[?Operations\PublicApiV1ProductsVariantsListResponse](../../Models/Operations/PublicApiV1ProductsVariantsListResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ProductsVariantsDelete

Retire a product variant while preserving the immutable snapshots stored on historical documents.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.variants.delete" method="delete" path="/companies/{company}/products/{product}/variants/{variant}" -->
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

$request = new Operations\PublicApiV1ProductsVariantsDeleteRequest(
    company: 'Hahn LLC',
    product: 'Handmade Fresh Sausages',
    variant: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->products->variants->publicApiV1ProductsVariantsDelete(
    request: $request
);

if ($response->statusCode === 200) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1ProductsVariantsDeleteRequest](../../Models/Operations/PublicApiV1ProductsVariantsDeleteRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1ProductsVariantsDeleteResponse](../../Models/Operations/PublicApiV1ProductsVariantsDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ProductsVariantsUpdate

Update the selected product variant and its optional price or stock overrides.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.products.variants.update" method="patch" path="/companies/{company}/products/{product}/variants/{variant}" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1ProductsVariantsUpdateRequest(
    company: '62032bf5-2e51-4c3a-924d-b6a882f6e1fd',
    product: '4a26020b-d4a3-49a8-9bae-fe880372a978',
    variant: '2f0d79d8-b3b9-4341-ba1a-2212cca21df1',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateProductVariantRequest(
        name: '<value>',
        manageStock: false,
        stock: 7261.71,
    ),
);

$response = $sdk->products->variants->publicApiV1ProductsVariantsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.variants.update" method="patch" path="/companies/{company}/products/{product}/variants/{variant}" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1ProductsVariantsUpdateRequest(
    company: 'Bernier, Kilback and Stracke',
    product: 'Bespoke Granite Soap',
    variant: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateProductVariantRequest(
        name: '<value>',
        manageStock: false,
        stock: 7261.71,
    ),
);

$response = $sdk->products->variants->publicApiV1ProductsVariantsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.products.variants.update" method="patch" path="/companies/{company}/products/{product}/variants/{variant}" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1ProductsVariantsUpdateRequest(
    company: 'eb67f17d-a80a-4cc9-b502-fe4b55a3e4cf',
    product: 'cf9395a6-e1fd-4ce0-83c2-e9379019a579',
    variant: '5fae927a-18b8-48eb-8a77-8f73a3bd0453',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateProductVariantRequest(
        name: '<value>',
        manageStock: false,
        stock: 7261.71,
    ),
);

$response = $sdk->products->variants->publicApiV1ProductsVariantsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.products.variants.update" method="patch" path="/companies/{company}/products/{product}/variants/{variant}" example="success" -->
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

$request = new Operations\PublicApiV1ProductsVariantsUpdateRequest(
    company: 'Reinger, Hodkiewicz and Konopelski',
    product: 'Handmade Granite Tuna',
    variant: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateProductVariantRequest(
        name: '<value>',
        manageStock: false,
        stock: 7261.71,
    ),
);

$response = $sdk->products->variants->publicApiV1ProductsVariantsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.products.variants.update" method="patch" path="/companies/{company}/products/{product}/variants/{variant}" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1ProductsVariantsUpdateRequest(
    company: 'd59841e9-9e05-419b-a023-ced4fd111220',
    product: 'eb107bcf-0bd6-4044-b9b8-b700caa459f2',
    variant: '1492fd57-7cb0-4f2e-82a3-00219c73033c',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateProductVariantRequest(
        name: '<value>',
        manageStock: false,
        stock: 7261.71,
    ),
);

$response = $sdk->products->variants->publicApiV1ProductsVariantsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1ProductsVariantsUpdateRequest](../../Models/Operations/PublicApiV1ProductsVariantsUpdateRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1ProductsVariantsUpdateResponse](../../Models/Operations/PublicApiV1ProductsVariantsUpdateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |