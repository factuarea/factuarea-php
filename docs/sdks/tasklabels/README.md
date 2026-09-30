# TaskLabels

## Overview

### Available Operations

* [publicApiV1TaskLabelsCreate](#publicapiv1tasklabelscreate) - Create a task label
* [publicApiV1TaskLabelsList](#publicapiv1tasklabelslist) - List task labels
* [publicApiV1TaskLabelsDelete](#publicapiv1tasklabelsdelete) - Delete a task label
* [publicApiV1TaskLabelsShow](#publicapiv1tasklabelsshow) - Retrieve a task label
* [publicApiV1TaskLabelsUpdate](#publicapiv1tasklabelsupdate) - Update a task label

## publicApiV1TaskLabelsCreate

Add a label to the catalog with a `name` (unique in your company, case-insensitive: 409 `task_label_name_in_use`) and a `color` from the task palette.

### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.task_labels.create" method="post" path="/task-labels" example="missing_api_key" -->
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

$body = new Components\CreateTaskLabelV1Request(
    name: '<value>',
    color: 'red',
);

$response = $sdk->taskLabels->publicApiV1TaskLabelsCreate(
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.task_labels.create" method="post" path="/task-labels" example="success" -->
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

$body = new Components\CreateTaskLabelV1Request(
    name: '<value>',
    color: 'red',
);

$response = $sdk->taskLabels->publicApiV1TaskLabelsCreate(
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                        | Type                                                                                                                                                                                                                                                                                                                                                                                             | Required                                                                                                                                                                                                                                                                                                                                                                                         | Description                                                                                                                                                                                                                                                                                                                                                                                      | Example                                                                                                                                                                                                                                                                                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `body`                                                                                                                                                                                                                                                                                                                                                                                           | [Components\CreateTaskLabelV1Request](../../Models/Components/CreateTaskLabelV1Request.md)                                                                                                                                                                                                                                                                                                       | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `409 idempotency_key_reused`. See the [Idempotency guide](/guides/idempotency).                                                                            | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                             |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1TaskLabelsCreateResponse](../../Models/Operations/PublicApiV1TaskLabelsCreateResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 409, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1TaskLabelsList

List the task label catalog of your company, ordered by name. Each label carries `tasks_count`: how many tasks of your company carry it, archived tasks included. Cursor-paginated: pass the `next_cursor` of a page as `starting_after` to get the next one; `has_more` tells whether there are more.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.task_labels.list" method="get" path="/task-labels" example="success" -->
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



$response = $sdk->taskLabels->publicApiV1TaskLabelsList(
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->taskLabelList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                        | Type                                                                                                                                                                                                                                                                                                                                                                                             | Required                                                                                                                                                                                                                                                                                                                                                                                         | Description                                                                                                                                                                                                                                                                                                                                                                                      | Example                                                                                                                                                                                                                                                                                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `limit`                                                                                                                                                                                                                                                                                                                                                                                          | *?int*                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Number of labels to return. Integer between 1 and 100. Defaults to 25.                                                                                                                                                                                                                                                                                                                           |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `startingAfter`                                                                                                                                                                                                                                                                                                                                                                                  | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Cursor for forward pagination: the `id` of the last label of the previous page (the `next_cursor` you received). Labels are ordered by name. An `id` that is not a label of your company returns 422 `invalid_param_value`.                                                                                                                                                                      |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1TaskLabelsListResponse](../../Models/Operations/PublicApiV1TaskLabelsListResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 422, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1TaskLabelsDelete

Permanently delete a label from the catalog and remove it from every task. Irreversible: there is no trash or restore, so send an `Idempotency-Key` header (required) to retry safely.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.task_labels.delete" method="delete" path="/task-labels/{label}" example="success" -->
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



$response = $sdk->taskLabels->publicApiV1TaskLabelsDelete(
    label: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `label`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `409 idempotency_key_reused`. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                      | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf).                                                                                                                                                                                                  | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1TaskLabelsDeleteResponse](../../Models/Operations/PublicApiV1TaskLabelsDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1TaskLabelsShow

Retrieve a label of the catalog, with `tasks_count`: how many tasks of your company carry it, archived tasks included.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.task_labels.show" method="get" path="/task-labels/{label}" example="success" -->
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



$response = $sdk->taskLabels->publicApiV1TaskLabelsShow(
    label: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                        | Type                                                                                                                                                                                                                                                                                                                                                                                             | Required                                                                                                                                                                                                                                                                                                                                                                                         | Description                                                                                                                                                                                                                                                                                                                                                                                      | Example                                                                                                                                                                                                                                                                                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `label`                                                                                                                                                                                                                                                                                                                                                                                          | *string*                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1TaskLabelsShowResponse](../../Models/Operations/PublicApiV1TaskLabelsShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1TaskLabelsUpdate

Partially update the `name` or `color` of a label; every task that carries it shows the change.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.task_labels.update" method="put" path="/task-labels/{label}" example="success" -->
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

$request = new Operations\PublicApiV1TaskLabelsUpdateRequest(
    label: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->taskLabels->publicApiV1TaskLabelsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                      | Type                                                                                                           | Required                                                                                                       | Description                                                                                                    |
| -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                     | [Operations\PublicApiV1TaskLabelsUpdateRequest](../../Models/Operations/PublicApiV1TaskLabelsUpdateRequest.md) | :heavy_check_mark:                                                                                             | The request object to use for the request.                                                                     |

### Response

**[?Operations\PublicApiV1TaskLabelsUpdateResponse](../../Models/Operations/PublicApiV1TaskLabelsUpdateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |