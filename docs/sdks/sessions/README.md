# Storefront.Sessions

## Overview

### Available Operations

* [publicApiV1StorefrontSessionsCreate](#publicapiv1storefrontsessionscreate) - Create a cart session
* [publicApiV1StorefrontSessionsRevalidate](#publicapiv1storefrontsessionsrevalidate) - Revalidate a cart session
* [publicApiV1StorefrontSessionsShow](#publicapiv1storefrontsessionsshow) - Retrieve a cart session
* [publicApiV1StorefrontSessionsUpdate](#publicapiv1storefrontsessionsupdate) - Update a cart session

## publicApiV1StorefrontSessionsCreate

Open a cart session that holds the shopping of a shopper between visits and that the order creation later converts. It is authenticated with the publishable storefront key that travels in the browser of an anonymous shopper, and it REQUIRES an `Idempotency-Key` header: retrying with the same key returns the same session instead of opening a second one. The session stores, line by line, the COMPLETE selection — article, variant, presentation, configuration, option values and quantity — and the amounts LAST RESOLVED BY THE SERVER together with the moment they were resolved; any amount present in the body is ignored and resolved again, because the browser of a shopper does not get to set a price. It is born with an expiry computed from the lifetime configured for the storefront, 24 hours by default, and its public id is an unguessable UUID that is the ONLY secret separating the cart of one shopper from the cart of another, so treat it as a secret and never as a number you can walk. Returns the session with its lines, its buyer identity when it already carries one, its totals, the moment its prices were resolved, its expiry and whether it is already expired. No stock is held by opening a cart: units are checked when the order is created.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.sessions.create" method="post" path="/companies/{company}/storefront/sessions" -->
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

$request = new Operations\PublicApiV1StorefrontSessionsCreateRequest(
    company: 'Strosin, Spencer and Ward',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateStorefrontSessionRequest(
        lines: [
            new Components\CreateStorefrontSessionRequestLine(
                productId: '0199f2a3-a101-7c3d-8e4f-d0e1f2a3b401',
                variantId: '0199f2a3-b101-7d4e-9f50-d0e1f2a3b411',
                presentationId: '0199f2a3-c101-7e5f-8061-d0e1f2a3b421',
                configurationId: '0199f2a3-a202-7293-84a5-d0e1f2a3b402',
                options: [
                    new Components\CreateStorefrontSessionRequestOption(
                        groupId: '0199f2a3-d101-7f60-9172-d0e1f2a3b431',
                        valueId: '0199f2a3-e101-7071-8283-d0e1f2a3b441',
                    ),
                ],
                quantity: 2,
            ),
            new Components\CreateStorefrontSessionRequestLine(
                productId: '0199f2a3-a505-7d4e-9f50-d0e1f2a3b405',
                quantity: 6,
            ),
        ],
    ),
);

$response = $sdk->storefront->sessions->publicApiV1StorefrontSessionsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                      | Type                                                                                                                           | Required                                                                                                                       | Description                                                                                                                    |
| ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                     | [Operations\PublicApiV1StorefrontSessionsCreateRequest](../../Models/Operations/PublicApiV1StorefrontSessionsCreateRequest.md) | :heavy_check_mark:                                                                                                             | The request object to use for the request.                                                                                     |

### Response

**[?Operations\PublicApiV1StorefrontSessionsCreateResponse](../../Models/Operations/PublicApiV1StorefrontSessionsCreateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1StorefrontSessionsRevalidate

Re-resolve the price and the availability of every line of a cart session and get back, line by line, whether the amount changed and whether there are still units. This is the call to make right before the shopper pays: it is what lets a storefront say "this went up" or "this ran out" BEFORE charging, instead of discovering it when the order is rejected. It is authenticated with the publishable storefront key that travels in the browser of an anonymous shopper. Every line comes back with its line id, the previous unit price and the current one, whether the price changed, the quantity, the available total, whether it is sufficient, whether the combination can still be offered and a reason code when it cannot; the envelope adds the refreshed session and two flags, whether anything changed at all and whether every line is sufficient. It writes nothing but the amounts the server has just resolved, so it can be repeated as often as you need. An expired, converted or abandoned session rejects it with its own business-rule error.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.sessions.revalidate" method="post" path="/companies/{company}/storefront/sessions/{session}/revalidate" -->
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

$request = new Operations\PublicApiV1StorefrontSessionsRevalidateRequest(
    company: 'Hansen, Satterfield and Haley',
    session: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->storefront->sessions->publicApiV1StorefrontSessionsRevalidate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                              | Type                                                                                                                                   | Required                                                                                                                               | Description                                                                                                                            |
| -------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                             | [Operations\PublicApiV1StorefrontSessionsRevalidateRequest](../../Models/Operations/PublicApiV1StorefrontSessionsRevalidateRequest.md) | :heavy_check_mark:                                                                                                                     | The request object to use for the request.                                                                                             |

### Response

**[?Operations\PublicApiV1StorefrontSessionsRevalidateResponse](../../Models/Operations/PublicApiV1StorefrontSessionsRevalidateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1StorefrontSessionsShow

Retrieve a cart session by its public id, with its status, its lines and the amounts the server resolved, its buyer identity when it carries one, its totals, the moment the prices were resolved, its expiry and whether it is already expired. It is authenticated with the publishable storefront key that travels in the browser of an anonymous shopper. The EXPIRY is evaluated ON READ and does not depend on any scheduled job, so a session past its expiry is readable and comes back flagged as expired while the write operations of the lane reject it. A session of another company, and an id that does not exist, answer exactly the same way — the id is the only secret that protects the cart, so probing ids tells you nothing.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.sessions.show" method="get" path="/companies/{company}/storefront/sessions/{session}" -->
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



$response = $sdk->storefront->sessions->publicApiV1StorefrontSessionsShow(
    company: 'Kirlin, Sipes and Renner',
    session: '<value>',
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
| `session`                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1StorefrontSessionsShowResponse](../../Models/Operations/PublicApiV1StorefrontSessionsShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1StorefrontSessionsUpdate

Partially update a cart session: its lines and the identity of the shopper. Only what travels in the body changes and an absent field keeps its value, so updating just the buyer leaves the three lines of the cart untouched. It is authenticated with the publishable storefront key that travels in the browser of an anonymous shopper. Amounts are NEVER taken from the body: a unit price you send is ignored and the server resolves the price of every line again, which is the only defence against a manipulated browser. A session that has EXPIRED, one that was already turned into an ORDER and one that was ABANDONED each reject the call with their own business-rule error, and each one has a different remedy — open another session, read the order that was created, or open another session —, which is why they are three codes and not one. A session of another company answers as not found. Returns the updated session with the amounts of the server.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront.sessions.update" method="patch" path="/companies/{company}/storefront/sessions/{session}" -->
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

$request = new Operations\PublicApiV1StorefrontSessionsUpdateRequest(
    company: 'Schoen - Beahan',
    session: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\UpdateStorefrontSessionRequest(
        buyerName: 'Comercial Arganda, S.L.',
        buyerEmail: 'compras@arganda.example',
        buyerPhone: '+34 918 765 432',
        buyerTaxId: 'B86544321',
        shippingAddressLine: 'Polígono Industrial Las Eras, nave 7',
        shippingCity: 'Arganda del Rey',
        shippingProvince: 'Madrid',
        shippingPostalCode: '28500',
        shippingCountry: 'ES',
        billingAddressLine: 'Polígono Industrial Las Eras, nave 7',
        billingCity: 'Arganda del Rey',
        billingProvince: 'Madrid',
        billingPostalCode: '28500',
        billingCountry: 'ES',
    ),
);

$response = $sdk->storefront->sessions->publicApiV1StorefrontSessionsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                      | Type                                                                                                                           | Required                                                                                                                       | Description                                                                                                                    |
| ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                     | [Operations\PublicApiV1StorefrontSessionsUpdateRequest](../../Models/Operations/PublicApiV1StorefrontSessionsUpdateRequest.md) | :heavy_check_mark:                                                                                                             | The request object to use for the request.                                                                                     |

### Response

**[?Operations\PublicApiV1StorefrontSessionsUpdateResponse](../../Models/Operations/PublicApiV1StorefrontSessionsUpdateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |