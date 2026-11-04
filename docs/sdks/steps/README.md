# Automations.Runs.Steps

## Overview

### Available Operations

* [publicApiV1AutomationsRunsStepsList](#publicapiv1automationsrunsstepslist) - List the steps of an automation run
* [publicApiV1AutomationsRunsStepsReplay](#publicapiv1automationsrunsstepsreplay) - Replay one step of an automation run

## publicApiV1AutomationsRunsStepsList

List the steps of a run in execution order with cursor pagination.
- Same step documents as the run detail, without the two heavy frozen snapshots; cheaper to poll.
- `step_index` identifies a step: it is what you replay.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.automations.runs.steps.list" method="get" path="/companies/{company}/automations/runs/{run}/steps" -->
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

$request = new Operations\PublicApiV1AutomationsRunsStepsListRequest(
    company: 'Hegmann, Bartoletti and Leffler',
    run: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->automations->runs->steps->publicApiV1AutomationsRunsStepsList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                      | Type                                                                                                                           | Required                                                                                                                       | Description                                                                                                                    |
| ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                     | [Operations\PublicApiV1AutomationsRunsStepsListRequest](../../Models/Operations/PublicApiV1AutomationsRunsStepsListRequest.md) | :heavy_check_mark:                                                                                                             | The request object to use for the request.                                                                                     |

### Response

**[?Operations\PublicApiV1AutomationsRunsStepsListResponse](../../Models/Operations/PublicApiV1AutomationsRunsStepsListResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1AutomationsRunsStepsReplay

Rearm a single parked step, leaving its siblings untouched.
- The step EXECUTES FOR REAL (email, webhooks, third parties); confirm with the account owner first.
- `step_index` is the one published by the steps listing; an out-of-range index returns 404.
- Returns 202 (queued, not finished) with the run id and the index.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.automations.runs.steps.replay" method="post" path="/companies/{company}/automations/runs/{run}/steps/{step_index}/replay" -->
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

$request = new Operations\PublicApiV1AutomationsRunsStepsReplayRequest(
    company: 'Fahey LLC',
    run: '<value>',
    stepIndex: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->automations->runs->steps->publicApiV1AutomationsRunsStepsReplay(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                          | Type                                                                                                                               | Required                                                                                                                           | Description                                                                                                                        |
| ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                         | [Operations\PublicApiV1AutomationsRunsStepsReplayRequest](../../Models/Operations/PublicApiV1AutomationsRunsStepsReplayRequest.md) | :heavy_check_mark:                                                                                                                 | The request object to use for the request.                                                                                         |

### Response

**[?Operations\PublicApiV1AutomationsRunsStepsReplayResponse](../../Models/Operations/PublicApiV1AutomationsRunsStepsReplayResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |