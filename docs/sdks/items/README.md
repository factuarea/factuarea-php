# PriceLists.Items

## Overview

### Available Operations

* [publicApiV1PriceListsItemsDelete](#publicapiv1pricelistsitemsdelete) - Delete a price list item
* [publicApiV1PriceListsItemsList](#publicapiv1pricelistsitemslist) - List price list items
* [publicApiV1PriceListsItemsUpsert](#publicapiv1pricelistsitemsupsert) - Upsert a price list item
* [publicApiV1PriceListsItemsPurgeRetired](#publicapiv1pricelistsitemspurgeretired) - Permanently delete a retired price list item
* [publicApiV1PriceListsItemsReassignRetired](#publicapiv1pricelistsitemsreassignretired) - Reassign a retired price list item

## publicApiV1PriceListsItemsDelete

Soft-delete a price entry from the list while historical document snapshots remain immutable.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.price-lists.items.delete" method="delete" path="/price-lists/{priceList}/items/{item}" -->
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

$request = new Operations\PublicApiV1PriceListsItemsDeleteRequest(
    priceList: '<value>',
    item: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->priceLists->items->publicApiV1PriceListsItemsDelete(
    request: $request
);

if ($response->statusCode === 200) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                | Type                                                                                                                     | Required                                                                                                                 | Description                                                                                                              |
| ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                               | [Operations\PublicApiV1PriceListsItemsDeleteRequest](../../Models/Operations/PublicApiV1PriceListsItemsDeleteRequest.md) | :heavy_check_mark:                                                                                                       | The request object to use for the request.                                                                               |

### Response

**[?Operations\PublicApiV1PriceListsItemsDeleteResponse](../../Models/Operations/PublicApiV1PriceListsItemsDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1PriceListsItemsList

List the product, variant and presentation prices configured in a price list.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.price-lists.items.list" method="get" path="/price-lists/{priceList}/items" example="success" -->
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

$request = new Operations\PublicApiV1PriceListsItemsListRequest(
    priceList: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->priceLists->items->publicApiV1PriceListsItemsList(
    request: $request
);

if ($response->priceListItemCollection !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                            | Type                                                                                                                 | Required                                                                                                             | Description                                                                                                          |
| -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                           | [Operations\PublicApiV1PriceListsItemsListRequest](../../Models/Operations/PublicApiV1PriceListsItemsListRequest.md) | :heavy_check_mark:                                                                                                   | The request object to use for the request.                                                                           |

### Response

**[?Operations\PublicApiV1PriceListsItemsListResponse](../../Models/Operations/PublicApiV1PriceListsItemsListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1PriceListsItemsUpsert

Create or update the unique price entry for a catalog target in this price list.

### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.price-lists.items.upsert" method="post" path="/price-lists/{priceList}/items" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1PriceListsItemsUpsertRequest(
    priceList: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\UpsertPriceListItemRequest(
        productId: '26de84ec-0a42-4323-9bfb-1bc6264d89a2',
        unitPrice: 4921.67,
        priceUnit: Components\UpsertPriceListItemRequestPriceUnit::Mtr,
    ),
);

$response = $sdk->priceLists->items->publicApiV1PriceListsItemsUpsert(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.price-lists.items.upsert" method="post" path="/price-lists/{priceList}/items" example="success" -->
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

$request = new Operations\PublicApiV1PriceListsItemsUpsertRequest(
    priceList: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\UpsertPriceListItemRequest(
        productId: '26de84ec-0a42-4323-9bfb-1bc6264d89a2',
        unitPrice: 4921.67,
        priceUnit: Components\UpsertPriceListItemRequestPriceUnit::Mtr,
    ),
);

$response = $sdk->priceLists->items->publicApiV1PriceListsItemsUpsert(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                | Type                                                                                                                     | Required                                                                                                                 | Description                                                                                                              |
| ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                               | [Operations\PublicApiV1PriceListsItemsUpsertRequest](../../Models/Operations/PublicApiV1PriceListsItemsUpsertRequest.md) | :heavy_check_mark:                                                                                                       | The request object to use for the request.                                                                               |

### Response

**[?Operations\PublicApiV1PriceListsItemsUpsertResponse](../../Models/Operations/PublicApiV1PriceListsItemsUpsertResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1PriceListsItemsPurgeRetired

Permanently destroy a retired price entry: its historical amount, its retirement reason and the snapshot of its target. This cannot be undone and there is no copy anywhere else, so `confirm` must be sent as `true`. Only a RETIRED entry can be purged — an active entry is rejected, and so is an unknown one.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.price-lists.items.purge_retired" method="post" path="/price-lists/{priceList}/items/{item}/purge" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1PriceListsItemsPurgeRetiredRequest(
    priceList: '<value>',
    item: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\PurgeRetiredPriceListItemRequest(
        confirm: Components\Confirm::One,
    ),
);

$response = $sdk->priceLists->items->publicApiV1PriceListsItemsPurgeRetired(
    request: $request
);

if ($response->statusCode === 200) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                            | Type                                                                                                                                 | Required                                                                                                                             | Description                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                           | [Operations\PublicApiV1PriceListsItemsPurgeRetiredRequest](../../Models/Operations/PublicApiV1PriceListsItemsPurgeRetiredRequest.md) | :heavy_check_mark:                                                                                                                   | The request object to use for the request.                                                                                           |

### Response

**[?Operations\PublicApiV1PriceListsItemsPurgeRetiredResponse](../../Models/Operations/PublicApiV1PriceListsItemsPurgeRetiredResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1PriceListsItemsReassignRetired

Create a NEW active price entry from a retired one, pointing it at a live catalog target and confirming its price. This is not a restore: the retired entry keeps its historical price, reason and date and is returned by `?status=retired` exactly as before. The response carries the new entry, with its own `id`. If the live target already has an active price in this list, the request is rejected instead of leaving two active prices for the same target.

### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.price-lists.items.reassign_retired" method="post" path="/price-lists/{priceList}/items/{item}/reassign" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1PriceListsItemsReassignRetiredRequest(
    priceList: '<value>',
    item: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ReassignRetiredPriceListItemRequest(
        productId: '79ba691d-62f7-4026-94b0-676285c7b17e',
        unitPrice: 9135.26,
        priceUnit: Components\ReassignRetiredPriceListItemRequestPriceUnit::Grm,
    ),
);

$response = $sdk->priceLists->items->publicApiV1PriceListsItemsReassignRetired(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.price-lists.items.reassign_retired" method="post" path="/price-lists/{priceList}/items/{item}/reassign" example="success" -->
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

$request = new Operations\PublicApiV1PriceListsItemsReassignRetiredRequest(
    priceList: '<value>',
    item: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ReassignRetiredPriceListItemRequest(
        productId: '79ba691d-62f7-4026-94b0-676285c7b17e',
        unitPrice: 9135.26,
        priceUnit: Components\ReassignRetiredPriceListItemRequestPriceUnit::Grm,
    ),
);

$response = $sdk->priceLists->items->publicApiV1PriceListsItemsReassignRetired(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                  | Type                                                                                                                                       | Required                                                                                                                                   | Description                                                                                                                                |
| ------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                                 | [Operations\PublicApiV1PriceListsItemsReassignRetiredRequest](../../Models/Operations/PublicApiV1PriceListsItemsReassignRetiredRequest.md) | :heavy_check_mark:                                                                                                                         | The request object to use for the request.                                                                                                 |

### Response

**[?Operations\PublicApiV1PriceListsItemsReassignRetiredResponse](../../Models/Operations/PublicApiV1PriceListsItemsReassignRetiredResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |