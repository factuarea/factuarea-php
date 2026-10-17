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

<!-- UsageSnippet language="php" operationID="public-api.v1.price-lists.items.delete" method="delete" path="/companies/{company}/price-lists/{priceList}/items/{item}" -->
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

$request = new Operations\PublicApiV1PriceListsItemsDeleteRequest(
    company: 'Armstrong Group',
    priceList: '<value>',
    item: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1PriceListsItemsList

List the product, variant and presentation prices configured in a price list.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.price-lists.items.list" method="get" path="/companies/{company}/price-lists/{priceList}/items" example="success" -->
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

$request = new Operations\PublicApiV1PriceListsItemsListRequest(
    company: 'Smith, Ruecker and Gulgowski',
    priceList: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1PriceListsItemsUpsert

Create or update the unique price entry for a catalog target in this price list.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.price-lists.items.upsert" method="post" path="/companies/{company}/price-lists/{priceList}/items" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1PriceListsItemsUpsertRequest(
    company: '18aadda3-b94a-4550-8885-b1a468dbd510',
    priceList: '53329c2c-88c2-4d28-beda-05a912d8f8df',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.price-lists.items.upsert" method="post" path="/companies/{company}/price-lists/{priceList}/items" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1PriceListsItemsUpsertRequest(
    company: 'Lakin, Goodwin and Torp',
    priceList: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.price-lists.items.upsert" method="post" path="/companies/{company}/price-lists/{priceList}/items" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1PriceListsItemsUpsertRequest(
    company: '26de84ec-0a42-4323-9bfb-1bc6264d89a2',
    priceList: '791fcf81-a277-4d0e-acd6-81669cc7f59f',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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

<!-- UsageSnippet language="php" operationID="public-api.v1.price-lists.items.upsert" method="post" path="/companies/{company}/price-lists/{priceList}/items" example="success" -->
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

$request = new Operations\PublicApiV1PriceListsItemsUpsertRequest(
    company: 'Jacobs - Shanahan',
    priceList: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.price-lists.items.upsert" method="post" path="/companies/{company}/price-lists/{priceList}/items" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1PriceListsItemsUpsertRequest(
    company: 'd9788a7d-f78e-44fb-9654-c496b40b2e2f',
    priceList: '3b8564d7-60c9-4608-91cf-a45cf478e374',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1PriceListsItemsPurgeRetired

Permanently destroy a retired price entry: its historical amount, its retirement reason and the snapshot of its target. This cannot be undone and there is no copy anywhere else, so `confirm` must be sent as `true`. Only a RETIRED entry can be purged — an active entry is rejected, and so is an unknown one.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.price-lists.items.purge_retired" method="post" path="/companies/{company}/price-lists/{priceList}/items/{item}/purge" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1PriceListsItemsPurgeRetiredRequest(
    company: '47b5d22d-082f-4339-9e7b-62ba42292a2a',
    priceList: '45a7db5d-f72c-4a32-8f50-1091c28e47f1',
    item: '58ff11c5-a96a-4b3a-8c34-9b7a3a80a4a1',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.price-lists.items.purge_retired" method="post" path="/companies/{company}/price-lists/{priceList}/items/{item}/purge" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1PriceListsItemsPurgeRetiredRequest(
    company: 'Hilll, Ledner and Yost',
    priceList: '<value>',
    item: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.price-lists.items.purge_retired" method="post" path="/companies/{company}/price-lists/{priceList}/items/{item}/purge" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1PriceListsItemsPurgeRetiredRequest(
    company: '58f5d300-6f42-488b-8de0-3671774a1e99',
    priceList: '32e61044-3030-42d3-8746-897877734822',
    item: '4f5b5051-e7bb-4ecf-a8b0-8c82912cf7ef',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.price-lists.items.purge_retired" method="post" path="/companies/{company}/price-lists/{priceList}/items/{item}/purge" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1PriceListsItemsPurgeRetiredRequest(
    company: '431160ec-a057-4e60-8590-97302a9b6e67',
    priceList: 'dcd36dd2-ed91-4d4e-ad04-fd858353069f',
    item: '69f3a932-4440-4b0d-a00f-3aff759ca3b6',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1PriceListsItemsReassignRetired

Create a NEW active price entry from a retired one, pointing at a live catalog target with a confirmed price.
- Not a restore: the retired entry keeps its history; the response carries the new entry with its own `id`.
- Rejected if the live target already has an active price in this list.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.price-lists.items.reassign_retired" method="post" path="/companies/{company}/price-lists/{priceList}/items/{item}/reassign" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1PriceListsItemsReassignRetiredRequest(
    company: '5e5274c4-bac4-4b7f-b4de-b98c7e07e82b',
    priceList: 'feb61e85-ea0f-4b0b-840d-77b38b805bae',
    item: '8fa44219-7db8-4b4c-be11-a9e0fe9e96ac',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.price-lists.items.reassign_retired" method="post" path="/companies/{company}/price-lists/{priceList}/items/{item}/reassign" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1PriceListsItemsReassignRetiredRequest(
    company: 'Hudson LLC',
    priceList: '<value>',
    item: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.price-lists.items.reassign_retired" method="post" path="/companies/{company}/price-lists/{priceList}/items/{item}/reassign" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1PriceListsItemsReassignRetiredRequest(
    company: '79ba691d-62f7-4026-94b0-676285c7b17e',
    priceList: 'e3b8676d-bcc1-4dab-bc59-15a5bb0966bf',
    item: 'ab5c99d3-8efa-4416-8169-dbd70dd64b0c',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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

<!-- UsageSnippet language="php" operationID="public-api.v1.price-lists.items.reassign_retired" method="post" path="/companies/{company}/price-lists/{priceList}/items/{item}/reassign" example="success" -->
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

$request = new Operations\PublicApiV1PriceListsItemsReassignRetiredRequest(
    company: 'Morar LLC',
    priceList: '<value>',
    item: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.price-lists.items.reassign_retired" method="post" path="/companies/{company}/price-lists/{priceList}/items/{item}/reassign" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1PriceListsItemsReassignRetiredRequest(
    company: '1831b3f7-1fbb-40b2-93d1-0a89db39f4e4',
    priceList: '31eedd45-b405-4291-be12-86dc52a06cbb',
    item: '2cb99257-5faa-466f-ab9c-5ad047819f35',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |