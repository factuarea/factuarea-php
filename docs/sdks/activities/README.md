# Tasks.Activities

## Overview

### Available Operations

* [publicApiV1TasksActivitiesList](#publicapiv1tasksactivitieslist) - List task activity

## publicApiV1TasksActivitiesList

List the activity log of a task, newest first: status and column changes, edits (with the changed fields), assignments, labels, comments, relations, attachments and time, each with its actor (`user`, `api_key`, `external` or `system`). Cursor-paginated with an OPAQUE cursor: pass the `next_cursor` back as `starting_after` untouched —activity entries have no `id` of their own.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.activities.list" method="get" path="/tasks/{task}/activities" example="success" -->
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

$request = new Operations\PublicApiV1TasksActivitiesListRequest(
    task: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->tasks->activities->publicApiV1TasksActivitiesList(
    request: $request
);

if ($response->taskActivityList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                            | Type                                                                                                                 | Required                                                                                                             | Description                                                                                                          |
| -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                           | [Operations\PublicApiV1TasksActivitiesListRequest](../../Models/Operations/PublicApiV1TasksActivitiesListRequest.md) | :heavy_check_mark:                                                                                                   | The request object to use for the request.                                                                           |

### Response

**[?Operations\PublicApiV1TasksActivitiesListResponse](../../Models/Operations/PublicApiV1TasksActivitiesListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |