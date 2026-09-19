# DeliveryNotes.FulfilmentStatus

## Overview

### Available Operations

* [publicApiV1DeliveryNotesFulfilmentStatusTransition](#publicapiv1deliverynotesfulfilmentstatustransition) - Transition the fulfilment status of a delivery note

## publicApiV1DeliveryNotesFulfilmentStatusTransition

Move a delivery note along its PHYSICAL axis, naming the target status in the body. It is ONE operation for the whole machine and not one route per step, and the state machine remains the only authority: a target that is not reachable from the current status is rejected with 422 and the message names the current status and the ones it does admit. The axis has seven statuses and ten allowed moves: pending to picking; picking back to pending or on to prepared; prepared back to picking or on to handed over to the carrier; handed over to in transit or to failed; in transit to delivered or to failed; and failed back to in transit, which is how a second delivery attempt is recorded. Delivered is TERMINAL — nothing moves a note out of it. The step to PREPARED is the only one with extra conditions, checked in this order: the note has to have a picking list open, the list must not be stale, it must not be empty, and every one of its lines has to be fully picked — an incomplete list is rejected with 422 and the answer says how many lines are still pending. The other six targets do not look at the picking list at all. When the target is the failed delivery the reason is required; on any other target it is not accepted. A cancelled delivery note does not advance on this axis either: that is the one rule that crosses the two axes. This axis is NOT the commercial status of the document: the `delivered` reached here means the goods arrived at the recipient, while the commercial `delivered` — published as `sent` — means the note was issued to the customer and is set by its own operation. Moving this axis never changes that commercial status, never adds a value to the status catalog of the document and, today, fires no webhook and no automation. Irreversible as an OPERATION and not per target: two of its targets cannot be undone — handing the goods to the carrier has an effect on a third party and the delivery to the recipient is terminal — so the whole operation requires an `Idempotency-Key` when the policy of your credential demands it, the reversible steps included. Over-marking costs you a header; under-marking would lose the guarantee exactly where it is needed.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.fulfilment_status.transition" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/fulfilment-status" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1DeliveryNotesFulfilmentStatusTransitionRequest(
    company: 'Kihn LLC',
    deliveryNote: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\TransitionFulfilmentStatusRequest(
        targetStatus: Components\TargetStatus::Picking,
    ),
);

$response = $sdk->deliveryNotes->fulfilmentStatus->publicApiV1DeliveryNotesFulfilmentStatusTransition(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: entrega_al_transportista

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.fulfilment_status.transition" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/fulfilment-status" example="entrega_al_transportista" -->
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

$request = new Operations\PublicApiV1DeliveryNotesFulfilmentStatusTransitionRequest(
    company: 'Kovacek, Emmerich and Bergnaum',
    deliveryNote: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\TransitionFulfilmentStatusRequest(
        targetStatus: Components\TargetStatus::HandedOver,
    ),
);

$response = $sdk->deliveryNotes->fulfilmentStatus->publicApiV1DeliveryNotesFulfilmentStatusTransition(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: entrega_fallida

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.fulfilment_status.transition" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/fulfilment-status" example="entrega_fallida" -->
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

$request = new Operations\PublicApiV1DeliveryNotesFulfilmentStatusTransitionRequest(
    company: 'Carter - Medhurst',
    deliveryNote: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\TransitionFulfilmentStatusRequest(
        targetStatus: Components\TargetStatus::Failed,
        failureReason: 'Nadie en el domicilio; se reintenta mañana.',
    ),
);

$response = $sdk->deliveryNotes->fulfilmentStatus->publicApiV1DeliveryNotesFulfilmentStatusTransition(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.fulfilment_status.transition" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/fulfilment-status" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1DeliveryNotesFulfilmentStatusTransitionRequest(
    company: 'Feil - Hansen',
    deliveryNote: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\TransitionFulfilmentStatusRequest(
        targetStatus: Components\TargetStatus::Picking,
    ),
);

$response = $sdk->deliveryNotes->fulfilmentStatus->publicApiV1DeliveryNotesFulfilmentStatusTransition(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.fulfilment_status.transition" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/fulfilment-status" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1DeliveryNotesFulfilmentStatusTransitionRequest(
    company: 'Wehner, Satterfield and Pfannerstill',
    deliveryNote: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\TransitionFulfilmentStatusRequest(
        targetStatus: Components\TargetStatus::Picking,
    ),
);

$response = $sdk->deliveryNotes->fulfilmentStatus->publicApiV1DeliveryNotesFulfilmentStatusTransition(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                    | Type                                                                                                                                                         | Required                                                                                                                                                     | Description                                                                                                                                                  |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                                                   | [Operations\PublicApiV1DeliveryNotesFulfilmentStatusTransitionRequest](../../Models/Operations/PublicApiV1DeliveryNotesFulfilmentStatusTransitionRequest.md) | :heavy_check_mark:                                                                                                                                           | The request object to use for the request.                                                                                                                   |

### Response

**[?Operations\PublicApiV1DeliveryNotesFulfilmentStatusTransitionResponse](../../Models/Operations/PublicApiV1DeliveryNotesFulfilmentStatusTransitionResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |