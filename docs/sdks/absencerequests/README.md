# AbsenceRequests

## Overview

### Available Operations

* [publicApiV1AbsenceRequestsApprove](#publicapiv1absencerequestsapprove) - Approve an absence request
* [publicApiV1AbsenceRequestsCancel](#publicapiv1absencerequestscancel) - Cancel an absence request
* [publicApiV1AbsenceRequestsCreate](#publicapiv1absencerequestscreate) - Create an absence request
* [publicApiV1AbsenceRequestsList](#publicapiv1absencerequestslist) - List all absence requests
* [publicApiV1AbsenceRequestsReject](#publicapiv1absencerequestsreject) - Reject an absence request
* [publicApiV1AbsenceRequestsShow](#publicapiv1absencerequestsshow) - Retrieve an absence request

## publicApiV1AbsenceRequestsApprove

Approve a pending absence request (transition `pending` → `approved`), consuming the employee’s balance. No request body (an optional `note` is accepted). A reviewer cannot approve the request they themselves created (422). Returns the updated request.

### Example Usage: approve

<!-- UsageSnippet language="php" operationID="public-api.v1.absence-requests.approve" method="post" path="/companies/{company}/absence-requests/{absence_request}/approve" example="approve" -->
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

$request = new Operations\PublicApiV1AbsenceRequestsApproveRequest(
    company: 'Simonis, Hane and Kertzmann',
    absenceRequest: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ApproveAbsenceRequestRequest(
        note: 'Aprobada; saldo suficiente para el periodo solicitado.',
    ),
);

$response = $sdk->absenceRequests->publicApiV1AbsenceRequestsApprove(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.absence-requests.approve" method="post" path="/companies/{company}/absence-requests/{absence_request}/approve" example="success" -->
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

$request = new Operations\PublicApiV1AbsenceRequestsApproveRequest(
    company: 'Hoeger, Volkman and Schamberger',
    absenceRequest: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->absenceRequests->publicApiV1AbsenceRequestsApprove(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1AbsenceRequestsApproveRequest](../../Models/Operations/PublicApiV1AbsenceRequestsApproveRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1AbsenceRequestsApproveResponse](../../Models/Operations/PublicApiV1AbsenceRequestsApproveResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1AbsenceRequestsCancel

Cancel an absence request. If it was approved, the consumed balance is released back. No request body. A request belonging to another company returns 404 `absence_request_not_found` (anti-enumeration). Returns the updated request.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.absence-requests.cancel" method="post" path="/companies/{company}/absence-requests/{absence_request}/cancel" example="success" -->
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



$response = $sdk->absenceRequests->publicApiV1AbsenceRequestsCancel(
    company: 'Connelly - Dach',
    absenceRequest: '<value>',
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
| `absenceRequest`                                                                                                                                                                                                                                                                                                                                                             | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the absence request, as returned in `id` by its list and detail responses.                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                             | *?string*                                                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`. See the [Idempotency guide](/guides/idempotency).                                                        | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1AbsenceRequestsCancelResponse](../../Models/Operations/PublicApiV1AbsenceRequestsCancelResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 409, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1AbsenceRequestsCreate

Create an absence request for the authenticated company (resolved from the API key, never from the payload). `employee_id` (UUID v7) is required — an API key acts as a system, so the target employee must be given. `absence_type_id` (UUID v7) and the `start_date`/`end_date` range (`YYYY-MM-DD`, end on or after start) are required; `note` is optional. The requested amount is computed in working days minus the applicable public holidays. If the absence type does not require approval it is auto-approved and consumes the balance. Returns the created request with its generated `id` (UUID v7).

### Example Usage: absence_request

<!-- UsageSnippet language="php" operationID="public-api.v1.absence-requests.create" method="post" path="/companies/{company}/absence-requests" example="absence_request" -->
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

$body = new Components\CreateAbsenceRequestRequest(
    employeeId: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8b01',
    absenceTypeId: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8b30',
    startDate: LocalDate::parse('2026-08-01'),
    endDate: LocalDate::parse('2026-08-15'),
    note: 'Vacaciones de verano.',
);

$response = $sdk->absenceRequests->publicApiV1AbsenceRequestsCreate(
    company: 'Lakin - Howe',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.absence-requests.create" method="post" path="/companies/{company}/absence-requests" example="missing_api_key" -->
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

$body = new Components\CreateAbsenceRequestRequest(
    employeeId: 'e98b13b2-de72-4ebc-939a-d5c65fe0dead',
    absenceTypeId: 'f0273c95-cf77-4c44-9f55-0d9cf5fd81e2',
    startDate: LocalDate::parse('2025-04-03'),
    endDate: LocalDate::parse('2024-02-29'),
);

$response = $sdk->absenceRequests->publicApiV1AbsenceRequestsCreate(
    company: 'Smitham, Trantow and Schmidt',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.absence-requests.create" method="post" path="/companies/{company}/absence-requests" example="success" -->
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

$body = new Components\CreateAbsenceRequestRequest(
    employeeId: 'e98b13b2-de72-4ebc-939a-d5c65fe0dead',
    absenceTypeId: 'f0273c95-cf77-4c44-9f55-0d9cf5fd81e2',
    startDate: LocalDate::parse('2025-04-03'),
    endDate: LocalDate::parse('2024-02-29'),
);

$response = $sdk->absenceRequests->publicApiV1AbsenceRequestsCreate(
    company: 'Lemke - Strosin',
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
| `body`                                                                                                                                                                                                                                                                                                                                                                       | [Components\CreateAbsenceRequestRequest](../../Models/Components/CreateAbsenceRequestRequest.md)                                                                                                                                                                                                                                                                             | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | N/A                                                                                                                                                                                                                                                                                                                                                                          |                                                                                                                                                                                                                                                                                                                                                                              |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                             | *?string*                                                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`. See the [Idempotency guide](/guides/idempotency).                                                        | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1AbsenceRequestsCreateResponse](../../Models/Operations/PublicApiV1AbsenceRequestsCreateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1AbsenceRequestsList

List your company’s absence requests with cursor-based pagination. Supports filtering by `employee_id` (UUID v7), `absence_type_id` (UUID v7), `status` (`pending`/`approved`/`rejected`/`cancelled`) and by date range (`from`/`to`, `YYYY-MM-DD`).

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.absence-requests.list" method="get" path="/companies/{company}/absence-requests" example="success" -->
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

$request = new Operations\PublicApiV1AbsenceRequestsListRequest(
    company: 'Bosco, Senger and Morissette',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->absenceRequests->publicApiV1AbsenceRequestsList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                            | Type                                                                                                                 | Required                                                                                                             | Description                                                                                                          |
| -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                           | [Operations\PublicApiV1AbsenceRequestsListRequest](../../Models/Operations/PublicApiV1AbsenceRequestsListRequest.md) | :heavy_check_mark:                                                                                                   | The request object to use for the request.                                                                           |

### Response

**[?Operations\PublicApiV1AbsenceRequestsListResponse](../../Models/Operations/PublicApiV1AbsenceRequestsListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1AbsenceRequestsReject

Reject a pending absence request (transition `pending` → `rejected`). A `reason` is required (422 without it); rejecting neither consumes nor releases balance. Returns the updated request.

### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.absence-requests.reject" method="post" path="/companies/{company}/absence-requests/{absence_request}/reject" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1AbsenceRequestsRejectRequest(
    company: 'Kutch, Cremin and Senger',
    absenceRequest: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RejectAbsenceRequestRequest(
        reason: '<value>',
    ),
);

$response = $sdk->absenceRequests->publicApiV1AbsenceRequestsReject(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: reject

<!-- UsageSnippet language="php" operationID="public-api.v1.absence-requests.reject" method="post" path="/companies/{company}/absence-requests/{absence_request}/reject" example="reject" -->
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

$request = new Operations\PublicApiV1AbsenceRequestsRejectRequest(
    company: 'Shanahan - Hegmann',
    absenceRequest: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RejectAbsenceRequestRequest(
        reason: 'Coincide con el cierre trimestral; por favor, solicita otras fechas.',
    ),
);

$response = $sdk->absenceRequests->publicApiV1AbsenceRequestsReject(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.absence-requests.reject" method="post" path="/companies/{company}/absence-requests/{absence_request}/reject" example="success" -->
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

$request = new Operations\PublicApiV1AbsenceRequestsRejectRequest(
    company: 'Littel - Cartwright',
    absenceRequest: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RejectAbsenceRequestRequest(
        reason: '<value>',
    ),
);

$response = $sdk->absenceRequests->publicApiV1AbsenceRequestsReject(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                | Type                                                                                                                     | Required                                                                                                                 | Description                                                                                                              |
| ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                               | [Operations\PublicApiV1AbsenceRequestsRejectRequest](../../Models/Operations/PublicApiV1AbsenceRequestsRejectRequest.md) | :heavy_check_mark:                                                                                                       | The request object to use for the request.                                                                               |

### Response

**[?Operations\PublicApiV1AbsenceRequestsRejectResponse](../../Models/Operations/PublicApiV1AbsenceRequestsRejectResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1AbsenceRequestsShow

Retrieve a single absence request by its `id` (UUID v7), including its type, date range, requested amount, lifecycle status and review fields. A request belonging to another company returns 404 `absence_request_not_found` (anti-enumeration).

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.absence-requests.show" method="get" path="/companies/{company}/absence-requests/{absence_request}" example="success" -->
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



$response = $sdk->absenceRequests->publicApiV1AbsenceRequestsShow(
    company: 'Hayes - West',
    absenceRequest: '<value>',
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
| `absenceRequest`                                                                                                                                                                                                                                                                                                                                                             | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the absence request, as returned in `id` by its list and detail responses.                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1AbsenceRequestsShowResponse](../../Models/Operations/PublicApiV1AbsenceRequestsShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |