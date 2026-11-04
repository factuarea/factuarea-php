# Projects.Tasks

## Overview

### Available Operations

* [publicApiV1ProjectsTasksExport](#publicapiv1projectstasksexport) - Export project tasks
* [publicApiV1ProjectsTasksImport](#publicapiv1projectstasksimport) - Import tasks into a project

## publicApiV1ProjectsTasksExport

Export a project's tasks as a `factuarea.tasks.v1` JSON document with its labels, custom fields and comments.
- The document can be re-imported with `POST /v1/companies/{company}/projects/{project}/tasks/import`.
- Needs only `projects:read`, yet includes every task description and comment.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.tasks.export" method="get" path="/companies/{company}/projects/{project}/tasks/export" example="success" -->
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



$response = $sdk->projects->tasks->publicApiV1ProjectsTasksExport(
    company: 'Dach and Sons',
    project: '<value>',
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
| `project`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the project.                                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1ProjectsTasksExportResponse](../../Models/Operations/PublicApiV1ProjectsTasksExportResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1ProjectsTasksImport

Import tasks into a project from a JSON document (`factuarea.tasks.v1` or a supported format).
- Synchronous, max 200 tasks per call (more returns 422); the report lists `id`/`key` or `error`.
- `Idempotency-Key` required (422 `idempotency_key_required`); a new key creates a new copy.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.tasks.import" method="post" path="/companies/{company}/projects/{project}/tasks/import" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1ProjectsTasksImportRequest(
    company: '9585c9b4-54b1-49d9-b3f5-4a4ce3d51783',
    project: '9844d522-7f93-43e6-bf6d-a6c05b2ff9f5',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ImportProjectTasksV1Request(
        tasks: [
            [
                'key' => '<value>',
            ],
        ],
    ),
);

$response = $sdk->projects->tasks->publicApiV1ProjectsTasksImport(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.tasks.import" method="post" path="/companies/{company}/projects/{project}/tasks/import" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1ProjectsTasksImportRequest(
    company: 'Kessler and Sons',
    project: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ImportProjectTasksV1Request(
        tasks: [
            [
                'key' => '<value>',
            ],
        ],
    ),
);

$response = $sdk->projects->tasks->publicApiV1ProjectsTasksImport(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.tasks.import" method="post" path="/companies/{company}/projects/{project}/tasks/import" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1ProjectsTasksImportRequest(
    company: '54b87eb6-84de-4348-bf2c-94e0fdb8c166',
    project: 'fa02c578-4e70-4dbd-9c63-23ee0cde6e22',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ImportProjectTasksV1Request(
        tasks: [
            [
                'key' => '<value>',
            ],
        ],
    ),
);

$response = $sdk->projects->tasks->publicApiV1ProjectsTasksImport(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.tasks.import" method="post" path="/companies/{company}/projects/{project}/tasks/import" example="success" -->
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

$request = new Operations\PublicApiV1ProjectsTasksImportRequest(
    company: 'Gleichner LLC',
    project: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ImportProjectTasksV1Request(
        tasks: [
            [
                'key' => '<value>',
            ],
        ],
    ),
);

$response = $sdk->projects->tasks->publicApiV1ProjectsTasksImport(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.tasks.import" method="post" path="/companies/{company}/projects/{project}/tasks/import" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1ProjectsTasksImportRequest(
    company: 'e43e8152-1d13-4f0f-bc02-b82e009b8213',
    project: '3c1cc13d-e152-4d20-b222-b0dd40058cc7',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ImportProjectTasksV1Request(
        tasks: [
            [
                'key' => '<value>',
            ],
        ],
    ),
);

$response = $sdk->projects->tasks->publicApiV1ProjectsTasksImport(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                            | Type                                                                                                                 | Required                                                                                                             | Description                                                                                                          |
| -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                           | [Operations\PublicApiV1ProjectsTasksImportRequest](../../Models/Operations/PublicApiV1ProjectsTasksImportRequest.md) | :heavy_check_mark:                                                                                                   | The request object to use for the request.                                                                           |

### Response

**[?Operations\PublicApiV1ProjectsTasksImportResponse](../../Models/Operations/PublicApiV1ProjectsTasksImportResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |