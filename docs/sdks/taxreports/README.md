# TaxReports

## Overview

### Available Operations

* [publicApiV1TaxReportsDownload](#publicapiv1taxreportsdownload) - Download tax report file
* [publicApiV1TaxReportsFindByPeriod](#publicapiv1taxreportsfindbyperiod) - Find a tax report by period
* [publicApiV1TaxReportsGenerate303](#publicapiv1taxreportsgenerate303) - Generate Modelo 303
* [publicApiV1TaxReportsGenerate347](#publicapiv1taxreportsgenerate347) - Generate Modelo 347
* [publicApiV1TaxReportsActivities](#publicapiv1taxreportsactivities) - List tax report activities
* [publicApiV1TaxReportsStats](#publicapiv1taxreportsstats) - Retrieve tax report stats
* [publicApiV1TaxReportsHistory](#publicapiv1taxreportshistory) - List tax report history
* [publicApiV1TaxReportsPreview](#publicapiv1taxreportspreview) - Preview a tax report

## publicApiV1TaxReportsDownload

Download Tax Report V1.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.tax_reports.download" method="get" path="/tax_reports/{tax_report}/download" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->taxReports->publicApiV1TaxReportsDownload(
    taxReport: '<value>'
);

if ($response->string !== null) {
    // handle response
}
```

### Parameters

| Parameter          | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `taxReport`        | *string*           | :heavy_check_mark: | N/A                |

### Response

**[?Operations\PublicApiV1TaxReportsDownloadResponse](../../Models/Operations/PublicApiV1TaxReportsDownloadResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1TaxReportsFindByPeriod

Find Tax Report By Period V1.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.tax_reports.find_by_period" method="post" path="/tax_reports/find-by-period" example="api_key_revoked" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Components\FindTaxReportByPeriodV1Request(
    type: Components\FindTaxReportByPeriodV1RequestType::Modelo347,
    year: 401435,
);

$response = $sdk->taxReports->publicApiV1TaxReportsFindByPeriod(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.tax_reports.find_by_period" method="post" path="/tax_reports/find-by-period" example="invalid_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Components\FindTaxReportByPeriodV1Request(
    type: Components\FindTaxReportByPeriodV1RequestType::Modelo347,
    year: 401435,
);

$response = $sdk->taxReports->publicApiV1TaxReportsFindByPeriod(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.tax_reports.find_by_period" method="post" path="/tax_reports/find-by-period" example="missing_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Components\FindTaxReportByPeriodV1Request(
    type: Components\FindTaxReportByPeriodV1RequestType::Modelo347,
    year: 401435,
);

$response = $sdk->taxReports->publicApiV1TaxReportsFindByPeriod(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                              | Type                                                                                                   | Required                                                                                               | Description                                                                                            |
| ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                             | [Components\FindTaxReportByPeriodV1Request](../../Models/Components/FindTaxReportByPeriodV1Request.md) | :heavy_check_mark:                                                                                     | The request object to use for the request.                                                             |

### Response

**[?Operations\PublicApiV1TaxReportsFindByPeriodResponse](../../Models/Operations/PublicApiV1TaxReportsFindByPeriodResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1TaxReportsGenerate303

Generate Modelo303 V1.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.tax_reports.generate_303" method="post" path="/tax_reports/303" example="api_key_revoked" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\GenerateModelo303V1Request(
    year: 745633,
    format: Components\GenerateModelo303V1RequestFormat::Excel,
);

$response = $sdk->taxReports->publicApiV1TaxReportsGenerate303(
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.tax_reports.generate_303" method="post" path="/tax_reports/303" example="invalid_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\GenerateModelo303V1Request(
    year: 745633,
    format: Components\GenerateModelo303V1RequestFormat::Excel,
);

$response = $sdk->taxReports->publicApiV1TaxReportsGenerate303(
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.tax_reports.generate_303" method="post" path="/tax_reports/303" example="missing_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\GenerateModelo303V1Request(
    year: 745633,
    format: Components\GenerateModelo303V1RequestFormat::Excel,
);

$response = $sdk->taxReports->publicApiV1TaxReportsGenerate303(
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                              | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                       | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | [Components\GenerateModelo303V1Request](../../Models/Components/GenerateModelo303V1Request.md)                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Unique key generated by the client to ensure idempotency on retries. It lets you safely resend the same request: the first response is cached and returned without re-executing the mutation. It is an **opaque string** to the server; any unique value of up to 64 characters is valid (UUID v7, UUID v4, ULID, nanoid, etc.). UUID v7 is recommended for consistency with the API identifiers. The same key reused with a different body returns `409 idempotency_key_reused`. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1TaxReportsGenerate303Response](../../Models/Operations/PublicApiV1TaxReportsGenerate303Response.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 409, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1TaxReportsGenerate347

Generate Modelo347 V1.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.tax_reports.generate_347" method="post" path="/tax_reports/347" example="api_key_revoked" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\GenerateModelo347V1Request(
    year: 376706,
    format: Components\GenerateModelo347V1RequestFormat::TxtAeat,
);

$response = $sdk->taxReports->publicApiV1TaxReportsGenerate347(
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.tax_reports.generate_347" method="post" path="/tax_reports/347" example="invalid_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\GenerateModelo347V1Request(
    year: 376706,
    format: Components\GenerateModelo347V1RequestFormat::TxtAeat,
);

$response = $sdk->taxReports->publicApiV1TaxReportsGenerate347(
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.tax_reports.generate_347" method="post" path="/tax_reports/347" example="missing_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\GenerateModelo347V1Request(
    year: 376706,
    format: Components\GenerateModelo347V1RequestFormat::TxtAeat,
);

$response = $sdk->taxReports->publicApiV1TaxReportsGenerate347(
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                              | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                       | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | [Components\GenerateModelo347V1Request](../../Models/Components/GenerateModelo347V1Request.md)                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Unique key generated by the client to ensure idempotency on retries. It lets you safely resend the same request: the first response is cached and returned without re-executing the mutation. It is an **opaque string** to the server; any unique value of up to 64 characters is valid (UUID v7, UUID v4, ULID, nanoid, etc.). UUID v7 is recommended for consistency with the API identifiers. The same key reused with a different body returns `409 idempotency_key_reused`. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1TaxReportsGenerate347Response](../../Models/Operations/PublicApiV1TaxReportsGenerate347Response.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 409, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1TaxReportsActivities

Get Tax Report Activities V1.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.tax_reports.activities" method="get" path="/tax_reports/{tax_report}/activities" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->taxReports->publicApiV1TaxReportsActivities(
    taxReport: '<value>'
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter          | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `taxReport`        | *string*           | :heavy_check_mark: | N/A                |

### Response

**[?Operations\PublicApiV1TaxReportsActivitiesResponse](../../Models/Operations/PublicApiV1TaxReportsActivitiesResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1TaxReportsStats

Get Tax Report Stats V1.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.tax_reports.stats" method="get" path="/tax_reports/stats" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->taxReports->publicApiV1TaxReportsStats(

);

if ($response->object !== null) {
    // handle response
}
```

### Response

**[?Operations\PublicApiV1TaxReportsStatsResponse](../../Models/Operations/PublicApiV1TaxReportsStatsResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 429       | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1TaxReportsHistory

List Tax Reports V1.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.tax_reports.history" method="get" path="/tax_reports/history" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->taxReports->publicApiV1TaxReportsHistory(

);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Response

**[?Operations\PublicApiV1TaxReportsHistoryResponse](../../Models/Operations/PublicApiV1TaxReportsHistoryResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 422, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1TaxReportsPreview

Preview Tax Report V1.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.tax_reports.preview" method="post" path="/tax_reports/preview" example="api_key_revoked" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Components\PreviewTaxReportV1Request(
    type: Components\PreviewTaxReportV1RequestType::Modelo303,
    year: 455020,
);

$response = $sdk->taxReports->publicApiV1TaxReportsPreview(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.tax_reports.preview" method="post" path="/tax_reports/preview" example="invalid_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Components\PreviewTaxReportV1Request(
    type: Components\PreviewTaxReportV1RequestType::Modelo303,
    year: 455020,
);

$response = $sdk->taxReports->publicApiV1TaxReportsPreview(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.tax_reports.preview" method="post" path="/tax_reports/preview" example="missing_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Components\PreviewTaxReportV1Request(
    type: Components\PreviewTaxReportV1RequestType::Modelo303,
    year: 455020,
);

$response = $sdk->taxReports->publicApiV1TaxReportsPreview(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                    | Type                                                                                         | Required                                                                                     | Description                                                                                  |
| -------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------- |
| `$request`                                                                                   | [Components\PreviewTaxReportV1Request](../../Models/Components/PreviewTaxReportV1Request.md) | :heavy_check_mark:                                                                           | The request object to use for the request.                                                   |

### Response

**[?Operations\PublicApiV1TaxReportsPreviewResponse](../../Models/Operations/PublicApiV1TaxReportsPreviewResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 409, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |