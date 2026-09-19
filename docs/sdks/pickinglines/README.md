# DeliveryNotes.PickingLines

## Overview

### Available Operations

* [publicApiV1DeliveryNotesPickingLinesPick](#publicapiv1deliverynotespickinglinespick) - Mark a picking line

## publicApiV1DeliveryNotesPickingLinesPick

Record how much of one picking line has been picked, naming the delivery note and the line in the path. The quantity travels as a decimal string with four decimal places, so articles that are picked by weight travel whole, and it cannot exceed what the line asks for: more than that is rejected with 422 and nothing is recorded. Marking again OVERWRITES the value and marking zero undoes it, which is why this operation is not published as irreversible; the moment of the marking is kept even when the quantity goes back to zero. A picking line id that is valid but belongs to ANOTHER delivery note is rejected with 422 and touches nothing: the note in the path bounds the operation even though the line resolves on its own. And if the list is stale — the note was edited after the list was opened — the call is rejected with 422 and the message names the remedy: open the list again.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.picking_lines.pick" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/picking-lines/{picking_line}/pick" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1DeliveryNotesPickingLinesPickRequest(
    company: 'Schroeder Group',
    deliveryNote: '<value>',
    pickingLine: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\MarkPickingLineRequest(
        pickedQuantity: 9288.68,
    ),
);

$response = $sdk->deliveryNotes->pickingLines->publicApiV1DeliveryNotesPickingLinesPick(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.picking_lines.pick" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/picking-lines/{picking_line}/pick" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1DeliveryNotesPickingLinesPickRequest(
    company: 'Prohaska, Stracke and Turner',
    deliveryNote: '<value>',
    pickingLine: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\MarkPickingLineRequest(
        pickedQuantity: 9288.68,
    ),
);

$response = $sdk->deliveryNotes->pickingLines->publicApiV1DeliveryNotesPickingLinesPick(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.picking_lines.pick" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/picking-lines/{picking_line}/pick" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1DeliveryNotesPickingLinesPickRequest(
    company: 'Terry - Rowe',
    deliveryNote: '<value>',
    pickingLine: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\MarkPickingLineRequest(
        pickedQuantity: 9288.68,
    ),
);

$response = $sdk->deliveryNotes->pickingLines->publicApiV1DeliveryNotesPickingLinesPick(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                | Type                                                                                                                                     | Required                                                                                                                                 | Description                                                                                                                              |
| ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                               | [Operations\PublicApiV1DeliveryNotesPickingLinesPickRequest](../../Models/Operations/PublicApiV1DeliveryNotesPickingLinesPickRequest.md) | :heavy_check_mark:                                                                                                                       | The request object to use for the request.                                                                                               |

### Response

**[?Operations\PublicApiV1DeliveryNotesPickingLinesPickResponse](../../Models/Operations/PublicApiV1DeliveryNotesPickingLinesPickResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |