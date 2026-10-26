# Tasks.Labels

## Overview

### Available Operations

* [publicApiV1TasksLabelsAssign](#publicapiv1taskslabelsassign) - Assign a label to a task
* [publicApiV1TasksLabelsUnassign](#publicapiv1taskslabelsunassign) - Remove a label from a task

## publicApiV1TasksLabelsAssign

Add a label of your company catalog (`label_id`) to a task. Adding a label the task already has is a no-op.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.labels.assign" method="post" path="/companies/{company}/tasks/{task}/labels" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1TasksLabelsAssignRequest(
    company: 'd2b2b15d-2256-43c5-ba04-7bf3f862d9fe',
    task: '8f2a2a9c-ac4e-4fbe-9223-ff7e1725471a',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AssignTaskLabelV1Request(
        labelId: 'be79d59e-da81-496f-83d9-bc3fef3e8f9c',
    ),
);

$response = $sdk->tasks->labels->publicApiV1TasksLabelsAssign(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.labels.assign" method="post" path="/companies/{company}/tasks/{task}/labels" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1TasksLabelsAssignRequest(
    company: 'Heller, Mills and Tillman',
    task: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AssignTaskLabelV1Request(
        labelId: 'be79d59e-da81-496f-83d9-bc3fef3e8f9c',
    ),
);

$response = $sdk->tasks->labels->publicApiV1TasksLabelsAssign(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.labels.assign" method="post" path="/companies/{company}/tasks/{task}/labels" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1TasksLabelsAssignRequest(
    company: 'be79d59e-da81-496f-83d9-bc3fef3e8f9c',
    task: '09012fe4-110a-4c38-8791-d6726997e4f7',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AssignTaskLabelV1Request(
        labelId: 'be79d59e-da81-496f-83d9-bc3fef3e8f9c',
    ),
);

$response = $sdk->tasks->labels->publicApiV1TasksLabelsAssign(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.labels.assign" method="post" path="/companies/{company}/tasks/{task}/labels" example="success" -->
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

$request = new Operations\PublicApiV1TasksLabelsAssignRequest(
    company: 'Towne, Krajcik and McLaughlin',
    task: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AssignTaskLabelV1Request(
        labelId: 'be79d59e-da81-496f-83d9-bc3fef3e8f9c',
    ),
);

$response = $sdk->tasks->labels->publicApiV1TasksLabelsAssign(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.labels.assign" method="post" path="/companies/{company}/tasks/{task}/labels" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1TasksLabelsAssignRequest(
    company: '7b4f63c8-0012-4b15-a121-92e611f6474c',
    task: '53657fc3-1bba-49d0-a356-5df25751934e',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AssignTaskLabelV1Request(
        labelId: 'be79d59e-da81-496f-83d9-bc3fef3e8f9c',
    ),
);

$response = $sdk->tasks->labels->publicApiV1TasksLabelsAssign(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                        | Type                                                                                                             | Required                                                                                                         | Description                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                       | [Operations\PublicApiV1TasksLabelsAssignRequest](../../Models/Operations/PublicApiV1TasksLabelsAssignRequest.md) | :heavy_check_mark:                                                                                               | The request object to use for the request.                                                                       |

### Response

**[?Operations\PublicApiV1TasksLabelsAssignResponse](../../Models/Operations/PublicApiV1TasksLabelsAssignResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1TasksLabelsUnassign

Remove a label from a task. The label stays in the catalog.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.labels.unassign" method="delete" path="/companies/{company}/tasks/{task}/labels/{label}" example="success" -->
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

$request = new Operations\PublicApiV1TasksLabelsUnassignRequest(
    company: 'O\'Hara, Nitzsche and Kovacek',
    task: '<value>',
    label: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->tasks->labels->publicApiV1TasksLabelsUnassign(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                            | Type                                                                                                                 | Required                                                                                                             | Description                                                                                                          |
| -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                           | [Operations\PublicApiV1TasksLabelsUnassignRequest](../../Models/Operations/PublicApiV1TasksLabelsUnassignRequest.md) | :heavy_check_mark:                                                                                                   | The request object to use for the request.                                                                           |

### Response

**[?Operations\PublicApiV1TasksLabelsUnassignResponse](../../Models/Operations/PublicApiV1TasksLabelsUnassignResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |