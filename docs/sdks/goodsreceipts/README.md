# GoodsReceipts

## Overview

### Available Operations

* [publicApiV1GoodsReceiptsBulkStatus](#publicapiv1goodsreceiptsbulkstatus) - Bulk change goods receipt status
* [publicApiV1GoodsReceiptsCancel](#publicapiv1goodsreceiptscancel) - Cancel a goods receipt
* [publicApiV1GoodsReceiptsStats](#publicapiv1goodsreceiptsstats) - Retrieve goods receipt stats
* [publicApiV1GoodsReceiptsStatuses](#publicapiv1goodsreceiptsstatuses) - List goods receipt statuses
* [publicApiV1GoodsReceiptsList](#publicapiv1goodsreceiptslist) - List all goods receipts
* [publicApiV1GoodsReceiptsCreate](#publicapiv1goodsreceiptscreate) - Register a goods receipt
* [publicApiV1GoodsReceiptsPost](#publicapiv1goodsreceiptspost) - Post a goods receipt
* [publicApiV1GoodsReceiptsShow](#publicapiv1goodsreceiptsshow) - Retrieve a goods receipt

## publicApiV1GoodsReceiptsBulkStatus

Move several goods receipts to the same status in one request. The body takes an `ids` array of receipt ids, up to 100, and `new_status`, one of `posted` or `cancelled`, which are the only two that are a legitimate transition target. It is applied element by element and returns a partial-success result with `total`, `successful` and `failed` counts and a `failures` list (`id` + `error_code` + Spanish `error_message`) for the receipts whose current status does not admit that transition; ids of another company are reported as not found and nothing of that company is touched. Irreversible, because it composes posting and cancelling. Supports `Idempotency-Key` for safe retries.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.goods_receipts.bulk_status" method="post" path="/companies/{company}/goods-receipts/bulk-status" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1GoodsReceiptsBulkStatusRequest(
    company: 'Fay - Roob',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\BulkStatusGoodsReceiptsRequest(
        ids: [
            '01b366c0-a9e0-4aa6-bfa5-1f5fa7feef83',
            'eeb1b578-d718-4e24-ae7c-9ad0431f80be',
        ],
        newStatus: Components\BulkStatusGoodsReceiptsRequestNewStatus::Cancelled,
    ),
);

$response = $sdk->goodsReceipts->publicApiV1GoodsReceiptsBulkStatus(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.goods_receipts.bulk_status" method="post" path="/companies/{company}/goods-receipts/bulk-status" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1GoodsReceiptsBulkStatusRequest(
    company: 'Gusikowski Inc',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\BulkStatusGoodsReceiptsRequest(
        ids: [
            '01b366c0-a9e0-4aa6-bfa5-1f5fa7feef83',
            'eeb1b578-d718-4e24-ae7c-9ad0431f80be',
        ],
        newStatus: Components\BulkStatusGoodsReceiptsRequestNewStatus::Cancelled,
    ),
);

$response = $sdk->goodsReceipts->publicApiV1GoodsReceiptsBulkStatus(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.goods_receipts.bulk_status" method="post" path="/companies/{company}/goods-receipts/bulk-status" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1GoodsReceiptsBulkStatusRequest(
    company: 'Kling - Harris',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\BulkStatusGoodsReceiptsRequest(
        ids: [
            '01b366c0-a9e0-4aa6-bfa5-1f5fa7feef83',
            'eeb1b578-d718-4e24-ae7c-9ad0431f80be',
        ],
        newStatus: Components\BulkStatusGoodsReceiptsRequestNewStatus::Cancelled,
    ),
);

$response = $sdk->goodsReceipts->publicApiV1GoodsReceiptsBulkStatus(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                    | Type                                                                                                                         | Required                                                                                                                     | Description                                                                                                                  |
| ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                   | [Operations\PublicApiV1GoodsReceiptsBulkStatusRequest](../../Models/Operations/PublicApiV1GoodsReceiptsBulkStatusRequest.md) | :heavy_check_mark:                                                                                                           | The request object to use for the request.                                                                                   |

### Response

**[?Operations\PublicApiV1GoodsReceiptsBulkStatusResponse](../../Models/Operations/PublicApiV1GoodsReceiptsBulkStatusResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1GoodsReceiptsCancel

Cancel a goods receipt. It takes no body. If the receipt had been posted, the quantity it consumed goes back to pending on the lines of the purchase order and the status derived from what has arrived is recalculated with the receipts that remain posted — an order that was complete goes back to partially received. Cancelled is terminal: no later transition prospers. Irreversible: the movement it produces is not undone by any published operation, so it requires an `Idempotency-Key` when the policy of your credential demands it.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.goods_receipts.cancel" method="post" path="/companies/{company}/goods-receipts/{goods_receipt}/cancel" -->
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

$request = new Operations\PublicApiV1GoodsReceiptsCancelRequest(
    company: 'Rogahn - O\'Reilly',
    goodsReceipt: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->goodsReceipts->publicApiV1GoodsReceiptsCancel(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                            | Type                                                                                                                 | Required                                                                                                             | Description                                                                                                          |
| -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                           | [Operations\PublicApiV1GoodsReceiptsCancelRequest](../../Models/Operations/PublicApiV1GoodsReceiptsCancelRequest.md) | :heavy_check_mark:                                                                                                   | The request object to use for the request.                                                                           |

### Response

**[?Operations\PublicApiV1GoodsReceiptsCancelResponse](../../Models/Operations/PublicApiV1GoodsReceiptsCancelResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1GoodsReceiptsStats

Aggregated KPIs of the goods receipts of the authenticated company: the total number of receipts, the count per status with the zeros included and the total quantity received, expressed in the base unit. This family counts units and not money, so no amount is returned. Returned as `{ "data": GoodsReceiptStats }`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.goods_receipts.stats" method="get" path="/companies/{company}/goods-receipts/stats" -->
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



$response = $sdk->goodsReceipts->publicApiV1GoodsReceiptsStats(
    company: 'Kuhic LLC',
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

**[?Operations\PublicApiV1GoodsReceiptsStatsResponse](../../Models/Operations/PublicApiV1GoodsReceiptsStatsResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1GoodsReceiptsStatuses

Returns the closed catalog of goods receipt statuses with their public `value`, localized `label` and UI `color`, plus whether a receipt in each status can still be edited, deleted or converted. Use it to populate filters and status pickers instead of hard-coding values. It contains the three statuses a receipt can reach — created, posted and cancelled — and the initial one is not a transition target: a receipt is born created.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.goods_receipts.statuses" method="get" path="/companies/{company}/goods-receipts/statuses" -->
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



$response = $sdk->goodsReceipts->publicApiV1GoodsReceiptsStatuses(
    company: 'Jerde Group',
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

**[?Operations\PublicApiV1GoodsReceiptsStatusesResponse](../../Models/Operations/PublicApiV1GoodsReceiptsStatusesResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1GoodsReceiptsList

List your goods receipts with cursor-based pagination. Filter by `status`, `purchase_order_id`, `supplier_id`, `warehouse_id`, `number` and `received_on`, and sort by reception date or by number. Each receipt comes back with the purchase order it belongs to, its warehouse and its lines.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.goods_receipts.list" method="get" path="/companies/{company}/goods-receipts" -->
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

$request = new Operations\PublicApiV1GoodsReceiptsListRequest(
    company: 'Jacobson and Sons',
    sort: Operations\PublicApiV1GoodsReceiptsListSort::MinusReceivedOn,
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->goodsReceipts->publicApiV1GoodsReceiptsList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                        | Type                                                                                                             | Required                                                                                                         | Description                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                       | [Operations\PublicApiV1GoodsReceiptsListRequest](../../Models/Operations/PublicApiV1GoodsReceiptsListRequest.md) | :heavy_check_mark:                                                                                               | The request object to use for the request.                                                                       |

### Response

**[?Operations\PublicApiV1GoodsReceiptsListResponse](../../Models/Operations/PublicApiV1GoodsReceiptsListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1GoodsReceiptsCreate

Register the arrival of goods against a purchase order: the order, the date, the warehouse the goods enter — optional —, a note and the lines that arrived, each one naming a line of the order and the quantity received in the PURCHASE unit. At least one line is required: an empty receipt is not a document, it is a mistake of whoever sends it. Quantities are converted to the base unit with the conversion factor the ORDER stored, never with the one the catalog has today. Receiving more than a line has pending returns 422 with the code that names it, before anything is created; what is pending counts POSTED receipts only, so a cancelled one gives its quantity back. The receipt is created in `created` status: nothing has moved and no quantity is consumed until you post it, which is why the creation itself is not irreversible.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.goods_receipts.create" method="post" path="/companies/{company}/goods-receipts" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1GoodsReceiptsCreateRequest(
    company: 'Collins, West and Abbott',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\RegisterGoodsReceiptRequest(
        receivedOn: LocalDate::parse('2026-01-11'),
        lines: [
            new Components\RegisterGoodsReceiptRequestLine(
                purchaseOrderLineId: 'ec41169f-530e-454b-b4c6-14c9da947082',
                quantity: 2277.56,
            ),
        ],
    ),
);

$response = $sdk->goodsReceipts->publicApiV1GoodsReceiptsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.goods_receipts.create" method="post" path="/companies/{company}/goods-receipts" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1GoodsReceiptsCreateRequest(
    company: 'Howe LLC',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\RegisterGoodsReceiptRequest(
        receivedOn: LocalDate::parse('2026-01-11'),
        lines: [
            new Components\RegisterGoodsReceiptRequestLine(
                purchaseOrderLineId: 'ec41169f-530e-454b-b4c6-14c9da947082',
                quantity: 2277.56,
            ),
        ],
    ),
);

$response = $sdk->goodsReceipts->publicApiV1GoodsReceiptsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.goods_receipts.create" method="post" path="/companies/{company}/goods-receipts" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1GoodsReceiptsCreateRequest(
    company: 'Wehner Group',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\RegisterGoodsReceiptRequest(
        receivedOn: LocalDate::parse('2026-01-11'),
        lines: [
            new Components\RegisterGoodsReceiptRequestLine(
                purchaseOrderLineId: 'ec41169f-530e-454b-b4c6-14c9da947082',
                quantity: 2277.56,
            ),
        ],
    ),
);

$response = $sdk->goodsReceipts->publicApiV1GoodsReceiptsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                            | Type                                                                                                                 | Required                                                                                                             | Description                                                                                                          |
| -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                           | [Operations\PublicApiV1GoodsReceiptsCreateRequest](../../Models/Operations/PublicApiV1GoodsReceiptsCreateRequest.md) | :heavy_check_mark:                                                                                                   | The request object to use for the request.                                                                           |

### Response

**[?Operations\PublicApiV1GoodsReceiptsCreateResponse](../../Models/Operations/PublicApiV1GoodsReceiptsCreateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1GoodsReceiptsPost

Post a goods receipt: this is the call in which the goods actually come in. It takes no body. It consumes received quantity on the lines of the purchase order, triggers the stock entry in the warehouse and, in the same operation, leaves the order in the status derived from what has arrived — partially received while something is still pending, received when nothing is. A receipt that is already posted returns 422 with the code that names it, and a cancelled one cannot be posted. Irreversible: no published operation takes the entry back, because cancelling the receipt is a new movement and not an undo. It requires an `Idempotency-Key` when the policy of your credential demands it, and repeating it with the same key returns the result of the first call without consuming the quantity twice.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.goods_receipts.post" method="post" path="/companies/{company}/goods-receipts/{goods_receipt}/post" -->
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

$request = new Operations\PublicApiV1GoodsReceiptsPostRequest(
    company: 'Quigley, Cruickshank and Purdy',
    goodsReceipt: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->goodsReceipts->publicApiV1GoodsReceiptsPost(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                        | Type                                                                                                             | Required                                                                                                         | Description                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                       | [Operations\PublicApiV1GoodsReceiptsPostRequest](../../Models/Operations/PublicApiV1GoodsReceiptsPostRequest.md) | :heavy_check_mark:                                                                                               | The request object to use for the request.                                                                       |

### Response

**[?Operations\PublicApiV1GoodsReceiptsPostResponse](../../Models/Operations/PublicApiV1GoodsReceiptsPostResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1GoodsReceiptsShow

Retrieve a goods receipt by its `id`, with the purchase order it belongs to — by its public id and its number —, its warehouse when it has one, its lines and the moments at which it was posted or cancelled. A receipt without a warehouse is valid: the field is simply absent. A receipt of another company answers exactly like one that does not exist.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.goods_receipts.show" method="get" path="/companies/{company}/goods-receipts/{goods_receipt}" -->
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



$response = $sdk->goodsReceipts->publicApiV1GoodsReceiptsShow(
    company: 'Veum - Ward',
    goodsReceipt: '<value>',
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
| `goodsReceipt`                                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1GoodsReceiptsShowResponse](../../Models/Operations/PublicApiV1GoodsReceiptsShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |