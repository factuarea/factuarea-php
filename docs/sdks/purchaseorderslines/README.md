# PurchaseOrders.Lines

## Overview

### Available Operations

* [publicApiV1PurchaseOrdersLinesCreate](#publicapiv1purchaseorderslinescreate) - Add a line to a purchase order
* [publicApiV1PurchaseOrdersLinesList](#publicapiv1purchaseorderslineslist) - List the lines of a purchase order
* [publicApiV1PurchaseOrdersLinesDelete](#publicapiv1purchaseorderslinesdelete) - Delete a line of a purchase order
* [publicApiV1PurchaseOrdersLinesUpdate](#publicapiv1purchaseorderslinesupdate) - Update a line of a purchase order

## publicApiV1PurchaseOrdersLinesCreate

Add a single line to a purchase order — the article, its variant, the supplier offer it is bought under and the quantity in the purchase unit — without resending the lines it already has. The unit cost, the conversion factor and the tax percentages are taken from the supplier offer at the moment the line is added and are not part of the body: a later change in the catalog does not move an order that already exists, and a later receipt converts with the factor the order stored. The quantity received is never writable: it moves only by posting or cancelling a goods receipt. An order that has left the state that admits editing rejects the call as a business rule violation.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_orders.lines.create" method="post" path="/companies/{company}/purchase-orders/{purchase_order}/lines" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1PurchaseOrdersLinesCreateRequest(
    company: 'Langworth, Yundt and Toy',
    purchaseOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\AddPurchaseOrderLineRequest(
        productId: 'ec011309-0926-44fc-8057-e2bec5bc8170',
        quantity: 2820.09,
    ),
);

$response = $sdk->purchaseOrders->lines->publicApiV1PurchaseOrdersLinesCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_orders.lines.create" method="post" path="/companies/{company}/purchase-orders/{purchase_order}/lines" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1PurchaseOrdersLinesCreateRequest(
    company: 'Haley - Halvorson',
    purchaseOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\AddPurchaseOrderLineRequest(
        productId: 'ec011309-0926-44fc-8057-e2bec5bc8170',
        quantity: 2820.09,
    ),
);

$response = $sdk->purchaseOrders->lines->publicApiV1PurchaseOrdersLinesCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_orders.lines.create" method="post" path="/companies/{company}/purchase-orders/{purchase_order}/lines" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1PurchaseOrdersLinesCreateRequest(
    company: 'Leffler, Miller and Lockman',
    purchaseOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\AddPurchaseOrderLineRequest(
        productId: 'ec011309-0926-44fc-8057-e2bec5bc8170',
        quantity: 2820.09,
    ),
);

$response = $sdk->purchaseOrders->lines->publicApiV1PurchaseOrdersLinesCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                        | Type                                                                                                                             | Required                                                                                                                         | Description                                                                                                                      |
| -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                       | [Operations\PublicApiV1PurchaseOrdersLinesCreateRequest](../../Models/Operations/PublicApiV1PurchaseOrdersLinesCreateRequest.md) | :heavy_check_mark:                                                                                                               | The request object to use for the request.                                                                                       |

### Response

**[?Operations\PublicApiV1PurchaseOrdersLinesCreateResponse](../../Models/Operations/PublicApiV1PurchaseOrdersLinesCreateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1PurchaseOrdersLinesList

List the lines of a purchase order with, for each one, the article and its variant, the quantity in the PURCHASE unit the supplier sells in, the code of that unit, the conversion factor to the base unit and the base quantity derived from it, the agreed unit cost, the taxes and the line total. Each line also carries how much has already arrived in posted goods receipts, how much is still pending, and the trace of the receipts that consumed that quantity, each one by its public id, its number and its date. It is the call that answers, in one round trip, what is left to receive and where the rest came in.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_orders.lines.list" method="get" path="/companies/{company}/purchase-orders/{purchase_order}/lines" -->
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



$response = $sdk->purchaseOrders->lines->publicApiV1PurchaseOrdersLinesList(
    company: 'Wilderman - Bins',
    purchaseOrder: '<value>',
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
| `purchaseOrder`                                                                                                                                                                                                                                                                                                                                                                                  | *string*                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1PurchaseOrdersLinesListResponse](../../Models/Operations/PublicApiV1PurchaseOrdersLinesListResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1PurchaseOrdersLinesDelete

Delete one line of a purchase order by its `id`. Removing a line is an edit of the order, not the destruction of a document: the order stays exactly where it was. An order that has left the state that admits editing rejects the call as a business rule violation.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_orders.lines.delete" method="delete" path="/companies/{company}/purchase-orders/{purchase_order}/lines/{line}" -->
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

$request = new Operations\PublicApiV1PurchaseOrdersLinesDeleteRequest(
    company: 'Rau - Steuber',
    purchaseOrder: '<value>',
    line: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->purchaseOrders->lines->publicApiV1PurchaseOrdersLinesDelete(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                        | Type                                                                                                                             | Required                                                                                                                         | Description                                                                                                                      |
| -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                       | [Operations\PublicApiV1PurchaseOrdersLinesDeleteRequest](../../Models/Operations/PublicApiV1PurchaseOrdersLinesDeleteRequest.md) | :heavy_check_mark:                                                                                                               | The request object to use for the request.                                                                                       |

### Response

**[?Operations\PublicApiV1PurchaseOrdersLinesDeleteResponse](../../Models/Operations/PublicApiV1PurchaseOrdersLinesDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1PurchaseOrdersLinesUpdate

Partially update one line of a purchase order by its `id`; the other lines are untouched. The article of a line is its identity and not one of its attributes, so it cannot be swapped — remove the line and add another one instead. The quantity received is not part of the body either: it moves only by posting or cancelling a goods receipt. An order that has left the state that admits editing rejects the call as a business rule violation.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_orders.lines.update" method="patch" path="/companies/{company}/purchase-orders/{purchase_order}/lines/{line}" -->
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

$request = new Operations\PublicApiV1PurchaseOrdersLinesUpdateRequest(
    company: 'Swift - O\'Reilly',
    purchaseOrder: '<value>',
    line: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\UpdatePurchaseOrderLineRequest(
        quantity: 10,
    ),
);

$response = $sdk->purchaseOrders->lines->publicApiV1PurchaseOrdersLinesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                        | Type                                                                                                                             | Required                                                                                                                         | Description                                                                                                                      |
| -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                       | [Operations\PublicApiV1PurchaseOrdersLinesUpdateRequest](../../Models/Operations/PublicApiV1PurchaseOrdersLinesUpdateRequest.md) | :heavy_check_mark:                                                                                                               | The request object to use for the request.                                                                                       |

### Response

**[?Operations\PublicApiV1PurchaseOrdersLinesUpdateResponse](../../Models/Operations/PublicApiV1PurchaseOrdersLinesUpdateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |