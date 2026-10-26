# EmployeeBatches

## Overview

### Available Operations

* [publicApiV1EmployeeBatchesCancel](#publicapiv1employeebatchescancel) - Cancel an employee batch
* [publicApiV1EmployeeBatchesConfirm](#publicapiv1employeebatchesconfirm) - Confirm an employee batch
* [publicApiV1EmployeeBatchesList](#publicapiv1employeebatcheslist) - List an employee batch
* [publicApiV1EmployeeBatchesPrepare](#publicapiv1employeebatchesprepare) - Prepare an employee batch
* [publicApiV1EmployeeBatchesRefreshQuote](#publicapiv1employeebatchesrefreshquote) - Refresh the quote for an employee batch
* [publicApiV1EmployeeBatchesShow](#publicapiv1employeebatchesshow) - Retrieve an employee batch

## publicApiV1EmployeeBatchesCancel

The current credential and tenant own this operation. Preparing and refreshing never charges, provisions people or consumes an offer. Confirm exactly the reviewed quote_version with a stable Idempotency-Key. After a 202, transport failure or replay, GET this same UUID until a verified terminal state; do not create a second purchase. Unknown totals never mean free. Financial history contains no private code, profile, Stripe identifiers or internal snapshot. A batch contains 1 to 100 explicit create profiles or inactive employee UUIDs; it sends no invitations. The entire batch is provisioned only after one verified financial outcome.

### Example Usage: current_operation

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-batches.cancel" method="post" path="/companies/{company}/employee-batches/{id}/cancel" example="current_operation" -->
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

$request = new Operations\PublicApiV1EmployeeBatchesCancelRequest(
    company: '8f0ecd94-86d2-4a50-b3a7-816689c6782e',
    id: 'b515f0a5-b2d4-43ea-aa55-f0c7ede9bade',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CancelDurableOperationBody(),
);

$response = $sdk->employeeBatches->publicApiV1EmployeeBatchesCancel(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-batches.cancel" method="post" path="/companies/{company}/employee-batches/{id}/cancel" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1EmployeeBatchesCancelRequest(
    company: '1b92a39e-8dab-407b-8830-9efe1ad8ad77',
    id: '25c9ed4c-b047-4aa2-a439-2d0ce3a8a981',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CancelDurableOperationBody(),
);

$response = $sdk->employeeBatches->publicApiV1EmployeeBatchesCancel(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                | Type                                                                                                                     | Required                                                                                                                 | Description                                                                                                              |
| ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                               | [Operations\PublicApiV1EmployeeBatchesCancelRequest](../../Models/Operations/PublicApiV1EmployeeBatchesCancelRequest.md) | :heavy_check_mark:                                                                                                       | The request object to use for the request.                                                                               |

### Response

**[?Operations\PublicApiV1EmployeeBatchesCancelResponse](../../Models/Operations/PublicApiV1EmployeeBatchesCancelResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1EmployeeBatchesConfirm

The current credential and tenant own this operation. Preparing and refreshing never charges, provisions people or consumes an offer. Confirm exactly the reviewed quote_version with a stable Idempotency-Key. After a 202, transport failure or replay, GET this same UUID until a verified terminal state; do not create a second purchase. Unknown totals never mean free. Financial history contains no private code, profile, Stripe identifiers or internal snapshot. A batch contains 1 to 100 explicit create profiles or inactive employee UUIDs; it sends no invitations. The entire batch is provisioned only after one verified financial outcome.

### Example Usage: current_operation

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-batches.confirm" method="post" path="/companies/{company}/employee-batches/{id}/confirm" example="current_operation" -->
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

$request = new Operations\PublicApiV1EmployeeBatchesConfirmRequest(
    company: '013d210f-ef5c-4133-a8b9-fc78c0b88bb6',
    id: 'bf86d2e2-c4b2-4b20-8cf2-ff71fe3bc79e',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedEmployeeBatchBody(
        quoteVersion: 651929,
    ),
);

$response = $sdk->employeeBatches->publicApiV1EmployeeBatchesConfirm(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: employee_batch_quote_unavailable

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-batches.confirm" method="post" path="/companies/{company}/employee-batches/{id}/confirm" example="employee_batch_quote_unavailable" -->
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

$request = new Operations\PublicApiV1EmployeeBatchesConfirmRequest(
    company: '2a05eaa0-e20c-4503-8fd7-c051a68de734',
    id: '6e056557-f2c8-49c8-b60e-86ebecdcf02e',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedEmployeeBatchBody(
        quoteVersion: 651929,
    ),
);

$response = $sdk->employeeBatches->publicApiV1EmployeeBatchesConfirm(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1EmployeeBatchesConfirmRequest](../../Models/Operations/PublicApiV1EmployeeBatchesConfirmRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1EmployeeBatchesConfirmResponse](../../Models/Operations/PublicApiV1EmployeeBatchesConfirmResponse.md)**

### Errors

| Error Type                             | Status Code                            | Content Type                           |
| -------------------------------------- | -------------------------------------- | -------------------------------------- |
| Errors\Error                           | 400, 401, 402, 403, 404, 409, 422, 429 | application/json                       |
| Errors\Error                           | 500                                    | application/json                       |
| Errors\Error                           | 503                                    | application/json                       |
| Errors\APIException                    | 4XX, 5XX                               | \*/\*                                  |

## publicApiV1EmployeeBatchesList

The current credential and tenant own this operation. Preparing and refreshing never charges, provisions people or consumes an offer. Confirm exactly the reviewed quote_version with a stable Idempotency-Key. After a 202, transport failure or replay, GET this same UUID until a verified terminal state; do not create a second purchase. Unknown totals never mean free. Financial history contains no private code, profile, Stripe identifiers or internal snapshot. A batch contains 1 to 100 explicit create profiles or inactive employee UUIDs; it sends no invitations. The entire batch is provisioned only after one verified financial outcome.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-batches.list" method="get" path="/companies/{company}/employee-batches" example="current_operation" -->
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

$request = new Operations\PublicApiV1EmployeeBatchesListRequest(
    company: '602bed0e-8f7d-457e-90c8-14a2814b68f0',
    statuses: [
        Operations\PublicApiV1EmployeeBatchesListStatuses::Draft,
    ],
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->employeeBatches->publicApiV1EmployeeBatchesList(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                            | Type                                                                                                                 | Required                                                                                                             | Description                                                                                                          |
| -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                           | [Operations\PublicApiV1EmployeeBatchesListRequest](../../Models/Operations/PublicApiV1EmployeeBatchesListRequest.md) | :heavy_check_mark:                                                                                                   | The request object to use for the request.                                                                           |

### Response

**[?Operations\PublicApiV1EmployeeBatchesListResponse](../../Models/Operations/PublicApiV1EmployeeBatchesListResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1EmployeeBatchesPrepare

The current credential and tenant own this operation. Preparing and refreshing never charges, provisions people or consumes an offer. Confirm exactly the reviewed quote_version with a stable Idempotency-Key. After a 202, transport failure or replay, GET this same UUID until a verified terminal state; do not create a second purchase. Unknown totals never mean free. Financial history contains no private code, profile, Stripe identifiers or internal snapshot. A batch contains 1 to 100 explicit create profiles or inactive employee UUIDs; it sends no invitations. The entire batch is provisioned only after one verified financial outcome.

### Example Usage: current_operation

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-batches.prepare" method="post" path="/companies/{company}/employee-batches" example="current_operation" -->
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



$response = $sdk->employeeBatches->publicApiV1EmployeeBatchesPrepare(
    company: '98755554-255a-42c7-ad14-b1cf25801554',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: new Components\PrepareEmployeeBatchBodyReactivate(
        items: [],
    ),
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: employee_batch_quote_unavailable

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-batches.prepare" method="post" path="/companies/{company}/employee-batches" example="employee_batch_quote_unavailable" -->
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



$response = $sdk->employeeBatches->publicApiV1EmployeeBatchesPrepare(
    company: '0077fe47-b6c9-457b-ad17-1259ec2a71c1',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: new Components\PrepareEmployeeBatchBodyReactivate(
        items: [
            new Components\PrepareEmployeeBatchBodyItem2(
                rowId: '<id>',
                employeeId: '073f8ffc-3160-400a-81c1-17699b57f180',
            ),
        ],
    ),
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
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Components\PrepareEmployeeBatchBodyCreate\|Components\PrepareEmployeeBatchBodyReactivate](../../Models/Components/PrepareEmployeeBatchBody.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1EmployeeBatchesPrepareResponse](../../Models/Operations/PublicApiV1EmployeeBatchesPrepareResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\Error                      | 503                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1EmployeeBatchesRefreshQuote

The current credential and tenant own this operation. Preparing and refreshing never charges, provisions people or consumes an offer. Confirm exactly the reviewed quote_version with a stable Idempotency-Key. After a 202, transport failure or replay, GET this same UUID until a verified terminal state; do not create a second purchase. Unknown totals never mean free. Financial history contains no private code, profile, Stripe identifiers or internal snapshot. A batch contains 1 to 100 explicit create profiles or inactive employee UUIDs; it sends no invitations. The entire batch is provisioned only after one verified financial outcome.

### Example Usage: current_operation

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-batches.refresh_quote" method="post" path="/companies/{company}/employee-batches/{id}/quote" example="current_operation" -->
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

$request = new Operations\PublicApiV1EmployeeBatchesRefreshQuoteRequest(
    company: '66ef1e43-d435-41d5-8352-5d3f1064a064',
    id: '42f0159f-7c1d-4503-88a4-861764095e86',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedEmployeeBatchBody(
        quoteVersion: 877140,
    ),
);

$response = $sdk->employeeBatches->publicApiV1EmployeeBatchesRefreshQuote(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: employee_batch_quote_unavailable

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-batches.refresh_quote" method="post" path="/companies/{company}/employee-batches/{id}/quote" example="employee_batch_quote_unavailable" -->
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

$request = new Operations\PublicApiV1EmployeeBatchesRefreshQuoteRequest(
    company: '9ddc555d-48ce-4741-b1ad-6222fff05b15',
    id: 'b3223eee-9a96-47e2-948f-91d7bd622931',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedEmployeeBatchBody(
        quoteVersion: 877140,
    ),
);

$response = $sdk->employeeBatches->publicApiV1EmployeeBatchesRefreshQuote(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                            | Type                                                                                                                                 | Required                                                                                                                             | Description                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                           | [Operations\PublicApiV1EmployeeBatchesRefreshQuoteRequest](../../Models/Operations/PublicApiV1EmployeeBatchesRefreshQuoteRequest.md) | :heavy_check_mark:                                                                                                                   | The request object to use for the request.                                                                                           |

### Response

**[?Operations\PublicApiV1EmployeeBatchesRefreshQuoteResponse](../../Models/Operations/PublicApiV1EmployeeBatchesRefreshQuoteResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\Error                      | 503                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1EmployeeBatchesShow

The current credential and tenant own this operation. Preparing and refreshing never charges, provisions people or consumes an offer. Confirm exactly the reviewed quote_version with a stable Idempotency-Key. After a 202, transport failure or replay, GET this same UUID until a verified terminal state; do not create a second purchase. Unknown totals never mean free. Financial history contains no private code, profile, Stripe identifiers or internal snapshot. A batch contains 1 to 100 explicit create profiles or inactive employee UUIDs; it sends no invitations. The entire batch is provisioned only after one verified financial outcome.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-batches.show" method="get" path="/companies/{company}/employee-batches/{id}" example="current_operation" -->
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



$response = $sdk->employeeBatches->publicApiV1EmployeeBatchesShow(
    company: 'f324f7fd-28f1-45ec-94e5-d96a1175dca1',
    id: '86d7465b-9495-4773-a605-b57af4b86891',
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
| `id`                                                                                                                                                                                                                                                                                                                                                                         | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the employee batch.                                                                                                                                                                                                                                                                                                                           |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1EmployeeBatchesShowResponse](../../Models/Operations/PublicApiV1EmployeeBatchesShowResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |