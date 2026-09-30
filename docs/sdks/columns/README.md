# Projects.Columns

## Overview

### Available Operations

* [publicApiV1ProjectsColumnsCreate](#publicapiv1projectscolumnscreate) - Create a project column
* [publicApiV1ProjectsColumnsList](#publicapiv1projectscolumnslist) - List project columns
* [publicApiV1ProjectsColumnsDelete](#publicapiv1projectscolumnsdelete) - Delete a project column
* [publicApiV1ProjectsColumnsUpdate](#publicapiv1projectscolumnsupdate) - Update a project column
* [publicApiV1ProjectsColumnsReorder](#publicapiv1projectscolumnsreorder) - Reorder project columns

## publicApiV1ProjectsColumnsCreate

Add a column at the end of the project board. `name` is required; `icon`, `color` (from the task palette) and `is_final` are optional. The `slug` is derived from the name and must be unique in the project: a clash returns 409 `column_slug_in_use`, and a reserved slug (`planned`, `archived`) returns 422 `column_slug_reserved`.

### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.columns.create" method="post" path="/projects/{project}/columns" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1ProjectsColumnsCreateRequest(
    project: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateProjectColumnV1Request(
        name: '<value>',
    ),
);

$response = $sdk->projects->columns->publicApiV1ProjectsColumnsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.columns.create" method="post" path="/projects/{project}/columns" example="success" -->
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

$request = new Operations\PublicApiV1ProjectsColumnsCreateRequest(
    project: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateProjectColumnV1Request(
        name: '<value>',
    ),
);

$response = $sdk->projects->columns->publicApiV1ProjectsColumnsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                | Type                                                                                                                     | Required                                                                                                                 | Description                                                                                                              |
| ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                               | [Operations\PublicApiV1ProjectsColumnsCreateRequest](../../Models/Operations/PublicApiV1ProjectsColumnsCreateRequest.md) | :heavy_check_mark:                                                                                                       | The request object to use for the request.                                                                               |

### Response

**[?Operations\PublicApiV1ProjectsColumnsCreateResponse](../../Models/Operations/PublicApiV1ProjectsColumnsCreateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ProjectsColumnsList

List the board columns of a project in board order. Each column carries its `slug`, `icon`, `color` and whether it is final (`is_final`: tasks moved there are completed). Returns a plain list, without pagination.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.columns.list" method="get" path="/projects/{project}/columns" example="success" -->
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



$response = $sdk->projects->columns->publicApiV1ProjectsColumnsList(
    project: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->projectColumnList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                        | Type                                                                                                                                                                                                                                                                                                                                                                                             | Required                                                                                                                                                                                                                                                                                                                                                                                         | Description                                                                                                                                                                                                                                                                                                                                                                                      | Example                                                                                                                                                                                                                                                                                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `project`                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1ProjectsColumnsListResponse](../../Models/Operations/PublicApiV1ProjectsColumnsListResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1ProjectsColumnsDelete

Permanently delete a board column. The optional query `move_to_column_id` is the UUID of another column of the same project that receives its tasks: if the column has tasks and it is not sent, the call returns 409 `column_has_tasks`; a target that does not exist, belongs to another project or is the column being deleted returns 422 `invalid_move_target_column`. With a valid target, the tasks are appended to the end of that column in their order, each one emits its status-change event, and the column is deleted in the same transaction. Irreversible: there is no trash or restore, so send an `Idempotency-Key` header (required) to retry safely.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.columns.delete" method="delete" path="/projects/{project}/columns/{column}" example="success" -->
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

$request = new Operations\PublicApiV1ProjectsColumnsDeleteRequest(
    project: '<value>',
    column: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->projects->columns->publicApiV1ProjectsColumnsDelete(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                | Type                                                                                                                     | Required                                                                                                                 | Description                                                                                                              |
| ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                               | [Operations\PublicApiV1ProjectsColumnsDeleteRequest](../../Models/Operations/PublicApiV1ProjectsColumnsDeleteRequest.md) | :heavy_check_mark:                                                                                                       | The request object to use for the request.                                                                               |

### Response

**[?Operations\PublicApiV1ProjectsColumnsDeleteResponse](../../Models/Operations/PublicApiV1ProjectsColumnsDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ProjectsColumnsUpdate

Partially update a column: `name` (its `slug` follows), `icon`, `color` and `is_final`. Turning a column final completes the tasks it holds; turning it back reopens them. A `slug` clash returns 409 `column_slug_in_use`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.columns.update" method="put" path="/projects/{project}/columns/{column}" example="success" -->
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

$request = new Operations\PublicApiV1ProjectsColumnsUpdateRequest(
    project: '<value>',
    column: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->projects->columns->publicApiV1ProjectsColumnsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                | Type                                                                                                                     | Required                                                                                                                 | Description                                                                                                              |
| ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                               | [Operations\PublicApiV1ProjectsColumnsUpdateRequest](../../Models/Operations/PublicApiV1ProjectsColumnsUpdateRequest.md) | :heavy_check_mark:                                                                                                       | The request object to use for the request.                                                                               |

### Response

**[?Operations\PublicApiV1ProjectsColumnsUpdateResponse](../../Models/Operations/PublicApiV1ProjectsColumnsUpdateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ProjectsColumnsReorder

Set the board order of the project columns by sending all their ids in `column_ids`, in the new order. The list must contain every column of the project exactly once; an unknown column returns 404 `project_column_not_found`.

### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.columns.reorder" method="put" path="/projects/{project}/columns/reorder" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1ProjectsColumnsReorderRequest(
    project: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ReorderProjectColumnsV1Request(
        columnIds: [
            '0ded0743-8ef6-44fb-ae64-fef93040867a',
        ],
    ),
);

$response = $sdk->projects->columns->publicApiV1ProjectsColumnsReorder(
    request: $request
);

if ($response->projectColumnList !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.columns.reorder" method="put" path="/projects/{project}/columns/reorder" example="success" -->
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

$request = new Operations\PublicApiV1ProjectsColumnsReorderRequest(
    project: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ReorderProjectColumnsV1Request(
        columnIds: [
            '0ded0743-8ef6-44fb-ae64-fef93040867a',
        ],
    ),
);

$response = $sdk->projects->columns->publicApiV1ProjectsColumnsReorder(
    request: $request
);

if ($response->projectColumnList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1ProjectsColumnsReorderRequest](../../Models/Operations/PublicApiV1ProjectsColumnsReorderRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1ProjectsColumnsReorderResponse](../../Models/Operations/PublicApiV1ProjectsColumnsReorderResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |