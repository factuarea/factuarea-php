# Storefront.Orders.BuyerIdentity

## Overview

### Available Operations

* [publicApiV1StorefrontOrdersBuyerIdentityUpdate](#publicapiv1storefrontordersbuyeridentityupdate) - Update the buyer fiscal identity of a storefront order

## publicApiV1StorefrontOrdersBuyerIdentityUpdate

Complete the fiscal identity of the buyer on an order that is already placed, which is the step between a shopper who bought as a private individual and an order that has to be invoiced with a full invoice. It is authenticated with the publishable storefront key that travels in the browser of an anonymous shopper. The body is PARTIAL and carries seven fields: the tax id, the five of the billing address — street, town, province, postal code and country — and the explicit request of a full invoice. Only what travels changes and an absent field keeps its value, so sending just the tax id leaves the address exactly as it was. Above the ceiling of the simplified invoice the identity stops being optional: a fiscal identification and a fiscal address — street, town and postal code — are required, and an order over the ceiling without them is rejected with 422 `buyer_identity_incomplete_for_full_invoice`. What is checked here is that those fields are THERE, not that the tax id is a real one: a tax id that is present and wrong is rejected later, when the invoice is issued, and the order is then left with the payment taken and the document missing. Completing the identity also promotes the buyer to a business contact of the company, REUSING the one that already carries that tax id instead of duplicating it, and the answer is the body of the order plus the contact it resolved to, or nothing when there was no identity to promote. An order that can no longer be paid rejects the call with 422 `sales_order_not_payable`, and an order of another company answers 404 `sales_order_not_found`. It needs no idempotency key: resending the same fields leaves the same order.

### Example Usage: completo

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.orders.buyer_identity.update" method="patch" path="/companies/{company}/storefront/orders/{order}/buyer-identity" example="completo" -->
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

$request = new Operations\PublicApiV1StorefrontOrdersBuyerIdentityUpdateRequest(
    company: 'Lowe - Mante',
    order: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\UpdateStorefrontBuyerIdentityRequest(
        buyerTaxId: 'B12345678',
        billingAddressLine: 'Calle Mayor 12, 3º B',
        billingCity: 'Alicante',
        billingProvince: 'Alicante',
        billingPostalCode: '03001',
        billingCountry: 'ES',
        fullInvoiceRequested: true,
    ),
);

$response = $sdk->storefront->orders->buyerIdentity->publicApiV1StorefrontOrdersBuyerIdentityUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: minimo

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.orders.buyer_identity.update" method="patch" path="/companies/{company}/storefront/orders/{order}/buyer-identity" example="minimo" -->
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

$request = new Operations\PublicApiV1StorefrontOrdersBuyerIdentityUpdateRequest(
    company: 'Hessel - Cassin',
    order: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\UpdateStorefrontBuyerIdentityRequest(
        buyerTaxId: 'B12345678',
    ),
);

$response = $sdk->storefront->orders->buyerIdentity->publicApiV1StorefrontOrdersBuyerIdentityUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                            | Type                                                                                                                                                 | Required                                                                                                                                             | Description                                                                                                                                          |
| ---------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                           | [Operations\PublicApiV1StorefrontOrdersBuyerIdentityUpdateRequest](../../Models/Operations/PublicApiV1StorefrontOrdersBuyerIdentityUpdateRequest.md) | :heavy_check_mark:                                                                                                                                   | The request object to use for the request.                                                                                                           |

### Response

**[?Operations\PublicApiV1StorefrontOrdersBuyerIdentityUpdateResponse](../../Models/Operations/PublicApiV1StorefrontOrdersBuyerIdentityUpdateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 401, 403, 404, 409, 410, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |