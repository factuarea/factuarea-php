# SalesOrders

## Overview

### Available Operations

* [publicApiV1SalesOrdersBulkCreate](#publicapiv1salesordersbulkcreate) - Bulk create sales orders
* [publicApiV1SalesOrdersBulkDelete](#publicapiv1salesordersbulkdelete) - Bulk delete sales orders
* [publicApiV1SalesOrdersBulkStatus](#publicapiv1salesordersbulkstatus) - Bulk change sales order status
* [publicApiV1SalesOrdersCancel](#publicapiv1salesorderscancel) - Cancel a sales order
* [publicApiV1SalesOrdersClose](#publicapiv1salesordersclose) - Close a sales order
* [publicApiV1SalesOrdersConfirm](#publicapiv1salesordersconfirm) - Confirm a sales order
* [publicApiV1SalesOrdersConvertToDeliveryNote](#publicapiv1salesordersconverttodeliverynote) - Convert a sales order to a delivery note
* [publicApiV1SalesOrdersConvertToInvoice](#publicapiv1salesordersconverttoinvoice) - Convert a sales order to an invoice
* [publicApiV1SalesOrdersCreate](#publicapiv1salesorderscreate) - Create a sales order
* [publicApiV1SalesOrdersList](#publicapiv1salesorderslist) - List all sales orders
* [publicApiV1SalesOrdersDelete](#publicapiv1salesordersdelete) - Delete a sales order
* [publicApiV1SalesOrdersShow](#publicapiv1salesordersshow) - Retrieve a sales order
* [publicApiV1SalesOrdersUpdate](#publicapiv1salesordersupdate) - Update a sales order
* [publicApiV1SalesOrdersPdf](#publicapiv1salesorderspdf) - Download sales order PDF
* [publicApiV1SalesOrdersFindByExternalId](#publicapiv1salesordersfindbyexternalid) - Find a sales order by external id
* [publicApiV1SalesOrdersStats](#publicapiv1salesordersstats) - Retrieve sales order stats
* [publicApiV1SalesOrdersStatuses](#publicapiv1salesordersstatuses) - List sales order statuses
* [publicApiV1SalesOrdersSend](#publicapiv1salesorderssend) - Send a sales order

## publicApiV1SalesOrdersBulkCreate

Create several sales orders in a single request. The body takes a `sales_orders` array of up to 100 payloads, each one identical to the body of the single create. Returns a partial-success result with `total`, `successful` and `failed` counts and a `failures` list (`error_code` + Spanish `error_message`) for the ones that could not be created: the orders that do go through are created even if others fail. On the entry plan the monthly cap applies to the batch too — an already exhausted cap rejects the whole call with 402, and a batch larger than the remaining quota creates what fits and reports the rest as failures with that same code. Supports `Idempotency-Key` for safe retries.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.bulk_create" method="post" path="/companies/{company}/sales-orders/bulk-create" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1SalesOrdersBulkCreateRequest(
    company: 'Lang, Orn and Skiles',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\BulkCreateSalesOrdersRequest(
        salesOrders: [
            new Components\BulkCreateSalesOrdersRequestSalesOrder(),
        ],
    ),
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersBulkCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.bulk_create" method="post" path="/companies/{company}/sales-orders/bulk-create" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1SalesOrdersBulkCreateRequest(
    company: 'Krajcik Inc',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\BulkCreateSalesOrdersRequest(
        salesOrders: [
            new Components\BulkCreateSalesOrdersRequestSalesOrder(),
        ],
    ),
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersBulkCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.bulk_create" method="post" path="/companies/{company}/sales-orders/bulk-create" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1SalesOrdersBulkCreateRequest(
    company: 'Jast, Harber and Crona',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\BulkCreateSalesOrdersRequest(
        salesOrders: [
            new Components\BulkCreateSalesOrdersRequestSalesOrder(),
        ],
    ),
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersBulkCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                | Type                                                                                                                     | Required                                                                                                                 | Description                                                                                                              |
| ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                               | [Operations\PublicApiV1SalesOrdersBulkCreateRequest](../../Models/Operations/PublicApiV1SalesOrdersBulkCreateRequest.md) | :heavy_check_mark:                                                                                                       | The request object to use for the request.                                                                               |

### Response

**[?Operations\PublicApiV1SalesOrdersBulkCreateResponse](../../Models/Operations/PublicApiV1SalesOrdersBulkCreateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 401, 402, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1SalesOrdersBulkDelete

Delete several sales orders in a single request. The body takes an `ids` array of order ids, up to 100. Returns a partial-success result with `total`, `successful` and `failed` counts and a `failures` list (`id` + `error_code` + Spanish `error_message`) for the ones that could not be deleted, typically because they already consumed a number or already moved goods. Irreversible: the deleted orders do not come back. Supports `Idempotency-Key` for safe retries.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.bulk_delete" method="post" path="/companies/{company}/sales-orders/bulk-delete" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1SalesOrdersBulkDeleteRequest(
    company: 'West, Steuber and Harris',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\BulkDeleteSalesOrdersRequest(
        ids: [
            'cfd80a3e-f5aa-4236-bc60-6256836cf668',
            'ad114612-6848-4f57-9ea3-8bf7001454a6',
        ],
    ),
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersBulkDelete(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.bulk_delete" method="post" path="/companies/{company}/sales-orders/bulk-delete" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1SalesOrdersBulkDeleteRequest(
    company: 'Ullrich - Satterfield',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\BulkDeleteSalesOrdersRequest(
        ids: [
            'cfd80a3e-f5aa-4236-bc60-6256836cf668',
            'ad114612-6848-4f57-9ea3-8bf7001454a6',
        ],
    ),
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersBulkDelete(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.bulk_delete" method="post" path="/companies/{company}/sales-orders/bulk-delete" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1SalesOrdersBulkDeleteRequest(
    company: 'Stiedemann, Balistreri and Sanford',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\BulkDeleteSalesOrdersRequest(
        ids: [
            'cfd80a3e-f5aa-4236-bc60-6256836cf668',
            'ad114612-6848-4f57-9ea3-8bf7001454a6',
        ],
    ),
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersBulkDelete(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                | Type                                                                                                                     | Required                                                                                                                 | Description                                                                                                              |
| ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                               | [Operations\PublicApiV1SalesOrdersBulkDeleteRequest](../../Models/Operations/PublicApiV1SalesOrdersBulkDeleteRequest.md) | :heavy_check_mark:                                                                                                       | The request object to use for the request.                                                                               |

### Response

**[?Operations\PublicApiV1SalesOrdersBulkDeleteResponse](../../Models/Operations/PublicApiV1SalesOrdersBulkDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1SalesOrdersBulkStatus

Move several sales orders to the same status in one request. The body takes an `ids` array of order ids, up to 100, and `new_status`, one of `confirmed`, `cancelled` or `closed`. Returns a partial-success result with `total`, `successful` and `failed` counts and a `failures` list (`id` + `error_code` + Spanish `error_message`) for the orders whose current status does not admit that transition. Irreversible: `confirmed` stamps a series number on every order that reaches it, and `cancelled` and `closed` are terminal. Supports `Idempotency-Key` for safe retries.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.bulk_status" method="post" path="/companies/{company}/sales-orders/bulk-status" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1SalesOrdersBulkStatusRequest(
    company: 'Huels Group',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\BulkStatusSalesOrdersRequest(
        ids: [
            '41ffa28d-d796-4fb3-8359-113330e8fb86',
            'a9e71446-2050-4c86-96a3-5a66d9e2238f',
            '361a303d-2424-43b6-85b6-4ee9c3caf3da',
        ],
        newStatus: Components\BulkStatusSalesOrdersRequestNewStatus::Closed,
    ),
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersBulkStatus(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.bulk_status" method="post" path="/companies/{company}/sales-orders/bulk-status" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1SalesOrdersBulkStatusRequest(
    company: 'Jacobs Inc',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\BulkStatusSalesOrdersRequest(
        ids: [
            '41ffa28d-d796-4fb3-8359-113330e8fb86',
            'a9e71446-2050-4c86-96a3-5a66d9e2238f',
            '361a303d-2424-43b6-85b6-4ee9c3caf3da',
        ],
        newStatus: Components\BulkStatusSalesOrdersRequestNewStatus::Closed,
    ),
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersBulkStatus(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.bulk_status" method="post" path="/companies/{company}/sales-orders/bulk-status" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1SalesOrdersBulkStatusRequest(
    company: 'Erdman, Oberbrunner and Mitchell',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\BulkStatusSalesOrdersRequest(
        ids: [
            '41ffa28d-d796-4fb3-8359-113330e8fb86',
            'a9e71446-2050-4c86-96a3-5a66d9e2238f',
            '361a303d-2424-43b6-85b6-4ee9c3caf3da',
        ],
        newStatus: Components\BulkStatusSalesOrdersRequestNewStatus::Closed,
    ),
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersBulkStatus(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                | Type                                                                                                                     | Required                                                                                                                 | Description                                                                                                              |
| ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                               | [Operations\PublicApiV1SalesOrdersBulkStatusRequest](../../Models/Operations/PublicApiV1SalesOrdersBulkStatusRequest.md) | :heavy_check_mark:                                                                                                       | The request object to use for the request.                                                                               |

### Response

**[?Operations\PublicApiV1SalesOrdersBulkStatusResponse](../../Models/Operations/PublicApiV1SalesOrdersBulkStatusResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1SalesOrdersCancel

Cancel a sales order. It takes no body: the reason is not part of the published contract. The order stops admitting new deliveries or invoices and no published operation brings it back, so this is a terminal, irreversible transition. Whatever was already served or invoiced stays where it is; undoing that is the job of a credit note or a return, not of this call.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.cancel" method="post" path="/companies/{company}/sales-orders/{sales_order}/cancel" -->
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

$request = new Operations\PublicApiV1SalesOrdersCancelRequest(
    company: 'Fisher Group',
    salesOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersCancel(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                        | Type                                                                                                             | Required                                                                                                         | Description                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                       | [Operations\PublicApiV1SalesOrdersCancelRequest](../../Models/Operations/PublicApiV1SalesOrdersCancelRequest.md) | :heavy_check_mark:                                                                                               | The request object to use for the request.                                                                       |

### Response

**[?Operations\PublicApiV1SalesOrdersCancelResponse](../../Models/Operations/PublicApiV1SalesOrdersCancelResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1SalesOrdersClose

Close a sales order when you consider it finished even if something is still pending — the customer gave up the rest of the shipment, or you will not serve it. It takes no body, it is terminal and no published operation reopens it, so it is irreversible.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.close" method="post" path="/companies/{company}/sales-orders/{sales_order}/close" -->
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

$request = new Operations\PublicApiV1SalesOrdersCloseRequest(
    company: 'Schultz Group',
    salesOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersClose(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                      | Type                                                                                                           | Required                                                                                                       | Description                                                                                                    |
| -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                     | [Operations\PublicApiV1SalesOrdersCloseRequest](../../Models/Operations/PublicApiV1SalesOrdersCloseRequest.md) | :heavy_check_mark:                                                                                             | The request object to use for the request.                                                                     |

### Response

**[?Operations\PublicApiV1SalesOrdersCloseResponse](../../Models/Operations/PublicApiV1SalesOrdersCloseResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1SalesOrdersConfirm

Confirm a draft sales order: it stamps the number of its series and the order becomes the reference against which goods are served, invoiced and returned. It takes no body — set the series with the partial update before calling it, and if the order carries none the default series for sales orders is used. Irreversible: cancelling the order afterwards does not give the consumed number back, and the number consumed is exactly what cannot be recovered.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.confirm" method="post" path="/companies/{company}/sales-orders/{sales_order}/confirm" -->
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

$request = new Operations\PublicApiV1SalesOrdersConfirmRequest(
    company: 'Hessel, Kertzmann and Morar',
    salesOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersConfirm(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                          | Type                                                                                                               | Required                                                                                                           | Description                                                                                                        |
| ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                         | [Operations\PublicApiV1SalesOrdersConfirmRequest](../../Models/Operations/PublicApiV1SalesOrdersConfirmRequest.md) | :heavy_check_mark:                                                                                                 | The request object to use for the request.                                                                         |

### Response

**[?Operations\PublicApiV1SalesOrdersConfirmResponse](../../Models/Operations/PublicApiV1SalesOrdersConfirmResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1SalesOrdersConvertToDeliveryNote

Generate a delivery note from a sales order. Send `lines` with the id of each order line and the quantity to ship to deliver part of it, or omit the selection to ship everything still pending; asking for more than a line has pending returns 422. The response is the delivery note that was created. Irreversible: the converted quantities are consumed on the order, the delivery note takes a number from its series, and no published operation gives either of the two back.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.convert_to_delivery_note" method="post" path="/companies/{company}/sales-orders/{sales_order}/convert-to-delivery-note" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1SalesOrdersConvertToDeliveryNoteRequest(
    company: 'Johnson - Yundt',
    salesOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ConvertSalesOrderToDeliveryNoteRequest(
        lines: [
            '0199aa00-0000-7000-8000-000000000001' => '3.0000',
        ],
    ),
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersConvertToDeliveryNote(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: everything_pending

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.convert_to_delivery_note" method="post" path="/companies/{company}/sales-orders/{sales_order}/convert-to-delivery-note" example="everything_pending" -->
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

$request = new Operations\PublicApiV1SalesOrdersConvertToDeliveryNoteRequest(
    company: 'Franey Group',
    salesOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ConvertSalesOrderToDeliveryNoteRequest(
        warehouseId: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8c10',
    ),
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersConvertToDeliveryNote(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.convert_to_delivery_note" method="post" path="/companies/{company}/sales-orders/{sales_order}/convert-to-delivery-note" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1SalesOrdersConvertToDeliveryNoteRequest(
    company: 'Braun LLC',
    salesOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ConvertSalesOrderToDeliveryNoteRequest(
        lines: [
            '0199aa00-0000-7000-8000-000000000001' => '3.0000',
        ],
    ),
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersConvertToDeliveryNote(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.convert_to_delivery_note" method="post" path="/companies/{company}/sales-orders/{sales_order}/convert-to-delivery-note" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1SalesOrdersConvertToDeliveryNoteRequest(
    company: 'Terry LLC',
    salesOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ConvertSalesOrderToDeliveryNoteRequest(
        lines: [
            '0199aa00-0000-7000-8000-000000000001' => '3.0000',
        ],
    ),
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersConvertToDeliveryNote(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: partial_selection

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.convert_to_delivery_note" method="post" path="/companies/{company}/sales-orders/{sales_order}/convert-to-delivery-note" example="partial_selection" -->
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

$request = new Operations\PublicApiV1SalesOrdersConvertToDeliveryNoteRequest(
    company: 'Feeney LLC',
    salesOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ConvertSalesOrderToDeliveryNoteRequest(
        lines: [
            '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8e11' => 8,
            '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8e12' => 2,
        ],
        targetSeriesId: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8b40',
        warehouseId: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8c10',
    ),
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersConvertToDeliveryNote(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                      | Type                                                                                                                                           | Required                                                                                                                                       | Description                                                                                                                                    |
| ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                     | [Operations\PublicApiV1SalesOrdersConvertToDeliveryNoteRequest](../../Models/Operations/PublicApiV1SalesOrdersConvertToDeliveryNoteRequest.md) | :heavy_check_mark:                                                                                                                             | The request object to use for the request.                                                                                                     |

### Response

**[?Operations\PublicApiV1SalesOrdersConvertToDeliveryNoteResponse](../../Models/Operations/PublicApiV1SalesOrdersConvertToDeliveryNoteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1SalesOrdersConvertToInvoice

Issue an invoice from a sales order. Send `lines` with the id of each order line and the quantity to invoice to bill part of it, or omit the selection to invoice everything still pending; asking for more than a line has pending returns 422. The response is the invoice that was created. Irreversible: the converted quantities are consumed on the order, the invoice takes a number from its series, and no published operation gives either of the two back.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.convert_to_invoice" method="post" path="/companies/{company}/sales-orders/{sales_order}/convert-to-invoice" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1SalesOrdersConvertToInvoiceRequest(
    company: 'Abernathy, Littel and Harber',
    salesOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ConvertSalesOrderToInvoiceRequest(
        lines: [
            '0199aa00-0000-7000-8000-000000000001' => '3.0000',
        ],
    ),
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersConvertToInvoice(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: everything_pending

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.convert_to_invoice" method="post" path="/companies/{company}/sales-orders/{sales_order}/convert-to-invoice" example="everything_pending" -->
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

$request = new Operations\PublicApiV1SalesOrdersConvertToInvoiceRequest(
    company: 'Baumbach, Weimann and Macejkovic',
    salesOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ConvertSalesOrderToInvoiceRequest(
        targetSeriesId: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8b30',
    ),
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersConvertToInvoice(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.convert_to_invoice" method="post" path="/companies/{company}/sales-orders/{sales_order}/convert-to-invoice" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1SalesOrdersConvertToInvoiceRequest(
    company: 'Wintheiser Inc',
    salesOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ConvertSalesOrderToInvoiceRequest(
        lines: [
            '0199aa00-0000-7000-8000-000000000001' => '3.0000',
        ],
    ),
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersConvertToInvoice(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.convert_to_invoice" method="post" path="/companies/{company}/sales-orders/{sales_order}/convert-to-invoice" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1SalesOrdersConvertToInvoiceRequest(
    company: 'Morissette and Sons',
    salesOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ConvertSalesOrderToInvoiceRequest(
        lines: [
            '0199aa00-0000-7000-8000-000000000001' => '3.0000',
        ],
    ),
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersConvertToInvoice(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: partial_selection

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.convert_to_invoice" method="post" path="/companies/{company}/sales-orders/{sales_order}/convert-to-invoice" example="partial_selection" -->
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

$request = new Operations\PublicApiV1SalesOrdersConvertToInvoiceRequest(
    company: 'Hintz - Little',
    salesOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ConvertSalesOrderToInvoiceRequest(
        lines: [
            '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8e11' => 4,
        ],
        targetSeriesId: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8b30',
    ),
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersConvertToInvoice(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                            | Type                                                                                                                                 | Required                                                                                                                             | Description                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                           | [Operations\PublicApiV1SalesOrdersConvertToInvoiceRequest](../../Models/Operations/PublicApiV1SalesOrdersConvertToInvoiceRequest.md) | :heavy_check_mark:                                                                                                                   | The request object to use for the request.                                                                                           |

### Response

**[?Operations\PublicApiV1SalesOrdersConvertToInvoiceResponse](../../Models/Operations/PublicApiV1SalesOrdersConvertToInvoiceResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1SalesOrdersCreate

Create a sales order in `draft` status with its buyer, its billing and shipping addresses and its lines. `external_id` is your own reference for the order and is unique per company: sending one that already exists returns 409 and names the order that already holds it, instead of creating a second one. The response is the order that was created, with its lines and, per line, the quantity ordered, served, invoiced and returned plus the pending quantity derived from them. A draft carries no number — the number is stamped when you confirm it. On the entry plan the number of sales orders you may create per calendar month is capped: once the cap is reached this call returns 402 and nothing is created.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.create" method="post" path="/companies/{company}/sales-orders" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1SalesOrdersCreateRequest(
    company: 'Koelpin, Boehm and O\'Keefe',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateSalesOrderRequest(
        channel: Components\CreateSalesOrderRequestChannel::Assistant,
        orderDate: LocalDate::parse('2024-11-16'),
        buyerName: '<value>',
        lines: [
            new Components\CreateSalesOrderRequestLine(
                quantity: 5743.7,
            ),
        ],
    ),
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.create" method="post" path="/companies/{company}/sales-orders" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1SalesOrdersCreateRequest(
    company: 'Pouros - Jacobson',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateSalesOrderRequest(
        channel: Components\CreateSalesOrderRequestChannel::Assistant,
        orderDate: LocalDate::parse('2024-11-16'),
        buyerName: '<value>',
        lines: [
            new Components\CreateSalesOrderRequestLine(
                quantity: 5743.7,
            ),
        ],
    ),
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.create" method="post" path="/companies/{company}/sales-orders" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1SalesOrdersCreateRequest(
    company: 'Leffler LLC',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateSalesOrderRequest(
        channel: Components\CreateSalesOrderRequestChannel::Assistant,
        orderDate: LocalDate::parse('2024-11-16'),
        buyerName: '<value>',
        lines: [
            new Components\CreateSalesOrderRequestLine(
                quantity: 5743.7,
            ),
        ],
    ),
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                        | Type                                                                                                             | Required                                                                                                         | Description                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                       | [Operations\PublicApiV1SalesOrdersCreateRequest](../../Models/Operations/PublicApiV1SalesOrdersCreateRequest.md) | :heavy_check_mark:                                                                                               | The request object to use for the request.                                                                       |

### Response

**[?Operations\PublicApiV1SalesOrdersCreateResponse](../../Models/Operations/PublicApiV1SalesOrdersCreateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 401, 402, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1SalesOrdersList

List your sales orders with cursor-based pagination. Filter by `status`, `channel`, `business_contact_id`, `series_id`, `warehouse_id`, `number`, `external_id`, `order_date` and `expected_delivery_date`, search free text across the number and the buyer name, and sort by creation, number or expected delivery date. Each order comes back with its lines and their quantities, so a listing already answers what is left to serve.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.list" method="get" path="/companies/{company}/sales-orders" -->
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

$request = new Operations\PublicApiV1SalesOrdersListRequest(
    company: 'Rempel, Lemke and Oberbrunner',
    sort: Operations\PublicApiV1SalesOrdersListSort::MinusCreated,
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                    | Type                                                                                                         | Required                                                                                                     | Description                                                                                                  |
| ------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                   | [Operations\PublicApiV1SalesOrdersListRequest](../../Models/Operations/PublicApiV1SalesOrdersListRequest.md) | :heavy_check_mark:                                                                                           | The request object to use for the request.                                                                   |

### Response

**[?Operations\PublicApiV1SalesOrdersListResponse](../../Models/Operations/PublicApiV1SalesOrdersListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1SalesOrdersDelete

Delete a sales order. Only an order that has not consumed a number and has nothing served, invoiced or returned can be deleted; anything else returns 422. Irreversible: the order does not come back, and creating it again produces a different one with a different id.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.delete" method="delete" path="/companies/{company}/sales-orders/{sales_order}" -->
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

$request = new Operations\PublicApiV1SalesOrdersDeleteRequest(
    company: 'Pollich - Beier',
    salesOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersDelete(
    request: $request
);

if ($response->statusCode === 200) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                        | Type                                                                                                             | Required                                                                                                         | Description                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                       | [Operations\PublicApiV1SalesOrdersDeleteRequest](../../Models/Operations/PublicApiV1SalesOrdersDeleteRequest.md) | :heavy_check_mark:                                                                                               | The request object to use for the request.                                                                       |

### Response

**[?Operations\PublicApiV1SalesOrdersDeleteResponse](../../Models/Operations/PublicApiV1SalesOrdersDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1SalesOrdersShow

Retrieve a sales order by its `id`, with its buyer, its two addresses, its totals and its lines. An order of another company answers exactly like one that does not exist, so probing ids tells you nothing about them.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.show" method="get" path="/companies/{company}/sales-orders/{sales_order}" -->
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



$response = $sdk->salesOrders->publicApiV1SalesOrdersShow(
    company: 'Morissette, Hirthe and Abernathy',
    salesOrder: '<value>',
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
| `salesOrder`                                                                                                                                                                                                                                                                                                                                                                                     | *string*                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1SalesOrdersShowResponse](../../Models/Operations/PublicApiV1SalesOrdersShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1SalesOrdersUpdate

Partially update a sales order: only the fields present in the body change, and the ones you omit keep their value. Sending `lines` replaces the whole line collection at once; to touch a single line without resending the rest use the line sub-resource. This is also where you set the series the order will use before confirming it. An order that has left the state that admits editing returns 422 `sales_order_not_editable`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.update" method="patch" path="/companies/{company}/sales-orders/{sales_order}" -->
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

$request = new Operations\PublicApiV1SalesOrdersUpdateRequest(
    company: 'Wilderman, Bergstrom and Jaskolski',
    salesOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\UpdateSalesOrderRequest(
        expectedDeliveryDate: LocalDate::parse('2026-03-24'),
        notes: 'El comprador pide entrega conjunta de las dos líneas.',
    ),
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                        | Type                                                                                                             | Required                                                                                                         | Description                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                       | [Operations\PublicApiV1SalesOrdersUpdateRequest](../../Models/Operations/PublicApiV1SalesOrdersUpdateRequest.md) | :heavy_check_mark:                                                                                               | The request object to use for the request.                                                                       |

### Response

**[?Operations\PublicApiV1SalesOrdersUpdateResponse](../../Models/Operations/PublicApiV1SalesOrdersUpdateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1SalesOrdersPdf

Download the PDF representation of a sales order. Returns the binary PDF stream (`application/pdf`). Pass `?download=1` for `Content-Disposition: attachment` (file download); otherwise it is served `inline`. The document is the order CONFIRMATION and not an invoice: the sales order is not a fiscal document, so the PDF carries no fiscal block and a draft is watermarked.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.pdf" method="get" path="/companies/{company}/sales-orders/{sales_order}/pdf" -->
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

$request = new Operations\PublicApiV1SalesOrdersPdfRequest(
    company: 'Kemmer LLC',
    salesOrder: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersPdf(
    request: $request
);

if ($response->bytes !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                  | Type                                                                                                       | Required                                                                                                   | Description                                                                                                |
| ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                 | [Operations\PublicApiV1SalesOrdersPdfRequest](../../Models/Operations/PublicApiV1SalesOrdersPdfRequest.md) | :heavy_check_mark:                                                                                         | The request object to use for the request.                                                                 |

### Response

**[?Operations\PublicApiV1SalesOrdersPdfResponse](../../Models/Operations/PublicApiV1SalesOrdersPdfResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1SalesOrdersFindByExternalId

Look up a sales order by the `external_id` you assigned when you created it, so your own system can reconcile without storing our ids. It is a read that travels as a POST because the identifier goes in the body: nothing is created and nothing is modified. An identifier that is not yours, or that belongs to another company, returns 404 `sales_order_not_found`.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.find_by_external_id" method="post" path="/companies/{company}/sales-orders/find-by-external-id" example="api_key_revoked" -->
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

$body = new Components\FindSalesOrderByExternalIdRequest(
    externalId: '<id>',
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersFindByExternalId(
    company: 'Douglas - Shanahan',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.find_by_external_id" method="post" path="/companies/{company}/sales-orders/find-by-external-id" example="invalid_api_key" -->
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

$body = new Components\FindSalesOrderByExternalIdRequest(
    externalId: '<id>',
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersFindByExternalId(
    company: 'Denesik LLC',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.find_by_external_id" method="post" path="/companies/{company}/sales-orders/find-by-external-id" example="missing_api_key" -->
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

$body = new Components\FindSalesOrderByExternalIdRequest(
    externalId: '<id>',
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersFindByExternalId(
    company: 'Ward - Koch',
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
| `body`                                                                                                                                                                                                                                                                                                                                                                                           | [Components\FindSalesOrderByExternalIdRequest](../../Models/Components/FindSalesOrderByExternalIdRequest.md)                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1SalesOrdersFindByExternalIdResponse](../../Models/Operations/PublicApiV1SalesOrdersFindByExternalIdResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1SalesOrdersStats

Aggregated KPIs for the authenticated company: total order count and amount, the count per status with the zeros included, the total quantity still pending to serve and how many orders still have something pending. Returned as `{ "data": SalesOrderStats }`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.stats" method="get" path="/companies/{company}/sales-orders/stats" -->
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



$response = $sdk->salesOrders->publicApiV1SalesOrdersStats(
    company: 'Bergstrom LLC',
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
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1SalesOrdersStatsResponse](../../Models/Operations/PublicApiV1SalesOrdersStatsResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1SalesOrdersStatuses

Returns the closed catalog of sales order statuses with their public `value`, localized `label` and UI `color`, plus whether an order in each status can still be edited, deleted or converted. Use it to populate filters and status pickers instead of hard-coding values. Not every published status is a transition target: the ones derived from the served and invoiced quantities describe where an order stands, and you reach them by serving or invoicing it, never by asking for the status directly.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.statuses" method="get" path="/companies/{company}/sales-orders/statuses" -->
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



$response = $sdk->salesOrders->publicApiV1SalesOrdersStatuses(
    company: 'O\'Keefe - Mohr',
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
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1SalesOrdersStatusesResponse](../../Models/Operations/PublicApiV1SalesOrdersStatusesResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1SalesOrdersSend

Send the confirmation of a sales order to its buyer by email, with the printable document attached. The recipients travel in the body: `to` is the list of addresses, `cc` and `bcc` are optional, and `subject` and `body` override the defaults of the template. The language of the message is NOT accepted: it is sealed from the issuing company, so the same document always leaves in the same language whatever the caller asks. The answer is `202` with an acknowledgement that the email was accepted and QUEUED — never that it was delivered; the delivery is observed afterwards on the email deliveries surface, filtering by the id of the document. Irreversible: a delivered email is not retracted, so it requires an `Idempotency-Key` when the policy of your credential demands it. A sales order whose status does not admit sending is rejected as a business rule violation with 422 and `sales_order_not_sendable`, never with 403.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.send" method="post" path="/companies/{company}/sales-orders/{sales_order}/send" -->
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

$request = new Operations\PublicApiV1SalesOrdersSendRequest(
    company: 'Schimmel - Leuschke',
    salesOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\SendSalesOrderV1Request(
        to: [
            'compras@clientedelnorte.example',
        ],
        cc: [
            'administracion@clientedelnorte.example',
        ],
        bcc: [
            'archivo@aurora.example',
        ],
        subject: 'Confirmación de tu pedido PED-2026-0007',
        body: 'Adjuntamos la confirmación del pedido. Gracias por tu confianza.',
    ),
);

$response = $sdk->salesOrders->publicApiV1SalesOrdersSend(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                    | Type                                                                                                         | Required                                                                                                     | Description                                                                                                  |
| ------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                   | [Operations\PublicApiV1SalesOrdersSendRequest](../../Models/Operations/PublicApiV1SalesOrdersSendRequest.md) | :heavy_check_mark:                                                                                           | The request object to use for the request.                                                                   |

### Response

**[?Operations\PublicApiV1SalesOrdersSendResponse](../../Models/Operations/PublicApiV1SalesOrdersSendResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |