# Products

## Overview

### Available Operations

* [publicApiV1ProductsBulkDelete](#publicapiv1productsbulkdelete) - Delete multiple products in bulk
* [publicApiV1ProductsBulkUpdateStock](#publicapiv1productsbulkupdatestock) - Update stock for many products
* [publicApiV1ProductsCreate](#publicapiv1productscreate) - Create a product
* [publicApiV1ProductsList](#publicapiv1productslist) - List all products
* [publicApiV1ProductsDelete](#publicapiv1productsdelete) - Delete a product
* [publicApiV1ProductsShow](#publicapiv1productsshow) - Retrieve a product
* [publicApiV1ProductsUpdate](#publicapiv1productsupdate) - Update a product
* [publicApiV1ProductsFindBySku](#publicapiv1productsfindbysku) - Find a product by SKU
* [publicApiV1ProductsLowStockReport](#publicapiv1productslowstockreport) - List products below the stock threshold
* [publicApiV1ProductsActivities](#publicapiv1productsactivities) - List product activity timeline
* [publicApiV1ProductsSalesAnalytics](#publicapiv1productssalesanalytics) - Get product sales analytics
* [publicApiV1ProductsStats](#publicapiv1productsstats) - Get product stats
* [publicApiV1ProductsSearch](#publicapiv1productssearch) - Search products
* [publicApiV1ProductsToggleActive](#publicapiv1productstoggleactive) - Toggle product active state
* [publicApiV1ProductsUpdateStock](#publicapiv1productsupdatestock) - Update product stock

## publicApiV1ProductsBulkDelete

Delete up to 200 products in one request. Products included in packs are reported in skipped instead of failing. Decrements the plan usage counter accordingly.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.products.bulk_delete" method="post" path="/products/bulk-delete" example="api_key_revoked" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkDeleteProductsRequest(
    ids: [
        '6d9fa561-5f9a-4c52-b5fc-576d8f9483a2',
    ],
);

$response = $sdk->products->publicApiV1ProductsBulkDelete(
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.bulk_delete" method="post" path="/products/bulk-delete" example="invalid_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkDeleteProductsRequest(
    ids: [
        '6d9fa561-5f9a-4c52-b5fc-576d8f9483a2',
    ],
);

$response = $sdk->products->publicApiV1ProductsBulkDelete(
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.bulk_delete" method="post" path="/products/bulk-delete" example="missing_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkDeleteProductsRequest(
    ids: [
        '6d9fa561-5f9a-4c52-b5fc-576d8f9483a2',
    ],
);

$response = $sdk->products->publicApiV1ProductsBulkDelete(
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                              | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                       | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | [Components\BulkDeleteProductsRequest](../../Models/Components/BulkDeleteProductsRequest.md)                                                                                                                                                                                                                                                                                                                                                                                      | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Unique key generated by the client to ensure idempotency on retries. It lets you safely resend the same request: the first response is cached and returned without re-executing the mutation. It is an **opaque string** to the server; any unique value of up to 64 characters is valid (UUID v7, UUID v4, ULID, nanoid, etc.). UUID v7 is recommended for consistency with the API identifiers. The same key reused with a different body returns `409 idempotency_key_reused`. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1ProductsBulkDeleteResponse](../../Models/Operations/PublicApiV1ProductsBulkDeleteResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 409, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1ProductsBulkUpdateStock

Apply a stock operation to multiple products in one request (up to 500). UUIDs that do not belong to your company are ignored silently.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.products.bulk_update_stock" method="post" path="/products/bulk-update-stock" example="api_key_revoked" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkUpdateProductStockRequest(
    updates: [
        new Components\Update(
            productId: '3304fd00-8f08-45dc-b01c-51ce6af1a38e',
            stock: 139707,
        ),
    ],
);

$response = $sdk->products->publicApiV1ProductsBulkUpdateStock(
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.bulk_update_stock" method="post" path="/products/bulk-update-stock" example="invalid_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkUpdateProductStockRequest(
    updates: [
        new Components\Update(
            productId: '3304fd00-8f08-45dc-b01c-51ce6af1a38e',
            stock: 139707,
        ),
    ],
);

$response = $sdk->products->publicApiV1ProductsBulkUpdateStock(
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.bulk_update_stock" method="post" path="/products/bulk-update-stock" example="missing_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkUpdateProductStockRequest(
    updates: [
        new Components\Update(
            productId: '3304fd00-8f08-45dc-b01c-51ce6af1a38e',
            stock: 139707,
        ),
    ],
);

$response = $sdk->products->publicApiV1ProductsBulkUpdateStock(
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                              | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                       | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | [Components\BulkUpdateProductStockRequest](../../Models/Components/BulkUpdateProductStockRequest.md)                                                                                                                                                                                                                                                                                                                                                                              | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Unique key generated by the client to ensure idempotency on retries. It lets you safely resend the same request: the first response is cached and returned without re-executing the mutation. It is an **opaque string** to the server; any unique value of up to 64 characters is valid (UUID v7, UUID v4, ULID, nanoid, etc.). UUID v7 is recommended for consistency with the API identifiers. The same key reused with a different body returns `409 idempotency_key_reused`. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1ProductsBulkUpdateStockResponse](../../Models/Operations/PublicApiV1ProductsBulkUpdateStockResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 409, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1ProductsCreate

Create a new product in your catalog.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.products.create" method="post" path="/products" example="api_key_revoked" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\CreateProductRequest(
    name: '<value>',
    price: '848.55',
    metadata: [
        'erp_code' => 'IVA-GEN',
        'ledger_account' => '477000',
    ],
);

$response = $sdk->products->publicApiV1ProductsCreate(
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.create" method="post" path="/products" example="invalid_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\CreateProductRequest(
    name: '<value>',
    price: '848.55',
    metadata: [
        'erp_code' => 'IVA-GEN',
        'ledger_account' => '477000',
    ],
);

$response = $sdk->products->publicApiV1ProductsCreate(
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.create" method="post" path="/products" example="missing_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\CreateProductRequest(
    name: '<value>',
    price: '848.55',
    metadata: [
        'erp_code' => 'IVA-GEN',
        'ledger_account' => '477000',
    ],
);

$response = $sdk->products->publicApiV1ProductsCreate(
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                              | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                       | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | [Components\CreateProductRequest](../../Models/Components/CreateProductRequest.md)                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Unique key generated by the client to ensure idempotency on retries. It lets you safely resend the same request: the first response is cached and returned without re-executing the mutation. It is an **opaque string** to the server; any unique value of up to 64 characters is valid (UUID v7, UUID v4, ULID, nanoid, etc.). UUID v7 is recommended for consistency with the API identifiers. The same key reused with a different body returns `409 idempotency_key_reused`. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1ProductsCreateResponse](../../Models/Operations/PublicApiV1ProductsCreateResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 409, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1ProductsList

List products in your catalog with cursor-based pagination.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.list" method="get" path="/products" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

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

$request = new Operations\PublicApiV1ProductsListRequest();

$response = $sdk->products->publicApiV1ProductsList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                              | Type                                                                                                   | Required                                                                                               | Description                                                                                            |
| ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                             | [Operations\PublicApiV1ProductsListRequest](../../Models/Operations/PublicApiV1ProductsListRequest.md) | :heavy_check_mark:                                                                                     | The request object to use for the request.                                                             |

### Response

**[?Operations\PublicApiV1ProductsListResponse](../../Models/Operations/PublicApiV1ProductsListResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 422, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1ProductsDelete

Delete a product. Returns 422 if the product is referenced by any document line.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.delete" method="delete" path="/products/{product}" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->products->publicApiV1ProductsDelete(
    product: 'Elegant Steel Table',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->statusCode === 200) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                              | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                       | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `product`                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Unique key generated by the client to ensure idempotency on retries. It lets you safely resend the same request: the first response is cached and returned without re-executing the mutation. It is an **opaque string** to the server; any unique value of up to 64 characters is valid (UUID v7, UUID v4, ULID, nanoid, etc.). UUID v7 is recommended for consistency with the API identifiers. The same key reused with a different body returns `409 idempotency_key_reused`. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1ProductsDeleteResponse](../../Models/Operations/PublicApiV1ProductsDeleteResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 409, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1ProductsShow

Retrieve a product by its `uuid`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.show" method="get" path="/products/{product}" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->products->publicApiV1ProductsShow(
    product: 'Fantastic Concrete Fish'
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter          | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `product`          | *string*           | :heavy_check_mark: | N/A                |

### Response

**[?Operations\PublicApiV1ProductsShowResponse](../../Models/Operations/PublicApiV1ProductsShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1ProductsUpdate

Update a product in your catalog.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.products.update" method="put" path="/products/{product}" example="api_key_revoked" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\UpdateProductRequest(
    metadata: [
        'erp_code' => 'IVA-GEN',
        'ledger_account' => '477000',
    ],
);

$response = $sdk->products->publicApiV1ProductsUpdate(
    product: 'Practical Plastic Hat',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.update" method="put" path="/products/{product}" example="invalid_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\UpdateProductRequest(
    metadata: [
        'erp_code' => 'IVA-GEN',
        'ledger_account' => '477000',
    ],
);

$response = $sdk->products->publicApiV1ProductsUpdate(
    product: 'Oriental Metal Mouse',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.update" method="put" path="/products/{product}" example="missing_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\UpdateProductRequest(
    metadata: [
        'erp_code' => 'IVA-GEN',
        'ledger_account' => '477000',
    ],
);

$response = $sdk->products->publicApiV1ProductsUpdate(
    product: 'Incredible Cotton Chair',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                              | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                       | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `product`                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Unique key generated by the client to ensure idempotency on retries. It lets you safely resend the same request: the first response is cached and returned without re-executing the mutation. It is an **opaque string** to the server; any unique value of up to 64 characters is valid (UUID v7, UUID v4, ULID, nanoid, etc.). UUID v7 is recommended for consistency with the API identifiers. The same key reused with a different body returns `409 idempotency_key_reused`. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                              |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | [?Components\UpdateProductRequest](../../Models/Components/UpdateProductRequest.md)                                                                                                                                                                                                                                                                                                                                                                                               | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1ProductsUpdateResponse](../../Models/Operations/PublicApiV1ProductsUpdateResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 409, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1ProductsFindBySku

Look up a single product by its `sku` (sent in the JSON body). Returns the matching product or 404 if no product uses that SKU within your company.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.products.find_by_sku" method="post" path="/products/find-by-sku" example="api_key_revoked" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Components\FindProductBySkuRequest(
    sku: '<value>',
);

$response = $sdk->products->publicApiV1ProductsFindBySku(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.find_by_sku" method="post" path="/products/find-by-sku" example="invalid_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Components\FindProductBySkuRequest(
    sku: '<value>',
);

$response = $sdk->products->publicApiV1ProductsFindBySku(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.find_by_sku" method="post" path="/products/find-by-sku" example="missing_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Components\FindProductBySkuRequest(
    sku: '<value>',
);

$response = $sdk->products->publicApiV1ProductsFindBySku(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                | Type                                                                                     | Required                                                                                 | Description                                                                              |
| ---------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------- |
| `$request`                                                                               | [Components\FindProductBySkuRequest](../../Models/Components/FindProductBySkuRequest.md) | :heavy_check_mark:                                                                       | The request object to use for the request.                                               |

### Response

**[?Operations\PublicApiV1ProductsFindBySkuResponse](../../Models/Operations/PublicApiV1ProductsFindBySkuResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1ProductsLowStockReport

Return products whose current stock is below their configured low-stock threshold. Useful for inventory alerts.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.low_stock_report" method="get" path="/products/low-stock-report" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->products->publicApiV1ProductsLowStockReport(

);

if ($response->object !== null) {
    // handle response
}
```

### Response

**[?Operations\PublicApiV1ProductsLowStockReportResponse](../../Models/Operations/PublicApiV1ProductsLowStockReportResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 429       | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1ProductsActivities

Return the audit timeline for a product combining its own domain events plus document events whose lines reference it. Paginated with page and per_page query params (default 50).

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.activities" method="get" path="/products/{product}/activities" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->products->publicApiV1ProductsActivities(
    product: 'Intelligent Plastic Soap',
    perPage: 50,
    page: 1

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter          | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `product`          | *string*           | :heavy_check_mark: | N/A                |
| `perPage`          | *?int*             | :heavy_minus_sign: | N/A                |
| `page`             | *?int*             | :heavy_minus_sign: | N/A                |

### Response

**[?Operations\PublicApiV1ProductsActivitiesResponse](../../Models/Operations/PublicApiV1ProductsActivitiesResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 429       | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1ProductsSalesAnalytics

Return units sold, revenue, invoice count, month-over-month delta, monthly trend for the last 6 months, last buyer and recent activity feed for a single product.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.sales_analytics" method="get" path="/products/{product}/sales-analytics" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->products->publicApiV1ProductsSalesAnalytics(
    product: 'Intelligent Bronze Hat'
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter          | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `product`          | *string*           | :heavy_check_mark: | N/A                |

### Response

**[?Operations\PublicApiV1ProductsSalesAnalyticsResponse](../../Models/Operations/PublicApiV1ProductsSalesAnalyticsResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 429       | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1ProductsStats

Aggregated KPIs for your product catalog: total product count, active count, count below the low-stock threshold, accumulated stock value, and totals by category. Returned as `{ "data": ProductStats }`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.stats" method="get" path="/products/stats" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->products->publicApiV1ProductsStats(

);

if ($response->object !== null) {
    // handle response
}
```

### Response

**[?Operations\PublicApiV1ProductsStatsResponse](../../Models/Operations/PublicApiV1ProductsStatsResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 429       | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1ProductsSearch

Search products by free-text query against `name` and `sku`. Capped at 50 results.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.search" method="get" path="/products/search" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->products->publicApiV1ProductsSearch(
    q: '<value>'
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter          | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `q`                | *string*           | :heavy_check_mark: | N/A                |

### Response

**[?Operations\PublicApiV1ProductsSearchResponse](../../Models/Operations/PublicApiV1ProductsSearchResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 422, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1ProductsToggleActive

Flip a product between active and inactive. Inactive products are hidden from line-item selectors on new documents.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.toggle_active" method="post" path="/products/{product}/toggle-active" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->products->publicApiV1ProductsToggleActive(
    product: 'Generic Frozen Chips',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                              | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                       | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `product`                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Unique key generated by the client to ensure idempotency on retries. It lets you safely resend the same request: the first response is cached and returned without re-executing the mutation. It is an **opaque string** to the server; any unique value of up to 64 characters is valid (UUID v7, UUID v4, ULID, nanoid, etc.). UUID v7 is recommended for consistency with the API identifiers. The same key reused with a different body returns `409 idempotency_key_reused`. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1ProductsToggleActiveResponse](../../Models/Operations/PublicApiV1ProductsToggleActiveResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 409, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1ProductsUpdateStock

Replace, increase or decrease the stock quantity of a product. Defaults to set (replace); add and subtract are accepted aliases for increase and decrease. Fails with 422 if the resulting stock would be negative.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.products.update_stock" method="put" path="/products/{product}/stock" example="api_key_revoked" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\UpdateProductStockRequest(
    stock: 591726,
);

$response = $sdk->products->publicApiV1ProductsUpdateStock(
    product: 'Handmade Rubber Fish',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.update_stock" method="put" path="/products/{product}/stock" example="invalid_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\UpdateProductStockRequest(
    stock: 591726,
);

$response = $sdk->products->publicApiV1ProductsUpdateStock(
    product: 'Licensed Rubber Hat',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.update_stock" method="put" path="/products/{product}/stock" example="missing_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\UpdateProductStockRequest(
    stock: 591726,
);

$response = $sdk->products->publicApiV1ProductsUpdateStock(
    product: 'Handcrafted Plastic Table',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                              | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                       | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `product`                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | [Components\UpdateProductStockRequest](../../Models/Components/UpdateProductStockRequest.md)                                                                                                                                                                                                                                                                                                                                                                                      | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Unique key generated by the client to ensure idempotency on retries. It lets you safely resend the same request: the first response is cached and returned without re-executing the mutation. It is an **opaque string** to the server; any unique value of up to 64 characters is valid (UUID v7, UUID v4, ULID, nanoid, etc.). UUID v7 is recommended for consistency with the API identifiers. The same key reused with a different body returns `409 idempotency_key_reused`. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1ProductsUpdateStockResponse](../../Models/Operations/PublicApiV1ProductsUpdateStockResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |