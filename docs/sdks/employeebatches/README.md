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

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-batches.cancel" method="post" path="/employee-batches/{id}/cancel" example="current_operation" -->
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

$request = new Operations\PublicApiV1EmployeeBatchesCancelRequest(
    id: '<id>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
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

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-batches.cancel" method="post" path="/employee-batches/{id}/cancel" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1EmployeeBatchesCancelRequest(
    id: '<id>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
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

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1EmployeeBatchesConfirm

The current credential and tenant own this operation. Preparing and refreshing never charges, provisions people or consumes an offer. Confirm exactly the reviewed quote_version with a stable Idempotency-Key. After a 202, transport failure or replay, GET this same UUID until a verified terminal state; do not create a second purchase. Unknown totals never mean free. Financial history contains no private code, profile, Stripe identifiers or internal snapshot. A batch contains 1 to 100 explicit create profiles or inactive employee UUIDs; it sends no invitations. The entire batch is provisioned only after one verified financial outcome.

### Example Usage: current_operation

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-batches.confirm" method="post" path="/employee-batches/{id}/confirm" example="current_operation" -->
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

$request = new Operations\PublicApiV1EmployeeBatchesConfirmRequest(
    id: '<id>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
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
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-batches.confirm" method="post" path="/employee-batches/{id}/confirm" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1EmployeeBatchesConfirmRequest(
    id: '<id>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
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

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 401, 402, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\Error                      | 503                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1EmployeeBatchesList

The current credential and tenant own this operation. Preparing and refreshing never charges, provisions people or consumes an offer. Confirm exactly the reviewed quote_version with a stable Idempotency-Key. After a 202, transport failure or replay, GET this same UUID until a verified terminal state; do not create a second purchase. Unknown totals never mean free. Financial history contains no private code, profile, Stripe identifiers or internal snapshot. A batch contains 1 to 100 explicit create profiles or inactive employee UUIDs; it sends no invitations. The entire batch is provisioned only after one verified financial outcome.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-batches.list" method="get" path="/employee-batches" example="current_operation" -->
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

$request = new Operations\PublicApiV1EmployeeBatchesListRequest(
    statuses: [],
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
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

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1EmployeeBatchesPrepare

The current credential and tenant own this operation. Preparing and refreshing never charges, provisions people or consumes an offer. Confirm exactly the reviewed quote_version with a stable Idempotency-Key. After a 202, transport failure or replay, GET this same UUID until a verified terminal state; do not create a second purchase. Unknown totals never mean free. Financial history contains no private code, profile, Stripe identifiers or internal snapshot. A batch contains 1 to 100 explicit create profiles or inactive employee UUIDs; it sends no invitations. The entire batch is provisioned only after one verified financial outcome.

### Example Usage: current_operation

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-batches.prepare" method="post" path="/employee-batches" example="current_operation" -->
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



$response = $sdk->employeeBatches->publicApiV1EmployeeBatchesPrepare(
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: new Components\PrepareEmployeeBatchBodyReactivate(
        items: [],
    ),
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-batches.prepare" method="post" path="/employee-batches" example="missing_api_key" -->
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



$response = $sdk->employeeBatches->publicApiV1EmployeeBatchesPrepare(
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: new Components\PrepareEmployeeBatchBodyReactivate(
        items: [
            new Components\PrepareEmployeeBatchBodyItem2(
                rowId: '<id>',
                employeeId: '75555425-5a2c-47d1-b4b1-cf2580155489',
            ),
        ],
    ),
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `409 idempotency_key_reused`. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | [Components\PrepareEmployeeBatchBodyCreate\|Components\PrepareEmployeeBatchBodyReactivate](../../Models/Components/PrepareEmployeeBatchBody.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                   | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                      | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf).                                                                                                                                                                                                  | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1EmployeeBatchesPrepareResponse](../../Models/Operations/PublicApiV1EmployeeBatchesPrepareResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\Error                 | 503                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1EmployeeBatchesRefreshQuote

The current credential and tenant own this operation. Preparing and refreshing never charges, provisions people or consumes an offer. Confirm exactly the reviewed quote_version with a stable Idempotency-Key. After a 202, transport failure or replay, GET this same UUID until a verified terminal state; do not create a second purchase. Unknown totals never mean free. Financial history contains no private code, profile, Stripe identifiers or internal snapshot. A batch contains 1 to 100 explicit create profiles or inactive employee UUIDs; it sends no invitations. The entire batch is provisioned only after one verified financial outcome.

### Example Usage: current_operation

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-batches.refresh_quote" method="post" path="/employee-batches/{id}/quote" example="current_operation" -->
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

$request = new Operations\PublicApiV1EmployeeBatchesRefreshQuoteRequest(
    id: '<id>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
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
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-batches.refresh_quote" method="post" path="/employee-batches/{id}/quote" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1EmployeeBatchesRefreshQuoteRequest(
    id: '<id>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
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

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\Error                 | 503                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1EmployeeBatchesShow

The current credential and tenant own this operation. Preparing and refreshing never charges, provisions people or consumes an offer. Confirm exactly the reviewed quote_version with a stable Idempotency-Key. After a 202, transport failure or replay, GET this same UUID until a verified terminal state; do not create a second purchase. Unknown totals never mean free. Financial history contains no private code, profile, Stripe identifiers or internal snapshot. A batch contains 1 to 100 explicit create profiles or inactive employee UUIDs; it sends no invitations. The entire batch is provisioned only after one verified financial outcome.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-batches.show" method="get" path="/employee-batches/{id}" example="current_operation" -->
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



$response = $sdk->employeeBatches->publicApiV1EmployeeBatchesShow(
    id: '<id>',
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
| `id`                                                                                                                                                                                                                                                                                                                                                                                             | *string*                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1EmployeeBatchesShowResponse](../../Models/Operations/PublicApiV1EmployeeBatchesShowResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |