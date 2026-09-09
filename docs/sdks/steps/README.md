# Automations.Runs.Steps

## Overview

### Available Operations

* [publicApiV1AutomationsRunsStepsList](#publicapiv1automationsrunsstepslist) - List the steps of an automation run
* [publicApiV1AutomationsRunsStepsReplay](#publicapiv1automationsrunsstepsreplay) - Replay one step of an automation run

## publicApiV1AutomationsRunsStepsList

List the steps of a single run in execution order, with cursor-based pagination. It serves exactly the same step documents that the run detail embeds; what it saves is payload rather than queries — it leaves out the two frozen snapshots, which are what weigh when you poll every few seconds while a step retries. `step_index` is the identity of a step: it is what you replay, and what the `automation_run.step_dead_lettered` event points at.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.automations.runs.steps.list" method="get" path="/automations/runs/{run}/steps" -->
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
    run: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
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

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1AutomationsRunsStepsReplay

Rearm a single parked step, leaving its siblings untouched with their state, their reason and their timestamps.

CAREFUL — the step EXECUTES FOR REAL: it sends email, delivers webhooks and calls third parties. This is not an inert retry, so confirm with the account owner before calling it.

`step_index` is the index published by `GET /v1/automations/runs/{run}/steps`, not the position of the action in the rule definition. The response is 202 — accepted and queued, not finished — with the run id and the index that was rearmed. An index outside the range of the run returns 404, exactly like a run that is not yours, so probing indices reveals nothing.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.automations.runs.steps.replay" method="post" path="/automations/runs/{run}/steps/{step_index}/replay" -->
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
    run: '<value>',
    stepIndex: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
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
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |