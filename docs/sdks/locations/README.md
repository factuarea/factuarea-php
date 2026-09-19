# Warehouses.Locations

## Overview

### Available Operations

* [publicApiV1WarehousesLocationsDelete](#publicapiv1warehouseslocationsdelete) - Archive a location of a warehouse
* [publicApiV1WarehousesLocationsShow](#publicapiv1warehouseslocationsshow) - Retrieve a location of a warehouse
* [publicApiV1WarehousesLocationsUpdate](#publicapiv1warehouseslocationsupdate) - Update a location of a warehouse
* [publicApiV1WarehousesLocationsCreate](#publicapiv1warehouseslocationscreate) - Create a location in a warehouse
* [publicApiV1WarehousesLocationsList](#publicapiv1warehouseslocationslist) - List the locations of a warehouse

## publicApiV1WarehousesLocationsDelete

Archive a location of a warehouse. The operation RETIRES the location from the operation and does NOT delete its row: the row and its history are preserved, it stops being returned among the active locations of its warehouse, and its code is freed for a later one. A location that still has an active child is not archived: it returns 422 as a business rule violation and the hierarchy is left exactly as it was, so archive the children first. It returns no content. Irreversible: this API publishes no operation that brings an archived location back, so it requires an `Idempotency-Key` when the policy of your credential demands it.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.warehouses.locations.delete" method="delete" path="/companies/{company}/warehouses/{warehouse}/locations/{location}" -->
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

$request = new Operations\PublicApiV1WarehousesLocationsDeleteRequest(
    company: 'Herzog Inc',
    warehouse: '<value>',
    location: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->warehouses->locations->publicApiV1WarehousesLocationsDelete(
    request: $request
);

if ($response->statusCode === 200) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                        | Type                                                                                                                             | Required                                                                                                                         | Description                                                                                                                      |
| -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                       | [Operations\PublicApiV1WarehousesLocationsDeleteRequest](../../Models/Operations/PublicApiV1WarehousesLocationsDeleteRequest.md) | :heavy_check_mark:                                                                                                               | The request object to use for the request.                                                                                       |

### Response

**[?Operations\PublicApiV1WarehousesLocationsDeleteResponse](../../Models/Operations/PublicApiV1WarehousesLocationsDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1WarehousesLocationsShow

Retrieve one location of a warehouse by its `id`, with its code, its name, its status and the location it hangs from when it is not a root one. It is resolved by the DOUBLE key company and warehouse: a location of another company, and one that exists but belongs to a different warehouse of yours, both answer exactly like one that does not exist.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.warehouses.locations.show" method="get" path="/companies/{company}/warehouses/{warehouse}/locations/{location}" -->
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

$request = new Operations\PublicApiV1WarehousesLocationsShowRequest(
    company: 'Wintheiser, Wilkinson and Schultz',
    warehouse: '<value>',
    location: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->warehouses->locations->publicApiV1WarehousesLocationsShow(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                    | Type                                                                                                                         | Required                                                                                                                     | Description                                                                                                                  |
| ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                   | [Operations\PublicApiV1WarehousesLocationsShowRequest](../../Models/Operations/PublicApiV1WarehousesLocationsShowRequest.md) | :heavy_check_mark:                                                                                                           | The request object to use for the request.                                                                                   |

### Response

**[?Operations\PublicApiV1WarehousesLocationsShowResponse](../../Models/Operations/PublicApiV1WarehousesLocationsShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1WarehousesLocationsUpdate

Rename a location of a warehouse: its code and its name. Neither the warehouse it belongs to nor the location it hangs from are part of the body — moving a location inside the hierarchy is not published in this version. The code keeps being unique per warehouse among the active locations. An archived location, and one whose warehouse has been archived, reject the call as a business rule violation with 422. Returns the location as it is left.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.warehouses.locations.update" method="patch" path="/companies/{company}/warehouses/{warehouse}/locations/{location}" -->
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

$request = new Operations\PublicApiV1WarehousesLocationsUpdateRequest(
    company: 'Huel, McLaughlin and Gottlieb',
    warehouse: '<value>',
    location: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->warehouses->locations->publicApiV1WarehousesLocationsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                        | Type                                                                                                                             | Required                                                                                                                         | Description                                                                                                                      |
| -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                       | [Operations\PublicApiV1WarehousesLocationsUpdateRequest](../../Models/Operations/PublicApiV1WarehousesLocationsUpdateRequest.md) | :heavy_check_mark:                                                                                                               | The request object to use for the request.                                                                                       |

### Response

**[?Operations\PublicApiV1WarehousesLocationsUpdateResponse](../../Models/Operations/PublicApiV1WarehousesLocationsUpdateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1WarehousesLocationsCreate

Add a location to a warehouse: its code, its name and, optionally, the location it hangs from. The hierarchy is ONE level deep — a location may have a parent, and that parent may not have one in turn —, so a third level is rejected as a business rule violation with 422. The code is up to 20 characters and unique per WAREHOUSE among the active locations, so two warehouses of the same company can each have their own `A-01`, and repeating one that is in use returns 422 naming `code`. The parent has to belong to the same warehouse. Returns the location that was created, with its public id and its parent when it has one.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.warehouses.locations.create" method="post" path="/companies/{company}/warehouses/{warehouse}/locations" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1WarehousesLocationsCreateRequest(
    company: 'Satterfield, Lind and Frami',
    warehouse: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateWarehouseLocationRequest(
        code: '<value>',
        name: '<value>',
    ),
);

$response = $sdk->warehouses->locations->publicApiV1WarehousesLocationsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: hija

<!-- UsageSnippet language="php" operationID="public-api.v1.warehouses.locations.create" method="post" path="/companies/{company}/warehouses/{warehouse}/locations" example="hija" -->
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

$request = new Operations\PublicApiV1WarehousesLocationsCreateRequest(
    company: 'Rau - DuBuque',
    warehouse: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateWarehouseLocationRequest(
        code: 'EST-A1',
        name: 'Estantería A1',
        parentId: '01935a7e-2d5e-7c32-8f94-0a1b2c3d4e02',
    ),
);

$response = $sdk->warehouses->locations->publicApiV1WarehousesLocationsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.warehouses.locations.create" method="post" path="/companies/{company}/warehouses/{warehouse}/locations" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1WarehousesLocationsCreateRequest(
    company: 'Kiehn, Kulas and Walsh',
    warehouse: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateWarehouseLocationRequest(
        code: '<value>',
        name: '<value>',
    ),
);

$response = $sdk->warehouses->locations->publicApiV1WarehousesLocationsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.warehouses.locations.create" method="post" path="/companies/{company}/warehouses/{warehouse}/locations" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1WarehousesLocationsCreateRequest(
    company: 'Pollich - Koelpin',
    warehouse: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateWarehouseLocationRequest(
        code: '<value>',
        name: '<value>',
    ),
);

$response = $sdk->warehouses->locations->publicApiV1WarehousesLocationsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: raiz

<!-- UsageSnippet language="php" operationID="public-api.v1.warehouses.locations.create" method="post" path="/companies/{company}/warehouses/{warehouse}/locations" example="raiz" -->
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

$request = new Operations\PublicApiV1WarehousesLocationsCreateRequest(
    company: 'O\'Reilly Inc',
    warehouse: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateWarehouseLocationRequest(
        code: 'PAS-A',
        name: 'Pasillo A',
    ),
);

$response = $sdk->warehouses->locations->publicApiV1WarehousesLocationsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                        | Type                                                                                                                             | Required                                                                                                                         | Description                                                                                                                      |
| -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                       | [Operations\PublicApiV1WarehousesLocationsCreateRequest](../../Models/Operations/PublicApiV1WarehousesLocationsCreateRequest.md) | :heavy_check_mark:                                                                                                               | The request object to use for the request.                                                                                       |

### Response

**[?Operations\PublicApiV1WarehousesLocationsCreateResponse](../../Models/Operations/PublicApiV1WarehousesLocationsCreateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1WarehousesLocationsList

List the locations of one warehouse — its aisles, shelves or bays — with cursor-based pagination, taking the warehouse from the path. Filter by status and by parent location to walk the hierarchy one level at a time, and sort by creation, code or name. Archived locations are returned as well: filter by status when you only want the ones in use. An archived warehouse still serves its locations, and a warehouse of another company answers as not found.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.warehouses.locations.list" method="get" path="/companies/{company}/warehouses/{warehouse}/locations" -->
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

$request = new Operations\PublicApiV1WarehousesLocationsListRequest(
    company: 'Leannon Inc',
    warehouse: '<value>',
    sort: Operations\PublicApiV1WarehousesLocationsListSort::MinusCreated,
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->warehouses->locations->publicApiV1WarehousesLocationsList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                    | Type                                                                                                                         | Required                                                                                                                     | Description                                                                                                                  |
| ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                   | [Operations\PublicApiV1WarehousesLocationsListRequest](../../Models/Operations/PublicApiV1WarehousesLocationsListRequest.md) | :heavy_check_mark:                                                                                                           | The request object to use for the request.                                                                                   |

### Response

**[?Operations\PublicApiV1WarehousesLocationsListResponse](../../Models/Operations/PublicApiV1WarehousesLocationsListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |