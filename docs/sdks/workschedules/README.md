# WorkSchedules

## Overview

### Available Operations

* [publicApiV1WorkSchedulesArchive](#publicapiv1workschedulesarchive) - Archive a work schedule
* [publicApiV1WorkSchedulesAssign](#publicapiv1workschedulesassign) - Assign a schedule to an employee
* [publicApiV1WorkSchedulesCreate](#publicapiv1workschedulescreate) - Create a work schedule
* [publicApiV1WorkSchedulesList](#publicapiv1workscheduleslist) - List all work schedules
* [publicApiV1WorkSchedulesEmployeeSchedule](#publicapiv1workschedulesemployeeschedule) - Get an employee’s schedule on a date
* [publicApiV1WorkSchedulesStats](#publicapiv1workschedulesstats) - Get work schedule stats
* [publicApiV1WorkSchedulesAssignments](#publicapiv1workschedulesassignments) - List a schedule’s assignments
* [publicApiV1WorkSchedulesShow](#publicapiv1workschedulesshow) - Retrieve a work schedule
* [publicApiV1WorkSchedulesUpdate](#publicapiv1workschedulesupdate) - Update a work schedule
* [publicApiV1WorkSchedulesUnarchive](#publicapiv1workschedulesunarchive) - Unarchive a work schedule
* [publicApiV1WorkSchedulesUnassign](#publicapiv1workschedulesunassign) - Unassign a schedule from an employee

## publicApiV1WorkSchedulesArchive

Archive a work schedule (transition `active` → `archived`), retiring it from use while preserving it. No request body. Returns 422 if it is already archived. Reversible via unarchive.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.archive" method="post" path="/companies/{company}/work-schedules/{schedule}/archive" example="success" -->
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



$response = $sdk->workSchedules->publicApiV1WorkSchedulesArchive(
    company: 'Daniel - Frami',
    schedule: '<value>',
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
| `schedule`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Public identifier (UUID v7) of the work schedule.                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                               | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |

### Response

**[?Operations\PublicApiV1WorkSchedulesArchiveResponse](../../Models/Operations/PublicApiV1WorkSchedulesArchiveResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1WorkSchedulesAssign

Assign the work schedule to an employee with an effective start date; the previous open assignment is closed and history is kept.
- `employee_id` and `effective_from` (`Y-m-d`) are required.
- An unknown employee returns 422 `assigned_employee_not_found`; moving back the start of an assignment already in effect returns 422.
- Returns the created assignment.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.assign" method="post" path="/companies/{company}/work-schedules/{schedule}/assign" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1WorkSchedulesAssignRequest(
    company: 'c79fb215-b043-460c-87f2-620747dc1e0b',
    schedule: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AssignScheduleRequest(
        employeeId: 'c79fb215-b043-460c-87f2-620747dc1e0b',
        effectiveFrom: LocalDate::parse('2025-09-15'),
    ),
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesAssign(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: assign

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.assign" method="post" path="/companies/{company}/work-schedules/{schedule}/assign" example="assign" -->
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

$request = new Operations\PublicApiV1WorkSchedulesAssignRequest(
    company: 'Koepp, Marquardt and Zemlak',
    schedule: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AssignScheduleRequest(
        employeeId: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8b01',
        effectiveFrom: LocalDate::parse('2026-06-01'),
    ),
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesAssign(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.assign" method="post" path="/companies/{company}/work-schedules/{schedule}/assign" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1WorkSchedulesAssignRequest(
    company: 'e910dae8-7ebb-4ad3-9c1e-0e077fbbc4d6',
    schedule: '19805bb8-3095-44c8-a0ff-7af69e990175',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AssignScheduleRequest(
        employeeId: 'c79fb215-b043-460c-87f2-620747dc1e0b',
        effectiveFrom: LocalDate::parse('2025-09-15'),
    ),
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesAssign(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.assign" method="post" path="/companies/{company}/work-schedules/{schedule}/assign" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1WorkSchedulesAssignRequest(
    company: '9b210684-1309-4761-b268-6ad503b03c9e',
    schedule: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AssignScheduleRequest(
        employeeId: 'c79fb215-b043-460c-87f2-620747dc1e0b',
        effectiveFrom: LocalDate::parse('2025-09-15'),
    ),
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesAssign(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.assign" method="post" path="/companies/{company}/work-schedules/{schedule}/assign" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1WorkSchedulesAssignRequest(
    company: 'Batz, Haag and Feeney',
    schedule: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AssignScheduleRequest(
        employeeId: 'c79fb215-b043-460c-87f2-620747dc1e0b',
        effectiveFrom: LocalDate::parse('2025-09-15'),
    ),
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesAssign(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.assign" method="post" path="/companies/{company}/work-schedules/{schedule}/assign" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1WorkSchedulesAssignRequest(
    company: 'c79fb215-b043-460c-87f2-620747dc1e0b',
    schedule: '9b210684-1309-4761-b268-6ad503b03c9e',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AssignScheduleRequest(
        employeeId: 'c79fb215-b043-460c-87f2-620747dc1e0b',
        effectiveFrom: LocalDate::parse('2025-09-15'),
    ),
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesAssign(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.assign" method="post" path="/companies/{company}/work-schedules/{schedule}/assign" example="success" -->
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

$request = new Operations\PublicApiV1WorkSchedulesAssignRequest(
    company: 'Corwin, Carter and Heller',
    schedule: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AssignScheduleRequest(
        employeeId: 'c79fb215-b043-460c-87f2-620747dc1e0b',
        effectiveFrom: LocalDate::parse('2025-09-15'),
    ),
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesAssign(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.assign" method="post" path="/companies/{company}/work-schedules/{schedule}/assign" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1WorkSchedulesAssignRequest(
    company: 'fde22a81-fb9d-43a6-888b-c4cccdbab1c8',
    schedule: 'fcb07512-c151-461c-aac1-622b4aa1edf2',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AssignScheduleRequest(
        employeeId: 'c79fb215-b043-460c-87f2-620747dc1e0b',
        effectiveFrom: LocalDate::parse('2025-09-15'),
    ),
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesAssign(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                            | Type                                                                                                                 | Required                                                                                                             | Description                                                                                                          |
| -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                           | [Operations\PublicApiV1WorkSchedulesAssignRequest](../../Models/Operations/PublicApiV1WorkSchedulesAssignRequest.md) | :heavy_check_mark:                                                                                                   | The request object to use for the request.                                                                           |

### Response

**[?Operations\PublicApiV1WorkSchedulesAssignResponse](../../Models/Operations/PublicApiV1WorkSchedulesAssignResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1WorkSchedulesCreate

Create a weekly work schedule for your company (resolved from the API key).
- `name` and `week_pattern` are required; `mode` defaults to `validated`. `week_pattern` lists ISO weekdays (1..7) with ordered, non-overlapping `HH:MM` ranges (empty `ranges` = rest day).
- Returns the schedule with its `id`; `weekly_hours` is derived.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.create" method="post" path="/companies/{company}/work-schedules" example="api_key_revoked" -->
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

$body = new Components\CreateWeeklyScheduleRequest(
    name: '<value>',
    mode: Components\CreateWeeklyScheduleRequestMode::Validated,
    weekPattern: [],
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesCreate(
    company: '00d63127-485d-4ec7-9497-08d3aa5bb6e4',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.create" method="post" path="/companies/{company}/work-schedules" example="factuarea_version_invalid_format" -->
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

$body = new Components\CreateWeeklyScheduleRequest(
    name: '<value>',
    mode: Components\CreateWeeklyScheduleRequestMode::Validated,
    weekPattern: [],
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesCreate(
    company: '7dbdf38c-e88e-4e41-87d0-77d356f50f85',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.create" method="post" path="/companies/{company}/work-schedules" example="invalid_api_key" -->
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

$body = new Components\CreateWeeklyScheduleRequest(
    name: '<value>',
    mode: Components\CreateWeeklyScheduleRequestMode::Validated,
    weekPattern: [],
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesCreate(
    company: '7dbdf38c-e88e-4e41-87d0-77d356f50f85',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.create" method="post" path="/companies/{company}/work-schedules" example="missing_api_key" -->
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

$body = new Components\CreateWeeklyScheduleRequest(
    name: '<value>',
    mode: Components\CreateWeeklyScheduleRequestMode::Validated,
    weekPattern: [],
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesCreate(
    company: 'Kohler - Green',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.create" method="post" path="/companies/{company}/work-schedules" example="retired_active_profile_header" -->
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

$body = new Components\CreateWeeklyScheduleRequest(
    name: '<value>',
    mode: Components\CreateWeeklyScheduleRequestMode::Validated,
    weekPattern: [],
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesCreate(
    company: '00d63127-485d-4ec7-9497-08d3aa5bb6e4',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.create" method="post" path="/companies/{company}/work-schedules" example="success" -->
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

$body = new Components\CreateWeeklyScheduleRequest(
    name: '<value>',
    mode: Components\CreateWeeklyScheduleRequestMode::Validated,
    weekPattern: [],
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesCreate(
    company: 'Ebert Group',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.create" method="post" path="/companies/{company}/work-schedules" example="unsupported_api_version" -->
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

$body = new Components\CreateWeeklyScheduleRequest(
    name: '<value>',
    mode: Components\CreateWeeklyScheduleRequestMode::Validated,
    weekPattern: [],
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesCreate(
    company: 'adc3da9c-deb5-4f42-aff2-21d3febd712b',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: weekly_schedule

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.create" method="post" path="/companies/{company}/work-schedules" example="weekly_schedule" -->
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

$body = new Components\CreateWeeklyScheduleRequest(
    name: 'Jornada estándar oficina',
    mode: Components\CreateWeeklyScheduleRequestMode::Validated,
    weekPattern: [
        new Components\CreateWeeklyScheduleRequestWeekPattern(
            day: 1,
            ranges: [
                new Components\CreateWeeklyScheduleRequestRange(
                    start: '09:00',
                    end: '14:00',
                ),
                new Components\CreateWeeklyScheduleRequestRange(
                    start: '15:00',
                    end: '18:00',
                ),
            ],
        ),
        new Components\CreateWeeklyScheduleRequestWeekPattern(
            day: 2,
            ranges: [
                new Components\CreateWeeklyScheduleRequestRange(
                    start: '09:00',
                    end: '14:00',
                ),
                new Components\CreateWeeklyScheduleRequestRange(
                    start: '15:00',
                    end: '18:00',
                ),
            ],
        ),
        new Components\CreateWeeklyScheduleRequestWeekPattern(
            day: 3,
            ranges: [
                new Components\CreateWeeklyScheduleRequestRange(
                    start: '09:00',
                    end: '14:00',
                ),
                new Components\CreateWeeklyScheduleRequestRange(
                    start: '15:00',
                    end: '18:00',
                ),
            ],
        ),
        new Components\CreateWeeklyScheduleRequestWeekPattern(
            day: 4,
            ranges: [
                new Components\CreateWeeklyScheduleRequestRange(
                    start: '09:00',
                    end: '14:00',
                ),
                new Components\CreateWeeklyScheduleRequestRange(
                    start: '15:00',
                    end: '18:00',
                ),
            ],
        ),
        new Components\CreateWeeklyScheduleRequestWeekPattern(
            day: 5,
            ranges: [
                new Components\CreateWeeklyScheduleRequestRange(
                    start: '08:00',
                    end: '15:00',
                    flexible: true,
                    requiredMinutes: 360,
                ),
            ],
        ),
        new Components\CreateWeeklyScheduleRequestWeekPattern(
            day: 6,
            ranges: [],
        ),
        new Components\CreateWeeklyScheduleRequestWeekPattern(
            day: 7,
            ranges: [],
        ),
    ],
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesCreate(
    company: 'Beer - Strosin',
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
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [Components\CreateWeeklyScheduleRequest](../../Models/Components/CreateWeeklyScheduleRequest.md)                                                                                                                                                                                                                                                                                                                                                                                                                            | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                               | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |

### Response

**[?Operations\PublicApiV1WorkSchedulesCreateResponse](../../Models/Operations/PublicApiV1WorkSchedulesCreateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1WorkSchedulesList

List the weekly work schedules of your company with cursor-based pagination. Supports filtering by `status` (`active`/`archived`) and `mode` (`validated`/`real_clocking`), plus free-text `search` over the schedule name.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.list" method="get" path="/companies/{company}/work-schedules" example="success" -->
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

$request = new Operations\PublicApiV1WorkSchedulesListRequest(
    company: 'Grimes - Langosh',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                        | Type                                                                                                             | Required                                                                                                         | Description                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                       | [Operations\PublicApiV1WorkSchedulesListRequest](../../Models/Operations/PublicApiV1WorkSchedulesListRequest.md) | :heavy_check_mark:                                                                                               | The request object to use for the request.                                                                       |

### Response

**[?Operations\PublicApiV1WorkSchedulesListResponse](../../Models/Operations/PublicApiV1WorkSchedulesListResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1WorkSchedulesEmployeeSchedule

Resolve the work schedule in effect for an employee on a day; `date` (`Y-m-d`) is optional and defaults to today.
- Returns the schedule, not the assignment.
- 404 `schedule_assignment_not_found` if none is in effect (or the employee belongs to another company); 422 for an invalid `date`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.employee_schedule" method="get" path="/companies/{company}/work-schedules/employee/{employee}" example="success" -->
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



$response = $sdk->workSchedules->publicApiV1WorkSchedulesEmployeeSchedule(
    company: 'Schmitt, McClure and Rogahn',
    employee: '<value>',
    date: LocalDate::parse('2026-10-16'),
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
| `employee`                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the employee.                                                                                                                                                                                                                                                                                                                                 |                                                                                                                                                                                                                                                                                                                                                                              |
| `date`                                                                                                                                                                                                                                                                                                                                                                       | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Date (Y-m-d) on which the employee's effective schedule is resolved; defaults to today when omitted.                                                                                                                                                                                                                                                                         | 2026-10-16                                                                                                                                                                                                                                                                                                                                                                   |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1WorkSchedulesEmployeeScheduleResponse](../../Models/Operations/PublicApiV1WorkSchedulesEmployeeScheduleResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1WorkSchedulesStats

Aggregated KPIs for your work schedules: total count, active and archived counts, a breakdown by mode (`validated`/`real_clocking`) and the number of employees with an assigned schedule. Returned as `{ "data": … }`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.stats" method="get" path="/companies/{company}/work-schedules/stats" example="success" -->
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



$response = $sdk->workSchedules->publicApiV1WorkSchedulesStats(
    company: 'Johnston - Dietrich',
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

**[?Operations\PublicApiV1WorkSchedulesStatsResponse](../../Models/Operations/PublicApiV1WorkSchedulesStatsResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1WorkSchedulesAssignments

List the employees with an open assignment (`effective_to` = null) to this work schedule, as a flat list under `{ "data": [ … ] }`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.assignments" method="get" path="/companies/{company}/work-schedules/{schedule}/assignments" example="success" -->
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



$response = $sdk->workSchedules->publicApiV1WorkSchedulesAssignments(
    company: 'Bednar Group',
    schedule: '<value>',
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
| `schedule`                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the work schedule.                                                                                                                                                                                                                                                                                                                            |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1WorkSchedulesAssignmentsResponse](../../Models/Operations/PublicApiV1WorkSchedulesAssignmentsResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1WorkSchedulesShow

Retrieve a single work schedule by its `id` (UUID v7). A schedule belonging to another company returns 404 `work_schedule_not_found` (anti-enumeration).

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.show" method="get" path="/companies/{company}/work-schedules/{schedule}" example="success" -->
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



$response = $sdk->workSchedules->publicApiV1WorkSchedulesShow(
    company: 'Mosciski, Pollich and Nienow',
    schedule: '<value>',
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
| `schedule`                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the work schedule.                                                                                                                                                                                                                                                                                                                            |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1WorkSchedulesShowResponse](../../Models/Operations/PublicApiV1WorkSchedulesShowResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1WorkSchedulesUpdate

Partially update a work schedule; an omitted field keeps its value.
- A sent `week_pattern` replaces the whole pattern and `weekly_hours` is recomputed.
- An invalid value returns 422 and changes nothing. Returns the updated schedule.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.update" method="patch" path="/companies/{company}/work-schedules/{schedule}" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1WorkSchedulesUpdateRequest(
    company: 'd734d209-541f-4c95-a2cf-7302b1696605',
    schedule: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateWeeklyScheduleRequest(
        name: '<value>',
        mode: Components\UpdateWeeklyScheduleRequestMode::RealClocking,
        weekPattern: [],
    ),
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.update" method="patch" path="/companies/{company}/work-schedules/{schedule}" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1WorkSchedulesUpdateRequest(
    company: '77d3bddb-d88e-48d3-a25b-f6ce33f220c9',
    schedule: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateWeeklyScheduleRequest(
        name: '<value>',
        mode: Components\UpdateWeeklyScheduleRequestMode::RealClocking,
        weekPattern: [],
    ),
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.update" method="patch" path="/companies/{company}/work-schedules/{schedule}" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1WorkSchedulesUpdateRequest(
    company: 'Haag - Casper',
    schedule: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateWeeklyScheduleRequest(
        name: '<value>',
        mode: Components\UpdateWeeklyScheduleRequestMode::RealClocking,
        weekPattern: [],
    ),
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.update" method="patch" path="/companies/{company}/work-schedules/{schedule}" example="success" -->
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

$request = new Operations\PublicApiV1WorkSchedulesUpdateRequest(
    company: 'D\'Amore, Bayer and Mosciski',
    schedule: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateWeeklyScheduleRequest(
        name: '<value>',
        mode: Components\UpdateWeeklyScheduleRequestMode::RealClocking,
        weekPattern: [],
    ),
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: weekly_schedule

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.update" method="patch" path="/companies/{company}/work-schedules/{schedule}" example="weekly_schedule" -->
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

$request = new Operations\PublicApiV1WorkSchedulesUpdateRequest(
    company: 'King, Dooley and Gorczany',
    schedule: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateWeeklyScheduleRequest(
        name: 'Jornada intensiva verano',
        mode: Components\UpdateWeeklyScheduleRequestMode::Validated,
        weekPattern: [
            new Components\UpdateWeeklyScheduleRequestWeekPattern(
                day: 1,
                ranges: [
                    new Components\UpdateWeeklyScheduleRequestRange(
                        start: '08:00',
                        end: '15:00',
                    ),
                ],
            ),
            new Components\UpdateWeeklyScheduleRequestWeekPattern(
                day: 2,
                ranges: [
                    new Components\UpdateWeeklyScheduleRequestRange(
                        start: '08:00',
                        end: '15:00',
                    ),
                ],
            ),
            new Components\UpdateWeeklyScheduleRequestWeekPattern(
                day: 3,
                ranges: [
                    new Components\UpdateWeeklyScheduleRequestRange(
                        start: '08:00',
                        end: '15:00',
                    ),
                ],
            ),
            new Components\UpdateWeeklyScheduleRequestWeekPattern(
                day: 4,
                ranges: [
                    new Components\UpdateWeeklyScheduleRequestRange(
                        start: '08:00',
                        end: '15:00',
                    ),
                ],
            ),
            new Components\UpdateWeeklyScheduleRequestWeekPattern(
                day: 5,
                ranges: [
                    new Components\UpdateWeeklyScheduleRequestRange(
                        start: '08:00',
                        end: '15:00',
                    ),
                ],
            ),
            new Components\UpdateWeeklyScheduleRequestWeekPattern(
                day: 6,
                ranges: [],
            ),
            new Components\UpdateWeeklyScheduleRequestWeekPattern(
                day: 7,
                ranges: [],
            ),
        ],
    ),
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                            | Type                                                                                                                 | Required                                                                                                             | Description                                                                                                          |
| -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                           | [Operations\PublicApiV1WorkSchedulesUpdateRequest](../../Models/Operations/PublicApiV1WorkSchedulesUpdateRequest.md) | :heavy_check_mark:                                                                                                   | The request object to use for the request.                                                                           |

### Response

**[?Operations\PublicApiV1WorkSchedulesUpdateResponse](../../Models/Operations/PublicApiV1WorkSchedulesUpdateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1WorkSchedulesUnarchive

Unarchive a work schedule (transition `archived` → `active`), returning it to use. No request body. Returns 422 if it is already active.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.unarchive" method="post" path="/companies/{company}/work-schedules/{schedule}/unarchive" example="success" -->
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



$response = $sdk->workSchedules->publicApiV1WorkSchedulesUnarchive(
    company: 'Pouros and Sons',
    schedule: '<value>',
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
| `schedule`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Public identifier (UUID v7) of the work schedule.                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                               | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |

### Response

**[?Operations\PublicApiV1WorkSchedulesUnarchiveResponse](../../Models/Operations/PublicApiV1WorkSchedulesUnarchiveResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1WorkSchedulesUnassign

Close the employee's open assignment to this schedule; `employee_id` is required and `effective_to` (`Y-m-d`) defaults to today.
- An assignment not yet started is cancelled and kept in history as an empty span.
- Closing an assignment in effect before its start returns 422; no open assignment returns 404. Responds 204.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.unassign" method="post" path="/companies/{company}/work-schedules/{schedule}/unassign" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1WorkSchedulesUnassignRequest(
    company: 'e55ef20b-e38d-4834-9804-0ffad781d3b3',
    schedule: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UnassignScheduleRequest(
        employeeId: 'e55ef20b-e38d-4834-9804-0ffad781d3b3',
    ),
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesUnassign(
    request: $request
);

if ($response->statusCode === 200) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.unassign" method="post" path="/companies/{company}/work-schedules/{schedule}/unassign" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1WorkSchedulesUnassignRequest(
    company: '0c0433f8-a5e7-47f7-b2c7-7b61989942aa',
    schedule: '80c3ec13-d6d7-4820-9a54-4a2810e69f3c',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UnassignScheduleRequest(
        employeeId: 'e55ef20b-e38d-4834-9804-0ffad781d3b3',
    ),
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesUnassign(
    request: $request
);

if ($response->statusCode === 200) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.unassign" method="post" path="/companies/{company}/work-schedules/{schedule}/unassign" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1WorkSchedulesUnassignRequest(
    company: 'f8d30550-04f1-42b3-b89d-c9c1cbef881b',
    schedule: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UnassignScheduleRequest(
        employeeId: 'e55ef20b-e38d-4834-9804-0ffad781d3b3',
    ),
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesUnassign(
    request: $request
);

if ($response->statusCode === 200) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.unassign" method="post" path="/companies/{company}/work-schedules/{schedule}/unassign" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1WorkSchedulesUnassignRequest(
    company: 'Connelly, Adams and Reichel',
    schedule: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UnassignScheduleRequest(
        employeeId: 'e55ef20b-e38d-4834-9804-0ffad781d3b3',
    ),
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesUnassign(
    request: $request
);

if ($response->statusCode === 200) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.unassign" method="post" path="/companies/{company}/work-schedules/{schedule}/unassign" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1WorkSchedulesUnassignRequest(
    company: 'f8d30550-04f1-42b3-b89d-c9c1cbef881b',
    schedule: '5a067bd7-bbdc-4f85-bc6b-ba214246f452',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UnassignScheduleRequest(
        employeeId: 'e55ef20b-e38d-4834-9804-0ffad781d3b3',
    ),
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesUnassign(
    request: $request
);

if ($response->statusCode === 200) {
    // handle response
}
```
### Example Usage: unassign

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.unassign" method="post" path="/companies/{company}/work-schedules/{schedule}/unassign" example="unassign" -->
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

$request = new Operations\PublicApiV1WorkSchedulesUnassignRequest(
    company: 'Hilll, Halvorson and Torphy',
    schedule: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UnassignScheduleRequest(
        employeeId: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8b01',
        effectiveTo: LocalDate::parse('2026-06-30'),
    ),
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesUnassign(
    request: $request
);

if ($response->statusCode === 200) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.work_schedules.unassign" method="post" path="/companies/{company}/work-schedules/{schedule}/unassign" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1WorkSchedulesUnassignRequest(
    company: 'd2520ca2-73c2-44bd-a007-17c8f1d54344',
    schedule: '1fd00d5e-842c-4f44-923f-a326eb910585',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UnassignScheduleRequest(
        employeeId: 'e55ef20b-e38d-4834-9804-0ffad781d3b3',
    ),
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesUnassign(
    request: $request
);

if ($response->statusCode === 200) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                | Type                                                                                                                     | Required                                                                                                                 | Description                                                                                                              |
| ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                               | [Operations\PublicApiV1WorkSchedulesUnassignRequest](../../Models/Operations/PublicApiV1WorkSchedulesUnassignRequest.md) | :heavy_check_mark:                                                                                                       | The request object to use for the request.                                                                               |

### Response

**[?Operations\PublicApiV1WorkSchedulesUnassignResponse](../../Models/Operations/PublicApiV1WorkSchedulesUnassignResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |