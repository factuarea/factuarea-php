# DeliveryNotes

## Overview

### Available Operations

* [publicApiV1DeliveryNotesAssignPresentation](#publicapiv1deliverynotesassignpresentation) - Assign the delivery-note visual template
* [publicApiV1DeliveryNotesGetPresentation](#publicapiv1deliverynotesgetpresentation) - Get the delivery-note presentation
* [publicApiV1DeliveryNotesBulkDelete](#publicapiv1deliverynotesbulkdelete) - Bulk delete delivery notes
* [publicApiV1DeliveryNotesBulkPdf](#publicapiv1deliverynotesbulkpdf) - Bulk download delivery note PDFs
* [publicApiV1DeliveryNotesBulkSend](#publicapiv1deliverynotesbulksend) - Bulk send delivery notes
* [publicApiV1DeliveryNotesBulkStatus](#publicapiv1deliverynotesbulkstatus) - Bulk change delivery note status
* [publicApiV1DeliveryNotesCancel](#publicapiv1deliverynotescancel) - Cancel a delivery note
* [publicApiV1DeliveryNotesCreate](#publicapiv1deliverynotescreate) - Create a delivery note
* [publicApiV1DeliveryNotesList](#publicapiv1deliverynoteslist) - List all delivery notes
* [publicApiV1DeliveryNotesDelete](#publicapiv1deliverynotesdelete) - Delete a delivery note
* [publicApiV1DeliveryNotesShow](#publicapiv1deliverynotesshow) - Retrieve a delivery note
* [publicApiV1DeliveryNotesUpdate](#publicapiv1deliverynotesupdate) - Update a delivery note
* [publicApiV1DeliveryNotesPdf](#publicapiv1deliverynotespdf) - Download delivery note PDF
* [publicApiV1DeliveryNotesDuplicate](#publicapiv1deliverynotesduplicate) - Duplicate a delivery note
* [publicApiV1DeliveryNotesFindByExternalId](#publicapiv1deliverynotesfindbyexternalid) - Find a delivery note by external ID
* [publicApiV1DeliveryNotesStats](#publicapiv1deliverynotesstats) - Retrieve delivery note stats
* [publicApiV1DeliveryNotesStatuses](#publicapiv1deliverynotesstatuses) - List delivery note statuses
* [publicApiV1DeliveryNotesListPresentations](#publicapiv1deliverynoteslistpresentations) - List retained delivery-note presentations
* [publicApiV1DeliveryNotesMarkDelivered](#publicapiv1deliverynotesmarkdelivered) - Mark delivery note as delivered
* [publicApiV1DeliveryNotesRevisePresentation](#publicapiv1deliverynotesrevisepresentation) - Create a delivery-note presentation revision
* [publicApiV1DeliveryNotesSend](#publicapiv1deliverynotessend) - Send a delivery note
* [publicApiV1DeliveryNotesSign](#publicapiv1deliverynotessign) - Sign a delivery note

## publicApiV1DeliveryNotesAssignPresentation

Set an explicit tenant template or null inheritance without changing numbering, totals, references, payment terms or the existing capture. Supply the visual selection version and/or the strong validator from this exact presentation URI. A successful replay preserves the original result; current actor authority is still required.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.assign_presentation" method="patch" path="/companies/{company}/delivery-notes/{delivery_note}/presentation" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1DeliveryNotesAssignPresentationRequest(
    company: '21eb9eff-9ccd-42f1-910b-00992b54ae12',
    deliveryNote: '98a4524d-a310-473b-af7e-c5fa5f978140',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AssignDocumentPresentationRequest(
        templateId: null,
        expectedVersion: 1,
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesAssignPresentation(
    request: $request
);

if ($response->documentPresentationStateEnvelope !== null) {
    // handle response
}
```
### Example Usage: inherit

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.assign_presentation" method="patch" path="/companies/{company}/delivery-notes/{delivery_note}/presentation" example="inherit" -->
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

$request = new Operations\PublicApiV1DeliveryNotesAssignPresentationRequest(
    company: '65e1dedd-a33e-46a9-a701-a195c1f46077',
    deliveryNote: '38612707-d9b6-4741-b085-07bd49cd8724',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AssignDocumentPresentationRequest(
        templateId: null,
        expectedVersion: 1,
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesAssignPresentation(
    request: $request
);

if ($response->documentPresentationStateEnvelope !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.assign_presentation" method="patch" path="/companies/{company}/delivery-notes/{delivery_note}/presentation" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1DeliveryNotesAssignPresentationRequest(
    company: 'd6536f67-10a7-476d-a6a1-0cbb86117ee5',
    deliveryNote: 'de3232b8-2b17-4a7d-8c0a-66f670d31fa4',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AssignDocumentPresentationRequest(
        templateId: null,
        expectedVersion: 1,
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesAssignPresentation(
    request: $request
);

if ($response->documentPresentationStateEnvelope !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.assign_presentation" method="patch" path="/companies/{company}/delivery-notes/{delivery_note}/presentation" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1DeliveryNotesAssignPresentationRequest(
    company: 'cfbb7759-95e7-4264-8cc1-b3c60d852ffa',
    deliveryNote: 'b2513b40-78eb-49d7-9dc1-b0460e39a4bf',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AssignDocumentPresentationRequest(
        templateId: null,
        expectedVersion: 1,
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesAssignPresentation(
    request: $request
);

if ($response->documentPresentationStateEnvelope !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                    | Type                                                                                                                                         | Required                                                                                                                                     | Description                                                                                                                                  |
| -------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                   | [Operations\PublicApiV1DeliveryNotesAssignPresentationRequest](../../Models/Operations/PublicApiV1DeliveryNotesAssignPresentationRequest.md) | :heavy_check_mark:                                                                                                                           | The request object to use for the request.                                                                                                   |

### Response

**[?Operations\PublicApiV1DeliveryNotesAssignPresentationResponse](../../Models/Operations/PublicApiV1DeliveryNotesAssignPresentationResponse.md)**

### Errors

| Error Type                                  | Status Code                                 | Content Type                                |
| ------------------------------------------- | ------------------------------------------- | ------------------------------------------- |
| Errors\Error                                | 400, 401, 403, 404, 409, 412, 422, 428, 429 | application/json                            |
| Errors\Error                                | 500                                         | application/json                            |
| Errors\APIException                         | 4XX, 5XX                                    | \*/\*                                       |

## publicApiV1DeliveryNotesGetPresentation

Inspect the current visual selection and retained capture independently. Returns a virtual state before the first visual action. Reads never initialize templates, capture, publish, render or refresh legal content.

### Example Usage: privacy_erased

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.get_presentation" method="get" path="/companies/{company}/delivery-notes/{delivery_note}/presentation" example="privacy_erased" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesGetPresentation(
    company: '983937e3-f8a6-43d2-babd-3fcf38ce7f31',
    deliveryNote: 'f7d56080-a3ff-4b3b-bcb7-67ea03659b12',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->documentPresentationStateEnvelope !== null) {
    // handle response
}
```
### Example Usage: retained_capture

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.get_presentation" method="get" path="/companies/{company}/delivery-notes/{delivery_note}/presentation" example="retained_capture" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesGetPresentation(
    company: '712fd34c-8fa4-4475-bbf7-b60e38f4ce33',
    deliveryNote: '60db4a86-e4d6-46de-a2ed-8926f2efafb5',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->documentPresentationStateEnvelope !== null) {
    // handle response
}
```
### Example Usage: series_candidate_requires_confirmation

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.get_presentation" method="get" path="/companies/{company}/delivery-notes/{delivery_note}/presentation" example="series_candidate_requires_confirmation" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesGetPresentation(
    company: 'a43ddde1-7bce-43ae-b8d3-fbf61e7e275e',
    deliveryNote: '918858f8-13e3-4e1e-9bb8-c1bce837f9ee',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->documentPresentationStateEnvelope !== null) {
    // handle response
}
```
### Example Usage: virtual_legacy_head

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.get_presentation" method="get" path="/companies/{company}/delivery-notes/{delivery_note}/presentation" example="virtual_legacy_head" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesGetPresentation(
    company: '6c4b5c9d-b827-485b-b63b-f81d31d9477f',
    deliveryNote: 'd085fafc-d773-4e12-9b27-47a621de09dd',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->documentPresentationStateEnvelope !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `deliveryNote`                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the delivery note.                                                                                                                                                                                                                                                                                                                            |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1DeliveryNotesGetPresentationResponse](../../Models/Operations/PublicApiV1DeliveryNotesGetPresentationResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1DeliveryNotesBulkDelete

Delete several delivery notes in one request: `ids` array of UUID v7.
- Returns a `BulkPartialSuccessResult`; `failures` list the notes not deleted (e.g. signed or invoiced).
- Supports `Idempotency-Key`.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_delete" method="post" path="/companies/{company}/delivery-notes/bulk-delete" example="api_key_revoked" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkDeleteDeliveryNotesRequest(
    ids: [
        'd3f2cfbd-3240-4768-ae58-08b4fbba9f17',
        '6f21babd-33d3-4ef2-8929-11464c5520a7',
    ],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkDelete(
    company: 'bd3f2cfb-d324-4076-98e5-808b4fbba9f1',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_delete" method="post" path="/companies/{company}/delivery-notes/bulk-delete" example="factuarea_version_invalid_format" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkDeleteDeliveryNotesRequest(
    ids: [
        'd3f2cfbd-3240-4768-ae58-08b4fbba9f17',
        '6f21babd-33d3-4ef2-8929-11464c5520a7',
    ],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkDelete(
    company: '96f21bab-d33d-43ef-9292-911464c5520a',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_delete" method="post" path="/companies/{company}/delivery-notes/bulk-delete" example="invalid_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkDeleteDeliveryNotesRequest(
    ids: [
        'd3f2cfbd-3240-4768-ae58-08b4fbba9f17',
        '6f21babd-33d3-4ef2-8929-11464c5520a7',
    ],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkDelete(
    company: '96f21bab-d33d-43ef-9292-911464c5520a',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_delete" method="post" path="/companies/{company}/delivery-notes/bulk-delete" example="missing_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkDeleteDeliveryNotesRequest(
    ids: [
        'd3f2cfbd-3240-4768-ae58-08b4fbba9f17',
        '6f21babd-33d3-4ef2-8929-11464c5520a7',
    ],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkDelete(
    company: 'Sporer, Douglas and Collins',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_delete" method="post" path="/companies/{company}/delivery-notes/bulk-delete" example="retired_active_profile_header" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkDeleteDeliveryNotesRequest(
    ids: [
        'd3f2cfbd-3240-4768-ae58-08b4fbba9f17',
        '6f21babd-33d3-4ef2-8929-11464c5520a7',
    ],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkDelete(
    company: 'bd3f2cfb-d324-4076-98e5-808b4fbba9f1',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_delete" method="post" path="/companies/{company}/delivery-notes/bulk-delete" example="success" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkDeleteDeliveryNotesRequest(
    ids: [
        'd3f2cfbd-3240-4768-ae58-08b4fbba9f17',
        '6f21babd-33d3-4ef2-8929-11464c5520a7',
    ],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkDelete(
    company: 'Ruecker - West',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_delete" method="post" path="/companies/{company}/delivery-notes/bulk-delete" example="unsupported_api_version" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkDeleteDeliveryNotesRequest(
    ids: [
        'd3f2cfbd-3240-4768-ae58-08b4fbba9f17',
        '6f21babd-33d3-4ef2-8929-11464c5520a7',
    ],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkDelete(
    company: '318e1ae8-8138-43d5-8459-bff45bbeb951',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Components\BulkDeleteDeliveryNotesRequest](../../Models/Components/BulkDeleteDeliveryNotesRequest.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1DeliveryNotesBulkDeleteResponse](../../Models/Operations/PublicApiV1DeliveryNotesBulkDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1DeliveryNotesBulkPdf

Packages the PDFs of up to 50 delivery notes (by id) into a single ZIP. Ids that are not found or have no generable PDF do not abort the request: the ZIP carries only the valid ones and the per-resource counts travel in the `X-Bulk-*` response headers.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_pdf" method="post" path="/companies/{company}/delivery-notes/bulk-pdf" example="api_key_revoked" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkPdfDeliveryNotesV1Request(
    ids: [],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkPdf(
    company: '1149b34d-c7ab-436d-b4e2-b35e7a026916',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->bytes !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_pdf" method="post" path="/companies/{company}/delivery-notes/bulk-pdf" example="factuarea_version_invalid_format" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkPdfDeliveryNotesV1Request(
    ids: [],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkPdf(
    company: 'aa7b5fc9-bda3-4150-8aa6-f066d5ec5f1d',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->bytes !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_pdf" method="post" path="/companies/{company}/delivery-notes/bulk-pdf" example="invalid_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkPdfDeliveryNotesV1Request(
    ids: [],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkPdf(
    company: 'faa7b5fc-9bda-4315-b0aa-6f066d5ec5f1',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->bytes !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_pdf" method="post" path="/companies/{company}/delivery-notes/bulk-pdf" example="missing_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkPdfDeliveryNotesV1Request(
    ids: [],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkPdf(
    company: 'Breitenberg - Greenfelder',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->bytes !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_pdf" method="post" path="/companies/{company}/delivery-notes/bulk-pdf" example="retired_active_profile_header" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkPdfDeliveryNotesV1Request(
    ids: [],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkPdf(
    company: '149b34dc-7ab3-46d4-be2b-35e7a026916d',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->bytes !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_pdf" method="post" path="/companies/{company}/delivery-notes/bulk-pdf" example="unsupported_api_version" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkPdfDeliveryNotesV1Request(
    ids: [],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkPdf(
    company: 'b5e138c1-719a-4516-a33a-9eb61c5ff8fe',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->bytes !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Components\BulkPdfDeliveryNotesV1Request](../../Models/Components/BulkPdfDeliveryNotesV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1DeliveryNotesBulkPdfResponse](../../Models/Operations/PublicApiV1DeliveryNotesBulkPdfResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 413, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1DeliveryNotesBulkSend

Sends up to 200 delivery notes by email (queued) in one call, reusing the single-send path per id. Returns a `BulkPartialSuccessResult` with `total`, `successful` and `failed` counts plus a `failures` list (`id` + `error_code` + Spanish `error_message`) for each delivery note that could not be sent (not found, non-sendable status or no resolvable recipient).

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_send" method="post" path="/companies/{company}/delivery-notes/bulk-send" example="api_key_revoked" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkSendDeliveryNotesV1Request(
    ids: [],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkSend(
    company: '163aea31-437b-49b9-aaf4-3d266469ca73',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_send" method="post" path="/companies/{company}/delivery-notes/bulk-send" example="factuarea_version_invalid_format" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkSendDeliveryNotesV1Request(
    ids: [],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkSend(
    company: '6490f1e2-fc98-49cf-913b-0198b3b3f51d',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_send" method="post" path="/companies/{company}/delivery-notes/bulk-send" example="invalid_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkSendDeliveryNotesV1Request(
    ids: [],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkSend(
    company: '4340a7f0-e935-48a9-8307-bd1c1d05e9ad',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_send" method="post" path="/companies/{company}/delivery-notes/bulk-send" example="missing_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkSendDeliveryNotesV1Request(
    ids: [],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkSend(
    company: 'Waters LLC',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: pdf_engine_unreachable_row

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_send" method="post" path="/companies/{company}/delivery-notes/bulk-send" example="pdf_engine_unreachable_row" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkSendDeliveryNotesV1Request(
    ids: [],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkSend(
    company: '163aea31-437b-49b9-aaf4-3d266469ca73',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_send" method="post" path="/companies/{company}/delivery-notes/bulk-send" example="retired_active_profile_header" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkSendDeliveryNotesV1Request(
    ids: [],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkSend(
    company: '4340a7f0-e935-48a9-8307-bd1c1d05e9ad',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_send" method="post" path="/companies/{company}/delivery-notes/bulk-send" example="success" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkSendDeliveryNotesV1Request(
    ids: [],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkSend(
    company: 'Jones - Fadel',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_send" method="post" path="/companies/{company}/delivery-notes/bulk-send" example="unsupported_api_version" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkSendDeliveryNotesV1Request(
    ids: [],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkSend(
    company: '63746ac1-f823-47f8-ba0c-90294fd4e78c',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Components\BulkSendDeliveryNotesV1Request](../../Models/Components/BulkSendDeliveryNotesV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Bulk delivery-note send accepts an optional UUID-keyed map of complete seven-field presentation preconditions. The presentation precondition fields only take effect when revisioned presentation is enabled for the company; otherwise they are ignored.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1DeliveryNotesBulkSendResponse](../../Models/Operations/PublicApiV1DeliveryNotesBulkSendResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1DeliveryNotesBulkStatus

Transition up to 50 delivery notes (by id) to a status from the closed set `[delivered, cancelled]`, each through the document state guard. Returns a `BulkPartialSuccessResult`; delivery notes whose transition is rejected (not found or not transitionable) come back in `failures[]`.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_status" method="post" path="/companies/{company}/delivery-notes/bulk-status" example="api_key_revoked" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkStatusDeliveryNotesV1Request(
    ids: [
        '99447643-c754-4c61-8d72-7228ac7d5729',
    ],
    newStatus: Components\BulkStatusDeliveryNotesV1RequestNewStatus::Delivered,
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkStatus(
    company: '15994476-43c7-454c-861d-727228ac7d57',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_status" method="post" path="/companies/{company}/delivery-notes/bulk-status" example="factuarea_version_invalid_format" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkStatusDeliveryNotesV1Request(
    ids: [],
    newStatus: Components\BulkStatusDeliveryNotesV1RequestNewStatus::Delivered,
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkStatus(
    company: '3f4cfa7b-0fc8-4305-a59f-0b853a3fc02b',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_status" method="post" path="/companies/{company}/delivery-notes/bulk-status" example="invalid_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkStatusDeliveryNotesV1Request(
    ids: [
        '99447643-c754-4c61-8d72-7228ac7d5729',
    ],
    newStatus: Components\BulkStatusDeliveryNotesV1RequestNewStatus::Delivered,
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkStatus(
    company: '91a622f3-0e0b-412c-8a65-3e2e6e0a5d84',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_status" method="post" path="/companies/{company}/delivery-notes/bulk-status" example="missing_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkStatusDeliveryNotesV1Request(
    ids: [],
    newStatus: Components\BulkStatusDeliveryNotesV1RequestNewStatus::Delivered,
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkStatus(
    company: 'Gorczany Inc',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: pdf_engine_unreachable_row

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_status" method="post" path="/companies/{company}/delivery-notes/bulk-status" example="pdf_engine_unreachable_row" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkStatusDeliveryNotesV1Request(
    ids: [],
    newStatus: Components\BulkStatusDeliveryNotesV1RequestNewStatus::Delivered,
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkStatus(
    company: '15994476-43c7-454c-861d-727228ac7d57',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_status" method="post" path="/companies/{company}/delivery-notes/bulk-status" example="retired_active_profile_header" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkStatusDeliveryNotesV1Request(
    ids: [],
    newStatus: Components\BulkStatusDeliveryNotesV1RequestNewStatus::Delivered,
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkStatus(
    company: '91a622f3-0e0b-412c-8a65-3e2e6e0a5d84',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_status" method="post" path="/companies/{company}/delivery-notes/bulk-status" example="success" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkStatusDeliveryNotesV1Request(
    ids: [],
    newStatus: Components\BulkStatusDeliveryNotesV1RequestNewStatus::Delivered,
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkStatus(
    company: 'Heathcote - Metz',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.bulk_status" method="post" path="/companies/{company}/delivery-notes/bulk-status" example="unsupported_api_version" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\BulkStatusDeliveryNotesV1Request(
    ids: [],
    newStatus: Components\BulkStatusDeliveryNotesV1RequestNewStatus::Delivered,
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesBulkStatus(
    company: '33a44475-29c9-4d4c-84a6-3be926ba90d3',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Components\BulkStatusDeliveryNotesV1Request](../../Models/Components/BulkStatusDeliveryNotesV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Bulk delivery-note status changes accept an optional UUID-keyed map of complete seven-field presentation preconditions. The presentation precondition fields only take effect when revisioned presentation is enabled for the company; otherwise they are ignored.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1DeliveryNotesBulkStatusResponse](../../Models/Operations/PublicApiV1DeliveryNotesBulkStatusResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1DeliveryNotesCancel

Transition a delivery note to the `cancelled` state. Canonical REST replacement for the deprecated `POST /change_status`. Returns 409 `invalid_status_transition` if the note cannot be cancelled (e.g. already invoiced). Supports `Idempotency-Key` for safe retries.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.cancel" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/cancel" example="success" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesCancel(
    company: 'Sipes LLC',
    deliveryNote: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `deliveryNote`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the delivery note.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1DeliveryNotesCancelResponse](../../Models/Operations/PublicApiV1DeliveryNotesCancelResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1DeliveryNotesCreate

Create a new delivery note (albarán) in `draft` status. Delivery notes track goods shipped to a customer and can later be converted to invoices.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.create" method="post" path="/companies/{company}/delivery-notes" example="api_key_revoked" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\CreateDeliveryNoteRequest(
    clientId: 'bca0e7ad-9fd1-4c6e-a9ea-e5c51d7f9743',
    metadata: [
        'erp_code' => 'IVA-GEN',
        'ledger_account' => '477000',
    ],
    lines: [],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesCreate(
    company: 'bca0e7ad-9fd1-4c6e-a9ea-e5c51d7f9743',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: delivery_note_es

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.create" method="post" path="/companies/{company}/delivery-notes" example="delivery_note_es" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\CreateDeliveryNoteRequest(
    clientId: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a01',
    seriesId: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a02',
    deliveryDate: LocalDate::parse('2026-01-21'),
    notes: 'Entregar en horario de mañana.',
    carrierCompany: 'SEUR',
    lines: [
        new Components\CreateDeliveryNoteRequestLine(
            description: 'Portátil 14" i7',
            quantity: 2,
            unitPrice: 400,
            taxRate: 21,
        ),
    ],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesCreate(
    company: 'Rowe, O\'Reilly and Beahan',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.create" method="post" path="/companies/{company}/delivery-notes" example="factuarea_version_invalid_format" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\CreateDeliveryNoteRequest(
    clientId: 'eae5c51d-7f97-4439-a6a0-93961580e35c',
    metadata: [
        'erp_code' => 'IVA-GEN',
        'ledger_account' => '477000',
    ],
    lines: [],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesCreate(
    company: 'd9740e09-c157-4844-b580-5ca23f0ce7c1',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.create" method="post" path="/companies/{company}/delivery-notes" example="invalid_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\CreateDeliveryNoteRequest(
    clientId: 'bca0e7ad-9fd1-4c6e-a9ea-e5c51d7f9743',
    metadata: [
        'erp_code' => 'IVA-GEN',
        'ledger_account' => '477000',
    ],
    lines: [],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesCreate(
    company: '6a093961-580e-435c-8b7e-f78e03e87122',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.create" method="post" path="/companies/{company}/delivery-notes" example="missing_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\CreateDeliveryNoteRequest(
    clientId: 'eae5c51d-7f97-4439-a6a0-93961580e35c',
    metadata: [
        'erp_code' => 'IVA-GEN',
        'ledger_account' => '477000',
    ],
    lines: [],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesCreate(
    company: 'Wyman and Sons',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.create" method="post" path="/companies/{company}/delivery-notes" example="retired_active_profile_header" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\CreateDeliveryNoteRequest(
    clientId: 'eae5c51d-7f97-4439-a6a0-93961580e35c',
    metadata: [
        'erp_code' => 'IVA-GEN',
        'ledger_account' => '477000',
    ],
    lines: [],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesCreate(
    company: '4ecc00ef-d73f-433d-aedf-2a5f29b27aa2',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.create" method="post" path="/companies/{company}/delivery-notes" example="success" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\CreateDeliveryNoteRequest(
    clientId: 'eae5c51d-7f97-4439-a6a0-93961580e35c',
    metadata: [
        'erp_code' => 'IVA-GEN',
        'ledger_account' => '477000',
    ],
    lines: [],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesCreate(
    company: 'Kshlerin, O\'Conner and Simonis',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.create" method="post" path="/companies/{company}/delivery-notes" example="unsupported_api_version" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\CreateDeliveryNoteRequest(
    clientId: 'eae5c51d-7f97-4439-a6a0-93961580e35c',
    metadata: [
        'erp_code' => 'IVA-GEN',
        'ledger_account' => '477000',
    ],
    lines: [],
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesCreate(
    company: '29f07a40-5295-42fe-8b32-516f18bac8e7',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                   |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [Components\CreateDeliveryNoteRequest](../../Models/Components/CreateDeliveryNoteRequest.md)                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                               | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |

### Response

**[?Operations\PublicApiV1DeliveryNotesCreateResponse](../../Models/Operations/PublicApiV1DeliveryNotesCreateResponse.md)**

### Errors

| Error Type                             | Status Code                            | Content Type                           |
| -------------------------------------- | -------------------------------------- | -------------------------------------- |
| Errors\Error                           | 400, 401, 402, 403, 404, 409, 422, 429 | application/json                       |
| Errors\Error                           | 500                                    | application/json                       |
| Errors\APIException                    | 4XX, 5XX                               | \*/\*                                  |

## publicApiV1DeliveryNotesList

List your delivery notes with cursor-based pagination.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.list" method="get" path="/companies/{company}/delivery-notes" example="success" -->
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

$request = new Operations\PublicApiV1DeliveryNotesListRequest(
    company: 'Muller Group',
    sort: Operations\PublicApiV1DeliveryNotesListSort::MinusCreated,
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                        | Type                                                                                                             | Required                                                                                                         | Description                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                       | [Operations\PublicApiV1DeliveryNotesListRequest](../../Models/Operations/PublicApiV1DeliveryNotesListRequest.md) | :heavy_check_mark:                                                                                               | The request object to use for the request.                                                                       |

### Response

**[?Operations\PublicApiV1DeliveryNotesListResponse](../../Models/Operations/PublicApiV1DeliveryNotesListResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1DeliveryNotesDelete

Delete a delivery note. Only `draft` notes without an assigned number can be deleted; any other state returns 409 `invalid_status_transition`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.delete" method="delete" path="/companies/{company}/delivery-notes/{delivery_note}" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesDelete(
    company: 'Friesen LLC',
    deliveryNote: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->statusCode === 200) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `deliveryNote`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the delivery note.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1DeliveryNotesDeleteResponse](../../Models/Operations/PublicApiV1DeliveryNotesDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1DeliveryNotesShow

Retrieve a delivery note by its `id`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.show" method="get" path="/companies/{company}/delivery-notes/{delivery_note}" example="success" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesShow(
    company: 'Ritchie LLC',
    deliveryNote: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `deliveryNote`                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the delivery note.                                                                                                                                                                                                                                                                                                                            |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1DeliveryNotesShowResponse](../../Models/Operations/PublicApiV1DeliveryNotesShowResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1DeliveryNotesUpdate

Update a draft delivery note. Once signed or invoiced, the delivery note becomes immutable.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.update" method="patch" path="/companies/{company}/delivery-notes/{delivery_note}" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1DeliveryNotesUpdateRequest(
    company: 'f638e2d4-fa5e-46f5-ab70-80d528f50661',
    deliveryNote: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateDeliveryNoteRequest(
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.update" method="patch" path="/companies/{company}/delivery-notes/{delivery_note}" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1DeliveryNotesUpdateRequest(
    company: 'a311df16-b380-4f26-9f18-d5581b50de3b',
    deliveryNote: '4521f6f4-a1e2-42d6-bc31-a1a5274634d5',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateDeliveryNoteRequest(
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.update" method="patch" path="/companies/{company}/delivery-notes/{delivery_note}" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1DeliveryNotesUpdateRequest(
    company: '2345d891-807a-4f6b-b0c1-d83d47acbcee',
    deliveryNote: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateDeliveryNoteRequest(
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.update" method="patch" path="/companies/{company}/delivery-notes/{delivery_note}" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1DeliveryNotesUpdateRequest(
    company: 'O\'Reilly, Heaney and Waters',
    deliveryNote: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateDeliveryNoteRequest(
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.update" method="patch" path="/companies/{company}/delivery-notes/{delivery_note}" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1DeliveryNotesUpdateRequest(
    company: 'c732767c-dd85-47e8-8ee6-5d6a3c781537',
    deliveryNote: 'b859596c-bd1f-488c-b642-a822a255c4f6',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateDeliveryNoteRequest(
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.update" method="patch" path="/companies/{company}/delivery-notes/{delivery_note}" example="success" -->
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

$request = new Operations\PublicApiV1DeliveryNotesUpdateRequest(
    company: 'Conn, Smith and Fritsch',
    deliveryNote: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateDeliveryNoteRequest(
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.update" method="patch" path="/companies/{company}/delivery-notes/{delivery_note}" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1DeliveryNotesUpdateRequest(
    company: '3f1e72e7-aadb-455e-a7c9-9a6f8eccd4cf',
    deliveryNote: 'd217634f-4f5f-440d-aa51-6c11e859a692',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateDeliveryNoteRequest(
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                            | Type                                                                                                                 | Required                                                                                                             | Description                                                                                                          |
| -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                           | [Operations\PublicApiV1DeliveryNotesUpdateRequest](../../Models/Operations/PublicApiV1DeliveryNotesUpdateRequest.md) | :heavy_check_mark:                                                                                                   | The request object to use for the request.                                                                           |

### Response

**[?Operations\PublicApiV1DeliveryNotesUpdateResponse](../../Models/Operations/PublicApiV1DeliveryNotesUpdateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1DeliveryNotesPdf

Download the delivery note PDF as a binary stream (`application/pdf`); `?download=1` serves it as an attachment.
- The `ETag` revalidates with `If-None-Match` (304 when unchanged).
- With revisioned presentation, a draft is served as a draft PDF; a document that left draft without a retained presentation captures its original on the first download, and a captured one serves that capture.

### Example Usage: failed_render_claimed_again

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.pdf" method="get" path="/companies/{company}/delivery-notes/{delivery_note}/pdf" example="failed_render_claimed_again" -->
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

$request = new Operations\PublicApiV1DeliveryNotesPdfRequest(
    company: '16d59d68-fc67-441d-8429-c676c9eb3202',
    deliveryNote: 'b3cd4a3f-43cf-4f15-91ad-2cc3a51e6aea',
    ifNoneMatch: '"5d41402abc4b2a76b9719d911017c592"',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesPdf(
    request: $request
);

if ($response->bytes !== null) {
    // handle response
}
```
### Example Usage: pending

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.pdf" method="get" path="/companies/{company}/delivery-notes/{delivery_note}/pdf" example="pending" -->
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

$request = new Operations\PublicApiV1DeliveryNotesPdfRequest(
    company: 'd15a2eb8-22bc-474c-a7bb-2a2419416c99',
    deliveryNote: '9a5118e6-a2ac-4a9d-9821-2e08016eb0d7',
    ifNoneMatch: '"5d41402abc4b2a76b9719d911017c592"',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesPdf(
    request: $request
);

if ($response->bytes !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                      | Type                                                                                                           | Required                                                                                                       | Description                                                                                                    |
| -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                     | [Operations\PublicApiV1DeliveryNotesPdfRequest](../../Models/Operations/PublicApiV1DeliveryNotesPdfRequest.md) | :heavy_check_mark:                                                                                             | The request object to use for the request.                                                                     |

### Response

**[?Operations\PublicApiV1DeliveryNotesPdfResponse](../../Models/Operations/PublicApiV1DeliveryNotesPdfResponse.md)**

### Errors

| Error Type                             | Status Code                            | Content Type                           |
| -------------------------------------- | -------------------------------------- | -------------------------------------- |
| Errors\Error                           | 400, 401, 403, 404, 409, 410, 422, 429 | application/json                       |
| Errors\Error                           | 500, 503                               | application/json                       |
| Errors\APIException                    | 4XX, 5XX                               | \*/\*                                  |

## publicApiV1DeliveryNotesDuplicate

Create a new draft delivery note by copying lines, client, and metadata.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.duplicate" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/duplicate" example="success" -->
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

$request = new Operations\PublicApiV1DeliveryNotesDuplicateRequest(
    company: 'Haag Inc',
    deliveryNote: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesDuplicate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1DeliveryNotesDuplicateRequest](../../Models/Operations/PublicApiV1DeliveryNotesDuplicateRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1DeliveryNotesDuplicateResponse](../../Models/Operations/PublicApiV1DeliveryNotesDuplicateResponse.md)**

### Errors

| Error Type                             | Status Code                            | Content Type                           |
| -------------------------------------- | -------------------------------------- | -------------------------------------- |
| Errors\Error                           | 400, 401, 402, 403, 404, 409, 422, 429 | application/json                       |
| Errors\Error                           | 500                                    | application/json                       |
| Errors\APIException                    | 4XX, 5XX                               | \*/\*                                  |

## publicApiV1DeliveryNotesFindByExternalId

Look up a single delivery note by its `external_id` (sent in the JSON body), the integration key that maps it to a record in a third-party system (ERP/CRM/e-commerce). Returns the matching delivery note or 404 `delivery_note_not_found` if no delivery note uses that external_id within your company.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.find_by_external_id" method="post" path="/companies/{company}/delivery-notes/find-by-external-id" example="api_key_revoked" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\FindDeliveryNoteByExternalIdRequest(
    externalId: '<id>',
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesFindByExternalId(
    company: 'df658e52-9038-4bea-9ebd-116f1728ab24',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.find_by_external_id" method="post" path="/companies/{company}/delivery-notes/find-by-external-id" example="factuarea_version_invalid_format" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\FindDeliveryNoteByExternalIdRequest(
    externalId: '<id>',
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesFindByExternalId(
    company: '9a4bf498-7423-490b-84af-2ac5da2ca1e2',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.find_by_external_id" method="post" path="/companies/{company}/delivery-notes/find-by-external-id" example="invalid_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\FindDeliveryNoteByExternalIdRequest(
    externalId: '<id>',
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesFindByExternalId(
    company: '9a4bf498-7423-490b-84af-2ac5da2ca1e2',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.find_by_external_id" method="post" path="/companies/{company}/delivery-notes/find-by-external-id" example="missing_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\FindDeliveryNoteByExternalIdRequest(
    externalId: '<id>',
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesFindByExternalId(
    company: 'Torp Inc',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.find_by_external_id" method="post" path="/companies/{company}/delivery-notes/find-by-external-id" example="retired_active_profile_header" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\FindDeliveryNoteByExternalIdRequest(
    externalId: '<id>',
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesFindByExternalId(
    company: 'df658e52-9038-4bea-9ebd-116f1728ab24',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.find_by_external_id" method="post" path="/companies/{company}/delivery-notes/find-by-external-id" example="success" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\FindDeliveryNoteByExternalIdRequest(
    externalId: '<id>',
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesFindByExternalId(
    company: 'Wehner, Howe and Hills',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.find_by_external_id" method="post" path="/companies/{company}/delivery-notes/find-by-external-id" example="unsupported_api_version" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\FindDeliveryNoteByExternalIdRequest(
    externalId: '<id>',
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesFindByExternalId(
    company: '5ebd179d-5048-4646-87d0-ef4ccc88c036',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `body`                                                                                                                                                                                                                                                                                                                                                                       | [Components\FindDeliveryNoteByExternalIdRequest](../../Models/Components/FindDeliveryNoteByExternalIdRequest.md)                                                                                                                                                                                                                                                             | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | N/A                                                                                                                                                                                                                                                                                                                                                                          |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1DeliveryNotesFindByExternalIdResponse](../../Models/Operations/PublicApiV1DeliveryNotesFindByExternalIdResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1DeliveryNotesStats

Return aggregated KPIs for your delivery notes: total count, accumulated amount, per-status breakdown, count pending signature, and count converted to invoice this month.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.stats" method="get" path="/companies/{company}/delivery-notes/stats" example="success" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesStats(
    company: 'Ryan - Prosacco',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1DeliveryNotesStatsResponse](../../Models/Operations/PublicApiV1DeliveryNotesStatsResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1DeliveryNotesStatuses

List the closed catalog of delivery note statuses (`draft`, `delivered`, `invoiced`, `cancelled`) with their public labels and colors. Use it to populate filters or status pickers instead of hard-coding values. The response `data` is an array of `{ value, label, color }` items.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.statuses" method="get" path="/companies/{company}/delivery-notes/statuses" example="success" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesStatuses(
    company: 'Fritsch - Sporer',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1DeliveryNotesStatusesResponse](../../Models/Operations/PublicApiV1DeliveryNotesStatusesResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1DeliveryNotesListPresentations

Return page-based capture history in descending number. Each summary retains its own purpose, format and availability; capture metadata does not prove prepared PDF bytes. Privacy-erased rows remain metadata tombstones.

### Example Usage: privacy_erased_history

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.list_presentations" method="get" path="/companies/{company}/delivery-notes/{delivery_note}/presentation-history" example="privacy_erased_history" -->
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

$request = new Operations\PublicApiV1DeliveryNotesListPresentationsRequest(
    company: 'fb72943c-54ee-4556-baa2-bd897535db73',
    deliveryNote: 'c0dec5e4-0871-4e94-a85d-75886dcfe460',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesListPresentations(
    request: $request
);

if ($response->documentPresentationHistoryEnvelope !== null) {
    // handle response
}
```
### Example Usage: retained_history

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.list_presentations" method="get" path="/companies/{company}/delivery-notes/{delivery_note}/presentation-history" example="retained_history" -->
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

$request = new Operations\PublicApiV1DeliveryNotesListPresentationsRequest(
    company: '6b3348a7-95d9-4566-a4d2-1e9c8e4e5703',
    deliveryNote: 'b2649424-80cb-4f0a-9883-f0977fb5d659',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesListPresentations(
    request: $request
);

if ($response->documentPresentationHistoryEnvelope !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                  | Type                                                                                                                                       | Required                                                                                                                                   | Description                                                                                                                                |
| ------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                                 | [Operations\PublicApiV1DeliveryNotesListPresentationsRequest](../../Models/Operations/PublicApiV1DeliveryNotesListPresentationsRequest.md) | :heavy_check_mark:                                                                                                                         | The request object to use for the request.                                                                                                 |

### Response

**[?Operations\PublicApiV1DeliveryNotesListPresentationsResponse](../../Models/Operations/PublicApiV1DeliveryNotesListPresentationsResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1DeliveryNotesMarkDelivered

Transition a delivery note to the `delivered` state (public `sent`). Canonical REST replacement for the deprecated `POST /change_status`. Returns 409 `invalid_status_transition` if the note cannot transition. Supports `Idempotency-Key` for safe retries.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.mark_delivered" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/mark-delivered" example="success" -->
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

$request = new Operations\PublicApiV1DeliveryNotesMarkDeliveredRequest(
    company: 'Bode, Funk and Ryan',
    deliveryNote: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesMarkDelivered(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                          | Type                                                                                                                               | Required                                                                                                                           | Description                                                                                                                        |
| ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                         | [Operations\PublicApiV1DeliveryNotesMarkDeliveredRequest](../../Models/Operations/PublicApiV1DeliveryNotesMarkDeliveredRequest.md) | :heavy_check_mark:                                                                                                                 | The request object to use for the request.                                                                                         |

### Response

**[?Operations\PublicApiV1DeliveryNotesMarkDeliveredResponse](../../Models/Operations/PublicApiV1DeliveryNotesMarkDeliveredResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1DeliveryNotesRevisePresentation

Append a derived capture using the authentic presentation_revision preview of the exact parent, target, policy and current source. Send the source token and preview fingerprint; a delivery or illustrative preview cannot authorize this action. Existing document state, fiscal numbering, references, instalments and totals remain intact. This action does not render, publish or send a PDF. Personal-data erasure is terminal.

### Example Usage: captured_visual_revision

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.revise_presentation" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/presentation-revisions" example="captured_visual_revision" -->
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

$request = new Operations\PublicApiV1DeliveryNotesRevisePresentationRequest(
    company: '41159ecf-d3ae-4b27-9840-e2c2d1367800',
    deliveryNote: '21601615-b54c-452f-9b57-2e1348dbe1cf',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReviseDocumentPresentationRequest(
        parentId: '019e0000-0000-7000-8000-000000000201',
        templateId: null,
        expectedVersion: 1,
        reason: 'Actualizar la presentación conservando el contenido.',
        expectedSourceToken: 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
        expectedPreviewContextFingerprint: 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb',
        expectedTemplateRevisionId: null,
        expectedCommercialIdentityRevisionId: null,
        purpose: Components\ReviseDocumentPresentationRequestPurpose::Complete,
        format: Components\ReviseDocumentPresentationRequestFormat::A4,
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesRevisePresentation(
    request: $request
);

if ($response->documentPresentationRevisionEnvelope !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.revise_presentation" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/presentation-revisions" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1DeliveryNotesRevisePresentationRequest(
    company: 'ec0f0e6c-2460-4f4f-991b-f2d772afa5a9',
    deliveryNote: 'cf2d7cc3-9322-4bad-9843-37f167af9c13',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReviseDocumentPresentationRequest(
        parentId: '019e0000-0000-7000-8000-000000000201',
        templateId: null,
        expectedVersion: 1,
        reason: 'Actualizar la presentación conservando el contenido.',
        expectedSourceToken: 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
        expectedPreviewContextFingerprint: 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb',
        expectedTemplateRevisionId: null,
        expectedCommercialIdentityRevisionId: null,
        purpose: Components\ReviseDocumentPresentationRequestPurpose::Complete,
        format: Components\ReviseDocumentPresentationRequestFormat::A4,
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesRevisePresentation(
    request: $request
);

if ($response->documentPresentationRevisionEnvelope !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.revise_presentation" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/presentation-revisions" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1DeliveryNotesRevisePresentationRequest(
    company: 'de7eb297-a790-457c-9193-03eb22f70f24',
    deliveryNote: '747613d0-df2d-4d6b-857a-a0c3be0cdea0',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReviseDocumentPresentationRequest(
        parentId: '019e0000-0000-7000-8000-000000000201',
        templateId: null,
        expectedVersion: 1,
        reason: 'Actualizar la presentación conservando el contenido.',
        expectedSourceToken: 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
        expectedPreviewContextFingerprint: 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb',
        expectedTemplateRevisionId: null,
        expectedCommercialIdentityRevisionId: null,
        purpose: Components\ReviseDocumentPresentationRequestPurpose::Complete,
        format: Components\ReviseDocumentPresentationRequestFormat::A4,
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesRevisePresentation(
    request: $request
);

if ($response->documentPresentationRevisionEnvelope !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.revise_presentation" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/presentation-revisions" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1DeliveryNotesRevisePresentationRequest(
    company: '973ed0b0-f72f-49f6-b498-e2e3f246ad92',
    deliveryNote: '5f216dc3-5a55-4f9a-b097-7c1a060d520c',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReviseDocumentPresentationRequest(
        parentId: '019e0000-0000-7000-8000-000000000201',
        templateId: null,
        expectedVersion: 1,
        reason: 'Actualizar la presentación conservando el contenido.',
        expectedSourceToken: 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
        expectedPreviewContextFingerprint: 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb',
        expectedTemplateRevisionId: null,
        expectedCommercialIdentityRevisionId: null,
        purpose: Components\ReviseDocumentPresentationRequestPurpose::Complete,
        format: Components\ReviseDocumentPresentationRequestFormat::A4,
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesRevisePresentation(
    request: $request
);

if ($response->documentPresentationRevisionEnvelope !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                    | Type                                                                                                                                         | Required                                                                                                                                     | Description                                                                                                                                  |
| -------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                   | [Operations\PublicApiV1DeliveryNotesRevisePresentationRequest](../../Models/Operations/PublicApiV1DeliveryNotesRevisePresentationRequest.md) | :heavy_check_mark:                                                                                                                           | The request object to use for the request.                                                                                                   |

### Response

**[?Operations\PublicApiV1DeliveryNotesRevisePresentationResponse](../../Models/Operations/PublicApiV1DeliveryNotesRevisePresentationResponse.md)**

### Errors

| Error Type                                  | Status Code                                 | Content Type                                |
| ------------------------------------------- | ------------------------------------------- | ------------------------------------------- |
| Errors\Error                                | 400, 401, 403, 404, 409, 410, 422, 428, 429 | application/json                            |
| Errors\Error                                | 500, 503                                    | application/json                            |
| Errors\APIException                         | 4XX, 5XX                                    | \*/\*                                       |

## publicApiV1DeliveryNotesSend

Send a delivery note to the client by email. Uses the email on file unless overridden in the payload.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.send" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/send" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1DeliveryNotesSendRequest(
    company: 'd110a0ac-80bc-4603-a483-e321b2204797',
    deliveryNote: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SendDeliveryNoteRequest(
        email: 'Arden_Beatty@hotmail.com',
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesSend(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.send" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/send" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1DeliveryNotesSendRequest(
    company: 'a179bb1c-e4ba-48a4-ab34-9b1c25c57e8f',
    deliveryNote: 'db45a5ee-1d1f-4de0-8407-331300f27c56',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SendDeliveryNoteRequest(
        email: 'Arden_Beatty@hotmail.com',
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesSend(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.send" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/send" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1DeliveryNotesSendRequest(
    company: '95a651a6-4da3-49a1-ae62-ed9af3e81c20',
    deliveryNote: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SendDeliveryNoteRequest(
        email: 'Arden_Beatty@hotmail.com',
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesSend(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.send" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/send" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1DeliveryNotesSendRequest(
    company: 'Little, Armstrong and Predovic',
    deliveryNote: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SendDeliveryNoteRequest(
        email: 'Arden_Beatty@hotmail.com',
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesSend(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.send" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/send" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1DeliveryNotesSendRequest(
    company: 'd110a0ac-80bc-4603-a483-e321b2204797',
    deliveryNote: '95a651a6-4da3-49a1-ae62-ed9af3e81c20',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SendDeliveryNoteRequest(
        email: 'Arden_Beatty@hotmail.com',
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesSend(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.send" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/send" example="success" -->
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

$request = new Operations\PublicApiV1DeliveryNotesSendRequest(
    company: 'Bartell LLC',
    deliveryNote: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SendDeliveryNoteRequest(
        email: 'Arden_Beatty@hotmail.com',
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesSend(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.send" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/send" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1DeliveryNotesSendRequest(
    company: '5cbdf9c2-7d6e-4659-a63e-97013b6636f7',
    deliveryNote: 'f4a4c1be-0fef-4be4-b81e-b18e505b5844',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SendDeliveryNoteRequest(
        email: 'Arden_Beatty@hotmail.com',
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesSend(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                        | Type                                                                                                             | Required                                                                                                         | Description                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                       | [Operations\PublicApiV1DeliveryNotesSendRequest](../../Models/Operations/PublicApiV1DeliveryNotesSendRequest.md) | :heavy_check_mark:                                                                                               | The request object to use for the request.                                                                       |

### Response

**[?Operations\PublicApiV1DeliveryNotesSendResponse](../../Models/Operations/PublicApiV1DeliveryNotesSendResponse.md)**

### Errors

| Error Type                             | Status Code                            | Content Type                           |
| -------------------------------------- | -------------------------------------- | -------------------------------------- |
| Errors\Error                           | 400, 401, 403, 404, 409, 422, 428, 429 | application/json                       |
| Errors\Error                           | 500, 503                               | application/json                       |
| Errors\APIException                    | 4XX, 5XX                               | \*/\*                                  |

## publicApiV1DeliveryNotesSign

Record a handwritten signature on a delivery note: base64 PNG up to 2 MB, other formats return 422.
- `signed_by` (up to 120 characters) is required; the server sets the signature time and ignores the deprecated `signed_at`. The status does not change.
- The optional presentation context (`expected_*`) is all-or-nothing and requires an `Idempotency-Key`.
- The audit log keeps hashed PII for 5 years; erase it via `signature-audits/{auditId}/forget`.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.sign" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/sign" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1DeliveryNotesSignRequest(
    company: '5d4c8edd-858d-4580-a37f-8960de1921f7',
    deliveryNote: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SignDeliveryNoteRequest(
        signedBy: '<value>',
        recipientDni: '<value>',
        signatureImageBase64: '<value>',
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesSign(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.sign" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/sign" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1DeliveryNotesSignRequest(
    company: '43684c22-5002-4813-92e6-b3494451c6b6',
    deliveryNote: 'fed951b0-37cc-4b82-91e0-55f66ca70d96',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SignDeliveryNoteRequest(
        signedBy: '<value>',
        recipientDni: '<value>',
        signatureImageBase64: '<value>',
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesSign(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.sign" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/sign" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1DeliveryNotesSignRequest(
    company: '0a5545b0-ca91-418d-9891-2f9ed5927f8b',
    deliveryNote: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SignDeliveryNoteRequest(
        signedBy: '<value>',
        recipientDni: '<value>',
        signatureImageBase64: '<value>',
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesSign(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.sign" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/sign" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1DeliveryNotesSignRequest(
    company: 'Lesch, Hintz and Lang',
    deliveryNote: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SignDeliveryNoteRequest(
        signedBy: '<value>',
        recipientDni: '<value>',
        signatureImageBase64: '<value>',
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesSign(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.sign" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/sign" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1DeliveryNotesSignRequest(
    company: 'aaf1670e-75b6-4f99-841a-1f74f2196aec',
    deliveryNote: '40fe14b2-5789-48cf-8c3a-fe0453726a9f',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SignDeliveryNoteRequest(
        signedBy: '<value>',
        recipientDni: '<value>',
        signatureImageBase64: '<value>',
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesSign(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.sign" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/sign" example="success" -->
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

$request = new Operations\PublicApiV1DeliveryNotesSignRequest(
    company: 'Luettgen, Ullrich and Stoltenberg',
    deliveryNote: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SignDeliveryNoteRequest(
        signedBy: '<value>',
        recipientDni: '<value>',
        signatureImageBase64: '<value>',
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesSign(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.sign" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/sign" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1DeliveryNotesSignRequest(
    company: '3357ac1b-4b3d-4f7f-a050-c5120b663126',
    deliveryNote: '20fc7868-8378-4969-a5e2-214d74a3ee2a',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SignDeliveryNoteRequest(
        signedBy: '<value>',
        recipientDni: '<value>',
        signatureImageBase64: '<value>',
    ),
);

$response = $sdk->deliveryNotes->publicApiV1DeliveryNotesSign(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                        | Type                                                                                                             | Required                                                                                                         | Description                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                       | [Operations\PublicApiV1DeliveryNotesSignRequest](../../Models/Operations/PublicApiV1DeliveryNotesSignRequest.md) | :heavy_check_mark:                                                                                               | The request object to use for the request.                                                                       |

### Response

**[?Operations\PublicApiV1DeliveryNotesSignResponse](../../Models/Operations/PublicApiV1DeliveryNotesSignResponse.md)**

### Errors

| Error Type                             | Status Code                            | Content Type                           |
| -------------------------------------- | -------------------------------------- | -------------------------------------- |
| Errors\Error                           | 400, 401, 402, 403, 404, 409, 422, 429 | application/json                       |
| Errors\Error                           | 500                                    | application/json                       |
| Errors\APIException                    | 4XX, 5XX                               | \*/\*                                  |