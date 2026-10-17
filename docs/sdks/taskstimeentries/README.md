# Tasks.TimeEntries

## Overview

### Available Operations

* [publicApiV1TasksTimeEntriesDelete](#publicapiv1taskstimeentriesdelete) - Delete a task time entry
* [publicApiV1TasksTimeEntriesShow](#publicapiv1taskstimeentriesshow) - Retrieve a task time entry
* [publicApiV1TasksTimeEntriesUpdate](#publicapiv1taskstimeentriesupdate) - Update a task time entry
* [publicApiV1TasksTimeEntriesList](#publicapiv1taskstimeentrieslist) - List task time entries
* [publicApiV1TasksTimeEntriesCreate](#publicapiv1taskstimeentriescreate) - Create a task time entry

## publicApiV1TasksTimeEntriesDelete

Permanently delete a time entry. Invoiced entries cannot be deleted (422 `task_time_entry_invoiced`). Irreversible: there is no trash or restore, so send an `Idempotency-Key` header (required) to retry safely.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.time_entries.delete" method="delete" path="/companies/{company}/tasks/{task}/time-entries/{time_entry}" example="success" -->
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

$request = new Operations\PublicApiV1TasksTimeEntriesDeleteRequest(
    company: 'Huel and Sons',
    task: '<value>',
    timeEntry: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->tasks->timeEntries->publicApiV1TasksTimeEntriesDelete(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1TasksTimeEntriesDeleteRequest](../../Models/Operations/PublicApiV1TasksTimeEntriesDeleteRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1TasksTimeEntriesDeleteResponse](../../Models/Operations/PublicApiV1TasksTimeEntriesDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1TasksTimeEntriesShow

Retrieve one time entry of a task. A running timer appears as an entry with `ended_at: null`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.time_entries.show" method="get" path="/companies/{company}/tasks/{task}/time-entries/{time_entry}" example="success" -->
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



$response = $sdk->tasks->timeEntries->publicApiV1TasksTimeEntriesShow(
    company: 'Gutkowski LLC',
    task: '<value>',
    timeEntry: '<value>',
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
| `task`                                                                                                                                                                                                                                                                                                                                                                       | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the task.                                                                                                                                                                                                                                                                                                                                     |                                                                                                                                                                                                                                                                                                                                                                              |
| `timeEntry`                                                                                                                                                                                                                                                                                                                                                                  | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the time entry.                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1TasksTimeEntriesShowResponse](../../Models/Operations/PublicApiV1TasksTimeEntriesShowResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1TasksTimeEntriesUpdate

Partially update a time entry: `started_at`, `ended_at`, `description` and `billable`. Invoiced entries cannot be edited (422 `task_time_entry_invoiced`) until the draft invoice is deleted.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.time_entries.update" method="patch" path="/companies/{company}/tasks/{task}/time-entries/{time_entry}" example="success" -->
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

$request = new Operations\PublicApiV1TasksTimeEntriesUpdateRequest(
    company: 'Murray Inc',
    task: '<value>',
    timeEntry: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->tasks->timeEntries->publicApiV1TasksTimeEntriesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1TasksTimeEntriesUpdateRequest](../../Models/Operations/PublicApiV1TasksTimeEntriesUpdateRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1TasksTimeEntriesUpdateResponse](../../Models/Operations/PublicApiV1TasksTimeEntriesUpdateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1TasksTimeEntriesList

List the time logged on a task, newest first, with author, period, duration, `billable` flag and whether it was invoiced.
- It is task time, not the working-day clock-ins at `/v1/companies/{company}/time-entries`.
- Cursor-paginated: pass `next_cursor` as `starting_after`; `has_more` tells if there are more.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.time_entries.list" method="get" path="/companies/{company}/tasks/{task}/time-entries" example="success" -->
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

$request = new Operations\PublicApiV1TasksTimeEntriesListRequest(
    company: 'Conroy Group',
    task: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->tasks->timeEntries->publicApiV1TasksTimeEntriesList(
    request: $request
);

if ($response->taskTimeEntryList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                              | Type                                                                                                                   | Required                                                                                                               | Description                                                                                                            |
| ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                             | [Operations\PublicApiV1TasksTimeEntriesListRequest](../../Models/Operations/PublicApiV1TasksTimeEntriesListRequest.md) | :heavy_check_mark:                                                                                                     | The request object to use for the request.                                                                             |

### Response

**[?Operations\PublicApiV1TasksTimeEntriesListResponse](../../Models/Operations/PublicApiV1TasksTimeEntriesListResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1TasksTimeEntriesCreate

Log a closed period of work on a task (`started_at`, `ended_at`, ISO 8601), with an optional `description` and `billable` flag (default `true`). The period must end after it starts (422 `invalid_time_entry_period`). This is task time, unrelated to the working-day records of the time tracking module.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.time_entries.create" method="post" path="/companies/{company}/tasks/{task}/time-entries" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1TasksTimeEntriesCreateRequest(
    company: '9a984b97-e8c2-4395-a340-ca8bd2ae6bb8',
    task: '028a1547-6645-4b7b-919a-bc3add7af4ad',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\LogTaskTimeV1Request(
        startedAt: '<value>',
        endedAt: '<value>',
    ),
);

$response = $sdk->tasks->timeEntries->publicApiV1TasksTimeEntriesCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.time_entries.create" method="post" path="/companies/{company}/tasks/{task}/time-entries" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1TasksTimeEntriesCreateRequest(
    company: 'Jenkins Inc',
    task: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\LogTaskTimeV1Request(
        startedAt: '<value>',
        endedAt: '<value>',
    ),
);

$response = $sdk->tasks->timeEntries->publicApiV1TasksTimeEntriesCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.time_entries.create" method="post" path="/companies/{company}/tasks/{task}/time-entries" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1TasksTimeEntriesCreateRequest(
    company: '969664ea-3059-4e24-8d43-0aee6cda8c23',
    task: '5c27c96b-750c-42d1-af51-b9fc0447e3f4',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\LogTaskTimeV1Request(
        startedAt: '<value>',
        endedAt: '<value>',
    ),
);

$response = $sdk->tasks->timeEntries->publicApiV1TasksTimeEntriesCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.time_entries.create" method="post" path="/companies/{company}/tasks/{task}/time-entries" example="success" -->
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

$request = new Operations\PublicApiV1TasksTimeEntriesCreateRequest(
    company: 'Howe LLC',
    task: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\LogTaskTimeV1Request(
        startedAt: '<value>',
        endedAt: '<value>',
    ),
);

$response = $sdk->tasks->timeEntries->publicApiV1TasksTimeEntriesCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.time_entries.create" method="post" path="/companies/{company}/tasks/{task}/time-entries" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1TasksTimeEntriesCreateRequest(
    company: 'e71cf90f-d6e6-4e62-a895-8659fe823398',
    task: '4f6ba977-d689-42e7-972d-27373540b355',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\LogTaskTimeV1Request(
        startedAt: '<value>',
        endedAt: '<value>',
    ),
);

$response = $sdk->tasks->timeEntries->publicApiV1TasksTimeEntriesCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1TasksTimeEntriesCreateRequest](../../Models/Operations/PublicApiV1TasksTimeEntriesCreateRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1TasksTimeEntriesCreateResponse](../../Models/Operations/PublicApiV1TasksTimeEntriesCreateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |