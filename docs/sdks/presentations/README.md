# Products.Presentations

## Overview

### Available Operations

* [publicApiV1ProductsPresentationsCreate](#publicapiv1productspresentationscreate) - Create a product presentation
* [publicApiV1ProductsPresentationsList](#publicapiv1productspresentationslist) - List product presentations
* [publicApiV1ProductsPresentationsDelete](#publicapiv1productspresentationsdelete) - Delete a product presentation
* [publicApiV1ProductsPresentationsUpdate](#publicapiv1productspresentationsupdate) - Update a product presentation

## publicApiV1ProductsPresentationsCreate

Create a commercial presentation for a product with its unit and conversion data.

### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.presentations.create" method="post" path="/companies/{company}/products/{product}/presentations" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1ProductsPresentationsCreateRequest(
    company: 'Gerhold - Collins',
    product: 'Modern Fresh Tuna',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateProductPresentationRequest(
        name: '<value>',
        mode: Components\CreateProductPresentationRequestMode::VariableMeasure,
        unit: Components\CreateProductPresentationRequestUnit::Grm,
    ),
);

$response = $sdk->products->presentations->publicApiV1ProductsPresentationsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.products.presentations.create" method="post" path="/companies/{company}/products/{product}/presentations" example="success" -->
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

$request = new Operations\PublicApiV1ProductsPresentationsCreateRequest(
    company: 'Waelchi, Kuhn and Rutherford',
    product: 'Licensed Cotton Table',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateProductPresentationRequest(
        name: '<value>',
        mode: Components\CreateProductPresentationRequestMode::VariableMeasure,
        unit: Components\CreateProductPresentationRequestUnit::Grm,
    ),
);

$response = $sdk->products->presentations->publicApiV1ProductsPresentationsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                            | Type                                                                                                                                 | Required                                                                                                                             | Description                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                           | [Operations\PublicApiV1ProductsPresentationsCreateRequest](../../Models/Operations/PublicApiV1ProductsPresentationsCreateRequest.md) | :heavy_check_mark:                                                                                                                   | The request object to use for the request.                                                                                           |

### Response

**[?Operations\PublicApiV1ProductsPresentationsCreateResponse](../../Models/Operations/PublicApiV1ProductsPresentationsCreateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ProductsPresentationsList

List the commercial presentations configured for a product, including unit, conversion factor and presentation mode.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.presentations.list" method="get" path="/companies/{company}/products/{product}/presentations" example="success" -->
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

$request = new Operations\PublicApiV1ProductsPresentationsListRequest(
    company: 'Rempel - Jacobi',
    product: 'Gorgeous Steel Shirt',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->products->presentations->publicApiV1ProductsPresentationsList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                        | Type                                                                                                                             | Required                                                                                                                         | Description                                                                                                                      |
| -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                       | [Operations\PublicApiV1ProductsPresentationsListRequest](../../Models/Operations/PublicApiV1ProductsPresentationsListRequest.md) | :heavy_check_mark:                                                                                                               | The request object to use for the request.                                                                                       |

### Response

**[?Operations\PublicApiV1ProductsPresentationsListResponse](../../Models/Operations/PublicApiV1ProductsPresentationsListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1ProductsPresentationsDelete

Retire a commercial presentation from the product catalog while historical document snapshots remain immutable.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.presentations.delete" method="delete" path="/companies/{company}/products/{product}/presentations/{presentation}" -->
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

$request = new Operations\PublicApiV1ProductsPresentationsDeleteRequest(
    company: 'VonRueden - Roob',
    product: 'Intelligent Concrete Shoes',
    presentation: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->products->presentations->publicApiV1ProductsPresentationsDelete(
    request: $request
);

if ($response->statusCode === 200) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                            | Type                                                                                                                                 | Required                                                                                                                             | Description                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                           | [Operations\PublicApiV1ProductsPresentationsDeleteRequest](../../Models/Operations/PublicApiV1ProductsPresentationsDeleteRequest.md) | :heavy_check_mark:                                                                                                                   | The request object to use for the request.                                                                                           |

### Response

**[?Operations\PublicApiV1ProductsPresentationsDeleteResponse](../../Models/Operations/PublicApiV1ProductsPresentationsDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ProductsPresentationsUpdate

Update the selected commercial presentation while preserving tenant and product ownership.

### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.presentations.update" method="patch" path="/companies/{company}/products/{product}/presentations/{presentation}" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1ProductsPresentationsUpdateRequest(
    company: 'Hermiston - Hoeger',
    product: 'Bespoke Metal Salad',
    presentation: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateProductPresentationRequest(
        name: '<value>',
        mode: Components\UpdateProductPresentationRequestMode::Fixed,
        unit: Components\UpdateProductPresentationRequestUnit::Mtk,
    ),
);

$response = $sdk->products->presentations->publicApiV1ProductsPresentationsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.products.presentations.update" method="patch" path="/companies/{company}/products/{product}/presentations/{presentation}" example="success" -->
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

$request = new Operations\PublicApiV1ProductsPresentationsUpdateRequest(
    company: 'Pacocha Group',
    product: 'Electronic Cotton Tuna',
    presentation: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateProductPresentationRequest(
        name: '<value>',
        mode: Components\UpdateProductPresentationRequestMode::Fixed,
        unit: Components\UpdateProductPresentationRequestUnit::Mtk,
    ),
);

$response = $sdk->products->presentations->publicApiV1ProductsPresentationsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                            | Type                                                                                                                                 | Required                                                                                                                             | Description                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                           | [Operations\PublicApiV1ProductsPresentationsUpdateRequest](../../Models/Operations/PublicApiV1ProductsPresentationsUpdateRequest.md) | :heavy_check_mark:                                                                                                                   | The request object to use for the request.                                                                                           |

### Response

**[?Operations\PublicApiV1ProductsPresentationsUpdateResponse](../../Models/Operations/PublicApiV1ProductsPresentationsUpdateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |