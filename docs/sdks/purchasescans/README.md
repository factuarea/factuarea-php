# PurchaseScans

## Overview

### Available Operations

* [publicApiV1PurchaseScansArchive](#publicapiv1purchasescansarchive) - Archive a purchase scan
* [publicApiV1PurchaseScansShow](#publicapiv1purchasescansshow) - Retrieve a purchase scan
* [publicApiV1PurchaseScansConvert](#publicapiv1purchasescansconvert) - Create the expense from a scan
* [publicApiV1PurchaseScansSource](#publicapiv1purchasescanssource) - Download the original document
* [publicApiV1PurchaseScansStats](#publicapiv1purchasescansstats) - Get purchase scanner stats
* [publicApiV1PurchaseScansDuplicateResolution](#publicapiv1purchasescansduplicateresolution) - Resolve a duplicate purchase scan
* [publicApiV1PurchaseScansRestore](#publicapiv1purchasescansrestore) - Restore an archived purchase scan
* [publicApiV1PurchaseScansRetry](#publicapiv1purchasescansretry) - Retry a failed purchase scan
* [publicApiV1PurchaseScansReview](#publicapiv1purchasescansreview) - Save the review of a purchase scan
* [publicApiV1PurchaseScansList](#publicapiv1purchasescanslist) - List purchase scans
* [publicApiV1PurchaseScansCreate](#publicapiv1purchasescanscreate) - Upload documents to the purchase scanner

## publicApiV1PurchaseScansArchive

Archive a scan that is not being processed. Reversible with the restore operation. Requires `expected_version`. Accepts `Idempotency-Key`.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.archive" method="delete" path="/companies/{company}/purchase-scans/{purchase_scan}" example="api_key_expired" -->
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

$request = new Operations\PublicApiV1PurchaseScansArchiveRequest(
    company: 'Fritsch - Lowe',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 851648,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansArchive(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.archive" method="delete" path="/companies/{company}/purchase-scans/{purchase_scan}" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1PurchaseScansArchiveRequest(
    company: 'O\'Keefe, Mayer and Conroy',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 851648,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansArchive(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.archive" method="delete" path="/companies/{company}/purchase-scans/{purchase_scan}" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1PurchaseScansArchiveRequest(
    company: '21a86522-a45b-441c-a412-025d8df0f927',
    purchaseScan: 'fe41af1d-def7-42a4-a17b-94fdfd8a8e02',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 851648,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansArchive(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.archive" method="delete" path="/companies/{company}/purchase-scans/{purchase_scan}" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1PurchaseScansArchiveRequest(
    company: 'Mraz - Mayert',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 851648,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansArchive(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.archive" method="delete" path="/companies/{company}/purchase-scans/{purchase_scan}" example="ip_not_allowed" -->
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

$request = new Operations\PublicApiV1PurchaseScansArchiveRequest(
    company: 'Friesen - Gerlach',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 851648,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansArchive(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.archive" method="delete" path="/companies/{company}/purchase-scans/{purchase_scan}" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1PurchaseScansArchiveRequest(
    company: 'Conroy, D\'Amore and Cruickshank',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 851648,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansArchive(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.archive" method="delete" path="/companies/{company}/purchase-scans/{purchase_scan}" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1PurchaseScansArchiveRequest(
    company: 'd24fe222-499f-4a92-a448-544ca420101f',
    purchaseScan: '68469634-6797-4cf9-b84e-12fc92d611ca',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 851648,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansArchive(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.archive" method="delete" path="/companies/{company}/purchase-scans/{purchase_scan}" example="success" -->
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

$request = new Operations\PublicApiV1PurchaseScansArchiveRequest(
    company: 'Crona, Greenfelder and Zieme',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 851648,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansArchive(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.archive" method="delete" path="/companies/{company}/purchase-scans/{purchase_scan}" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1PurchaseScansArchiveRequest(
    company: 'c542888b-fe37-439f-83e9-3849dcb8afa9',
    purchaseScan: '6c095122-ad68-4103-8e3b-e1ae6eeb5fa5',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 851648,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansArchive(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                              | Type                                                                                                                   | Required                                                                                                               | Description                                                                                                            |
| ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                             | [Operations\PublicApiV1PurchaseScansArchiveRequest](../../Models/Operations/PublicApiV1PurchaseScansArchiveRequest.md) | :heavy_check_mark:                                                                                                     | The request object to use for the request.                                                                             |

### Response

**[?Operations\PublicApiV1PurchaseScansArchiveResponse](../../Models/Operations/PublicApiV1PurchaseScansArchiveResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1PurchaseScansShow

Retrieve a purchase scan by its public `id` (UUID v7): evidence-first extraction (value, confidence and page/bounding-box evidence per field and line), issues, duplicate match, attempts, linked expense and `available_actions`. The current `version` is required by every mutation.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.show" method="get" path="/companies/{company}/purchase-scans/{purchase_scan}" example="success" -->
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



$response = $sdk->purchaseScans->publicApiV1PurchaseScansShow(
    company: 'Waelchi - Trantow',
    purchaseScan: '<value>',
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
| `purchaseScan`                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the purchase scan.                                                                                                                                                                                                                                                                                                                            |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1PurchaseScansShowResponse](../../Models/Operations/PublicApiV1PurchaseScansShowResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1PurchaseScansConvert

Create exactly one draft expense from a reviewed scan and link it. Responds `200` on the first call and on replays, returning the same linked expense. Requires `expected_version` and that no blocking issue remains; otherwise `422`.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.convert" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/convert" example="api_key_expired" -->
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

$request = new Operations\PublicApiV1PurchaseScansConvertRequest(
    company: 'Feest - Waelchi',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ConvertPurchaseScanRequest(
        expectedVersion: 53718,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansConvert(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.convert" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/convert" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1PurchaseScansConvertRequest(
    company: 'Conroy, Fisher and Prosacco',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ConvertPurchaseScanRequest(
        expectedVersion: 53718,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansConvert(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.convert" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/convert" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1PurchaseScansConvertRequest(
    company: '0a61565e-f7f0-46f5-9b4b-6eb4f3e92e51',
    purchaseScan: '5ceb6d9a-e8ea-40d8-b9db-ba02ef237f82',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ConvertPurchaseScanRequest(
        expectedVersion: 53718,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansConvert(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.convert" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/convert" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1PurchaseScansConvertRequest(
    company: 'Heathcote - Koepp',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ConvertPurchaseScanRequest(
        expectedVersion: 53718,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansConvert(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.convert" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/convert" example="ip_not_allowed" -->
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

$request = new Operations\PublicApiV1PurchaseScansConvertRequest(
    company: 'Kling Inc',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ConvertPurchaseScanRequest(
        expectedVersion: 53718,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansConvert(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.convert" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/convert" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1PurchaseScansConvertRequest(
    company: 'Zboncak and Sons',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ConvertPurchaseScanRequest(
        expectedVersion: 53718,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansConvert(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.convert" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/convert" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1PurchaseScansConvertRequest(
    company: '0657ff45-7d23-4b03-ae67-648b5f0684f7',
    purchaseScan: '93146f63-b908-4452-a4af-48d74c6ca7bd',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ConvertPurchaseScanRequest(
        expectedVersion: 53718,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansConvert(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.convert" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/convert" example="success" -->
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

$request = new Operations\PublicApiV1PurchaseScansConvertRequest(
    company: 'Kemmer - Halvorson',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ConvertPurchaseScanRequest(
        expectedVersion: 53718,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansConvert(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.convert" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/convert" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1PurchaseScansConvertRequest(
    company: '907833e9-a39b-4391-8fe9-4bdd10476511',
    purchaseScan: '49aed9a1-e571-47a4-baae-3c7eeb109f49',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ConvertPurchaseScanRequest(
        expectedVersion: 53718,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansConvert(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                              | Type                                                                                                                   | Required                                                                                                               | Description                                                                                                            |
| ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                             | [Operations\PublicApiV1PurchaseScansConvertRequest](../../Models/Operations/PublicApiV1PurchaseScansConvertRequest.md) | :heavy_check_mark:                                                                                                     | The request object to use for the request.                                                                             |

### Response

**[?Operations\PublicApiV1PurchaseScansConvertResponse](../../Models/Operations/PublicApiV1PurchaseScansConvertResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500, 503                          | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1PurchaseScansSource

Download the original file of a purchase scan (PDF or image) as a binary stream with `Content-Disposition: attachment`. Available while the original is retained.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.source" method="get" path="/companies/{company}/purchase-scans/{purchase_scan}/source" -->
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



$response = $sdk->purchaseScans->publicApiV1PurchaseScansSource(
    company: 'DuBuque - Kris',
    purchaseScan: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationPdfBytes !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `purchaseScan`                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the purchase scan.                                                                                                                                                                                                                                                                                                                            |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1PurchaseScansSourceResponse](../../Models/Operations/PublicApiV1PurchaseScansSourceResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500, 503                | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1PurchaseScansStats

Aggregated counters of the purchase scanner for your company: documents in progress, pending review and with incidents, plus per-status counts. Returned as `{ "data": PurchaseScanStats }`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.stats" method="get" path="/companies/{company}/purchase-scans/stats" example="success" -->
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



$response = $sdk->purchaseScans->publicApiV1PurchaseScansStats(
    company: 'Waelchi - Spencer',
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
| `scope`                                                                                                                                                                                                                                                                                                                                                                      | [?Operations\PublicApiV1PurchaseScansStatsScope](../../Models/Operations/PublicApiV1PurchaseScansStatsScope.md)                                                                                                                                                                                                                                                              | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Universe of the counts per option: `inbox` (default, scans still to be handled) or `history` (the whole history).                                                                                                                                                                                                                                                            |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1PurchaseScansStatsResponse](../../Models/Operations/PublicApiV1PurchaseScansStatsResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1PurchaseScansDuplicateResolution

Resolve a scan in `duplicate` status with `resolution` = `link_existing` (link the existing expense) or `archive`. Duplicate overrides require an authorised user in the Factuarea app; they are not available through the public API or MCP. Requires `expected_version`. Accepts `Idempotency-Key`.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.duplicate_resolution" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/duplicate-resolution" example="api_key_expired" -->
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

$request = new Operations\PublicApiV1PurchaseScansDuplicateResolutionRequest(
    company: 'Abbott Inc',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ResolvePurchaseScanDuplicateV1Request(
        expectedVersion: 48616,
        resolution: Components\Resolution::Archive,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansDuplicateResolution(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.duplicate_resolution" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/duplicate-resolution" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1PurchaseScansDuplicateResolutionRequest(
    company: 'Beatty, Jenkins and Miller',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ResolvePurchaseScanDuplicateV1Request(
        expectedVersion: 48616,
        resolution: Components\Resolution::Archive,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansDuplicateResolution(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.duplicate_resolution" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/duplicate-resolution" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1PurchaseScansDuplicateResolutionRequest(
    company: '84d1c39a-af27-4bcd-b4f3-c6da38d40a3d',
    purchaseScan: '8c600340-53e1-40ad-b166-6481ee17679b',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ResolvePurchaseScanDuplicateV1Request(
        expectedVersion: 48616,
        resolution: Components\Resolution::Archive,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansDuplicateResolution(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.duplicate_resolution" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/duplicate-resolution" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1PurchaseScansDuplicateResolutionRequest(
    company: 'Kertzmann and Sons',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ResolvePurchaseScanDuplicateV1Request(
        expectedVersion: 48616,
        resolution: Components\Resolution::Archive,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansDuplicateResolution(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.duplicate_resolution" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/duplicate-resolution" example="ip_not_allowed" -->
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

$request = new Operations\PublicApiV1PurchaseScansDuplicateResolutionRequest(
    company: 'DuBuque and Sons',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ResolvePurchaseScanDuplicateV1Request(
        expectedVersion: 48616,
        resolution: Components\Resolution::Archive,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansDuplicateResolution(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.duplicate_resolution" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/duplicate-resolution" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1PurchaseScansDuplicateResolutionRequest(
    company: 'Pollich - Ebert',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ResolvePurchaseScanDuplicateV1Request(
        expectedVersion: 48616,
        resolution: Components\Resolution::Archive,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansDuplicateResolution(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.duplicate_resolution" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/duplicate-resolution" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1PurchaseScansDuplicateResolutionRequest(
    company: '0d54b366-de06-4980-9793-d5c122e74a2d',
    purchaseScan: 'ec604b5f-d574-4974-9605-9d4a808a2b02',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ResolvePurchaseScanDuplicateV1Request(
        expectedVersion: 48616,
        resolution: Components\Resolution::Archive,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansDuplicateResolution(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.duplicate_resolution" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/duplicate-resolution" example="success" -->
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

$request = new Operations\PublicApiV1PurchaseScansDuplicateResolutionRequest(
    company: 'Stokes - Hegmann',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ResolvePurchaseScanDuplicateV1Request(
        expectedVersion: 48616,
        resolution: Components\Resolution::Archive,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansDuplicateResolution(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.duplicate_resolution" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/duplicate-resolution" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1PurchaseScansDuplicateResolutionRequest(
    company: '4c0f766b-55fa-4c3a-a842-8f844758c588',
    purchaseScan: 'fe6ac786-2c23-467e-b046-04d1a66b6c76',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ResolvePurchaseScanDuplicateV1Request(
        expectedVersion: 48616,
        resolution: Components\Resolution::Archive,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansDuplicateResolution(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                      | Type                                                                                                                                           | Required                                                                                                                                       | Description                                                                                                                                    |
| ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                     | [Operations\PublicApiV1PurchaseScansDuplicateResolutionRequest](../../Models/Operations/PublicApiV1PurchaseScansDuplicateResolutionRequest.md) | :heavy_check_mark:                                                                                                                             | The request object to use for the request.                                                                                                     |

### Response

**[?Operations\PublicApiV1PurchaseScansDuplicateResolutionResponse](../../Models/Operations/PublicApiV1PurchaseScansDuplicateResolutionResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1PurchaseScansRestore

Return an archived scan to its previous restorable state; interrupted processing resumes from `queued`, and interrupted automatic conversion returns to `needs_review`. Check `available_actions` before reviewing or converting; restoring does not recover a purged original. Requires `expected_version`. Accepts `Idempotency-Key`.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.restore" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/restore" example="api_key_expired" -->
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

$request = new Operations\PublicApiV1PurchaseScansRestoreRequest(
    company: 'Huel - Balistreri',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 263337,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansRestore(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.restore" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/restore" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1PurchaseScansRestoreRequest(
    company: 'Adams, Schuppe and Gislason',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 263337,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansRestore(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.restore" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/restore" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1PurchaseScansRestoreRequest(
    company: '1a4e7809-790c-4078-b74d-4ae85815d58f',
    purchaseScan: '15b86127-2f34-44b8-bba8-0d4105505b9e',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 263337,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansRestore(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.restore" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/restore" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1PurchaseScansRestoreRequest(
    company: 'Nienow LLC',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 263337,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansRestore(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.restore" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/restore" example="ip_not_allowed" -->
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

$request = new Operations\PublicApiV1PurchaseScansRestoreRequest(
    company: 'Daniel and Sons',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 263337,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansRestore(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.restore" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/restore" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1PurchaseScansRestoreRequest(
    company: 'Klein, Koch and Steuber',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 263337,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansRestore(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.restore" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/restore" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1PurchaseScansRestoreRequest(
    company: '449f77d7-a9e0-4d43-a607-2e9ba47864ec',
    purchaseScan: 'bee1fa74-7758-4917-9513-63bbfd894e48',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 263337,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansRestore(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.restore" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/restore" example="success" -->
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

$request = new Operations\PublicApiV1PurchaseScansRestoreRequest(
    company: 'Hahn - Medhurst',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 263337,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansRestore(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.restore" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/restore" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1PurchaseScansRestoreRequest(
    company: '0ebfd356-6979-4097-bee6-85b4ce50f275',
    purchaseScan: '58c85eb4-00f5-4913-88e5-b10a680dbca7',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 263337,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansRestore(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                              | Type                                                                                                                   | Required                                                                                                               | Description                                                                                                            |
| ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                             | [Operations\PublicApiV1PurchaseScansRestoreRequest](../../Models/Operations/PublicApiV1PurchaseScansRestoreRequest.md) | :heavy_check_mark:                                                                                                     | The request object to use for the request.                                                                             |

### Response

**[?Operations\PublicApiV1PurchaseScansRestoreResponse](../../Models/Operations/PublicApiV1PurchaseScansRestoreResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1PurchaseScansRetry

Start OCR for a scan in `received`, or queue it again for a scan in `failed` whose error is recoverable. Requires `expected_version`; a stale version responds `409`. Accepts `Idempotency-Key`.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.retry" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/retry" example="api_key_expired" -->
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

$request = new Operations\PublicApiV1PurchaseScansRetryRequest(
    company: 'Leannon, Kessler and Schulist',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 677254,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansRetry(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.retry" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/retry" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1PurchaseScansRetryRequest(
    company: 'Harber Group',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 677254,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansRetry(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.retry" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/retry" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1PurchaseScansRetryRequest(
    company: 'dda6487e-c1ea-45f4-8e9c-c451d44fb30b',
    purchaseScan: '9a6b2a79-a482-41df-b1c8-edf307a5eee2',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 677254,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansRetry(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.retry" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/retry" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1PurchaseScansRetryRequest(
    company: 'Murphy - Donnelly',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 677254,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansRetry(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.retry" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/retry" example="ip_not_allowed" -->
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

$request = new Operations\PublicApiV1PurchaseScansRetryRequest(
    company: 'Sawayn, Luettgen and McLaughlin',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 677254,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansRetry(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.retry" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/retry" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1PurchaseScansRetryRequest(
    company: 'Schmidt, Auer and Schmidt',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 677254,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansRetry(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.retry" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/retry" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1PurchaseScansRetryRequest(
    company: 'ae3dbc0c-0a38-452e-887c-bc89ab1117b7',
    purchaseScan: 'f116b2cf-62a1-4e52-ba6a-d998775316aa',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 677254,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansRetry(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.retry" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/retry" example="success" -->
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

$request = new Operations\PublicApiV1PurchaseScansRetryRequest(
    company: 'Terry, Fadel and Swaniawski',
    purchaseScan: '<value>',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 677254,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansRetry(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.retry" method="post" path="/companies/{company}/purchase-scans/{purchase_scan}/retry" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1PurchaseScansRetryRequest(
    company: '8da3373c-ed94-4ec3-8f30-357c4cbcf969',
    purchaseScan: 'a2a95b70-274f-4564-90b5-ff784f8c2324',
    idempotencyKey: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\VersionedPurchaseScanRequest(
        expectedVersion: 677254,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansRetry(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                          | Type                                                                                                               | Required                                                                                                           | Description                                                                                                        |
| ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                         | [Operations\PublicApiV1PurchaseScansRetryRequest](../../Models/Operations/PublicApiV1PurchaseScansRetryRequest.md) | :heavy_check_mark:                                                                                                 | The request object to use for the request.                                                                         |

### Response

**[?Operations\PublicApiV1PurchaseScansRetryResponse](../../Models/Operations/PublicApiV1PurchaseScansRetryResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500, 503                          | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1PurchaseScansReview

Persist the human corrections of a scan in `needs_review`: supplier, fiscal header, lines and notes, each field marked as verified. Requires `expected_version`; a stale version responds `409` and the latest version must be reloaded. Returns the updated scan. Malformed payloads return `422` before version comparison.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.review" method="patch" path="/companies/{company}/purchase-scans/{purchase_scan}/review" example="api_key_expired" -->
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

$request = new Operations\PublicApiV1PurchaseScansReviewRequest(
    company: 'Spencer, Walker and Denesik',
    purchaseScan: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SavePurchaseScanReviewV1Request(
        expectedVersion: 956114,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansReview(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.review" method="patch" path="/companies/{company}/purchase-scans/{purchase_scan}/review" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1PurchaseScansReviewRequest(
    company: 'Bernier - Tillman',
    purchaseScan: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SavePurchaseScanReviewV1Request(
        expectedVersion: 956114,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansReview(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.review" method="patch" path="/companies/{company}/purchase-scans/{purchase_scan}/review" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1PurchaseScansReviewRequest(
    company: '882c2f59-258e-4662-ab27-4335a545ab2a',
    purchaseScan: '1b6ff4a0-27c3-43e2-9e41-2d9a4f3e8741',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SavePurchaseScanReviewV1Request(
        expectedVersion: 956114,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansReview(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.review" method="patch" path="/companies/{company}/purchase-scans/{purchase_scan}/review" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1PurchaseScansReviewRequest(
    company: 'Vandervort, Harris and Hayes',
    purchaseScan: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SavePurchaseScanReviewV1Request(
        expectedVersion: 956114,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansReview(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.review" method="patch" path="/companies/{company}/purchase-scans/{purchase_scan}/review" example="ip_not_allowed" -->
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

$request = new Operations\PublicApiV1PurchaseScansReviewRequest(
    company: 'Hamill - Funk',
    purchaseScan: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SavePurchaseScanReviewV1Request(
        expectedVersion: 956114,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansReview(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.review" method="patch" path="/companies/{company}/purchase-scans/{purchase_scan}/review" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1PurchaseScansReviewRequest(
    company: 'Kirlin - Gulgowski',
    purchaseScan: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SavePurchaseScanReviewV1Request(
        expectedVersion: 956114,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansReview(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.review" method="patch" path="/companies/{company}/purchase-scans/{purchase_scan}/review" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1PurchaseScansReviewRequest(
    company: 'f753374f-e554-41ef-8de2-3546b5fbcf55',
    purchaseScan: 'd5575139-6468-477a-9f9c-969551ce3761',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SavePurchaseScanReviewV1Request(
        expectedVersion: 956114,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansReview(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.review" method="patch" path="/companies/{company}/purchase-scans/{purchase_scan}/review" example="success" -->
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

$request = new Operations\PublicApiV1PurchaseScansReviewRequest(
    company: 'Konopelski, Heaney and Dicki',
    purchaseScan: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SavePurchaseScanReviewV1Request(
        expectedVersion: 956114,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansReview(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.review" method="patch" path="/companies/{company}/purchase-scans/{purchase_scan}/review" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1PurchaseScansReviewRequest(
    company: 'a16e82a3-5f0f-4d62-887a-abb7bcdf4630',
    purchaseScan: '1c19b98e-82c0-42bc-87bf-764e355a30bc',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SavePurchaseScanReviewV1Request(
        expectedVersion: 956114,
    ),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansReview(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                            | Type                                                                                                                 | Required                                                                                                             | Description                                                                                                          |
| -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                           | [Operations\PublicApiV1PurchaseScansReviewRequest](../../Models/Operations/PublicApiV1PurchaseScansReviewRequest.md) | :heavy_check_mark:                                                                                                   | The request object to use for the request.                                                                           |

### Response

**[?Operations\PublicApiV1PurchaseScansReviewResponse](../../Models/Operations/PublicApiV1PurchaseScansReviewResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1PurchaseScansList

List the documents received by the purchase scanner (upload, API or mailbox) with their lifecycle status, extraction issues and `available_actions`. Use `filter[status]`, `filter[source]` and the documented date/range filters; cursor-based pagination.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.list" method="get" path="/companies/{company}/purchase-scans" example="success" -->
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

$request = new Operations\PublicApiV1PurchaseScansListRequest(
    company: 'Davis - Robel',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansList(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                        | Type                                                                                                             | Required                                                                                                         | Description                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                       | [Operations\PublicApiV1PurchaseScansListRequest](../../Models/Operations/PublicApiV1PurchaseScansListRequest.md) | :heavy_check_mark:                                                                                               | The request object to use for the request.                                                                       |

### Response

**[?Operations\PublicApiV1PurchaseScansListResponse](../../Models/Operations/PublicApiV1PurchaseScansListResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1PurchaseScansCreate

Upload up to 20 PDF, JPEG or PNG files as `multipart/form-data` (`files[]`) to the purchase scanner.
- Responds `202` with `accepted[]` and `rejected[]`; `422` when no file was accepted.
- Send an `Idempotency-Key` header: a replay returns the same result without re-ingesting.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.create" method="post" path="/companies/{company}/purchase-scans" example="api_key_expired" -->
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

$body = new Components\UploadPurchaseScansRequest(
    files: [],
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansCreate(
    company: 'Moen and Sons',
    idempotencyKey: '<value>',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.create" method="post" path="/companies/{company}/purchase-scans" example="api_key_revoked" -->
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

$body = new Components\UploadPurchaseScansRequest(
    files: [],
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansCreate(
    company: 'Mertz - Walter',
    idempotencyKey: '<value>',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.create" method="post" path="/companies/{company}/purchase-scans" example="factuarea_version_invalid_format" -->
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

$body = new Components\UploadPurchaseScansRequest(
    files: [],
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansCreate(
    company: 'cac738f9-e8d3-4245-ae4c-4eca29948e32',
    idempotencyKey: '<value>',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: idempotency_key_required

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.create" method="post" path="/companies/{company}/purchase-scans" example="idempotency_key_required" -->
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

$body = new Components\UploadPurchaseScansRequest(
    files: [],
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansCreate(
    company: 'O\'Reilly Inc',
    idempotencyKey: '<value>',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: idempotency_key_reused

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.create" method="post" path="/companies/{company}/purchase-scans" example="idempotency_key_reused" -->
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

$body = new Components\UploadPurchaseScansRequest(
    files: [],
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansCreate(
    company: 'Lang - Wolf',
    idempotencyKey: '<value>',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.create" method="post" path="/companies/{company}/purchase-scans" example="invalid_api_key" -->
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

$body = new Components\UploadPurchaseScansRequest(
    files: [],
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansCreate(
    company: 'Cremin and Sons',
    idempotencyKey: '<value>',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_idempotency_key_format

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.create" method="post" path="/companies/{company}/purchase-scans" example="invalid_idempotency_key_format" -->
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

$body = new Components\UploadPurchaseScansRequest(
    files: [],
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansCreate(
    company: 'Bashirian LLC',
    idempotencyKey: '<value>',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.create" method="post" path="/companies/{company}/purchase-scans" example="ip_not_allowed" -->
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

$body = new Components\UploadPurchaseScansRequest(
    files: [],
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansCreate(
    company: 'Watsica, Marks and Hyatt',
    idempotencyKey: '<value>',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.create" method="post" path="/companies/{company}/purchase-scans" example="missing_api_key" -->
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

$body = new Components\UploadPurchaseScansRequest(
    files: [],
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansCreate(
    company: 'Fay Inc',
    idempotencyKey: '<value>',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: rejected_batch

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.create" method="post" path="/companies/{company}/purchase-scans" example="rejected_batch" -->
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

$body = new Components\UploadPurchaseScansRequest(
    files: [],
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansCreate(
    company: 'Rolfson - Vandervort',
    idempotencyKey: '<value>',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.create" method="post" path="/companies/{company}/purchase-scans" example="retired_active_profile_header" -->
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

$body = new Components\UploadPurchaseScansRequest(
    files: [],
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansCreate(
    company: '65a2ce28-f608-47a7-9a36-a2f49e89cee9',
    idempotencyKey: '<value>',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.create" method="post" path="/companies/{company}/purchase-scans" example="success" -->
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

$body = new Components\UploadPurchaseScansRequest(
    files: [],
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansCreate(
    company: 'Hammes LLC',
    idempotencyKey: '<value>',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scans.create" method="post" path="/companies/{company}/purchase-scans" example="unsupported_api_version" -->
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

$body = new Components\UploadPurchaseScansRequest(
    files: [],
);

$response = $sdk->purchaseScans->publicApiV1PurchaseScansCreate(
    company: '10a50799-1bea-4178-a5fd-24f128072e6d',
    idempotencyKey: '<value>',
    body: $body,
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
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                             | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Stable key for this mutation (16–128 characters). Reuse it only with the same payload; after editing the review, start a new mutation with a new key.                                                                                                                                                                                                                        |                                                                                                                                                                                                                                                                                                                                                                              |
| `body`                                                                                                                                                                                                                                                                                                                                                                       | [Components\UploadPurchaseScansRequest](../../Models/Components/UploadPurchaseScansRequest.md)                                                                                                                                                                                                                                                                               | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Multipart form with one part per file. Every part must be named `files[]`; repeated parts without the brackets are collapsed into a single file by the server.                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1PurchaseScansCreateResponse](../../Models/Operations/PublicApiV1PurchaseScansCreateResponse.md)**

### Errors

| Error Type                                                 | Status Code                                                | Content Type                                               |
| ---------------------------------------------------------- | ---------------------------------------------------------- | ---------------------------------------------------------- |
| Errors\Error                                               | 422                                                        | application/json                                           |
| Errors\PublicApiV1PurchaseScansCreateResponseBodyException | 422                                                        | application/json                                           |
| Errors\Error                                               | 400, 401, 403, 404, 409, 413, 429                          | application/json                                           |
| Errors\Error                                               | 500, 503                                                   | application/json                                           |
| Errors\APIException                                        | 4XX, 5XX                                                   | \*/\*                                                      |