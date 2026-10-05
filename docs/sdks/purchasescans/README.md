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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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

Upload up to 20 PDF, JPEG or PNG files as `multipart/form-data` (`files[]`, 20 MiB each, 100 MiB per request). Each file is scanned for safety and stored encrypted. It is queued for OCR when automatic scanning is enabled; otherwise it remains `received` until explicitly started. Responds `202` with `accepted[]` (index plus the full scan detail) and `rejected[]` (index, filename, error code); `422` when no file was accepted. Send an `Idempotency-Key` header: a replay returns the same result without re-ingesting.

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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 422                               | application/json                  |
| Errors\ResponseBodyException      | 422                               | application/json                  |
| Errors\Error                      | 400, 401, 403, 404, 409, 413, 429 | application/json                  |
| Errors\Error                      | 500, 503                          | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |