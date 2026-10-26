# Invoices.FaceSubmissions

## Overview

### Available Operations

* [publicApiV1InvoicesFaceSubmissionsList](#publicapiv1invoicesfacesubmissionslist) - List invoice FACe submissions
* [publicApiV1InvoicesFaceSubmissionsSubmit](#publicapiv1invoicesfacesubmissionssubmit) - Prepare or continue a FACe submission

## publicApiV1InvoicesFaceSubmissionsList

Flat durable history for the authorized issuer and invoice. Empty history returns data: []. Includes prepared/uncertain status, source and artifact fingerprints, retained administrative/payment versions, can_transmit and requires_reconciliation. GET performs no capture, signing, transmission or reconciliation.

### Example Usage: durable_history

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.face_submissions.list" method="get" path="/companies/{company}/invoices/{invoice}/face-submissions" example="durable_history" -->
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



$response = $sdk->invoices->faceSubmissions->publicApiV1InvoicesFaceSubmissionsList(
    company: '5edce117-c40f-4fc7-b2fc-3a2e36f88f0e',
    invoice: 'd0380f84-24d0-4b73-b3c3-f888968b7c09',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: empty

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.face_submissions.list" method="get" path="/companies/{company}/invoices/{invoice}/face-submissions" example="empty" -->
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



$response = $sdk->invoices->faceSubmissions->publicApiV1InvoicesFaceSubmissionsList(
    company: '993eae63-e262-4b75-bf73-26b3accf95da',
    invoice: '704b2abd-acbb-4674-81d0-da659e5d964f',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.face_submissions.list" method="get" path="/companies/{company}/invoices/{invoice}/face-submissions" example="success" -->
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



$response = $sdk->invoices->faceSubmissions->publicApiV1InvoicesFaceSubmissionsList(
    company: 'Trantow and Sons',
    invoice: '<value>',
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
| `invoice`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the invoice.                                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1InvoicesFaceSubmissionsListResponse](../../Models/Operations/PublicApiV1InvoicesFaceSubmissionsListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1InvoicesFaceSubmissionsSubmit

Submit an issued invoice to FACe; needs send capability, the issuer signing certificate, DIR3 roles 01/02/03 and a contact email.
- Omit the body for a fresh attempt (201); an own `submission_id` continues that prepared attempt with its retained bytes (200).
- Concurrent claims and uncertain transmissions return 409; after a 500 or 502 with `attempt_id`, read that attempt before retrying.
- Test keys never contact FACe.

### Example Usage: continue_prepared

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.face_submissions.submit" method="post" path="/companies/{company}/invoices/{invoice}/face-submissions" example="continue_prepared" -->
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

$request = new Operations\PublicApiV1InvoicesFaceSubmissionsSubmitRequest(
    company: 'bd4c2f39-aba5-4db5-b64f-975e382c2019',
    invoice: 'd4c1b441-1b12-4d8e-8a4a-b94c02758968',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1InvoicesFaceSubmissionsSubmitRequestBody(
        submissionId: '019e0000-0000-7000-8000-000000000901',
    ),
);

$response = $sdk->invoices->faceSubmissions->publicApiV1InvoicesFaceSubmissionsSubmit(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: continued_same_attempt

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.face_submissions.submit" method="post" path="/companies/{company}/invoices/{invoice}/face-submissions" example="continued_same_attempt" -->
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

$request = new Operations\PublicApiV1InvoicesFaceSubmissionsSubmitRequest(
    company: '6b551c4b-db0d-4d28-a2a0-eee5346141f0',
    invoice: 'f041232d-7646-4e6f-934f-518be520f4f8',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->invoices->faceSubmissions->publicApiV1InvoicesFaceSubmissionsSubmit(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: first_submission

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.face_submissions.submit" method="post" path="/companies/{company}/invoices/{invoice}/face-submissions" example="first_submission" -->
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

$request = new Operations\PublicApiV1InvoicesFaceSubmissionsSubmitRequest(
    company: '2fd36061-5eeb-432c-94c9-e15157533409',
    invoice: '9e8712da-5039-451e-b67c-289ef50b7e5e',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->invoices->faceSubmissions->publicApiV1InvoicesFaceSubmissionsSubmit(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: fresh

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.face_submissions.submit" method="post" path="/companies/{company}/invoices/{invoice}/face-submissions" example="fresh" -->
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

$request = new Operations\PublicApiV1InvoicesFaceSubmissionsSubmitRequest(
    company: '4a213f49-640a-4bd5-8ab7-383ea35c87a8',
    invoice: '0407dd58-7a2b-4b54-8e6a-701ede38700c',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1InvoicesFaceSubmissionsSubmitRequestBody(),
);

$response = $sdk->invoices->faceSubmissions->publicApiV1InvoicesFaceSubmissionsSubmit(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.face_submissions.submit" method="post" path="/companies/{company}/invoices/{invoice}/face-submissions" example="success" -->
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

$request = new Operations\PublicApiV1InvoicesFaceSubmissionsSubmitRequest(
    company: 'Nicolas - Dibbert',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->invoices->faceSubmissions->publicApiV1InvoicesFaceSubmissionsSubmit(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                | Type                                                                                                                                     | Required                                                                                                                                 | Description                                                                                                                              |
| ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                               | [Operations\PublicApiV1InvoicesFaceSubmissionsSubmitRequest](../../Models/Operations/PublicApiV1InvoicesFaceSubmissionsSubmitRequest.md) | :heavy_check_mark:                                                                                                                       | The request object to use for the request.                                                                                               |

### Response

**[?Operations\PublicApiV1InvoicesFaceSubmissionsSubmitResponse](../../Models/Operations/PublicApiV1InvoicesFaceSubmissionsSubmitResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500, 502                          | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |