# Invoices.AdministrativeRevisions

## Overview

### Available Operations

* [publicApiV1InvoicesAdministrativeRevisionsIndex](#publicapiv1invoicesadministrativerevisionsindex) - Retrieve invoice administrative routing history
* [publicApiV1InvoicesAdministrativeRevisionsStore](#publicapiv1invoicesadministrativerevisionsstore) - Revise invoice administrative routing

## publicApiV1InvoicesAdministrativeRevisionsIndex

Retrieve the frozen administrative snapshot, explicit override and append-only revisions of an invoice in your company. The administrative version starts at 1. A null override inherits the snapshot; an empty override explicitly selects no centres. This history is independent of fiscal parties, payment terms and PDF presentation.

### Example Usage: known_empty

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.administrative_revisions.index" method="get" path="/companies/{company}/invoices/{invoice}/administrative-revisions" example="known_empty" -->
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



$response = $sdk->invoices->administrativeRevisions->publicApiV1InvoicesAdministrativeRevisionsIndex(
    company: '76bd49b9-ccc0-409e-960e-cd3deea7eb5a',
    invoice: '61724602-76ca-4caa-b6de-edd1f1aefef1',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: legacy_unknown

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.administrative_revisions.index" method="get" path="/companies/{company}/invoices/{invoice}/administrative-revisions" example="legacy_unknown" -->
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



$response = $sdk->invoices->administrativeRevisions->publicApiV1InvoicesAdministrativeRevisionsIndex(
    company: 'd7023abd-c177-4e69-9209-1ca777a08af9',
    invoice: '949d2c54-f2be-4da1-b1bb-9630f7dd0e55',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: revised

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.administrative_revisions.index" method="get" path="/companies/{company}/invoices/{invoice}/administrative-revisions" example="revised" -->
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



$response = $sdk->invoices->administrativeRevisions->publicApiV1InvoicesAdministrativeRevisionsIndex(
    company: '81c033df-d814-4ce1-9ece-7246ad0bf8d5',
    invoice: '74f5741a-0a4a-4897-a742-4c8725c0a9aa',
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
| `invoice`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the invoice.                                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1InvoicesAdministrativeRevisionsIndexResponse](../../Models/Operations/PublicApiV1InvoicesAdministrativeRevisionsIndexResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1InvoicesAdministrativeRevisionsStore

Append an administrative routing revision to an issued or overdue invoice. Supply administrative_centres (including [] to select none), a nonblank reason of at most 500 characters and the current expected_version in the body. Missing version returns 428; a stale version returns 409 with current_version only after authorization. A successful append returns 201 and its committed administrative_version. Parent invoice If-Match does not replace the body precondition. Fiscal data, issuance, payment terms and delivery are unchanged; no invoice is emailed or submitted by this operation.

### Example Usage: commercial_1

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.administrative_revisions.store" method="post" path="/companies/{company}/invoices/{invoice}/administrative-revisions" example="commercial_1" -->
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

$request = new Operations\PublicApiV1InvoicesAdministrativeRevisionsStoreRequest(
    company: 'ef1ba89d-ed64-432b-b2b6-142b304ae289',
    invoice: 'b883ffd0-ce17-4d9c-9d8e-eb8623301027',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReviseInvoiceAdministrativeRoutingRequest(
        administrativeCentres: [
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::One,
                code: 'L01000001',
                name: 'Oficina contable',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Two,
                code: 'L01000002',
                name: 'Órgano gestor',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Three,
                code: 'L01000003',
                name: 'Unidad tramitadora',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Four,
                code: 'L01000004',
                name: 'Órgano proponente',
            ),
        ],
        reason: 'Actualizar unidad tramitadora',
        expectedVersion: 1,
    ),
);

$response = $sdk->invoices->administrativeRevisions->publicApiV1InvoicesAdministrativeRevisionsStore(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: commercial_2

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.administrative_revisions.store" method="post" path="/companies/{company}/invoices/{invoice}/administrative-revisions" example="commercial_2" -->
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

$request = new Operations\PublicApiV1InvoicesAdministrativeRevisionsStoreRequest(
    company: 'ceae99ee-1bfd-4aa1-9430-486d5acecc7c',
    invoice: '5a7df5d1-2003-4756-8b72-6955236bb031',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReviseInvoiceAdministrativeRoutingRequest(
        administrativeCentres: [],
        reason: 'Sin encaminamiento administrativo',
        expectedVersion: 2,
    ),
);

$response = $sdk->invoices->administrativeRevisions->publicApiV1InvoicesAdministrativeRevisionsStore(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: explicit_empty

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.administrative_revisions.store" method="post" path="/companies/{company}/invoices/{invoice}/administrative-revisions" example="explicit_empty" -->
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

$request = new Operations\PublicApiV1InvoicesAdministrativeRevisionsStoreRequest(
    company: '6fa7e100-f700-4aae-988d-f985dca1d3aa',
    invoice: 'f9bdbb33-be99-4564-89bd-52846263b49f',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReviseInvoiceAdministrativeRoutingRequest(
        administrativeCentres: [
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::One,
                code: 'L01000001',
                name: 'Oficina contable',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Two,
                code: 'L01000002',
                name: 'Órgano gestor',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Three,
                code: 'L01000003',
                name: 'Unidad tramitadora',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Four,
                code: 'L01000004',
                name: 'Órgano proponente',
            ),
        ],
        reason: 'Actualizar unidad tramitadora',
        expectedVersion: 1,
    ),
);

$response = $sdk->invoices->administrativeRevisions->publicApiV1InvoicesAdministrativeRevisionsStore(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.administrative_revisions.store" method="post" path="/companies/{company}/invoices/{invoice}/administrative-revisions" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1InvoicesAdministrativeRevisionsStoreRequest(
    company: '075b2a88-f880-410d-94b1-e1657b59d380',
    invoice: '18569bee-cce5-4943-bce5-909e4dacf2b3',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReviseInvoiceAdministrativeRoutingRequest(
        administrativeCentres: [
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::One,
                code: 'L01000001',
                name: 'Oficina contable',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Two,
                code: 'L01000002',
                name: 'Órgano gestor',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Three,
                code: 'L01000003',
                name: 'Unidad tramitadora',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Four,
                code: 'L01000004',
                name: 'Órgano proponente',
            ),
        ],
        reason: 'Actualizar unidad tramitadora',
        expectedVersion: 1,
    ),
);

$response = $sdk->invoices->administrativeRevisions->publicApiV1InvoicesAdministrativeRevisionsStore(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invoice_administrative_version_conflict

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.administrative_revisions.store" method="post" path="/companies/{company}/invoices/{invoice}/administrative-revisions" example="invoice_administrative_version_conflict" -->
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

$request = new Operations\PublicApiV1InvoicesAdministrativeRevisionsStoreRequest(
    company: 'cb93c998-0a5b-4cf1-a70b-0ba2f6be818d',
    invoice: '4b8e53a7-9361-4cbd-ba4c-4dc8ef1bb71f',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReviseInvoiceAdministrativeRoutingRequest(
        administrativeCentres: [
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::One,
                code: 'L01000001',
                name: 'Oficina contable',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Two,
                code: 'L01000002',
                name: 'Órgano gestor',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Three,
                code: 'L01000003',
                name: 'Unidad tramitadora',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Four,
                code: 'L01000004',
                name: 'Órgano proponente',
            ),
        ],
        reason: 'Actualizar unidad tramitadora',
        expectedVersion: 1,
    ),
);

$response = $sdk->invoices->administrativeRevisions->publicApiV1InvoicesAdministrativeRevisionsStore(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.administrative_revisions.store" method="post" path="/companies/{company}/invoices/{invoice}/administrative-revisions" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1InvoicesAdministrativeRevisionsStoreRequest(
    company: '8e1f8552-12a4-49d4-b97c-ea5d8aa6472d',
    invoice: '369af496-a2ee-492b-88d1-b99a89011b2f',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReviseInvoiceAdministrativeRoutingRequest(
        administrativeCentres: [
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::One,
                code: 'L01000001',
                name: 'Oficina contable',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Two,
                code: 'L01000002',
                name: 'Órgano gestor',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Three,
                code: 'L01000003',
                name: 'Unidad tramitadora',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Four,
                code: 'L01000004',
                name: 'Órgano proponente',
            ),
        ],
        reason: 'Actualizar unidad tramitadora',
        expectedVersion: 1,
    ),
);

$response = $sdk->invoices->administrativeRevisions->publicApiV1InvoicesAdministrativeRevisionsStore(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: revised

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.administrative_revisions.store" method="post" path="/companies/{company}/invoices/{invoice}/administrative-revisions" example="revised" -->
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

$request = new Operations\PublicApiV1InvoicesAdministrativeRevisionsStoreRequest(
    company: '40c1b16e-e750-4c97-b574-1504013881ec',
    invoice: '08e02d16-b88b-449a-aab6-7f18291e11ea',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReviseInvoiceAdministrativeRoutingRequest(
        administrativeCentres: [
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::One,
                code: 'L01000001',
                name: 'Oficina contable',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Two,
                code: 'L01000002',
                name: 'Órgano gestor',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Three,
                code: 'L01000003',
                name: 'Unidad tramitadora',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Four,
                code: 'L01000004',
                name: 'Órgano proponente',
            ),
        ],
        reason: 'Actualizar unidad tramitadora',
        expectedVersion: 1,
    ),
);

$response = $sdk->invoices->administrativeRevisions->publicApiV1InvoicesAdministrativeRevisionsStore(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.administrative_revisions.store" method="post" path="/companies/{company}/invoices/{invoice}/administrative-revisions" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1InvoicesAdministrativeRevisionsStoreRequest(
    company: '5e4434c3-602a-4ded-85c0-c14257575362',
    invoice: 'a781cd13-f9ff-4ae4-93c7-d1a4f1bbef42',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReviseInvoiceAdministrativeRoutingRequest(
        administrativeCentres: [
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::One,
                code: 'L01000001',
                name: 'Oficina contable',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Two,
                code: 'L01000002',
                name: 'Órgano gestor',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Three,
                code: 'L01000003',
                name: 'Unidad tramitadora',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Four,
                code: 'L01000004',
                name: 'Órgano proponente',
            ),
        ],
        reason: 'Actualizar unidad tramitadora',
        expectedVersion: 1,
    ),
);

$response = $sdk->invoices->administrativeRevisions->publicApiV1InvoicesAdministrativeRevisionsStore(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                              | Type                                                                                                                                                   | Required                                                                                                                                               | Description                                                                                                                                            |
| ------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                                             | [Operations\PublicApiV1InvoicesAdministrativeRevisionsStoreRequest](../../Models/Operations/PublicApiV1InvoicesAdministrativeRevisionsStoreRequest.md) | :heavy_check_mark:                                                                                                                                     | The request object to use for the request.                                                                                                             |

### Response

**[?Operations\PublicApiV1InvoicesAdministrativeRevisionsStoreResponse](../../Models/Operations/PublicApiV1InvoicesAdministrativeRevisionsStoreResponse.md)**

### Errors

| Error Type                                                                  | Status Code                                                                 | Content Type                                                                |
| --------------------------------------------------------------------------- | --------------------------------------------------------------------------- | --------------------------------------------------------------------------- |
| Errors\PublicApiV1InvoicesAdministrativeRevisionsStoreResponseBodyException | 409                                                                         | application/json                                                            |
| Errors\Error                                                                | 409                                                                         | application/json                                                            |
| Errors\Error                                                                | 400, 401, 403, 404, 422, 428, 429                                           | application/json                                                            |
| Errors\Error                                                                | 500                                                                         | application/json                                                            |
| Errors\APIException                                                         | 4XX, 5XX                                                                    | \*/\*                                                                       |