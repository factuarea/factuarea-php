# EmployeeSeatOfferRedemptions

## Overview

### Available Operations

* [publicApiV1EmployeeSeatOfferRedemptionsCancel](#publicapiv1employeeseatofferredemptionscancel) - Cancel an employee-seat offer redemption
* [publicApiV1EmployeeSeatOfferRedemptionsConfirm](#publicapiv1employeeseatofferredemptionsconfirm) - Confirm an employee-seat offer redemption
* [publicApiV1EmployeeSeatOfferRedemptionsList](#publicapiv1employeeseatofferredemptionslist) - List an employee-seat offer redemption
* [publicApiV1EmployeeSeatOfferRedemptionsPrepare](#publicapiv1employeeseatofferredemptionsprepare) - Prepare an employee-seat offer redemption
* [publicApiV1EmployeeSeatOfferRedemptionsRefreshQuote](#publicapiv1employeeseatofferredemptionsrefreshquote) - Refresh the quote for an employee-seat offer redemption
* [publicApiV1EmployeeSeatOfferRedemptionsShow](#publicapiv1employeeseatofferredemptionsshow) - Retrieve an employee-seat offer redemption

## publicApiV1EmployeeSeatOfferRedemptionsCancel

Cancel an employee-seat offer redemption before it is confirmed.
- Cancelling never charges; the offer is not consumed.
- Use a stable Idempotency-Key and GET the same UUID to read the final state.
- Unavailable or other-company codes get the same controlled rejection.

### Example Usage: current_operation

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-seat-offer-redemptions.cancel" method="post" path="/companies/{company}/employee-seat-offer-redemptions/{id}/cancel" example="current_operation" -->
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

$request = new Operations\PublicApiV1EmployeeSeatOfferRedemptionsCancelRequest(
    company: 'ba399ab5-e989-4822-bcd5-494be6a1ecfa',
    id: 'db4bbe17-1c2e-4ebd-be7a-084609a0920d',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CancelDurableOperationBody(),
);

$response = $sdk->employeeSeatOfferRedemptions->publicApiV1EmployeeSeatOfferRedemptionsCancel(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-seat-offer-redemptions.cancel" method="post" path="/companies/{company}/employee-seat-offer-redemptions/{id}/cancel" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1EmployeeSeatOfferRedemptionsCancelRequest(
    company: 'b3c00a70-8225-4c2f-bb18-6aa19f2513db',
    id: '8ac48380-f58d-461c-a276-230c26467c2e',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CancelDurableOperationBody(),
);

$response = $sdk->employeeSeatOfferRedemptions->publicApiV1EmployeeSeatOfferRedemptionsCancel(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-seat-offer-redemptions.cancel" method="post" path="/companies/{company}/employee-seat-offer-redemptions/{id}/cancel" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1EmployeeSeatOfferRedemptionsCancelRequest(
    company: '8709991d-c707-4d05-8a4c-34a28ed20052',
    id: 'a7f7af99-54ca-410f-a8c8-c15a4d396d7a',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CancelDurableOperationBody(),
);

$response = $sdk->employeeSeatOfferRedemptions->publicApiV1EmployeeSeatOfferRedemptionsCancel(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-seat-offer-redemptions.cancel" method="post" path="/companies/{company}/employee-seat-offer-redemptions/{id}/cancel" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1EmployeeSeatOfferRedemptionsCancelRequest(
    company: '938e87c0-0345-4d60-888a-b3f1a9072410',
    id: '692b653e-40da-4851-a51b-b15cdd48e53a',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CancelDurableOperationBody(),
);

$response = $sdk->employeeSeatOfferRedemptions->publicApiV1EmployeeSeatOfferRedemptionsCancel(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                          | Type                                                                                                                                               | Required                                                                                                                                           | Description                                                                                                                                        |
| -------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                         | [Operations\PublicApiV1EmployeeSeatOfferRedemptionsCancelRequest](../../Models/Operations/PublicApiV1EmployeeSeatOfferRedemptionsCancelRequest.md) | :heavy_check_mark:                                                                                                                                 | The request object to use for the request.                                                                                                         |

### Response

**[?Operations\PublicApiV1EmployeeSeatOfferRedemptionsCancelResponse](../../Models/Operations/PublicApiV1EmployeeSeatOfferRedemptionsCancelResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1EmployeeSeatOfferRedemptionsConfirm

Confirm an employee-seat offer redemption, charging the reviewed quote.
- Send exactly the reviewed `quote_version` with a stable Idempotency-Key.
- After a 202, failure or replay, GET the same UUID until terminal; never create a second purchase.
- Existing discounts are never silently stacked or replaced.

### Example Usage: current_operation

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-seat-offer-redemptions.confirm" method="post" path="/companies/{company}/employee-seat-offer-redemptions/{id}/confirm" example="current_operation" -->
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

$request = new Operations\PublicApiV1EmployeeSeatOfferRedemptionsConfirmRequest(
    company: '4faaab9b-2ec0-4988-98f7-60315022186b',
    id: '9774bf62-5b12-43a5-89b2-1d044169557b',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedDurableOperationBody(
        quoteVersion: 28428,
    ),
);

$response = $sdk->employeeSeatOfferRedemptions->publicApiV1EmployeeSeatOfferRedemptionsConfirm(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-seat-offer-redemptions.confirm" method="post" path="/companies/{company}/employee-seat-offer-redemptions/{id}/confirm" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1EmployeeSeatOfferRedemptionsConfirmRequest(
    company: '6dc03a16-36d3-4e0d-99b4-2bbd076a849f',
    id: '11754492-54ac-4f4f-a6f1-7bedaf4832da',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedDurableOperationBody(
        quoteVersion: 28428,
    ),
);

$response = $sdk->employeeSeatOfferRedemptions->publicApiV1EmployeeSeatOfferRedemptionsConfirm(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-seat-offer-redemptions.confirm" method="post" path="/companies/{company}/employee-seat-offer-redemptions/{id}/confirm" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1EmployeeSeatOfferRedemptionsConfirmRequest(
    company: '55664a81-1d35-47b7-8a44-80f2548061f4',
    id: '8ae220bd-ca49-4b21-b4a1-0b183ac84a26',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedDurableOperationBody(
        quoteVersion: 28428,
    ),
);

$response = $sdk->employeeSeatOfferRedemptions->publicApiV1EmployeeSeatOfferRedemptionsConfirm(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-seat-offer-redemptions.confirm" method="post" path="/companies/{company}/employee-seat-offer-redemptions/{id}/confirm" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1EmployeeSeatOfferRedemptionsConfirmRequest(
    company: '4129a137-c49f-49ff-a7db-13ce2726187c',
    id: 'b27995fa-3dc8-42b4-8c69-4ee192a00d6a',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedDurableOperationBody(
        quoteVersion: 28428,
    ),
);

$response = $sdk->employeeSeatOfferRedemptions->publicApiV1EmployeeSeatOfferRedemptionsConfirm(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                            | Type                                                                                                                                                 | Required                                                                                                                                             | Description                                                                                                                                          |
| ---------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                           | [Operations\PublicApiV1EmployeeSeatOfferRedemptionsConfirmRequest](../../Models/Operations/PublicApiV1EmployeeSeatOfferRedemptionsConfirmRequest.md) | :heavy_check_mark:                                                                                                                                   | The request object to use for the request.                                                                                                           |

### Response

**[?Operations\PublicApiV1EmployeeSeatOfferRedemptionsConfirmResponse](../../Models/Operations/PublicApiV1EmployeeSeatOfferRedemptionsConfirmResponse.md)**

### Errors

| Error Type                             | Status Code                            | Content Type                           |
| -------------------------------------- | -------------------------------------- | -------------------------------------- |
| Errors\Error                           | 400, 401, 402, 403, 404, 409, 422, 429 | application/json                       |
| Errors\Error                           | 500                                    | application/json                       |
| Errors\Error                           | 503                                    | application/json                       |
| Errors\APIException                    | 4XX, 5XX                               | \*/\*                                  |

## publicApiV1EmployeeSeatOfferRedemptionsList

List an employee-seat offer redemptions of the company.
- Financial history exposes no private code, profile or Stripe identifiers.
- The private offer belongs to the paying company; unavailable or other-company codes get the same rejection.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-seat-offer-redemptions.list" method="get" path="/companies/{company}/employee-seat-offer-redemptions" example="current_operation" -->
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

$request = new Operations\PublicApiV1EmployeeSeatOfferRedemptionsListRequest(
    company: '9357f044-c219-4ba1-9294-f07abacbc49c',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->employeeSeatOfferRedemptions->publicApiV1EmployeeSeatOfferRedemptionsList(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                      | Type                                                                                                                                           | Required                                                                                                                                       | Description                                                                                                                                    |
| ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                     | [Operations\PublicApiV1EmployeeSeatOfferRedemptionsListRequest](../../Models/Operations/PublicApiV1EmployeeSeatOfferRedemptionsListRequest.md) | :heavy_check_mark:                                                                                                                             | The request object to use for the request.                                                                                                     |

### Response

**[?Operations\PublicApiV1EmployeeSeatOfferRedemptionsListResponse](../../Models/Operations/PublicApiV1EmployeeSeatOfferRedemptionsListResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1EmployeeSeatOfferRedemptionsPrepare

Prepare an employee-seat offer redemption and its quote without charging.
- Preparing never charges or consumes the offer; confirm later with a stable Idempotency-Key.
- The private offer belongs to the paying company; unavailable or other-company codes get the same rejection.
- Existing discounts are never silently stacked or replaced.

### Example Usage: current_operation

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-seat-offer-redemptions.prepare" method="post" path="/companies/{company}/employee-seat-offer-redemptions" example="current_operation" -->
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

$body = new Components\PrepareEmployeeSeatOfferBody(
    code: '<value>',
    purchase: new Components\PurchaseEmployeeIndividualCreate(
        profile: new Components\EmployeeBatchProfile(
            firstName: 'Ana',
            lastName: 'Acceptance',
            email: 'Skyla_Gleichner@gmail.com',
            employmentType: Components\EmployeeBatchProfileEmploymentType::PartTime,
            contractHours: 792.91,
            ccaa: Components\EmployeeBatchProfileCcaa::Vc,
            hireDate: LocalDate::parse('2025-08-05'),
        ),
    ),
);

$response = $sdk->employeeSeatOfferRedemptions->publicApiV1EmployeeSeatOfferRedemptionsPrepare(
    company: '00b111f8-37f7-442a-9a48-bb217d364175',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-seat-offer-redemptions.prepare" method="post" path="/companies/{company}/employee-seat-offer-redemptions" example="factuarea_version_invalid_format" -->
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

$body = new Components\PrepareEmployeeSeatOfferBody(
    code: '<value>',
    purchase: new Components\PurchaseEmployeeIndividualCreate(
        profile: new Components\EmployeeBatchProfile(
            firstName: 'Ana',
            lastName: 'Acceptance',
            email: 'Skyla_Gleichner@gmail.com',
            employmentType: Components\EmployeeBatchProfileEmploymentType::PartTime,
            contractHours: 792.91,
            ccaa: Components\EmployeeBatchProfileCcaa::Vc,
            hireDate: LocalDate::parse('2025-08-05'),
        ),
    ),
);

$response = $sdk->employeeSeatOfferRedemptions->publicApiV1EmployeeSeatOfferRedemptionsPrepare(
    company: '05782a10-fbf6-4bb6-bb65-b13797a8e06b',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-seat-offer-redemptions.prepare" method="post" path="/companies/{company}/employee-seat-offer-redemptions" example="retired_active_profile_header" -->
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

$body = new Components\PrepareEmployeeSeatOfferBody(
    code: '<value>',
    purchase: new Components\PurchaseEmployeeIndividualCreate(
        profile: new Components\EmployeeBatchProfile(
            firstName: 'Ana',
            lastName: 'Acceptance',
            email: 'Skyla_Gleichner@gmail.com',
            employmentType: Components\EmployeeBatchProfileEmploymentType::PartTime,
            contractHours: 792.91,
            ccaa: Components\EmployeeBatchProfileCcaa::Vc,
            hireDate: LocalDate::parse('2025-08-05'),
        ),
    ),
);

$response = $sdk->employeeSeatOfferRedemptions->publicApiV1EmployeeSeatOfferRedemptionsPrepare(
    company: 'e5b1f45c-9257-44dd-822c-6327e442da00',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-seat-offer-redemptions.prepare" method="post" path="/companies/{company}/employee-seat-offer-redemptions" example="unsupported_api_version" -->
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

$body = new Components\PrepareEmployeeSeatOfferBody(
    code: '<value>',
    purchase: new Components\PurchaseEmployeeIndividualCreate(
        profile: new Components\EmployeeBatchProfile(
            firstName: 'Ana',
            lastName: 'Acceptance',
            email: 'Skyla_Gleichner@gmail.com',
            employmentType: Components\EmployeeBatchProfileEmploymentType::PartTime,
            contractHours: 792.91,
            ccaa: Components\EmployeeBatchProfileCcaa::Vc,
            hireDate: LocalDate::parse('2025-08-05'),
        ),
    ),
);

$response = $sdk->employeeSeatOfferRedemptions->publicApiV1EmployeeSeatOfferRedemptionsPrepare(
    company: '169c49af-4b2f-4a8b-a8e0-7624eae3fd2e',
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
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Components\PrepareEmployeeSeatOfferBody](../../Models/Components/PrepareEmployeeSeatOfferBody.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1EmployeeSeatOfferRedemptionsPrepareResponse](../../Models/Operations/PublicApiV1EmployeeSeatOfferRedemptionsPrepareResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\Error                      | 503                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1EmployeeSeatOfferRedemptionsRefreshQuote

Refresh the quote of an employee-seat offer redemption.
- Refreshing never charges or consumes the offer; confirm exactly the new `quote_version`.
- The private offer belongs to the paying company; unavailable or other-company codes get the same rejection.

### Example Usage: current_operation

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-seat-offer-redemptions.refresh_quote" method="post" path="/companies/{company}/employee-seat-offer-redemptions/{id}/quote" example="current_operation" -->
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

$request = new Operations\PublicApiV1EmployeeSeatOfferRedemptionsRefreshQuoteRequest(
    company: 'de49f548-5e75-4274-a320-c9e8ed8d1278',
    id: 'f6bac61e-befa-4a2f-abe1-f692883b0bde',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedDurableOperationBody(
        quoteVersion: 518880,
    ),
);

$response = $sdk->employeeSeatOfferRedemptions->publicApiV1EmployeeSeatOfferRedemptionsRefreshQuote(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-seat-offer-redemptions.refresh_quote" method="post" path="/companies/{company}/employee-seat-offer-redemptions/{id}/quote" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1EmployeeSeatOfferRedemptionsRefreshQuoteRequest(
    company: '832a238c-de8b-4ab8-920c-12333e5d7b5a',
    id: '2b9081db-43e5-478d-9bb5-c009ba3af4e1',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedDurableOperationBody(
        quoteVersion: 518880,
    ),
);

$response = $sdk->employeeSeatOfferRedemptions->publicApiV1EmployeeSeatOfferRedemptionsRefreshQuote(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-seat-offer-redemptions.refresh_quote" method="post" path="/companies/{company}/employee-seat-offer-redemptions/{id}/quote" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1EmployeeSeatOfferRedemptionsRefreshQuoteRequest(
    company: '0345c5fb-5c10-42b0-b400-c8f4440fad2b',
    id: '5988110e-0feb-4935-81a4-2c37fe2e3ccb',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedDurableOperationBody(
        quoteVersion: 518880,
    ),
);

$response = $sdk->employeeSeatOfferRedemptions->publicApiV1EmployeeSeatOfferRedemptionsRefreshQuote(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-seat-offer-redemptions.refresh_quote" method="post" path="/companies/{company}/employee-seat-offer-redemptions/{id}/quote" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1EmployeeSeatOfferRedemptionsRefreshQuoteRequest(
    company: '8411b361-c0e5-4d5f-8983-749bbd984cde',
    id: '102959bf-8e79-4ee0-ba86-8b4af5d3c454',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedDurableOperationBody(
        quoteVersion: 518880,
    ),
);

$response = $sdk->employeeSeatOfferRedemptions->publicApiV1EmployeeSeatOfferRedemptionsRefreshQuote(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                      | Type                                                                                                                                                           | Required                                                                                                                                                       | Description                                                                                                                                                    |
| -------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                                     | [Operations\PublicApiV1EmployeeSeatOfferRedemptionsRefreshQuoteRequest](../../Models/Operations/PublicApiV1EmployeeSeatOfferRedemptionsRefreshQuoteRequest.md) | :heavy_check_mark:                                                                                                                                             | The request object to use for the request.                                                                                                                     |

### Response

**[?Operations\PublicApiV1EmployeeSeatOfferRedemptionsRefreshQuoteResponse](../../Models/Operations/PublicApiV1EmployeeSeatOfferRedemptionsRefreshQuoteResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\Error                      | 503                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1EmployeeSeatOfferRedemptionsShow

Retrieve an employee-seat offer redemption.
- GET the same UUID after a 202 or failure until a verified terminal state.
- Unknown totals never mean free; no private code or Stripe identifiers are exposed.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.employee-seat-offer-redemptions.show" method="get" path="/companies/{company}/employee-seat-offer-redemptions/{id}" example="current_operation" -->
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



$response = $sdk->employeeSeatOfferRedemptions->publicApiV1EmployeeSeatOfferRedemptionsShow(
    company: '9158b991-d24f-4eba-b64c-967a71941fea',
    id: 'e0cef1ce-8ea5-4818-b364-cb853b922e23',
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
| `id`                                                                                                                                                                                                                                                                                                                                                                         | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the employee seat offer redemption.                                                                                                                                                                                                                                                                                                           |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1EmployeeSeatOfferRedemptionsShowResponse](../../Models/Operations/PublicApiV1EmployeeSeatOfferRedemptionsShowResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |