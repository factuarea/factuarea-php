# DeliveryNotes.Shipment

## Overview

### Available Operations

* [publicApiV1DeliveryNotesShipmentShow](#publicapiv1deliverynotesshipmentshow) - Retrieve the shipment of a delivery note
* [publicApiV1DeliveryNotesShipmentUpdate](#publicapiv1deliverynotesshipmentupdate) - Update the shipment of a delivery note

## publicApiV1DeliveryNotesShipmentShow

Retrieve the shipment of a delivery note: the note by its public id and its number, its fulfilment status with its label, the carrier as an opaque id together with its name when one has been assigned, the free-text carrier written on the note, the tracking number, the tracking URL, the expected delivery date, the packages reference, and the packages with their count and their total weight. The tracking URL is DERIVED on every read from the template of the carrier and the tracking number, with the number escaped: no URL is stored anywhere, so changing the template of a carrier changes the link of every note it carries without rewriting a single row, and the field is simply ABSENT when the carrier has no template or the note has no tracking number — never an empty string and never a half-built link. The total weight is the SUM of the packages, added up on each read and not taken from any stored total. The free-text carrier travels ALONGSIDE the reference to the carrier entity and not instead of it: that is what makes moving from text to entity reversible, and it is why deleting a carrier never touches what the note already said.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.shipment.show" method="get" path="/companies/{company}/delivery-notes/{delivery_note}/shipment" -->
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



$response = $sdk->deliveryNotes->shipment->publicApiV1DeliveryNotesShipmentShow(
    company: 'Herzog, Hudson and Purdy',
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

**[?Operations\PublicApiV1DeliveryNotesShipmentShowResponse](../../Models/Operations/PublicApiV1DeliveryNotesShipmentShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1DeliveryNotesShipmentUpdate

Partially update the shipment of a delivery note: only the fields present in the body change, the ones you omit keep their value, and a field sent explicitly as null is cleared. Three fields are accepted. The CARRIER, by its public id and only among the carriers of your company: one that is no longer active is rejected as a business rule violation with 422 and never with 403, and one of another company answers as not found. The EXPECTED DELIVERY DATE, as a plain calendar day. And the PACKAGES REFERENCE. The tracking NUMBER is deliberately NOT part of this body, and it is the one thing to read before wiring your integration: the number has a single place where it is written — the update of the delivery note itself — and accepting it here would be a second one, so this operation would let two callers disagree about the same field. It is published in the read of the shipment; it is set there. Assigning a carrier does not overwrite the free-text carrier the note already carried: the two travel together. This operation is not published as irreversible, because every field it touches can be set again.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.shipment.update" method="patch" path="/companies/{company}/delivery-notes/{delivery_note}/shipment" -->
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

$request = new Operations\PublicApiV1DeliveryNotesShipmentUpdateRequest(
    company: 'Reinger, Prohaska and Casper',
    deliveryNote: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\UpdateDeliveryNoteShipmentRequest(
        carrierId: '01934c8d-2e5f-7a1b-8c9d-4e5f6a7b8d01',
        expectedDeliveryDate: LocalDate::parse('2026-01-22'),
        packagesReference: 'PAL-2026-0031',
    ),
);

$response = $sdk->deliveryNotes->shipment->publicApiV1DeliveryNotesShipmentUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                            | Type                                                                                                                                 | Required                                                                                                                             | Description                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                           | [Operations\PublicApiV1DeliveryNotesShipmentUpdateRequest](../../Models/Operations/PublicApiV1DeliveryNotesShipmentUpdateRequest.md) | :heavy_check_mark:                                                                                                                   | The request object to use for the request.                                                                                           |

### Response

**[?Operations\PublicApiV1DeliveryNotesShipmentUpdateResponse](../../Models/Operations/PublicApiV1DeliveryNotesShipmentUpdateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |