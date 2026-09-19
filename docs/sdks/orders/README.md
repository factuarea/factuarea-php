# Storefront.Orders

## Overview

### Available Operations

* [publicApiV1StorefrontOrdersConfirmPayment](#publicapiv1storefrontordersconfirmpayment) - Confirm the payment of a storefront order
* [publicApiV1StorefrontOrdersCreate](#publicapiv1storefrontorderscreate) - Create a storefront order
* [publicApiV1StorefrontOrdersShow](#publicapiv1storefrontordersshow) - Retrieve a storefront order
* [publicApiV1StorefrontOrdersTracking](#publicapiv1storefrontorderstracking) - Retrieve the shipment tracking of a storefront order
* [publicApiV1StorefrontOrdersCheckout](#publicapiv1storefrontorderscheckout) - Start the checkout of a storefront order

## publicApiV1StorefrontOrdersConfirmPayment

Close the purchase when the shopper comes back from the gateway, with the identifier of the payment session that the return address carries. It is authenticated with the publishable storefront key that travels in the browser of an anonymous shopper, it REQUIRES an `Idempotency-Key` header, and it is the ONLY IRREVERSIBLE operation of the shopper lane: when the payment holds it issues the invoice of the order, a fiscal document that consumes correlative numbering and that, with the billing register active, is filed with the tax administration — it is voided with a corrective invoice and it is never deleted. Returns three things and no more: the situation of the payment, the situation of the order, and the invoice issued — public id and number — or nothing when none was issued. Repeating the call returns the SAME outcome instead of charging or invoicing twice, and it can be exercised in a LATER visit for up to seven days with the session identifier the storefront kept, which is the net under a shopper who closes the tab on the way back. A session that cannot be read, one that is not paid, and one that belongs to ANOTHER order mark nothing and are not errors either: the answer publishes the CURRENT situation of the order, with no reason field — publishing the reason would turn this into an oracle with which to find out, by trying session identifiers, which session belongs to which order. A payment that holds whose invoice is refused — the order falls inside the window of a recurring invoice, the identity of its buyer is incomplete — answers with the code of that refusal (422 `business_rule_violation` with the subcode `sales_order_recurring_invoice_overlap` for the overlap, 422 `buyer_identity_incomplete_for_full_invoice` for the identity) and leaves the order with its payment `verified` and no document, which is the truth and not `paid`. Without the header the call is rejected with 422 `idempotency_key_required`, and the same key with a different body with 422 `idempotency_key_reused`. An order of another company answers 404 `sales_order_not_found`. With the company in TEST MODE the payment comes back as `simulated`, with no invoice and no charge.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.orders.confirm_payment" method="post" path="/companies/{company}/storefront/orders/{order}/confirm-payment" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1StorefrontOrdersConfirmPaymentRequest(
    company: 'Robel - Kassulke',
    order: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ConfirmStorefrontCheckoutRequest(
        sessionId: '<id>',
    ),
);

$response = $sdk->storefront->orders->publicApiV1StorefrontOrdersConfirmPayment(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.orders.confirm_payment" method="post" path="/companies/{company}/storefront/orders/{order}/confirm-payment" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1StorefrontOrdersConfirmPaymentRequest(
    company: 'Heidenreich Inc',
    order: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ConfirmStorefrontCheckoutRequest(
        sessionId: '<id>',
    ),
);

$response = $sdk->storefront->orders->publicApiV1StorefrontOrdersConfirmPayment(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.orders.confirm_payment" method="post" path="/companies/{company}/storefront/orders/{order}/confirm-payment" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1StorefrontOrdersConfirmPaymentRequest(
    company: 'Lindgren, Wuckert and Lebsack',
    order: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ConfirmStorefrontCheckoutRequest(
        sessionId: '<id>',
    ),
);

$response = $sdk->storefront->orders->publicApiV1StorefrontOrdersConfirmPayment(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                  | Type                                                                                                                                       | Required                                                                                                                                   | Description                                                                                                                                |
| ------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                                 | [Operations\PublicApiV1StorefrontOrdersConfirmPaymentRequest](../../Models/Operations/PublicApiV1StorefrontOrdersConfirmPaymentRequest.md) | :heavy_check_mark:                                                                                                                         | The request object to use for the request.                                                                                                 |

### Response

**[?Operations\PublicApiV1StorefrontOrdersConfirmPaymentResponse](../../Models/Operations/PublicApiV1StorefrontOrdersConfirmPaymentResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 401, 403, 404, 409, 410, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1StorefrontOrdersCreate

Turn a cart into a real sales order. It is authenticated with the publishable storefront key that travels in the browser of an anonymous shopper, and it is the operation of the lane to read whole before integrating. It is IDEMPOTENT BY TWO INDEPENDENT AXES, and the contract keeps them apart on purpose: the `Idempotency-Key` header, which is REQUIRED here and identifies the REQUEST — the same key with the same body returns the same order and leaves one single row, and a call without it is rejected —, and the optional external id, which identifies the ORDER in your own system and is unique PER COMPANY, so two creations with the same external id return the same order even from different requests and days apart, while two different companies may each use the same one. Neither axis is derived from the other. THE PRICE IS THE SERVER'S: every line is priced again at creation and any amount in the body is ignored, so a manipulated browser cannot change what is charged, and neither the previewed price nor the one stored in the cart session is taken as the price of the line without resolving it again. Availability is checked line by line and a single line without enough units rejects the WHOLE order naming that line: no partial order is ever created. The identity of the shopper needs a name, an email and a shipping address; the tax id is OPTIONAL and a shopper without one completes the order, which then creates no business contact at all, because a business contact requires a fiscal identity and a private individual does not have one. The consent to commercial communications travels as an explicit field instead of being presumed, and neither the IP address of the shopper nor any browser tracking identifier is stored. The order is born with the storefront channel, so its origin is distinguishable from a manual entry, from a connected store and from an integrator API call. When the company that owns the key is in TEST MODE the lane keeps working and what is neutralized is the EFFECT and never the access: the check happens as the LAST step before any effect, so what is simulated is exactly what would have been produced. Returns the created order with its public id, its number, its status, its channel, its lines with the amounts of the server, its totals and the session it came from.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.orders.create" method="post" path="/companies/{company}/storefront/orders" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1StorefrontOrdersCreateRequest(
    company: 'Flatley, Gleason and Anderson',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateStorefrontOrderRequest(
        marketingConsent: true,
        buyerName: '<value>',
        buyerEmail: 'Alverta.Schumm@gmail.com',
        shippingAddressLine: '<value>',
        shippingCity: '<value>',
        shippingCountry: '<value>',
    ),
);

$response = $sdk->storefront->orders->publicApiV1StorefrontOrdersCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.orders.create" method="post" path="/companies/{company}/storefront/orders" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1StorefrontOrdersCreateRequest(
    company: 'Huels and Sons',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateStorefrontOrderRequest(
        marketingConsent: true,
        buyerName: '<value>',
        buyerEmail: 'Alverta.Schumm@gmail.com',
        shippingAddressLine: '<value>',
        shippingCity: '<value>',
        shippingCountry: '<value>',
    ),
);

$response = $sdk->storefront->orders->publicApiV1StorefrontOrdersCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.orders.create" method="post" path="/companies/{company}/storefront/orders" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1StorefrontOrdersCreateRequest(
    company: 'Balistreri - Lakin',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateStorefrontOrderRequest(
        marketingConsent: true,
        buyerName: '<value>',
        buyerEmail: 'Alverta.Schumm@gmail.com',
        shippingAddressLine: '<value>',
        shippingCity: '<value>',
        shippingCountry: '<value>',
    ),
);

$response = $sdk->storefront->orders->publicApiV1StorefrontOrdersCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1StorefrontOrdersCreateRequest](../../Models/Operations/PublicApiV1StorefrontOrdersCreateRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1StorefrontOrdersCreateResponse](../../Models/Operations/PublicApiV1StorefrontOrdersCreateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1StorefrontOrdersShow

Retrieve the order a shopper placed, by its public id, with everything the page that follows a purchase needs: the id of the order and its number, its date, its lines, its subtotal, its tax total, its total, its currency, the situation of the ORDER, the situation of its PAYMENT and the invoice issued for it — public id and number — or nothing when there is none. It is authenticated with the publishable storefront key that travels in the browser of an anonymous shopper. The list of fields is an INCLUSION list: the sales order is an aggregate of the merchant with dozens of fields that grows with its own module, so cost, margin, supplier, per-warehouse balance, series, warehouse and internal notes are never published here, and a new field of the order does not reach the shopper by omission. Every line carries its description, its quantity, its unit price, its line total and its tax rate, and NO catalog identifier: the storefront already received the article, the variant and the presentation in the answer to the creation. The number comes back empty while the order still carries no numbering, and the identity of the buyer is not republished here — whoever opens this window already knows who they are. The situation of the PAYMENT is what the system REMEMBERS about the charge, one of `not_started`, `link_issued`, `verified`, `paid` and `simulated`, and this read only ever reports `not_started` or `paid`: `link_issued` is what starting a checkout leaves behind, and `verified` and `simulated` are outcomes the confirmation publishes. `verified` means money taken with the document still missing, and it is published as such instead of saying `paid`. The window is open while the public link of the order is: a link past its expiry answers 410 `link_expired` and one the merchant closed answers 410 `link_revoked` — except right after a payment that has just been confirmed, which is served — and an order of another company answers 404 `sales_order_not_found`, exactly like one that does not exist. Unlike the shapes of the cart lane, the four shapes of the checkout carry no `object` discriminator: they were frozen enumerating their keys and nothing else.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.orders.show" method="get" path="/companies/{company}/storefront/orders/{order}" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->storefront->orders->publicApiV1StorefrontOrdersShow(
    company: 'Flatley - Langosh',
    order: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                        | Type                                                                                                                                                                                                                                                                                                                                                                                             | Required                                                                                                                                                                                                                                                                                                                                                                                         | Description                                                                                                                                                                                                                                                                                                                                                                                      | Example                                                                                                                                                                                                                                                                                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `company`                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `order`                                                                                                                                                                                                                                                                                                                                                                                          | *string*                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1StorefrontOrdersShowResponse](../../Models/Operations/PublicApiV1StorefrontOrdersShowResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 410, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1StorefrontOrdersTracking

Follow the shipment of an order with the eight fields a tracking page needs, and the eight always travel: whether there is a shipment at all, its situation, that same situation already written in Spanish for you to paint, the NAME of the carrier, the expected delivery date, the tracking number, the tracking address and the number of packages. It is authenticated with the publishable storefront key that travels in the browser of an anonymous shopper. An order that has not shipped yet is NOT an error and NOT a not-found: it answers with no shipment, the six data fields empty and zero packages, because answering not found would make «nothing has shipped yet» indistinguishable from «that order does not exist», and the shopper who has just paid would read the second one. The situation is one of `pending`, `picking`, `prepared`, `handed_over`, `in_transit`, `delivered` and `failed`, and its label comes resolved from the server so that a storefront never keeps a copy of the catalog that goes stale the day it grows. The carrier is ONE readable name, whether it comes from the carrier master or from the text the delivery note kept. The tracking address travels whole or empty and never half: a carrier that is tracked by phone or through the store has no address to compose. What is never published here: the origin warehouse, the picking list, the delivery signature and who signed it, the cost of the shipment, the internal reference of the packages, and the identifier of the delivery note itself — the shopper bought an order, and the document of the seller is not theirs. Same window as the order: 410 `link_expired` or 410 `link_revoked` when the public link of the order is closed, and 404 `sales_order_not_found` for an order of another company.

### Example Usage: con_envio

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.orders.tracking" method="get" path="/companies/{company}/storefront/orders/{order}/tracking" example="con_envio" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->storefront->orders->publicApiV1StorefrontOrdersTracking(
    company: 'Greenholt - Krajcik',
    order: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: sin_envio

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.orders.tracking" method="get" path="/companies/{company}/storefront/orders/{order}/tracking" example="sin_envio" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->storefront->orders->publicApiV1StorefrontOrdersTracking(
    company: 'Hudson - Durgan',
    order: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                        | Type                                                                                                                                                                                                                                                                                                                                                                                             | Required                                                                                                                                                                                                                                                                                                                                                                                         | Description                                                                                                                                                                                                                                                                                                                                                                                      | Example                                                                                                                                                                                                                                                                                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `company`                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `order`                                                                                                                                                                                                                                                                                                                                                                                          | *string*                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1StorefrontOrdersTrackingResponse](../../Models/Operations/PublicApiV1StorefrontOrdersTrackingResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 410, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1StorefrontOrdersCheckout

Issue the payment address a shopper is sent to in order to pay an order that is already placed, and get back three things: the address, the instant it stops working and whether the effect was simulated. It is authenticated with the publishable storefront key that travels in the browser of an anonymous shopper. The amount, the currency and the gateway account are ALWAYS the server's and are resolved from the order itself: no amount, no currency and no account is taken from the body, and one that travels there is ignored — accepting it would turn this route into a form with which to set the price of a purchase. The body carries only the RELATIVE path the shopper is returned to, at most 512 characters, and the explicit request of a full invoice. An absolute address is rejected by FORM even when it points at your own domain, because the origin is taken from the origins declared in the publishable credential: what the gateway sends the shopper to is always a site you declared. Asking again while the address is still valid returns the SAME address and leaves ONE single session in the gateway, so this call is safe to retry and it is not the operation that needs an idempotency key; the address outlives the shopper hesitating and expires on its own, below the life the gateway gives its own session. An order that cannot be paid — cancelled, already invoiced, already charged — is rejected with 422 `sales_order_not_payable`, and an order over the ceiling of the simplified invoice whose buyer has no fiscal identity with 422 `buyer_identity_incomplete_for_full_invoice`. When the merchant has NOT connected a payment provider the answer is 422 `payment_provider_not_connected` and not a payment error: what is missing is a connection of the seller, and there is nothing the shopper can pay to fix it. An order of another company answers 404 `sales_order_not_found`. With the company in TEST MODE the lane keeps working and the address comes back marked as simulated: a simulated link is not a link that is missing, it is a link that exists and does not charge.

### Example Usage: enlace_real

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.orders.checkout" method="post" path="/companies/{company}/storefront/orders/{order}/checkout" example="enlace_real" -->
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

$request = new Operations\PublicApiV1StorefrontOrdersCheckoutRequest(
    company: 'MacGyver, Daniel and Kemmer',
    order: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->storefront->orders->publicApiV1StorefrontOrdersCheckout(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: enlace_simulado

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.orders.checkout" method="post" path="/companies/{company}/storefront/orders/{order}/checkout" example="enlace_simulado" -->
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

$request = new Operations\PublicApiV1StorefrontOrdersCheckoutRequest(
    company: 'Hilll - Beatty',
    order: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->storefront->orders->publicApiV1StorefrontOrdersCheckout(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                      | Type                                                                                                                           | Required                                                                                                                       | Description                                                                                                                    |
| ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                     | [Operations\PublicApiV1StorefrontOrdersCheckoutRequest](../../Models/Operations/PublicApiV1StorefrontOrdersCheckoutRequest.md) | :heavy_check_mark:                                                                                                             | The request object to use for the request.                                                                                     |

### Response

**[?Operations\PublicApiV1StorefrontOrdersCheckoutResponse](../../Models/Operations/PublicApiV1StorefrontOrdersCheckoutResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |