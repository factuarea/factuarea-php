# TimeCorrections

## Overview

### Available Operations

* [publicApiV1TimeCorrectionsApprove](#publicapiv1timecorrectionsapprove) - Approve a time entry correction
* [publicApiV1TimeCorrectionsList](#publicapiv1timecorrectionslist) - List all time entry corrections
* [publicApiV1TimeCorrectionsCreate](#publicapiv1timecorrectionscreate) - Request a time entry correction
* [publicApiV1TimeCorrectionsReject](#publicapiv1timecorrectionsreject) - Reject a time entry correction
* [publicApiV1TimeCorrectionsShow](#publicapiv1timecorrectionsshow) - Retrieve a time entry correction

## publicApiV1TimeCorrectionsApprove

Approve a pending correction request by its `id` (UUID v7), appending the resolving `correction_entry` linked to the original time entry. An optional `note` from the approver may be supplied. A request that is not pending returns 422 (already resolved), and approving your own request returns 422 (self-approval is forbidden). Returns 200 with the resolved correction.

### Example Usage: approve

<!-- UsageSnippet language="php" operationID="public-api.v1.time_corrections.approve" method="post" path="/companies/{company}/time-corrections/{time_correction}/approve" example="approve" -->
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

$request = new Operations\PublicApiV1TimeCorrectionsApproveRequest(
    company: 'Kertzmann Inc',
    timeCorrection: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ApproveTimeCorrectionRequest(
        note: 'Corrección verificada con el registro de acceso; se aprueba.',
    ),
);

$response = $sdk->timeCorrections->publicApiV1TimeCorrectionsApprove(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.time_corrections.approve" method="post" path="/companies/{company}/time-corrections/{time_correction}/approve" example="success" -->
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

$request = new Operations\PublicApiV1TimeCorrectionsApproveRequest(
    company: 'Lowe - Langosh',
    timeCorrection: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->timeCorrections->publicApiV1TimeCorrectionsApprove(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1TimeCorrectionsApproveRequest](../../Models/Operations/PublicApiV1TimeCorrectionsApproveRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1TimeCorrectionsApproveResponse](../../Models/Operations/PublicApiV1TimeCorrectionsApproveResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1TimeCorrectionsList

List the time entry correction requests of your company with cursor-based pagination, ordered by request time. Supports filtering by `status` (`pending` is the manager inbox, `approved`/`rejected` are resolved), `employee_id` (UUID v7) and a date range (`from`/`to`).

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.time_corrections.list" method="get" path="/companies/{company}/time-corrections" example="success" -->
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

$request = new Operations\PublicApiV1TimeCorrectionsListRequest(
    company: 'Doyle, Considine and Stehr',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->timeCorrections->publicApiV1TimeCorrectionsList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                            | Type                                                                                                                 | Required                                                                                                             | Description                                                                                                          |
| -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                           | [Operations\PublicApiV1TimeCorrectionsListRequest](../../Models/Operations/PublicApiV1TimeCorrectionsListRequest.md) | :heavy_check_mark:                                                                                                   | The request object to use for the request.                                                                           |

### Response

**[?Operations\PublicApiV1TimeCorrectionsListResponse](../../Models/Operations/PublicApiV1TimeCorrectionsListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1TimeCorrectionsCreate

Request the correction of a time entry (RD-ley 8/2019). `time_entry_id` (UUID v7 of the entry to correct), `kind` (`add_missing_entry`/`adjust_time`/`remove_entry`), a `reason` and the `proposed` values are required. A correction is a new append-only entry that references the original entry without mutating it (analogous to a corrective invoice); the workflow stays `pending` until a manager approves or rejects it. Returns 201 with the created request and a `Location` header.

### Example Usage: adjust_time

<!-- UsageSnippet language="php" operationID="public-api.v1.time_corrections.create" method="post" path="/companies/{company}/time-corrections" example="adjust_time" -->
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

$body = new Components\RequestTimeCorrectionRequest(
    timeEntryId: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8b20',
    kind: Components\RequestTimeCorrectionRequestKind::AdjustTime,
    reason: 'La hora de entrada real fue las 09:05, no las 09:30.',
    proposed: [
        'occurred_at' => '2026-05-28T09:05:00+02:00',
    ],
);

$response = $sdk->timeCorrections->publicApiV1TimeCorrectionsCreate(
    company: 'Rosenbaum - Hilll',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.time_corrections.create" method="post" path="/companies/{company}/time-corrections" example="missing_api_key" -->
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

$body = new Components\RequestTimeCorrectionRequest(
    timeEntryId: '93fb317c-c190-4b75-9e39-8204e90d76cb',
    kind: Components\RequestTimeCorrectionRequestKind::RemoveEntry,
    reason: '<value>',
);

$response = $sdk->timeCorrections->publicApiV1TimeCorrectionsCreate(
    company: 'Brakus and Sons',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.time_corrections.create" method="post" path="/companies/{company}/time-corrections" example="success" -->
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

$body = new Components\RequestTimeCorrectionRequest(
    timeEntryId: '93fb317c-c190-4b75-9e39-8204e90d76cb',
    kind: Components\RequestTimeCorrectionRequestKind::RemoveEntry,
    reason: '<value>',
);

$response = $sdk->timeCorrections->publicApiV1TimeCorrectionsCreate(
    company: 'Larson, Schaden and DuBuque',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID) of the company the request acts on. It must be in your credential's scope; read it from `GET /v1/me` (`data.scope[].id`). Never the tax ID.                                                                                                                                                                                                         |                                                                                                                                                                                                                                                                                                                                                                              |
| `body`                                                                                                                                                                                                                                                                                                                                                                       | [Components\RequestTimeCorrectionRequest](../../Models/Components/RequestTimeCorrectionRequest.md)                                                                                                                                                                                                                                                                           | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | N/A                                                                                                                                                                                                                                                                                                                                                                          |                                                                                                                                                                                                                                                                                                                                                                              |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                             | *?string*                                                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`. See the [Idempotency guide](/guides/idempotency).                                                        | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1TimeCorrectionsCreateResponse](../../Models/Operations/PublicApiV1TimeCorrectionsCreateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1TimeCorrectionsReject

Reject a pending correction request by its `id` (UUID v7) with a required `reason`, resolving it without touching the original time entry. A request that is not pending returns 422 (already resolved). Returns 200 with the resolved correction.

### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.time_corrections.reject" method="post" path="/companies/{company}/time-corrections/{time_correction}/reject" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1TimeCorrectionsRejectRequest(
    company: 'Okuneva, Terry and Little',
    timeCorrection: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RejectTimeCorrectionRequest(
        reason: '<value>',
    ),
);

$response = $sdk->timeCorrections->publicApiV1TimeCorrectionsReject(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: reject

<!-- UsageSnippet language="php" operationID="public-api.v1.time_corrections.reject" method="post" path="/companies/{company}/time-corrections/{time_correction}/reject" example="reject" -->
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

$request = new Operations\PublicApiV1TimeCorrectionsRejectRequest(
    company: 'Lesch - Sipes',
    timeCorrection: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RejectTimeCorrectionRequest(
        reason: 'La corrección no coincide con el registro de acceso al edificio.',
    ),
);

$response = $sdk->timeCorrections->publicApiV1TimeCorrectionsReject(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.time_corrections.reject" method="post" path="/companies/{company}/time-corrections/{time_correction}/reject" example="success" -->
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

$request = new Operations\PublicApiV1TimeCorrectionsRejectRequest(
    company: 'Keeling - Cassin',
    timeCorrection: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RejectTimeCorrectionRequest(
        reason: '<value>',
    ),
);

$response = $sdk->timeCorrections->publicApiV1TimeCorrectionsReject(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                | Type                                                                                                                     | Required                                                                                                                 | Description                                                                                                              |
| ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                               | [Operations\PublicApiV1TimeCorrectionsRejectRequest](../../Models/Operations/PublicApiV1TimeCorrectionsRejectRequest.md) | :heavy_check_mark:                                                                                                       | The request object to use for the request.                                                                               |

### Response

**[?Operations\PublicApiV1TimeCorrectionsRejectResponse](../../Models/Operations/PublicApiV1TimeCorrectionsRejectResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1TimeCorrectionsShow

Retrieve a single correction request by its `id` (UUID v7), including its derived status. A request belonging to another company returns 404 `correction_request_not_found` (anti-enumeration).

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.time_corrections.show" method="get" path="/companies/{company}/time-corrections/{time_correction}" example="success" -->
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



$response = $sdk->timeCorrections->publicApiV1TimeCorrectionsShow(
    company: 'Nitzsche, Miller and Bosco',
    timeCorrection: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID) of the company the request acts on. It must be in your credential's scope; read it from `GET /v1/me` (`data.scope[].id`). Never the tax ID.                                                                                                                                                                                                         |                                                                                                                                                                                                                                                                                                                                                                              |
| `timeCorrection`                                                                                                                                                                                                                                                                                                                                                             | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the time correction request, as returned in `id` by its list and detail responses.                                                                                                                                                                                                                                                            |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1TimeCorrectionsShowResponse](../../Models/Operations/PublicApiV1TimeCorrectionsShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |