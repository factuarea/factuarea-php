# GestoriaSeatOfferRedemptions

## Overview

### Available Operations

* [publicApiV1GestoriaSeatOfferRedemptionsCancel](#publicapiv1gestoriaseatofferredemptionscancel) - Cancel a managed-company-seat offer redemption
* [publicApiV1GestoriaSeatOfferRedemptionsConfirm](#publicapiv1gestoriaseatofferredemptionsconfirm) - Confirm a managed-company-seat offer redemption
* [publicApiV1GestoriaSeatOfferRedemptionsList](#publicapiv1gestoriaseatofferredemptionslist) - List a managed-company-seat offer redemption
* [publicApiV1GestoriaSeatOfferRedemptionsPrepare](#publicapiv1gestoriaseatofferredemptionsprepare) - Prepare a managed-company-seat offer redemption
* [publicApiV1GestoriaSeatOfferRedemptionsRefreshQuote](#publicapiv1gestoriaseatofferredemptionsrefreshquote) - Refresh the quote for a managed-company-seat offer redemption
* [publicApiV1GestoriaSeatOfferRedemptionsShow](#publicapiv1gestoriaseatofferredemptionsshow) - Retrieve a managed-company-seat offer redemption

## publicApiV1GestoriaSeatOfferRedemptionsCancel

Cancel a managed-company-seat offer redemption before it is confirmed.
- Cancelling never charges; the offer is not consumed.
- Use a stable Idempotency-Key and GET the same UUID to read the final state.
- Unavailable or other-company codes get the same controlled rejection.

### Example Usage: current_operation

<!-- UsageSnippet language="php" operationID="public-api.v1.gestoria-seat-offer-redemptions.cancel" method="post" path="/companies/{company}/gestoria-seat-offer-redemptions/{id}/cancel" example="current_operation" -->
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

$request = new Operations\PublicApiV1GestoriaSeatOfferRedemptionsCancelRequest(
    company: '6bd57324-a7a5-415d-9e66-b1c39e2e087d',
    id: '69f7fc67-5ce3-4ae2-80c7-08412932aa8c',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CancelDurableOperationBody(),
);

$response = $sdk->gestoriaSeatOfferRedemptions->publicApiV1GestoriaSeatOfferRedemptionsCancel(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.gestoria-seat-offer-redemptions.cancel" method="post" path="/companies/{company}/gestoria-seat-offer-redemptions/{id}/cancel" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1GestoriaSeatOfferRedemptionsCancelRequest(
    company: 'd85299c3-90b1-432a-937c-8ed68afabcd5',
    id: '40594a27-e2a7-42dd-88d0-5eea6cabe2a1',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CancelDurableOperationBody(),
);

$response = $sdk->gestoriaSeatOfferRedemptions->publicApiV1GestoriaSeatOfferRedemptionsCancel(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.gestoria-seat-offer-redemptions.cancel" method="post" path="/companies/{company}/gestoria-seat-offer-redemptions/{id}/cancel" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1GestoriaSeatOfferRedemptionsCancelRequest(
    company: 'ae830639-7120-4d06-bd37-a46063ef21df',
    id: '96d9da29-6d5c-49e4-b5e8-4e4081385a68',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CancelDurableOperationBody(),
);

$response = $sdk->gestoriaSeatOfferRedemptions->publicApiV1GestoriaSeatOfferRedemptionsCancel(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.gestoria-seat-offer-redemptions.cancel" method="post" path="/companies/{company}/gestoria-seat-offer-redemptions/{id}/cancel" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1GestoriaSeatOfferRedemptionsCancelRequest(
    company: '54433cf3-ba7d-4bca-bbf8-0537c67f0ee1',
    id: 'feb0c8bc-031c-414f-b6df-937090beb834',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CancelDurableOperationBody(),
);

$response = $sdk->gestoriaSeatOfferRedemptions->publicApiV1GestoriaSeatOfferRedemptionsCancel(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                          | Type                                                                                                                                               | Required                                                                                                                                           | Description                                                                                                                                        |
| -------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                         | [Operations\PublicApiV1GestoriaSeatOfferRedemptionsCancelRequest](../../Models/Operations/PublicApiV1GestoriaSeatOfferRedemptionsCancelRequest.md) | :heavy_check_mark:                                                                                                                                 | The request object to use for the request.                                                                                                         |

### Response

**[?Operations\PublicApiV1GestoriaSeatOfferRedemptionsCancelResponse](../../Models/Operations/PublicApiV1GestoriaSeatOfferRedemptionsCancelResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1GestoriaSeatOfferRedemptionsConfirm

Confirm a managed-company-seat offer redemption, charging the reviewed quote.
- Send exactly the reviewed `quote_version` with a stable Idempotency-Key.
- After a 202, failure or replay, GET the same UUID until terminal; never create a second purchase.
- Existing discounts are never silently stacked or replaced.

### Example Usage: current_operation

<!-- UsageSnippet language="php" operationID="public-api.v1.gestoria-seat-offer-redemptions.confirm" method="post" path="/companies/{company}/gestoria-seat-offer-redemptions/{id}/confirm" example="current_operation" -->
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

$request = new Operations\PublicApiV1GestoriaSeatOfferRedemptionsConfirmRequest(
    company: '160916a2-3b89-4f4e-8ea9-4b4fc7bdcb4a',
    id: '42e0eed2-36d1-4754-b540-dd9e6ee0c49a',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedDurableOperationBody(
        quoteVersion: 619519,
    ),
);

$response = $sdk->gestoriaSeatOfferRedemptions->publicApiV1GestoriaSeatOfferRedemptionsConfirm(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.gestoria-seat-offer-redemptions.confirm" method="post" path="/companies/{company}/gestoria-seat-offer-redemptions/{id}/confirm" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1GestoriaSeatOfferRedemptionsConfirmRequest(
    company: 'dff2ca9c-3995-424d-b0f5-f144a0ba5611',
    id: '8f882e00-ef60-45ef-ac5b-6d8d1b9c3da4',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedDurableOperationBody(
        quoteVersion: 619519,
    ),
);

$response = $sdk->gestoriaSeatOfferRedemptions->publicApiV1GestoriaSeatOfferRedemptionsConfirm(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.gestoria-seat-offer-redemptions.confirm" method="post" path="/companies/{company}/gestoria-seat-offer-redemptions/{id}/confirm" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1GestoriaSeatOfferRedemptionsConfirmRequest(
    company: 'c28533f5-925e-44c3-9971-45b3cfc264b1',
    id: 'b0106b73-7249-4981-9a0c-9baceba1efba',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedDurableOperationBody(
        quoteVersion: 619519,
    ),
);

$response = $sdk->gestoriaSeatOfferRedemptions->publicApiV1GestoriaSeatOfferRedemptionsConfirm(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.gestoria-seat-offer-redemptions.confirm" method="post" path="/companies/{company}/gestoria-seat-offer-redemptions/{id}/confirm" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1GestoriaSeatOfferRedemptionsConfirmRequest(
    company: 'e1d2d8f5-0667-49be-ab7b-0480b049db60',
    id: '9f07195b-866b-475c-a20e-ddddfe866068',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedDurableOperationBody(
        quoteVersion: 619519,
    ),
);

$response = $sdk->gestoriaSeatOfferRedemptions->publicApiV1GestoriaSeatOfferRedemptionsConfirm(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                            | Type                                                                                                                                                 | Required                                                                                                                                             | Description                                                                                                                                          |
| ---------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                           | [Operations\PublicApiV1GestoriaSeatOfferRedemptionsConfirmRequest](../../Models/Operations/PublicApiV1GestoriaSeatOfferRedemptionsConfirmRequest.md) | :heavy_check_mark:                                                                                                                                   | The request object to use for the request.                                                                                                           |

### Response

**[?Operations\PublicApiV1GestoriaSeatOfferRedemptionsConfirmResponse](../../Models/Operations/PublicApiV1GestoriaSeatOfferRedemptionsConfirmResponse.md)**

### Errors

| Error Type                             | Status Code                            | Content Type                           |
| -------------------------------------- | -------------------------------------- | -------------------------------------- |
| Errors\Error                           | 400, 401, 402, 403, 404, 409, 422, 429 | application/json                       |
| Errors\Error                           | 500                                    | application/json                       |
| Errors\Error                           | 503                                    | application/json                       |
| Errors\APIException                    | 4XX, 5XX                               | \*/\*                                  |

## publicApiV1GestoriaSeatOfferRedemptionsList

List a managed-company-seat offer redemptions of the company.
- Financial history exposes no private code, profile or Stripe identifiers.
- The private offer belongs to the paying company; unavailable or other-company codes get the same rejection.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.gestoria-seat-offer-redemptions.list" method="get" path="/companies/{company}/gestoria-seat-offer-redemptions" example="current_operation" -->
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

$request = new Operations\PublicApiV1GestoriaSeatOfferRedemptionsListRequest(
    company: 'afa0144b-520f-4b4e-a463-60a11347ddf4',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->gestoriaSeatOfferRedemptions->publicApiV1GestoriaSeatOfferRedemptionsList(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                      | Type                                                                                                                                           | Required                                                                                                                                       | Description                                                                                                                                    |
| ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                     | [Operations\PublicApiV1GestoriaSeatOfferRedemptionsListRequest](../../Models/Operations/PublicApiV1GestoriaSeatOfferRedemptionsListRequest.md) | :heavy_check_mark:                                                                                                                             | The request object to use for the request.                                                                                                     |

### Response

**[?Operations\PublicApiV1GestoriaSeatOfferRedemptionsListResponse](../../Models/Operations/PublicApiV1GestoriaSeatOfferRedemptionsListResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1GestoriaSeatOfferRedemptionsPrepare

Prepare a managed-company-seat offer redemption and its quote without charging.
- Preparing never charges or consumes the offer; confirm later with a stable Idempotency-Key.
- The private offer belongs to the paying company; unavailable or other-company codes get the same rejection.
- Existing discounts are never silently stacked or replaced.

### Example Usage: current_operation

<!-- UsageSnippet language="php" operationID="public-api.v1.gestoria-seat-offer-redemptions.prepare" method="post" path="/companies/{company}/gestoria-seat-offer-redemptions" example="current_operation" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\PrepareGestoriaSeatOfferBody(
    code: '<value>',
    purchase: new Components\PurchaseActivate(
        companyId: '22a2a23f-3757-493a-9514-904bee583229',
    ),
);

$response = $sdk->gestoriaSeatOfferRedemptions->publicApiV1GestoriaSeatOfferRedemptionsPrepare(
    company: '58cc2612-3bf0-49d5-8d3e-bf416f80359f',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.gestoria-seat-offer-redemptions.prepare" method="post" path="/companies/{company}/gestoria-seat-offer-redemptions" example="factuarea_version_invalid_format" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\PrepareGestoriaSeatOfferBody(
    code: '<value>',
    purchase: new Components\PurchaseActivate(
        companyId: '22a2a23f-3757-493a-9514-904bee583229',
    ),
);

$response = $sdk->gestoriaSeatOfferRedemptions->publicApiV1GestoriaSeatOfferRedemptionsPrepare(
    company: 'baa3ea72-f08f-4a29-a96b-ec7ed5299835',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.gestoria-seat-offer-redemptions.prepare" method="post" path="/companies/{company}/gestoria-seat-offer-redemptions" example="retired_active_profile_header" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\PrepareGestoriaSeatOfferBody(
    code: '<value>',
    purchase: new Components\PurchaseActivate(
        companyId: '22a2a23f-3757-493a-9514-904bee583229',
    ),
);

$response = $sdk->gestoriaSeatOfferRedemptions->publicApiV1GestoriaSeatOfferRedemptionsPrepare(
    company: '8c07af9f-1984-41a2-9ba5-772b28371df3',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.gestoria-seat-offer-redemptions.prepare" method="post" path="/companies/{company}/gestoria-seat-offer-redemptions" example="unsupported_api_version" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\PrepareGestoriaSeatOfferBody(
    code: '<value>',
    purchase: new Components\PurchaseActivate(
        companyId: '22a2a23f-3757-493a-9514-904bee583229',
    ),
);

$response = $sdk->gestoriaSeatOfferRedemptions->publicApiV1GestoriaSeatOfferRedemptionsPrepare(
    company: '76991d18-7227-4756-a3a6-a3485d5a6af2',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Components\PrepareGestoriaSeatOfferBody](../../Models/Components/PrepareGestoriaSeatOfferBody.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1GestoriaSeatOfferRedemptionsPrepareResponse](../../Models/Operations/PublicApiV1GestoriaSeatOfferRedemptionsPrepareResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\Error                      | 503                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1GestoriaSeatOfferRedemptionsRefreshQuote

Refresh the quote of a managed-company-seat offer redemption.
- Refreshing never charges or consumes the offer; confirm exactly the new `quote_version`.
- The private offer belongs to the paying company; unavailable or other-company codes get the same rejection.

### Example Usage: current_operation

<!-- UsageSnippet language="php" operationID="public-api.v1.gestoria-seat-offer-redemptions.refresh_quote" method="post" path="/companies/{company}/gestoria-seat-offer-redemptions/{id}/quote" example="current_operation" -->
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

$request = new Operations\PublicApiV1GestoriaSeatOfferRedemptionsRefreshQuoteRequest(
    company: '7f249829-3b72-48e0-882f-0a68ea7e4b31',
    id: 'f6759bf9-0856-4d2e-ac1e-f21c991ab723',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedDurableOperationBody(
        quoteVersion: 246897,
    ),
);

$response = $sdk->gestoriaSeatOfferRedemptions->publicApiV1GestoriaSeatOfferRedemptionsRefreshQuote(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.gestoria-seat-offer-redemptions.refresh_quote" method="post" path="/companies/{company}/gestoria-seat-offer-redemptions/{id}/quote" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1GestoriaSeatOfferRedemptionsRefreshQuoteRequest(
    company: 'fe4e9353-03e7-4de3-a485-97e958998029',
    id: '5ae4fe89-91f7-4cb9-80ac-52c9f0abc8ce',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedDurableOperationBody(
        quoteVersion: 246897,
    ),
);

$response = $sdk->gestoriaSeatOfferRedemptions->publicApiV1GestoriaSeatOfferRedemptionsRefreshQuote(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.gestoria-seat-offer-redemptions.refresh_quote" method="post" path="/companies/{company}/gestoria-seat-offer-redemptions/{id}/quote" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1GestoriaSeatOfferRedemptionsRefreshQuoteRequest(
    company: '3ef3715e-af40-4e60-aa98-8d952a6e0a48',
    id: '46bda620-c8f2-4cc7-a120-7570cf8e17f0',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedDurableOperationBody(
        quoteVersion: 246897,
    ),
);

$response = $sdk->gestoriaSeatOfferRedemptions->publicApiV1GestoriaSeatOfferRedemptionsRefreshQuote(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.gestoria-seat-offer-redemptions.refresh_quote" method="post" path="/companies/{company}/gestoria-seat-offer-redemptions/{id}/quote" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1GestoriaSeatOfferRedemptionsRefreshQuoteRequest(
    company: 'ee7995a5-6860-4cd3-b2f2-6b5e204a1f43',
    id: '6c8efd9c-7f2e-41df-8294-5773e5132624',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedDurableOperationBody(
        quoteVersion: 246897,
    ),
);

$response = $sdk->gestoriaSeatOfferRedemptions->publicApiV1GestoriaSeatOfferRedemptionsRefreshQuote(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                      | Type                                                                                                                                                           | Required                                                                                                                                                       | Description                                                                                                                                                    |
| -------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                                     | [Operations\PublicApiV1GestoriaSeatOfferRedemptionsRefreshQuoteRequest](../../Models/Operations/PublicApiV1GestoriaSeatOfferRedemptionsRefreshQuoteRequest.md) | :heavy_check_mark:                                                                                                                                             | The request object to use for the request.                                                                                                                     |

### Response

**[?Operations\PublicApiV1GestoriaSeatOfferRedemptionsRefreshQuoteResponse](../../Models/Operations/PublicApiV1GestoriaSeatOfferRedemptionsRefreshQuoteResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\Error                      | 503                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1GestoriaSeatOfferRedemptionsShow

Retrieve a managed-company-seat offer redemption.
- GET the same UUID after a 202 or failure until a verified terminal state.
- Unknown totals never mean free; no private code or Stripe identifiers are exposed.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.gestoria-seat-offer-redemptions.show" method="get" path="/companies/{company}/gestoria-seat-offer-redemptions/{id}" example="current_operation" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->gestoriaSeatOfferRedemptions->publicApiV1GestoriaSeatOfferRedemptionsShow(
    company: '6f3de54d-a44b-4b95-b6d1-f8ace64e0881',
    id: '3b76d135-dc32-4942-b66d-f7bc9e2185ee',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `id`                                                                                                                                                                                                                                                                                                                                                                         | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the additional NIF seat offer redemption.                                                                                                                                                                                                                                                                                                     |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1GestoriaSeatOfferRedemptionsShowResponse](../../Models/Operations/PublicApiV1GestoriaSeatOfferRedemptionsShowResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |