# SalesOrders.Buyer

## Overview

### Available Operations

* [publicApiV1SalesOrdersBuyerUpdate](#publicapiv1salesordersbuyerupdate) - Update the buyer of a sales order

## publicApiV1SalesOrdersBuyerUpdate

Replace the buyer identity of a sales order — name, email, phone and the optional tax id — without touching anything else on the order. It exists as its own operation because completing the buyer is the usual step between capturing an order from a storefront and confirming it, and doing it here does not require resending the lines.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.buyer.update" method="patch" path="/companies/{company}/sales-orders/{sales_order}/buyer" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1SalesOrdersBuyerUpdateRequest(
    company: 'Frami, Volkman and Ruecker',
    salesOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\UpdateSalesOrderBuyerRequest(
        buyerName: '<value>',
    ),
);

$response = $sdk->salesOrders->buyer->publicApiV1SalesOrdersBuyerUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.buyer.update" method="patch" path="/companies/{company}/sales-orders/{sales_order}/buyer" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1SalesOrdersBuyerUpdateRequest(
    company: 'Stark LLC',
    salesOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\UpdateSalesOrderBuyerRequest(
        buyerName: '<value>',
    ),
);

$response = $sdk->salesOrders->buyer->publicApiV1SalesOrdersBuyerUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.buyer.update" method="patch" path="/companies/{company}/sales-orders/{sales_order}/buyer" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1SalesOrdersBuyerUpdateRequest(
    company: 'Hayes, Medhurst and Nicolas',
    salesOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\UpdateSalesOrderBuyerRequest(
        buyerName: '<value>',
    ),
);

$response = $sdk->salesOrders->buyer->publicApiV1SalesOrdersBuyerUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1SalesOrdersBuyerUpdateRequest](../../Models/Operations/PublicApiV1SalesOrdersBuyerUpdateRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1SalesOrdersBuyerUpdateResponse](../../Models/Operations/PublicApiV1SalesOrdersBuyerUpdateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |