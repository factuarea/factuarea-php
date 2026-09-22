# WorkSchedules

## Overview

### Available Operations

* [publicApiV1WorkSchedulesArchive](#publicapiv1workschedulesarchive) - Archive a work schedule
* [publicApiV1WorkSchedulesAssign](#publicapiv1workschedulesassign) - Assign a schedule to an employee
* [publicApiV1WorkSchedulesCreate](#publicapiv1workschedulescreate) - Create a work schedule
* [publicApiV1WorkSchedulesList](#publicapiv1workscheduleslist) - List all work schedules
* [publicApiV1WorkSchedulesEmployeeSchedule](#publicapiv1workschedulesemployeeschedule) - Get an employee’s current schedule
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID) of the company the request acts on. It must be in your credential's scope; read it from `GET /v1/me` (`data.scope[].id`). Never the tax ID.                                                                                                                                                                                                         |                                                                                                                                                                                                                                                                                                                                                                              |
| `schedule`                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the work schedule, as returned in `id` by its list and detail responses.                                                                                                                                                                                                                                                                      |                                                                                                                                                                                                                                                                                                                                                                              |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                             | *?string*                                                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`. See the [Idempotency guide](/guides/idempotency).                                                        | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1WorkSchedulesArchiveResponse](../../Models/Operations/PublicApiV1WorkSchedulesArchiveResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 409, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1WorkSchedulesAssign

Assign the work schedule to an employee with an effective start date. `employee_id` (UUID v7, must belong to your company) and `effective_from` (`Y-m-d`) are required. Assigning closes the employee’s previously open assignment and opens the new one (an employee has at most one open assignment; history is preserved). An unknown employee returns 422 `assigned_employee_not_found`; an unknown schedule returns 404. Returns the created assignment.

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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1WorkSchedulesAssignRequest(
    company: 'Doyle - Bayer',
    schedule: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AssignScheduleRequest(
        employeeId: 'b215b043-60c7-4f26-8207-47dc1e0b29b2',
        effectiveFrom: LocalDate::parse('2024-01-11'),
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1WorkSchedulesAssignRequest(
    company: 'Lowe Inc',
    schedule: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AssignScheduleRequest(
        employeeId: 'b215b043-60c7-4f26-8207-47dc1e0b29b2',
        effectiveFrom: LocalDate::parse('2024-01-11'),
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

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1WorkSchedulesCreate

Create a weekly work schedule for the authenticated company (resolved from the API key, never from the payload). `name` and `week_pattern` are required; `mode` defaults to `validated`. The `week_pattern` is a list of weekdays (ISO 8601 1..7) each with its ordered, non-overlapping `HH:MM` time ranges (an empty `ranges` means a rest day). Returns the created schedule with its generated `id` (UUID v7); `weekly_hours` is derived from the pattern.

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
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\CreateWeeklyScheduleRequest(
    name: '<value>',
    mode: Components\CreateWeeklyScheduleRequestMode::Validated,
    weekPattern: [],
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesCreate(
    company: 'Lakin - Hermiston',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\CreateWeeklyScheduleRequest(
    name: '<value>',
    mode: Components\CreateWeeklyScheduleRequestMode::Validated,
    weekPattern: [],
);

$response = $sdk->workSchedules->publicApiV1WorkSchedulesCreate(
    company: 'Cronin - Kohler',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID) of the company the request acts on. It must be in your credential's scope; read it from `GET /v1/me` (`data.scope[].id`). Never the tax ID.                                                                                                                                                                                                         |                                                                                                                                                                                                                                                                                                                                                                              |
| `body`                                                                                                                                                                                                                                                                                                                                                                       | [Components\CreateWeeklyScheduleRequest](../../Models/Components/CreateWeeklyScheduleRequest.md)                                                                                                                                                                                                                                                                             | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | N/A                                                                                                                                                                                                                                                                                                                                                                          |                                                                                                                                                                                                                                                                                                                                                                              |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                             | *?string*                                                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`. See the [Idempotency guide](/guides/idempotency).                                                        | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1WorkSchedulesCreateResponse](../../Models/Operations/PublicApiV1WorkSchedulesCreateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1WorkSchedulesEmployeeSchedule

Resolve the work schedule currently in effect (today) for an employee by its `id` (UUID v7). Returns 404 `schedule_assignment_not_found` when the employee has no schedule in effect (or belongs to another company). The result is the resolved schedule (`id` = UUID v7 of the schedule), not the assignment.

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
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->workSchedules->publicApiV1WorkSchedulesEmployeeSchedule(
    company: 'Berge, Rutherford and Baumbach',
    employee: '<value>',
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
| `employee`                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the employee, as returned in `id` by its list and detail responses.                                                                                                                                                                                                                                                                           |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1WorkSchedulesEmployeeScheduleResponse](../../Models/Operations/PublicApiV1WorkSchedulesEmployeeScheduleResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID) of the company the request acts on. It must be in your credential's scope; read it from `GET /v1/me` (`data.scope[].id`). Never the tax ID.                                                                                                                                                                                                         |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1WorkSchedulesStatsResponse](../../Models/Operations/PublicApiV1WorkSchedulesStatsResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID) of the company the request acts on. It must be in your credential's scope; read it from `GET /v1/me` (`data.scope[].id`). Never the tax ID.                                                                                                                                                                                                         |                                                                                                                                                                                                                                                                                                                                                                              |
| `schedule`                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the work schedule, as returned in `id` by its list and detail responses.                                                                                                                                                                                                                                                                      |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1WorkSchedulesAssignmentsResponse](../../Models/Operations/PublicApiV1WorkSchedulesAssignmentsResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID) of the company the request acts on. It must be in your credential's scope; read it from `GET /v1/me` (`data.scope[].id`). Never the tax ID.                                                                                                                                                                                                         |                                                                                                                                                                                                                                                                                                                                                                              |
| `schedule`                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the work schedule, as returned in `id` by its list and detail responses.                                                                                                                                                                                                                                                                      |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1WorkSchedulesShowResponse](../../Models/Operations/PublicApiV1WorkSchedulesShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1WorkSchedulesUpdate

Fully replace a work schedule: `name`, `mode` and the complete `week_pattern` are required (there is no partial update of the pattern). `weekly_hours` is recomputed from the new pattern. Returns the updated schedule.

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
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1WorkSchedulesUpdateRequest(
    company: 'Casper - Wisozk',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1WorkSchedulesUpdateRequest(
    company: 'Mosciski - Hansen',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID) of the company the request acts on. It must be in your credential's scope; read it from `GET /v1/me` (`data.scope[].id`). Never the tax ID.                                                                                                                                                                                                         |                                                                                                                                                                                                                                                                                                                                                                              |
| `schedule`                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the work schedule, as returned in `id` by its list and detail responses.                                                                                                                                                                                                                                                                      |                                                                                                                                                                                                                                                                                                                                                                              |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                             | *?string*                                                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`. See the [Idempotency guide](/guides/idempotency).                                                        | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1WorkSchedulesUnarchiveResponse](../../Models/Operations/PublicApiV1WorkSchedulesUnarchiveResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 409, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1WorkSchedulesUnassign

Close the employee’s open assignment to this schedule. `employee_id` (UUID v7) is required; `effective_to` (`Y-m-d`) is optional and defaults to today. Returns 404 when there is no open assignment. Responds 204 No Content.

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
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1WorkSchedulesUnassignRequest(
    company: 'Heller - Harber',
    schedule: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UnassignScheduleRequest(
        employeeId: 'f20be38d-8348-4040-8ffa-d781d3b34f8d',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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

### Parameters

| Parameter                                                                                                                | Type                                                                                                                     | Required                                                                                                                 | Description                                                                                                              |
| ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                               | [Operations\PublicApiV1WorkSchedulesUnassignRequest](../../Models/Operations/PublicApiV1WorkSchedulesUnassignRequest.md) | :heavy_check_mark:                                                                                                       | The request object to use for the request.                                                                               |

### Response

**[?Operations\PublicApiV1WorkSchedulesUnassignResponse](../../Models/Operations/PublicApiV1WorkSchedulesUnassignResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |