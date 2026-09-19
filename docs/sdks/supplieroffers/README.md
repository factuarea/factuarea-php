# Products.SupplierOffers

## Overview

### Available Operations

* [publicApiV1ProductsSupplierOffersCreate](#publicapiv1productssupplierofferscreate) - Create a supplier offer
* [publicApiV1ProductsSupplierOffersList](#publicapiv1productssupplierofferslist) - List supplier offers
* [publicApiV1ProductsSupplierOffersDelete](#publicapiv1productssupplieroffersdelete) - Delete a supplier offer
* [publicApiV1ProductsSupplierOffersUpdate](#publicapiv1productssupplieroffersupdate) - Update a supplier offer
* [publicApiV1ProductsSupplierOffersPreferred](#publicapiv1productssupplierofferspreferred) - Set the preferred supplier offer

## publicApiV1ProductsSupplierOffersCreate

Create a tenant-scoped supplier offer for a product or one of its variants.

### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.supplier-offers.create" method="post" path="/products/{product}/supplier-offers" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1ProductsSupplierOffersCreateRequest(
    product: 'Incredible Concrete Cheese',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateSupplierProductOfferRequest(
        supplierId: '9353861a-fd9e-456e-8a4e-87473aed3aae',
        purchaseUnit: Components\CreateSupplierProductOfferRequestPurchaseUnit::Hur,
        conversionFactor: 9830.52,
        availability: Components\CreateSupplierProductOfferRequestAvailability::StoreDependent,
    ),
);

$response = $sdk->products->supplierOffers->publicApiV1ProductsSupplierOffersCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.products.supplier-offers.create" method="post" path="/products/{product}/supplier-offers" example="success" -->
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

$request = new Operations\PublicApiV1ProductsSupplierOffersCreateRequest(
    product: 'Elegant Fresh Bike',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateSupplierProductOfferRequest(
        supplierId: '9353861a-fd9e-456e-8a4e-87473aed3aae',
        purchaseUnit: Components\CreateSupplierProductOfferRequestPurchaseUnit::Hur,
        conversionFactor: 9830.52,
        availability: Components\CreateSupplierProductOfferRequestAvailability::StoreDependent,
    ),
);

$response = $sdk->products->supplierOffers->publicApiV1ProductsSupplierOffersCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                              | Type                                                                                                                                   | Required                                                                                                                               | Description                                                                                                                            |
| -------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                             | [Operations\PublicApiV1ProductsSupplierOffersCreateRequest](../../Models/Operations/PublicApiV1ProductsSupplierOffersCreateRequest.md) | :heavy_check_mark:                                                                                                                     | The request object to use for the request.                                                                                             |

### Response

**[?Operations\PublicApiV1ProductsSupplierOffersCreateResponse](../../Models/Operations/PublicApiV1ProductsSupplierOffersCreateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ProductsSupplierOffersList

List supplier offers for a product, optionally filtered by variant, supplier, availability or preferred status.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.supplier-offers.list" method="get" path="/products/{product}/supplier-offers" example="success" -->
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

$request = new Operations\PublicApiV1ProductsSupplierOffersListRequest(
    product: 'Refined Metal Shoes',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->products->supplierOffers->publicApiV1ProductsSupplierOffersList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                          | Type                                                                                                                               | Required                                                                                                                           | Description                                                                                                                        |
| ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                         | [Operations\PublicApiV1ProductsSupplierOffersListRequest](../../Models/Operations/PublicApiV1ProductsSupplierOffersListRequest.md) | :heavy_check_mark:                                                                                                                 | The request object to use for the request.                                                                                         |

### Response

**[?Operations\PublicApiV1ProductsSupplierOffersListResponse](../../Models/Operations/PublicApiV1ProductsSupplierOffersListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1ProductsSupplierOffersDelete

Retire a supplier offer from the active catalog without changing historical purchase snapshots.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.supplier-offers.delete" method="delete" path="/products/{product}/supplier-offers/{offer}" -->
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

$request = new Operations\PublicApiV1ProductsSupplierOffersDeleteRequest(
    product: 'Rustic Rubber Hat',
    offer: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->products->supplierOffers->publicApiV1ProductsSupplierOffersDelete(
    request: $request
);

if ($response->statusCode === 200) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                              | Type                                                                                                                                   | Required                                                                                                                               | Description                                                                                                                            |
| -------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                             | [Operations\PublicApiV1ProductsSupplierOffersDeleteRequest](../../Models/Operations/PublicApiV1ProductsSupplierOffersDeleteRequest.md) | :heavy_check_mark:                                                                                                                     | The request object to use for the request.                                                                                             |

### Response

**[?Operations\PublicApiV1ProductsSupplierOffersDeleteResponse](../../Models/Operations/PublicApiV1ProductsSupplierOffersDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ProductsSupplierOffersUpdate

Update cost, unit conversion, availability or activation data of a supplier offer.

### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.supplier-offers.update" method="put" path="/products/{product}/supplier-offers/{offer}" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1ProductsSupplierOffersUpdateRequest(
    product: 'Generic Wooden Mouse',
    offer: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\UpdateSupplierProductOfferRequest(
        supplierId: 'cc8fedac-b4cd-401f-8578-6be095739522',
        purchaseUnit: Components\UpdateSupplierProductOfferRequestPurchaseUnit::Kgm,
        conversionFactor: 6554.65,
        availability: Components\UpdateSupplierProductOfferRequestAvailability::Seasonal,
    ),
);

$response = $sdk->products->supplierOffers->publicApiV1ProductsSupplierOffersUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.products.supplier-offers.update" method="put" path="/products/{product}/supplier-offers/{offer}" example="success" -->
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

$request = new Operations\PublicApiV1ProductsSupplierOffersUpdateRequest(
    product: 'Intelligent Soft Salad',
    offer: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\UpdateSupplierProductOfferRequest(
        supplierId: 'cc8fedac-b4cd-401f-8578-6be095739522',
        purchaseUnit: Components\UpdateSupplierProductOfferRequestPurchaseUnit::Kgm,
        conversionFactor: 6554.65,
        availability: Components\UpdateSupplierProductOfferRequestAvailability::Seasonal,
    ),
);

$response = $sdk->products->supplierOffers->publicApiV1ProductsSupplierOffersUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                              | Type                                                                                                                                   | Required                                                                                                                               | Description                                                                                                                            |
| -------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                             | [Operations\PublicApiV1ProductsSupplierOffersUpdateRequest](../../Models/Operations/PublicApiV1ProductsSupplierOffersUpdateRequest.md) | :heavy_check_mark:                                                                                                                     | The request object to use for the request.                                                                                             |

### Response

**[?Operations\PublicApiV1ProductsSupplierOffersUpdateResponse](../../Models/Operations/PublicApiV1ProductsSupplierOffersUpdateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ProductsSupplierOffersPreferred

Atomically mark this offer as preferred for its product and variant target. No request body is required.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.supplier-offers.preferred" method="post" path="/products/{product}/supplier-offers/{offer}/preferred" example="success" -->
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

$request = new Operations\PublicApiV1ProductsSupplierOffersPreferredRequest(
    product: 'Ergonomic Frozen Salad',
    offer: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->products->supplierOffers->publicApiV1ProductsSupplierOffersPreferred(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                    | Type                                                                                                                                         | Required                                                                                                                                     | Description                                                                                                                                  |
| -------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                   | [Operations\PublicApiV1ProductsSupplierOffersPreferredRequest](../../Models/Operations/PublicApiV1ProductsSupplierOffersPreferredRequest.md) | :heavy_check_mark:                                                                                                                           | The request object to use for the request.                                                                                                   |

### Response

**[?Operations\PublicApiV1ProductsSupplierOffersPreferredResponse](../../Models/Operations/PublicApiV1ProductsSupplierOffersPreferredResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |