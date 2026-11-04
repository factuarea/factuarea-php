# Tasks.EntityLinks

## Overview

### Available Operations

* [publicApiV1TasksEntityLinksCreate](#publicapiv1tasksentitylinkscreate) - Link a task to an entity
* [publicApiV1TasksEntityLinksList](#publicapiv1tasksentitylinkslist) - List task entity links
* [publicApiV1TasksEntityLinksDelete](#publicapiv1tasksentitylinksdelete) - Unlink a task from an entity

## publicApiV1TasksEntityLinksCreate

Link a task to a Factuarea entity (`entity_type` + `entity_id`). An entity that does not exist, belongs to another company or whose module is not accessible returns 404 `linked_entity_not_found`. Linking it again returns the existing link.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.entity_links.create" method="post" path="/companies/{company}/tasks/{task}/entity-links" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1TasksEntityLinksCreateRequest(
    company: '93b99fbb-7db2-4ae7-8fa5-9686fd77d6f0',
    task: '49225acd-f9ea-421e-9e6b-bffdea88fdb8',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\LinkTaskToEntityV1Request(
        entityType: Components\LinkTaskToEntityV1RequestEntityType::Proforma,
        entityId: '8a0e4354-9976-4c41-9b0b-7fe92de33c71',
    ),
);

$response = $sdk->tasks->entityLinks->publicApiV1TasksEntityLinksCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.entity_links.create" method="post" path="/companies/{company}/tasks/{task}/entity-links" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1TasksEntityLinksCreateRequest(
    company: 'Towne - Hackett',
    task: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\LinkTaskToEntityV1Request(
        entityType: Components\LinkTaskToEntityV1RequestEntityType::Proforma,
        entityId: '8a0e4354-9976-4c41-9b0b-7fe92de33c71',
    ),
);

$response = $sdk->tasks->entityLinks->publicApiV1TasksEntityLinksCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.entity_links.create" method="post" path="/companies/{company}/tasks/{task}/entity-links" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1TasksEntityLinksCreateRequest(
    company: '48a0e435-4997-46c4-81b0-b7fe92de33c7',
    task: '40996e5a-616d-44e2-a170-59ca91998f48',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\LinkTaskToEntityV1Request(
        entityType: Components\LinkTaskToEntityV1RequestEntityType::Proforma,
        entityId: '8a0e4354-9976-4c41-9b0b-7fe92de33c71',
    ),
);

$response = $sdk->tasks->entityLinks->publicApiV1TasksEntityLinksCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.entity_links.create" method="post" path="/companies/{company}/tasks/{task}/entity-links" example="success" -->
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

$request = new Operations\PublicApiV1TasksEntityLinksCreateRequest(
    company: 'Lynch - Ondricka',
    task: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\LinkTaskToEntityV1Request(
        entityType: Components\LinkTaskToEntityV1RequestEntityType::Proforma,
        entityId: '8a0e4354-9976-4c41-9b0b-7fe92de33c71',
    ),
);

$response = $sdk->tasks->entityLinks->publicApiV1TasksEntityLinksCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.entity_links.create" method="post" path="/companies/{company}/tasks/{task}/entity-links" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1TasksEntityLinksCreateRequest(
    company: '4c866302-aea2-4ef1-90b2-e39e5ffe910c',
    task: '1bd9516f-8856-42f2-be71-459c79c21963',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\LinkTaskToEntityV1Request(
        entityType: Components\LinkTaskToEntityV1RequestEntityType::Proforma,
        entityId: '8a0e4354-9976-4c41-9b0b-7fe92de33c71',
    ),
);

$response = $sdk->tasks->entityLinks->publicApiV1TasksEntityLinksCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1TasksEntityLinksCreateRequest](../../Models/Operations/PublicApiV1TasksEntityLinksCreateRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1TasksEntityLinksCreateResponse](../../Models/Operations/PublicApiV1TasksEntityLinksCreateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1TasksEntityLinksList

List the Factuarea entities a task is linked to (invoices, quotes, contacts, products…), with their display label. Plain list, no pagination.
- A link is always returned; if its entity is deleted, its module is inaccessible or the key lacks the entity read scope, it has `available: false` and `summary: null`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.entity_links.list" method="get" path="/companies/{company}/tasks/{task}/entity-links" example="success" -->
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



$response = $sdk->tasks->entityLinks->publicApiV1TasksEntityLinksList(
    company: 'Reichel, Frami and Olson',
    task: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->taskEntityLinkList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `task`                                                                                                                                                                                                                                                                                                                                                                       | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the task.                                                                                                                                                                                                                                                                                                                                     |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1TasksEntityLinksListResponse](../../Models/Operations/PublicApiV1TasksEntityLinksListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1TasksEntityLinksDelete

Remove the link between a task and a Factuarea entity. The entity itself is not affected.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.entity_links.delete" method="delete" path="/companies/{company}/tasks/{task}/entity-links/{link}" example="success" -->
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

$request = new Operations\PublicApiV1TasksEntityLinksDeleteRequest(
    company: 'Hackett LLC',
    task: '<value>',
    link: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->tasks->entityLinks->publicApiV1TasksEntityLinksDelete(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1TasksEntityLinksDeleteRequest](../../Models/Operations/PublicApiV1TasksEntityLinksDeleteRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1TasksEntityLinksDeleteResponse](../../Models/Operations/PublicApiV1TasksEntityLinksDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |