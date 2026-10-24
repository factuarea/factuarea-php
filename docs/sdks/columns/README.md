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

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.columns.create" method="post" path="/companies/{company}/projects/{project}/columns" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1ProjectsColumnsCreateRequest(
    company: '22baf439-7a11-4f4f-bb86-2973d7c2e18e',
    project: '4d1543d3-bcbe-4d8e-9405-5d3cafbaf996',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.columns.create" method="post" path="/companies/{company}/projects/{project}/columns" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1ProjectsColumnsCreateRequest(
    company: 'Reichert - Harvey',
    project: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.columns.create" method="post" path="/companies/{company}/projects/{project}/columns" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1ProjectsColumnsCreateRequest(
    company: '0293b59b-9031-434f-aa0d-7b12483207b5',
    project: 'e4b73055-6c88-4178-aeac-f9eb641f49ce',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.columns.create" method="post" path="/companies/{company}/projects/{project}/columns" example="success" -->
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

$request = new Operations\PublicApiV1ProjectsColumnsCreateRequest(
    company: 'Collier - Mayert',
    project: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.columns.create" method="post" path="/companies/{company}/projects/{project}/columns" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1ProjectsColumnsCreateRequest(
    company: 'ab7a9faa-13e3-486b-bc00-2f9f30d331e1',
    project: '2a124ac3-b7ab-4239-be4f-3c6c0cc15608',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1ProjectsColumnsList

List the board columns of a project in board order. Each column carries its `slug`, `icon`, `color` and whether it is final (`is_final`: tasks moved there are completed). Returns a plain list, without pagination.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.columns.list" method="get" path="/companies/{company}/projects/{project}/columns" example="success" -->
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



$response = $sdk->projects->columns->publicApiV1ProjectsColumnsList(
    company: 'Purdy LLC',
    project: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->projectColumnList !== null) {
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

**[?Operations\PublicApiV1ProjectsColumnsListResponse](../../Models/Operations/PublicApiV1ProjectsColumnsListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1ProjectsColumnsDelete

Permanently delete a board column; irreversible, so send the required `Idempotency-Key` header.
- If the column has tasks and `move_to_column_id` is missing, returns 409 `column_has_tasks`.
- An invalid target returns 422 `invalid_move_target_column`; a valid one receives the tasks at its end.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.columns.delete" method="delete" path="/companies/{company}/projects/{project}/columns/{column}" example="success" -->
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

$request = new Operations\PublicApiV1ProjectsColumnsDeleteRequest(
    company: 'Rath, Bernhard and Schroeder',
    project: '<value>',
    column: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1ProjectsColumnsUpdate

Partially update a column: `name` (its `slug` follows), `icon`, `color` and `is_final`. Turning a column final completes the tasks it holds; turning it back reopens them. A `slug` clash returns 409 `column_slug_in_use`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.columns.update" method="patch" path="/companies/{company}/projects/{project}/columns/{column}" example="success" -->
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

$request = new Operations\PublicApiV1ProjectsColumnsUpdateRequest(
    company: 'Legros, Leuschke and Lang',
    project: '<value>',
    column: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1ProjectsColumnsReorder

Set the board order of the project columns by sending all their ids in `column_ids`, in the new order. The list must contain every column of the project exactly once; an unknown column returns 404 `project_column_not_found`.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.columns.reorder" method="put" path="/companies/{company}/projects/{project}/columns/reorder" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1ProjectsColumnsReorderRequest(
    company: '8f7a5a76-79d0-4c76-abc6-1164ef493267',
    project: '45f0c8f1-5170-468c-990e-c93f9713cba4',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.columns.reorder" method="put" path="/companies/{company}/projects/{project}/columns/reorder" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1ProjectsColumnsReorderRequest(
    company: 'Stark, Adams and Kuhic',
    project: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.columns.reorder" method="put" path="/companies/{company}/projects/{project}/columns/reorder" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1ProjectsColumnsReorderRequest(
    company: '60ded074-38ef-464f-abe6-4fef93040867',
    project: 'b36d04b6-6e55-45b0-8314-cc0ecdd52670',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.columns.reorder" method="put" path="/companies/{company}/projects/{project}/columns/reorder" example="success" -->
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

$request = new Operations\PublicApiV1ProjectsColumnsReorderRequest(
    company: 'Baumbach and Sons',
    project: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.columns.reorder" method="put" path="/companies/{company}/projects/{project}/columns/reorder" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1ProjectsColumnsReorderRequest(
    company: 'd4dae26e-ec24-4f57-b9a1-42a090205cb4',
    project: '45aeb0b6-886e-4482-9249-53d68e4c87b3',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |