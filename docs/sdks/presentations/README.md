# Products.Presentations

## Overview

### Available Operations

* [publicApiV1ProductsPresentationsCreate](#publicapiv1productspresentationscreate) - Create a product presentation
* [publicApiV1ProductsPresentationsList](#publicapiv1productspresentationslist) - List product presentations
* [publicApiV1ProductsPresentationsDelete](#publicapiv1productspresentationsdelete) - Delete a product presentation
* [publicApiV1ProductsPresentationsUpdate](#publicapiv1productspresentationsupdate) - Update a product presentation

## publicApiV1ProductsPresentationsCreate

Create a commercial presentation for a product with its unit and conversion data.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.products.presentations.create" method="post" path="/companies/{company}/products/{product}/presentations" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1ProductsPresentationsCreateRequest(
    company: 'bc9f2e15-2f9a-468a-a0da-c7b530d1ada2',
    product: 'd3c2c6bf-e431-46a4-b501-04469efa4c21',
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
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1ProductsPresentationsCreateRequest(
    company: 'Rutherford LLC',
    product: 'Refined Metal Cheese',
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
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.products.presentations.create" method="post" path="/companies/{company}/products/{product}/presentations" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1ProductsPresentationsCreateRequest(
    company: 'e4ce7c83-e342-494f-b32b-3a2704da1d1a',
    product: '898d832c-defa-4796-8263-31de0fffc0d7',
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
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1ProductsPresentationsCreateRequest(
    company: 'Gusikowski, Russel and Waelchi',
    product: 'Refined Wooden Hat',
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
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.products.presentations.create" method="post" path="/companies/{company}/products/{product}/presentations" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1ProductsPresentationsCreateRequest(
    company: 'c217bb05-2a29-4898-b0ed-829629d9117a',
    product: '61b466a6-b9aa-4011-9bc5-c37f5dd225db',
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
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
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
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1ProductsPresentationsListRequest(
    company: 'Rempel - Jacobi',
    product: 'Elegant Rubber Fish',
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

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

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
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1ProductsPresentationsDeleteRequest(
    company: 'VonRueden - Roob',
    product: 'Fantastic Wooden Shirt',
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
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ProductsPresentationsUpdate

Update the selected commercial presentation while preserving tenant and product ownership.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.products.presentations.update" method="patch" path="/companies/{company}/products/{product}/presentations/{presentation}" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1ProductsPresentationsUpdateRequest(
    company: '47bc6b21-780b-4832-9a78-4fafb88d92d8',
    product: 'ea104798-6357-42c0-b648-a2927f1095e5',
    presentation: '8ed9d77c-1aa1-46eb-b5b9-cd936b62b909',
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
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1ProductsPresentationsUpdateRequest(
    company: 'DuBuque, Bradtke and Dooley',
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
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.products.presentations.update" method="patch" path="/companies/{company}/products/{product}/presentations/{presentation}" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1ProductsPresentationsUpdateRequest(
    company: '5b9a313f-1560-49aa-9a41-e07f5d09df7f',
    product: '97d8a034-ceb3-4ce8-87f9-bcb66a777785',
    presentation: 'c591656f-acf0-4c61-b8f9-2c4a2f7ef20c',
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
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1ProductsPresentationsUpdateRequest(
    company: 'Quitzon LLC',
    product: 'Luxurious Rubber Chair',
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
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.products.presentations.update" method="patch" path="/companies/{company}/products/{product}/presentations/{presentation}" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1ProductsPresentationsUpdateRequest(
    company: '86938f44-1f2f-4630-a9fc-332ff5282bae',
    product: '11a14c05-7edc-4146-bac0-989981418d33',
    presentation: '82758046-80f4-44f7-b1d1-be54fac749a3',
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
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |