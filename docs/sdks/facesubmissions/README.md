# FaceSubmissions

## Overview

### Available Operations

* [publicApiV1FaceSubmissionsCancel](#publicapiv1facesubmissionscancel) - Request FACe submission cancellation
* [publicApiV1FaceSubmissionsShow](#publicapiv1facesubmissionsshow) - Retrieve a durable FACe attempt

## publicApiV1FaceSubmissionsCancel

Requires current issuer/actor access, the edit capability of the Facturae module, facturae:write and a nonempty reason. Only submitted, registered_rcf and accounted with a durable registry number may request cancellation. Prepared and uncertain attempts cannot be cancelled. The durable processing state becomes cancellation_requested after the FACe request; retries never create another submission.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.face_submissions.cancel" method="post" path="/companies/{company}/face-submissions/{faceSubmission}/cancel" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1FaceSubmissionsCancelRequest(
    company: '3b08667b-9912-4fd2-abcd-2b639c2def36',
    faceSubmission: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CancelFaceSubmissionV1Request(
        reason: '<value>',
    ),
);

$response = $sdk->faceSubmissions->publicApiV1FaceSubmissionsCancel(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.face_submissions.cancel" method="post" path="/companies/{company}/face-submissions/{faceSubmission}/cancel" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1FaceSubmissionsCancelRequest(
    company: '8d937efb-1a1b-49a6-a0f8-2820d3b521cb',
    faceSubmission: '69208954-f4e9-45a4-b355-46d6e5e9521f',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CancelFaceSubmissionV1Request(
        reason: '<value>',
    ),
);

$response = $sdk->faceSubmissions->publicApiV1FaceSubmissionsCancel(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.face_submissions.cancel" method="post" path="/companies/{company}/face-submissions/{faceSubmission}/cancel" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1FaceSubmissionsCancelRequest(
    company: '10fc20f8-72b3-4646-b791-bfebc802fad3',
    faceSubmission: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CancelFaceSubmissionV1Request(
        reason: '<value>',
    ),
);

$response = $sdk->faceSubmissions->publicApiV1FaceSubmissionsCancel(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.face_submissions.cancel" method="post" path="/companies/{company}/face-submissions/{faceSubmission}/cancel" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1FaceSubmissionsCancelRequest(
    company: 'Jacobs Inc',
    faceSubmission: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CancelFaceSubmissionV1Request(
        reason: '<value>',
    ),
);

$response = $sdk->faceSubmissions->publicApiV1FaceSubmissionsCancel(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: requested

<!-- UsageSnippet language="php" operationID="public-api.v1.face_submissions.cancel" method="post" path="/companies/{company}/face-submissions/{faceSubmission}/cancel" example="requested" -->
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

$request = new Operations\PublicApiV1FaceSubmissionsCancelRequest(
    company: '3b08667b-9912-4fd2-abcd-2b639c2def36',
    faceSubmission: '10fc20f8-72b3-4646-b791-bfebc802fad3',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CancelFaceSubmissionV1Request(
        reason: '<value>',
    ),
);

$response = $sdk->faceSubmissions->publicApiV1FaceSubmissionsCancel(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.face_submissions.cancel" method="post" path="/companies/{company}/face-submissions/{faceSubmission}/cancel" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1FaceSubmissionsCancelRequest(
    company: '9b4ef8f1-7acb-463d-b3d9-3f4bffc97f64',
    faceSubmission: 'f4d742a3-de41-42dc-afb1-78495ac99798',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CancelFaceSubmissionV1Request(
        reason: '<value>',
    ),
);

$response = $sdk->faceSubmissions->publicApiV1FaceSubmissionsCancel(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.face_submissions.cancel" method="post" path="/companies/{company}/face-submissions/{faceSubmission}/cancel" example="success" -->
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

$request = new Operations\PublicApiV1FaceSubmissionsCancelRequest(
    company: 'Prohaska - Anderson',
    faceSubmission: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CancelFaceSubmissionV1Request(
        reason: '<value>',
    ),
);

$response = $sdk->faceSubmissions->publicApiV1FaceSubmissionsCancel(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.face_submissions.cancel" method="post" path="/companies/{company}/face-submissions/{faceSubmission}/cancel" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1FaceSubmissionsCancelRequest(
    company: 'a118daf0-2804-49d9-af16-630472f9c805',
    faceSubmission: 'd6f5cd18-7d95-4f2d-af5d-7d3456c78e83',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CancelFaceSubmissionV1Request(
        reason: '<value>',
    ),
);

$response = $sdk->faceSubmissions->publicApiV1FaceSubmissionsCancel(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                | Type                                                                                                                     | Required                                                                                                                 | Description                                                                                                              |
| ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                               | [Operations\PublicApiV1FaceSubmissionsCancelRequest](../../Models/Operations/PublicApiV1FaceSubmissionsCancelRequest.md) | :heavy_check_mark:                                                                                                       | The request object to use for the request.                                                                               |

### Response

**[?Operations\PublicApiV1FaceSubmissionsCancelResponse](../../Models/Operations/PublicApiV1FaceSubmissionsCancelResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500, 502                          | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1FaceSubmissionsShow

Read one authorized own attempt, including prepared or transmission_uncertain. Unknown and foreign identities are indistinguishable. Processing polling applies only to pollable attempts with a durable registry number. An uncertain attempt blocks new submission, cancellation and automatic retry; authorized operational reconciliation is required. Read access does not imply send capability. There is no public refresh endpoint.

### Example Usage: legacy_unverified

<!-- UsageSnippet language="php" operationID="public-api.v1.face_submissions.show" method="get" path="/companies/{company}/face-submissions/{faceSubmission}" example="legacy_unverified" -->
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



$response = $sdk->faceSubmissions->publicApiV1FaceSubmissionsShow(
    company: 'f976e58b-99c6-4585-887d-f9757a043553',
    faceSubmission: '56f61dbd-987d-4c6f-b434-af9f942de60e',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: prepared

<!-- UsageSnippet language="php" operationID="public-api.v1.face_submissions.show" method="get" path="/companies/{company}/face-submissions/{faceSubmission}" example="prepared" -->
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



$response = $sdk->faceSubmissions->publicApiV1FaceSubmissionsShow(
    company: '64eb796e-0a11-42a9-a300-51e0e090d0fa',
    faceSubmission: '481df3a8-0620-4491-831f-fe6ced6c3de9',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.face_submissions.show" method="get" path="/companies/{company}/face-submissions/{faceSubmission}" example="success" -->
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



$response = $sdk->faceSubmissions->publicApiV1FaceSubmissionsShow(
    company: 'Gutmann and Sons',
    faceSubmission: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: uncertain

<!-- UsageSnippet language="php" operationID="public-api.v1.face_submissions.show" method="get" path="/companies/{company}/face-submissions/{faceSubmission}" example="uncertain" -->
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



$response = $sdk->faceSubmissions->publicApiV1FaceSubmissionsShow(
    company: 'c7c6c10e-f97f-4951-82eb-19923af7207c',
    faceSubmission: 'c0f7f620-f92f-4589-b3a4-e6b6d35307e2',
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
| `faceSubmission`                                                                                                                                                                                                                                                                                                                                                             | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the FACe submission.                                                                                                                                                                                                                                                                                                                          |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1FaceSubmissionsShowResponse](../../Models/Operations/PublicApiV1FaceSubmissionsShowResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |