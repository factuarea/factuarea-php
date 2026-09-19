# DeliveryNotes.PickingList

## Overview

### Available Operations

* [publicApiV1DeliveryNotesPickingListPdf](#publicapiv1deliverynotespickinglistpdf) - Download the picking list PDF
* [publicApiV1DeliveryNotesPickingListOpen](#publicapiv1deliverynotespickinglistopen) - Open the picking list of a delivery note
* [publicApiV1DeliveryNotesPickingListShow](#publicapiv1deliverynotespickinglistshow) - Retrieve the picking list of a delivery note

## publicApiV1DeliveryNotesPickingListPdf

Download the PDF representation of a picking list. Returns the binary PDF stream (`application/pdf`). Pass `?download=1` for `Content-Disposition: attachment` (file download); otherwise it is served `inline`. It is the paper the warehouse walks with: one row per line to pick, ordered by the code of the location it was last taken from so the route through the warehouse is followed and not the order of the document, with the unlocated lines at the END and flagged as such. It carries a tick box per line and a signature block, and NO amounts at all — a picking list is not a commercial document and printing prices on it would put them in the hands of whoever walks the aisle.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.picking_list.pdf" method="get" path="/companies/{company}/delivery-notes/{delivery_note}/picking-list/pdf" -->
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

$request = new Operations\PublicApiV1DeliveryNotesPickingListPdfRequest(
    company: 'Kerluke - Ziemann',
    deliveryNote: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->deliveryNotes->pickingList->publicApiV1DeliveryNotesPickingListPdf(
    request: $request
);

if ($response->bytes !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                            | Type                                                                                                                                 | Required                                                                                                                             | Description                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                           | [Operations\PublicApiV1DeliveryNotesPickingListPdfRequest](../../Models/Operations/PublicApiV1DeliveryNotesPickingListPdfRequest.md) | :heavy_check_mark:                                                                                                                   | The request object to use for the request.                                                                                           |

### Response

**[?Operations\PublicApiV1DeliveryNotesPickingListPdfResponse](../../Models/Operations/PublicApiV1DeliveryNotesPickingListPdfResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1DeliveryNotesPickingListOpen

Open — or re-open — the picking list of a delivery note. It takes no body: the list is BUILT from the lines the note has at this moment, one picking line per line of the note, and it comes back COMPLETE with the public id of every line, so you can start marking without a second read. Opening it on a note that ALREADY has one replaces the previous list entirely and the marking recorded on it is lost: re-opening is the operation and not an edge case of it, and it is how a stale list is refreshed after the note has been edited. Nothing replaces the list on its own, not even when it is stale — it is always somebody asking for it. Opening does NOT move the fulfilment status: the physical axis advances only through its own transition operation. A cancelled delivery note does not enter fulfilment, so the call is rejected as a business rule violation with 422 — never 403 — and no list is created. Irreversible, because the previous list and the work recorded on it are not recoverable: it requires an `Idempotency-Key` when the policy of your credential demands it.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.picking_list.open" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/picking-list" -->
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

$request = new Operations\PublicApiV1DeliveryNotesPickingListOpenRequest(
    company: 'Buckridge, McLaughlin and Morissette',
    deliveryNote: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->deliveryNotes->pickingList->publicApiV1DeliveryNotesPickingListOpen(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                              | Type                                                                                                                                   | Required                                                                                                                               | Description                                                                                                                            |
| -------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                             | [Operations\PublicApiV1DeliveryNotesPickingListOpenRequest](../../Models/Operations/PublicApiV1DeliveryNotesPickingListOpenRequest.md) | :heavy_check_mark:                                                                                                                     | The request object to use for the request.                                                                                             |

### Response

**[?Operations\PublicApiV1DeliveryNotesPickingListOpenResponse](../../Models/Operations/PublicApiV1DeliveryNotesPickingListOpenResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1DeliveryNotesPickingListShow

Retrieve the picking list of a delivery note: its header — the note by its public id and its number, its fulfilment status with its label, the origin warehouse and the source sales order as opaque ids when it has them, how many lines it has and how many are still pending — and its lines. Each line carries its own public id, which is the one the marking operation takes, its position, the description and the article as they were WHEN THE LIST WAS OPENED, the quantity to pick, the quantity picked so far and the moment it was marked. A line that has never been marked carries NO picked quantity, which is not the same as a quantity of zero: zero is somebody recording that the goods were not there. The operator who marked it is not published. The header says whether the list is STALE: stale means the delivery note was edited after the list was opened, so the snapshot no longer describes the lines of the note — while it is stale both the marking and the step to prepared are rejected, and the way out is to open the list again. A note that never entered fulfilment has no list and answers as not found, and a note of another company answers exactly like one that does not exist.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.picking_list.show" method="get" path="/companies/{company}/delivery-notes/{delivery_note}/picking-list" -->
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



$response = $sdk->deliveryNotes->pickingList->publicApiV1DeliveryNotesPickingListShow(
    company: 'Shields, Murray and Stamm',
    deliveryNote: '<value>',
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
| `deliveryNote`                                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1DeliveryNotesPickingListShowResponse](../../Models/Operations/PublicApiV1DeliveryNotesPickingListShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |