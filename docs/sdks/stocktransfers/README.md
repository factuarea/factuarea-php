# StockTransfers

## Overview

### Available Operations

* [publicApiV1StockTransfersCancel](#publicapiv1stocktransferscancel) - Cancel a stock transfer
* [publicApiV1StockTransfersCreate](#publicapiv1stocktransferscreate) - Create a stock transfer
* [publicApiV1StockTransfersList](#publicapiv1stocktransferslist) - List all stock transfers
* [publicApiV1StockTransfersDispatch](#publicapiv1stocktransfersdispatch) - Dispatch a stock transfer
* [publicApiV1StockTransfersPdf](#publicapiv1stocktransferspdf) - Download stock transfer note PDF
* [publicApiV1StockTransfersStatuses](#publicapiv1stocktransfersstatuses) - List stock transfer statuses
* [publicApiV1StockTransfersReceive](#publicapiv1stocktransfersreceive) - Receive a stock transfer
* [publicApiV1StockTransfersSend](#publicapiv1stocktransferssend) - Send a stock transfer note
* [publicApiV1StockTransfersShow](#publicapiv1stocktransfersshow) - Retrieve a stock transfer

## publicApiV1StockTransfersCancel

Cancel a stock transfer. It takes no body: the reason is not part of the published contract. A draft and a dispatched transfer can be cancelled; one that has already been received cannot, and returns 422 with the code that names the invalid transition. Cancelling does NOT move stock: it writes no movement and it does not give back what dispatching took out of the origin warehouse — goods that come back come back as a transfer of their own, in the opposite direction. Cancelled is terminal and no published operation reopens the transfer, so this transition is irreversible and requires an `Idempotency-Key` when the policy of your credential demands it. Returns the transfer as cancelled.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.stock_transfers.cancel" method="post" path="/companies/{company}/stock-transfers/{stock_transfer}/cancel" -->
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

$request = new Operations\PublicApiV1StockTransfersCancelRequest(
    company: 'McClure, Hickle and Legros',
    stockTransfer: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->stockTransfers->publicApiV1StockTransfersCancel(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                              | Type                                                                                                                   | Required                                                                                                               | Description                                                                                                            |
| ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                             | [Operations\PublicApiV1StockTransfersCancelRequest](../../Models/Operations/PublicApiV1StockTransfersCancelRequest.md) | :heavy_check_mark:                                                                                                     | The request object to use for the request.                                                                             |

### Response

**[?Operations\PublicApiV1StockTransfersCancelResponse](../../Models/Operations/PublicApiV1StockTransfersCancelResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1StockTransfersCreate

Create a transfer of goods between two of your warehouses, in `draft` status: the origin warehouse, the destination warehouse — which has to be a different one of the same company —, an optional note and, optionally, its lines, each one with the article, its variant when it has one and the quantity to move. Quantities carry four decimal places, so articles that are sold by weight travel whole. The number is stamped when the transfer is created, it is unique per company and it is NOT a fiscal number: it consumes no series, and a transfer that never ships leaves a permanent gap. Creating it moves no stock — a draft has not left any warehouse yet. The response is the transfer with its number, its status and its lines, each one with the quantity to dispatch, the quantity received so far and the quantity still pending.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.stock_transfers.create" method="post" path="/companies/{company}/stock-transfers" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1StockTransfersCreateRequest(
    company: 'Beatty - Franey',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateStockTransferRequest(
        originWarehouseId: 'b9b00013-cee5-47f4-9d05-6f020d470c49',
        destinationWarehouseId: '1bcce32f-123f-458e-b4e2-33f693c95065',
    ),
);

$response = $sdk->stockTransfers->publicApiV1StockTransfersCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.stock_transfers.create" method="post" path="/companies/{company}/stock-transfers" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1StockTransfersCreateRequest(
    company: 'Pfannerstill - Funk',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateStockTransferRequest(
        originWarehouseId: 'b9b00013-cee5-47f4-9d05-6f020d470c49',
        destinationWarehouseId: '1bcce32f-123f-458e-b4e2-33f693c95065',
    ),
);

$response = $sdk->stockTransfers->publicApiV1StockTransfersCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.stock_transfers.create" method="post" path="/companies/{company}/stock-transfers" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1StockTransfersCreateRequest(
    company: 'Kunde - Koelpin',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateStockTransferRequest(
        originWarehouseId: 'b9b00013-cee5-47f4-9d05-6f020d470c49',
        destinationWarehouseId: '1bcce32f-123f-458e-b4e2-33f693c95065',
    ),
);

$response = $sdk->stockTransfers->publicApiV1StockTransfersCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                              | Type                                                                                                                   | Required                                                                                                               | Description                                                                                                            |
| ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                             | [Operations\PublicApiV1StockTransfersCreateRequest](../../Models/Operations/PublicApiV1StockTransfersCreateRequest.md) | :heavy_check_mark:                                                                                                     | The request object to use for the request.                                                                             |

### Response

**[?Operations\PublicApiV1StockTransfersCreateResponse](../../Models/Operations/PublicApiV1StockTransfersCreateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1StockTransfersList

List your stock transfers with cursor-based pagination. Filter by status, by origin or destination warehouse — both by their public id —, by number and by the dispatch and reception dates, search free text over the number and the notes, and sort by creation, number or dispatch date. A date filter given as a plain day covers that whole day. Each transfer comes back with its two warehouses, its moments and its lines, and each line with what it dispatches, what has been received and what is still pending, derived from those two.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.stock_transfers.list" method="get" path="/companies/{company}/stock-transfers" -->
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

$request = new Operations\PublicApiV1StockTransfersListRequest(
    company: 'Buckridge, Prohaska and Deckow',
    sort: Operations\PublicApiV1StockTransfersListSort::MinusCreated,
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->stockTransfers->publicApiV1StockTransfersList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                          | Type                                                                                                               | Required                                                                                                           | Description                                                                                                        |
| ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                         | [Operations\PublicApiV1StockTransfersListRequest](../../Models/Operations/PublicApiV1StockTransfersListRequest.md) | :heavy_check_mark:                                                                                                 | The request object to use for the request.                                                                         |

### Response

**[?Operations\PublicApiV1StockTransfersListResponse](../../Models/Operations/PublicApiV1StockTransfersListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1StockTransfersDispatch

Dispatch a stock transfer: the goods leave the ORIGIN warehouse and stay in transit until they are received. It takes no body and it dispatches the lines as they stand, so a transfer with no lines is rejected with 422, and dispatching one that is already dispatched returns 422 with the code that names that condition. This call DOES move stock, and it is the part to read before building your own reconciliation: dispatching records, for every line, the OUTGOING movement in the origin warehouse, so the balance of that article in that warehouse goes down by the quantity dispatched. That ledger entry is written only for articles whose stock you track and while stock management is active for your company; when it is not, the transfer still advances but no balance changes. Irreversible: no published operation takes that movement back, so it requires an `Idempotency-Key` when the policy of your credential demands it. Returns the transfer as it is left.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.stock_transfers.dispatch" method="post" path="/companies/{company}/stock-transfers/{stock_transfer}/dispatch" -->
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

$request = new Operations\PublicApiV1StockTransfersDispatchRequest(
    company: 'Fritsch, Roob and Ziemann',
    stockTransfer: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->stockTransfers->publicApiV1StockTransfersDispatch(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1StockTransfersDispatchRequest](../../Models/Operations/PublicApiV1StockTransfersDispatchRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1StockTransfersDispatchResponse](../../Models/Operations/PublicApiV1StockTransfersDispatchResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1StockTransfersPdf

Download the PDF representation of a stock transfer. Returns the binary PDF stream (`application/pdf`). Pass `?download=1` for `Content-Disposition: attachment` (file download); otherwise it is served `inline`. The note carries the origin and destination warehouses, the lines with the dispatched quantity and a BLANK column for the received one, ruled for signing on arrival, and two signature blocks. It carries no amounts: a transfer moves goods between two warehouses of the same company and no money changes hands.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.stock_transfers.pdf" method="get" path="/companies/{company}/stock-transfers/{stock_transfer}/pdf" -->
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

$request = new Operations\PublicApiV1StockTransfersPdfRequest(
    company: 'Rutherford - Rempel',
    stockTransfer: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->stockTransfers->publicApiV1StockTransfersPdf(
    request: $request
);

if ($response->bytes !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                        | Type                                                                                                             | Required                                                                                                         | Description                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                       | [Operations\PublicApiV1StockTransfersPdfRequest](../../Models/Operations/PublicApiV1StockTransfersPdfRequest.md) | :heavy_check_mark:                                                                                               | The request object to use for the request.                                                                       |

### Response

**[?Operations\PublicApiV1StockTransfersPdfResponse](../../Models/Operations/PublicApiV1StockTransfersPdfResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1StockTransfersStatuses

Returns the closed catalog of stock transfer statuses with their public `value`, their localized `label` and what a transfer in each status still admits — whether it can be edited, dispatched, received or cancelled. Use it to populate filters and status pickers instead of hard-coding values. It contains the four statuses a transfer can reach — draft, dispatched, received and cancelled — and the last two are terminal and admit nothing. The catalog enumerates STATUSES and not transition targets: a transfer is born a draft, and no transition leads back to it.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.stock_transfers.statuses" method="get" path="/companies/{company}/stock-transfers/statuses" -->
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



$response = $sdk->stockTransfers->publicApiV1StockTransfersStatuses(
    company: 'Bernhard, Kovacek and Deckow',
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

**[?Operations\PublicApiV1StockTransfersStatusesResponse](../../Models/Operations/PublicApiV1StockTransfersStatusesResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1StockTransfersReceive

Receive a stock transfer, in full or in part: the body carries the quantity received per line, and a line you do not name receives nothing. Reception is CUMULATIVE — successive receptions add up towards what was dispatched — and the whole body is validated before anything is written, so receiving more than a line has pending is rejected with 422 and records nothing at all. The transfer only becomes received when every line has received what it dispatched; while something is still pending it stays dispatched and admits further receptions. This call DOES move stock, and WHEN it does is the part to read before building your own reconciliation: the INCOMING movement in the DESTINATION warehouse is written when the transfer becomes fully received, not on each partial reception. A partial reception records the quantity on the line and leaves the transfer dispatched, and no balance moves until the last unit arrives; then the whole of what was dispatched enters the destination warehouse at once. As with dispatching, the ledger entry is written only for articles whose stock you track and while stock management is active for your company. Irreversible, and this is the operation where the idempotency key really matters: repeating the same reception without it would advance the received quantity twice, and that could close the transfer or make a legitimate later reception be rejected as excess. It requires an `Idempotency-Key` when the policy of your credential demands it, and repeating it with the same key produces the effect once. Returns the transfer with its lines and what is left pending.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.stock_transfers.receive" method="post" path="/companies/{company}/stock-transfers/{stock_transfer}/receive" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1StockTransfersReceiveRequest(
    company: 'Gibson, Hand and Gerhold',
    stockTransfer: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ReceiveStockTransferRequest(
        lines: [
            '0199aa00-0000-7000-8000-000000000001' => '2.0000',
        ],
    ),
);

$response = $sdk->stockTransfers->publicApiV1StockTransfersReceive(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.stock_transfers.receive" method="post" path="/companies/{company}/stock-transfers/{stock_transfer}/receive" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1StockTransfersReceiveRequest(
    company: 'Kuphal, Fay and Crist',
    stockTransfer: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ReceiveStockTransferRequest(
        lines: [
            '0199aa00-0000-7000-8000-000000000001' => '2.0000',
        ],
    ),
);

$response = $sdk->stockTransfers->publicApiV1StockTransfersReceive(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.stock_transfers.receive" method="post" path="/companies/{company}/stock-transfers/{stock_transfer}/receive" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1StockTransfersReceiveRequest(
    company: 'Schultz Inc',
    stockTransfer: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ReceiveStockTransferRequest(
        lines: [
            '0199aa00-0000-7000-8000-000000000001' => '2.0000',
        ],
    ),
);

$response = $sdk->stockTransfers->publicApiV1StockTransfersReceive(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                | Type                                                                                                                     | Required                                                                                                                 | Description                                                                                                              |
| ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                               | [Operations\PublicApiV1StockTransfersReceiveRequest](../../Models/Operations/PublicApiV1StockTransfersReceiveRequest.md) | :heavy_check_mark:                                                                                                       | The request object to use for the request.                                                                               |

### Response

**[?Operations\PublicApiV1StockTransfersReceiveResponse](../../Models/Operations/PublicApiV1StockTransfersReceiveResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1StockTransfersSend

Send the note of a stock transfer by email, with the printable document attached. The recipients travel in the body: `to` is the list of addresses, `cc` and `bcc` are optional, and `subject` and `body` override the defaults of the template. The language of the message is NOT accepted: it is sealed from the issuing company, so the same document always leaves in the same language whatever the caller asks. The answer is `202` with an acknowledgement that the email was accepted and QUEUED — never that it was delivered; the delivery is observed afterwards on the email deliveries surface, filtering by the id of the document. Irreversible: a delivered email is not retracted, so it requires an `Idempotency-Key` when the policy of your credential demands it. `to` is REQUIRED here and optional on the other document families: a warehouse is not a contact and carries no email address, so there is no default recipient to fall back on — omitting it is rejected with 422 and `param: to`. A cancelled transfer is rejected as a business rule violation with 422 and `stock_transfer_not_sendable`, never with 403.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.stock_transfers.send" method="post" path="/companies/{company}/stock-transfers/{stock_transfer}/send" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1StockTransfersSendRequest(
    company: 'Dietrich, MacGyver and Emmerich',
    stockTransfer: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\SendStockTransferV1Request(
        to: [],
    ),
);

$response = $sdk->stockTransfers->publicApiV1StockTransfersSend(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.stock_transfers.send" method="post" path="/companies/{company}/stock-transfers/{stock_transfer}/send" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1StockTransfersSendRequest(
    company: 'Dare, Halvorson and Corkery',
    stockTransfer: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\SendStockTransferV1Request(
        to: [],
    ),
);

$response = $sdk->stockTransfers->publicApiV1StockTransfersSend(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.stock_transfers.send" method="post" path="/companies/{company}/stock-transfers/{stock_transfer}/send" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1StockTransfersSendRequest(
    company: 'Cummerata Inc',
    stockTransfer: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\SendStockTransferV1Request(
        to: [],
    ),
);

$response = $sdk->stockTransfers->publicApiV1StockTransfersSend(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                          | Type                                                                                                               | Required                                                                                                           | Description                                                                                                        |
| ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                         | [Operations\PublicApiV1StockTransfersSendRequest](../../Models/Operations/PublicApiV1StockTransfersSendRequest.md) | :heavy_check_mark:                                                                                                 | The request object to use for the request.                                                                         |

### Response

**[?Operations\PublicApiV1StockTransfersSendResponse](../../Models/Operations/PublicApiV1StockTransfersSendResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1StockTransfersShow

Retrieve a stock transfer by its `id`, with its number, its status, its origin and destination warehouses, the moment it was dispatched, the moment it was fully received, its notes and its lines. A transfer that is still a draft carries neither moment, and one received in parts carries the dispatch moment only, because the reception moment is the one of the COMPLETE reception. A transfer of another company answers exactly like one that does not exist.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.stock_transfers.show" method="get" path="/companies/{company}/stock-transfers/{stock_transfer}" -->
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



$response = $sdk->stockTransfers->publicApiV1StockTransfersShow(
    company: 'Medhurst, Marks and Halvorson',
    stockTransfer: '<value>',
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
| `stockTransfer`                                                                                                                                                                                                                                                                                                                                                                                  | *string*                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1StockTransfersShowResponse](../../Models/Operations/PublicApiV1StockTransfersShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |