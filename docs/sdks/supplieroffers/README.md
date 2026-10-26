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

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.products.supplier-offers.create" method="post" path="/companies/{company}/products/{product}/supplier-offers" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1ProductsSupplierOffersCreateRequest(
    company: '38a20ce8-561f-4f4c-aa46-27a6512cae8d',
    product: '6c7d1c54-0a4f-4b43-b11b-54e84810f464',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.supplier-offers.create" method="post" path="/companies/{company}/products/{product}/supplier-offers" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1ProductsSupplierOffersCreateRequest(
    company: 'Lind - Jacobi',
    product: 'Incredible Concrete Cheese',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.products.supplier-offers.create" method="post" path="/companies/{company}/products/{product}/supplier-offers" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1ProductsSupplierOffersCreateRequest(
    company: '9353861a-fd9e-456e-8a4e-87473aed3aae',
    product: 'dfd24171-3914-42b7-8444-498ec3bf13c7',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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

<!-- UsageSnippet language="php" operationID="public-api.v1.products.supplier-offers.create" method="post" path="/companies/{company}/products/{product}/supplier-offers" example="success" -->
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

$request = new Operations\PublicApiV1ProductsSupplierOffersCreateRequest(
    company: 'Farrell Inc',
    product: 'Elegant Fresh Bike',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.products.supplier-offers.create" method="post" path="/companies/{company}/products/{product}/supplier-offers" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1ProductsSupplierOffersCreateRequest(
    company: 'a5a71121-d074-424b-a4f1-3d028aa454c0',
    product: '2c3f3d2f-a121-4f8e-8fe1-f1b11abc020a',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ProductsSupplierOffersList

List supplier offers for a product, optionally filtered by variant, supplier, availability or preferred status.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.supplier-offers.list" method="get" path="/companies/{company}/products/{product}/supplier-offers" example="success" -->
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

$request = new Operations\PublicApiV1ProductsSupplierOffersListRequest(
    company: 'Legros, Shields and Bailey',
    product: 'Refined Metal Shoes',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ProductsSupplierOffersDelete

Retire a supplier offer from the active catalog without changing historical purchase snapshots.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.supplier-offers.delete" method="delete" path="/companies/{company}/products/{product}/supplier-offers/{offer}" -->
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

$request = new Operations\PublicApiV1ProductsSupplierOffersDeleteRequest(
    company: 'Pouros, Kulas and Kuvalis',
    product: 'Rustic Rubber Hat',
    offer: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ProductsSupplierOffersUpdate

Update cost, unit conversion, availability or activation data of a supplier offer.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.products.supplier-offers.update" method="patch" path="/companies/{company}/products/{product}/supplier-offers/{offer}" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1ProductsSupplierOffersUpdateRequest(
    company: '3a6d3bc7-e279-4b34-9546-f2146b44a813',
    product: '6ab7c06b-1a31-4613-96da-bc8d6968e898',
    offer: '7c8e442f-93e8-4b06-8e5d-1ed0fb15c176',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.supplier-offers.update" method="patch" path="/companies/{company}/products/{product}/supplier-offers/{offer}" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1ProductsSupplierOffersUpdateRequest(
    company: 'Simonis, Nikolaus and Roob',
    product: 'Generic Wooden Mouse',
    offer: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.products.supplier-offers.update" method="patch" path="/companies/{company}/products/{product}/supplier-offers/{offer}" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1ProductsSupplierOffersUpdateRequest(
    company: 'cc8fedac-b4cd-401f-8578-6be095739522',
    product: '1ab7ca4f-9340-4fce-8308-aaf02b914144',
    offer: '2b45da63-f42a-4377-89f7-73c09a207dd0',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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

<!-- UsageSnippet language="php" operationID="public-api.v1.products.supplier-offers.update" method="patch" path="/companies/{company}/products/{product}/supplier-offers/{offer}" example="success" -->
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

$request = new Operations\PublicApiV1ProductsSupplierOffersUpdateRequest(
    company: 'Rosenbaum, Macejkovic and Wyman',
    product: 'Intelligent Soft Salad',
    offer: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.products.supplier-offers.update" method="patch" path="/companies/{company}/products/{product}/supplier-offers/{offer}" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1ProductsSupplierOffersUpdateRequest(
    company: 'e14ebcf5-32ff-42f3-9713-f3664159bf02',
    product: '682caca6-432e-4613-8a0d-80363c4b7382',
    offer: '8ab50de5-eedb-472e-9b79-41a9963d637a',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ProductsSupplierOffersPreferred

Atomically mark this offer as preferred for its product and variant target. No request body is required.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.supplier-offers.preferred" method="post" path="/companies/{company}/products/{product}/supplier-offers/{offer}/preferred" example="success" -->
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

$request = new Operations\PublicApiV1ProductsSupplierOffersPreferredRequest(
    company: 'Hayes - Purdy',
    product: 'Ergonomic Frozen Salad',
    offer: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |