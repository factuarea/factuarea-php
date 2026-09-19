# PurchaseOrders.Receipts

## Overview

### Available Operations

* [publicApiV1PurchaseOrdersReceiptsList](#publicapiv1purchaseordersreceiptslist) - List the goods receipts of a purchase order
* [publicApiV1PurchaseOrdersReceiptsCreate](#publicapiv1purchaseordersreceiptscreate) - Register a goods receipt for a purchase order

## publicApiV1PurchaseOrdersReceiptsList

List the goods receipts registered against one purchase order, taking the order from the path, with cursor-based pagination and a filter by `status` so you can ask for the posted ones only. It returns exactly the same documents the goods receipt listing returns filtered by that order. It needs the READ scope of the GOODS RECEIPT, not the one of the purchase order: the module that gates receiving goods is the purchase order one, but the scope that authorizes this call is the scope of the receipt.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_orders.receipts.list" method="get" path="/companies/{company}/purchase-orders/{purchase_order}/receipts" -->
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

$request = new Operations\PublicApiV1PurchaseOrdersReceiptsListRequest(
    company: 'Gibson LLC',
    purchaseOrder: '<value>',
    sort: Operations\PublicApiV1PurchaseOrdersReceiptsListSort::MinusReceivedOn,
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->purchaseOrders->receipts->publicApiV1PurchaseOrdersReceiptsList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                          | Type                                                                                                                               | Required                                                                                                                           | Description                                                                                                                        |
| ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                         | [Operations\PublicApiV1PurchaseOrdersReceiptsListRequest](../../Models/Operations/PublicApiV1PurchaseOrdersReceiptsListRequest.md) | :heavy_check_mark:                                                                                                                 | The request object to use for the request.                                                                                         |

### Response

**[?Operations\PublicApiV1PurchaseOrdersReceiptsListResponse](../../Models/Operations/PublicApiV1PurchaseOrdersReceiptsListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1PurchaseOrdersReceiptsCreate

Register a goods receipt against one purchase order, taking the order from the path instead of from the body. It produces exactly the same effect as registering it through the goods receipt resource: same document, same rules and same response, with the order fixed by the URL. The receipt is created and NOT posted — posting it, which is what consumes ordered quantity and moves the goods, is a separate call. It needs the WRITE scope of the GOODS RECEIPT, not the one of the purchase order.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_orders.receipts.create" method="post" path="/companies/{company}/purchase-orders/{purchase_order}/receipts" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1PurchaseOrdersReceiptsCreateRequest(
    company: 'Jones - Hahn',
    purchaseOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\RegisterGoodsReceiptRequest(
        receivedOn: LocalDate::parse('2025-05-06'),
        lines: [],
    ),
);

$response = $sdk->purchaseOrders->receipts->publicApiV1PurchaseOrdersReceiptsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_orders.receipts.create" method="post" path="/companies/{company}/purchase-orders/{purchase_order}/receipts" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1PurchaseOrdersReceiptsCreateRequest(
    company: 'Waelchi - Bernier',
    purchaseOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\RegisterGoodsReceiptRequest(
        receivedOn: LocalDate::parse('2025-05-06'),
        lines: [],
    ),
);

$response = $sdk->purchaseOrders->receipts->publicApiV1PurchaseOrdersReceiptsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_orders.receipts.create" method="post" path="/companies/{company}/purchase-orders/{purchase_order}/receipts" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1PurchaseOrdersReceiptsCreateRequest(
    company: 'Rowe, Dietrich and Williamson',
    purchaseOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\RegisterGoodsReceiptRequest(
        receivedOn: LocalDate::parse('2025-05-06'),
        lines: [],
    ),
);

$response = $sdk->purchaseOrders->receipts->publicApiV1PurchaseOrdersReceiptsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                              | Type                                                                                                                                   | Required                                                                                                                               | Description                                                                                                                            |
| -------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                             | [Operations\PublicApiV1PurchaseOrdersReceiptsCreateRequest](../../Models/Operations/PublicApiV1PurchaseOrdersReceiptsCreateRequest.md) | :heavy_check_mark:                                                                                                                     | The request object to use for the request.                                                                                             |

### Response

**[?Operations\PublicApiV1PurchaseOrdersReceiptsCreateResponse](../../Models/Operations/PublicApiV1PurchaseOrdersReceiptsCreateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |