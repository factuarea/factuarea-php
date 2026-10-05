# Projects.TimeSummary

## Overview

### Available Operations

* [publicApiV1ProjectsTimeSummaryShow](#publicapiv1projectstimesummaryshow) - Retrieve a project time summary

## publicApiV1ProjectsTimeSummaryShow

Summarize the time logged on the tasks of a project, optionally between `from` and `to` (dates in the company time zone): total, billable, invoiced and pending-to-invoice seconds, broken down by member (with their names) and by task. Requires `projects:read` only, without `tasks:read` or `users:read`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.time_summary.show" method="get" path="/projects/{project}/time-summary" example="success" -->
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

$request = new Operations\PublicApiV1ProjectsTimeSummaryShowRequest(
    project: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->projects->timeSummary->publicApiV1ProjectsTimeSummaryShow(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                    | Type                                                                                                                         | Required                                                                                                                     | Description                                                                                                                  |
| ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                   | [Operations\PublicApiV1ProjectsTimeSummaryShowRequest](../../Models/Operations/PublicApiV1ProjectsTimeSummaryShowRequest.md) | :heavy_check_mark:                                                                                                           | The request object to use for the request.                                                                                   |

### Response

**[?Operations\PublicApiV1ProjectsTimeSummaryShowResponse](../../Models/Operations/PublicApiV1ProjectsTimeSummaryShowResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |