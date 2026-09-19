# SalesOrders.ShippingAddress

## Overview

### Available Operations

* [publicApiV1SalesOrdersShippingAddressUpdate](#publicapiv1salesordersshippingaddressupdate) - Update the shipping address of a sales order

## publicApiV1SalesOrdersShippingAddressUpdate

Replace the shipping address of a sales order — address line, city, province, postal code and country. The billing address is not touched: that one travels in the partial update of the order.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.sales_orders.shipping_address.update" method="patch" path="/companies/{company}/sales-orders/{sales_order}/shipping-address" -->
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

$request = new Operations\PublicApiV1SalesOrdersShippingAddressUpdateRequest(
    company: 'Thiel, Torp and Wiegand',
    salesOrder: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\UpdateSalesOrderShippingAddressRequest(
        shippingAddressLine: 'Carrer de Pallars 193, nau 4',
        shippingCity: 'Barcelona',
        shippingProvince: 'Barcelona',
        shippingPostalCode: '08005',
        shippingCountry: 'ES',
    ),
);

$response = $sdk->salesOrders->shippingAddress->publicApiV1SalesOrdersShippingAddressUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                      | Type                                                                                                                                           | Required                                                                                                                                       | Description                                                                                                                                    |
| ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                     | [Operations\PublicApiV1SalesOrdersShippingAddressUpdateRequest](../../Models/Operations/PublicApiV1SalesOrdersShippingAddressUpdateRequest.md) | :heavy_check_mark:                                                                                                                             | The request object to use for the request.                                                                                                     |

### Response

**[?Operations\PublicApiV1SalesOrdersShippingAddressUpdateResponse](../../Models/Operations/PublicApiV1SalesOrdersShippingAddressUpdateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |