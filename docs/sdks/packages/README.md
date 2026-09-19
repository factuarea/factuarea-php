# DeliveryNotes.Packages

## Overview

### Available Operations

* [publicApiV1DeliveryNotesPackagesCreate](#publicapiv1deliverynotespackagescreate) - Add a package to a delivery note
* [publicApiV1DeliveryNotesPackagesList](#publicapiv1deliverynotespackageslist) - List the packages of a delivery note
* [publicApiV1DeliveryNotesPackagesDelete](#publicapiv1deliverynotespackagesdelete) - Delete a package of a delivery note
* [publicApiV1DeliveryNotesPackagesUpdate](#publicapiv1deliverynotespackagesupdate) - Update a package of a delivery note

## publicApiV1DeliveryNotesPackagesCreate

Declare one package on the shipment of a delivery note: its reference, its weight in kilograms, its three dimensions in centimetres and its notes, all of them optional — a package can be declared with nothing at all, which is what a warehouse does when it only needs to count parcels. Weight and dimensions travel as decimal strings with two decimal places and carry the ceiling of their column, so a value above it is rejected with 422 before anything is written. The POSITION is not part of the body: it is assigned deterministically as the next one of the note, so the packages keep the order in which they were declared. The total weight of the shipment grows by what you declare here, because that total is the sum of the packages and is recalculated on every read. Returns the package that was created with its public id, and a location header pointing at it. It is not published as irreversible: a package that was just added is edited or removed with its sibling operations.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.packages.create" method="post" path="/companies/{company}/delivery-notes/{delivery_note}/packages" -->
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

$request = new Operations\PublicApiV1DeliveryNotesPackagesCreateRequest(
    company: 'Sawayn - Brekke',
    deliveryNote: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\AddShipmentPackageRequest(
        reference: 'BULTO-1/2',
        weightKg: 10.5,
        lengthCm: 120,
        widthCm: 80,
        heightCm: 45,
        notes: 'Palé retractilado; no apilar.',
    ),
);

$response = $sdk->deliveryNotes->packages->publicApiV1DeliveryNotesPackagesCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                            | Type                                                                                                                                 | Required                                                                                                                             | Description                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                           | [Operations\PublicApiV1DeliveryNotesPackagesCreateRequest](../../Models/Operations/PublicApiV1DeliveryNotesPackagesCreateRequest.md) | :heavy_check_mark:                                                                                                                   | The request object to use for the request.                                                                                           |

### Response

**[?Operations\PublicApiV1DeliveryNotesPackagesCreateResponse](../../Models/Operations/PublicApiV1DeliveryNotesPackagesCreateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1DeliveryNotesPackagesList

List the packages declared on the shipment of a delivery note, together with how many there are and their total weight, which is the SUM of the individual weights added up on each read and never a stored total. Each package carries its public id, its position in the shipment, its reference, its weight in kilograms, its three dimensions in centimetres and its notes. Weight and dimensions travel as decimal strings with two decimal places, so nothing is lost to rounding. All of those fields are always present and any of them may be null: a package can be declared with nothing but its position. A delivery note with no packages answers with an empty list and a total weight of zero, not with an error, and a note of another company answers exactly like one that does not exist.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.packages.list" method="get" path="/companies/{company}/delivery-notes/{delivery_note}/packages" -->
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



$response = $sdk->deliveryNotes->packages->publicApiV1DeliveryNotesPackagesList(
    company: 'Russel, Bruen and McClure',
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

**[?Operations\PublicApiV1DeliveryNotesPackagesListResponse](../../Models/Operations/PublicApiV1DeliveryNotesPackagesListResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1DeliveryNotesPackagesDelete

Delete one package from the shipment of a delivery note. It returns no content. The total weight of the shipment drops by the weight of that package, because the total is the sum of the packages and is recalculated on each read. The positions of the remaining packages are NOT renumbered, so a gap in the sequence is expected and means nothing is missing: the order is what the position preserves, not the count. The package is resolved by the double key company and delivery note, so one of another company and one that hangs from a different note of yours both answer as not found. Irreversible: this API publishes no operation that brings a deleted package back — declaring it again creates a new one, with a new id and a new position at the end — so it requires an `Idempotency-Key` when the policy of your credential demands it.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.packages.delete" method="delete" path="/companies/{company}/delivery-notes/{delivery_note}/packages/{package}" -->
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

$request = new Operations\PublicApiV1DeliveryNotesPackagesDeleteRequest(
    company: 'Larkin - Hettinger',
    deliveryNote: '<value>',
    package: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->deliveryNotes->packages->publicApiV1DeliveryNotesPackagesDelete(
    request: $request
);

if ($response->statusCode === 200) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                            | Type                                                                                                                                 | Required                                                                                                                             | Description                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                           | [Operations\PublicApiV1DeliveryNotesPackagesDeleteRequest](../../Models/Operations/PublicApiV1DeliveryNotesPackagesDeleteRequest.md) | :heavy_check_mark:                                                                                                                   | The request object to use for the request.                                                                                           |

### Response

**[?Operations\PublicApiV1DeliveryNotesPackagesDeleteResponse](../../Models/Operations/PublicApiV1DeliveryNotesPackagesDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1DeliveryNotesPackagesUpdate

Partially update one package of a delivery note: only the fields present in the body change, the ones you omit keep their value, and a field sent explicitly as null is cleared. The reference, the weight, the three dimensions and the notes are editable; the POSITION is not, because it is what keeps the packages in the order they were declared. Weight and dimensions keep the ceiling and the two decimal places of their columns, so a value above the ceiling is rejected with 422 and nothing is written. Editing a weight changes the total weight of the shipment, which is the sum of the packages and is recalculated on each read. The package is resolved by the DOUBLE key company and delivery note: one of another company, and one that exists but hangs from a different note of yours, both answer exactly like one that does not exist.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.delivery_notes.packages.update" method="patch" path="/companies/{company}/delivery-notes/{delivery_note}/packages/{package}" -->
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

$request = new Operations\PublicApiV1DeliveryNotesPackagesUpdateRequest(
    company: 'Ankunding, Runolfsson and Buckridge',
    deliveryNote: '<value>',
    package: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\UpdateShipmentPackageRequest(
        weightKg: 3.25,
    ),
);

$response = $sdk->deliveryNotes->packages->publicApiV1DeliveryNotesPackagesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                            | Type                                                                                                                                 | Required                                                                                                                             | Description                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                           | [Operations\PublicApiV1DeliveryNotesPackagesUpdateRequest](../../Models/Operations/PublicApiV1DeliveryNotesPackagesUpdateRequest.md) | :heavy_check_mark:                                                                                                                   | The request object to use for the request.                                                                                           |

### Response

**[?Operations\PublicApiV1DeliveryNotesPackagesUpdateResponse](../../Models/Operations/PublicApiV1DeliveryNotesPackagesUpdateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |