# Invoices.Quarterly

## Overview

### Available Operations

* [publicApiV1InvoicesQuarterlyAvailable](#publicapiv1invoicesquarterlyavailable) - List quarters with invoices
* [publicApiV1InvoicesQuarterlyDownloadZip](#publicapiv1invoicesquarterlydownloadzip) - Generate quarterly ZIP archive
* [publicApiV1InvoicesQuarterlySendEmail](#publicapiv1invoicesquarterlysendemail) - Email quarterly ZIP to accountant

## publicApiV1InvoicesQuarterlyAvailable

Returns the quarters that have at least one invoice, with breakdown by invoice type (F1/F2/F3/R5). Useful to populate "quarter to export" selectors.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.quarterly.available" method="get" path="/invoices/quarterly/available-quarters" -->
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



$response = $sdk->invoices->quarterly->publicApiV1InvoicesQuarterlyAvailable(

);

if ($response->object !== null) {
    // handle response
}
```

### Response

**[?Operations\PublicApiV1InvoicesQuarterlyAvailableResponse](../../Models/Operations/PublicApiV1InvoicesQuarterlyAvailableResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 429       | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1InvoicesQuarterlyDownloadZip

Builds a ZIP with all invoice PDFs of the given quarter. Returns ZIP metadata (path, processed counts, errors).

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.quarterly.download_zip" method="post" path="/invoices/quarterly/download-zip" example="api_key_revoked" -->
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

$request = new Components\QuarterlyDownloadV1Request(
    year: 105193,
    quarter: 852790,
);

$response = $sdk->invoices->quarterly->publicApiV1InvoicesQuarterlyDownloadZip(
    request: $request
);

if ($response->bytes !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.quarterly.download_zip" method="post" path="/invoices/quarterly/download-zip" example="invalid_api_key" -->
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

$request = new Components\QuarterlyDownloadV1Request(
    year: 105193,
    quarter: 852790,
);

$response = $sdk->invoices->quarterly->publicApiV1InvoicesQuarterlyDownloadZip(
    request: $request
);

if ($response->bytes !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.quarterly.download_zip" method="post" path="/invoices/quarterly/download-zip" example="missing_api_key" -->
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

$request = new Components\QuarterlyDownloadV1Request(
    year: 105193,
    quarter: 852790,
);

$response = $sdk->invoices->quarterly->publicApiV1InvoicesQuarterlyDownloadZip(
    request: $request
);

if ($response->bytes !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                      | Type                                                                                           | Required                                                                                       | Description                                                                                    |
| ---------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------- |
| `$request`                                                                                     | [Components\QuarterlyDownloadV1Request](../../Models/Components/QuarterlyDownloadV1Request.md) | :heavy_check_mark:                                                                             | The request object to use for the request.                                                     |

### Response

**[?Operations\PublicApiV1InvoicesQuarterlyDownloadZipResponse](../../Models/Operations/PublicApiV1InvoicesQuarterlyDownloadZipResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1InvoicesQuarterlySendEmail

Generates the quarterly ZIP and emails it to the recipient, typically the tax accountant.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.quarterly.send_email" method="post" path="/invoices/quarterly/send-email" example="api_key_revoked" -->
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

$body = new Components\QuarterlyDownloadV1Request(
    year: 96908,
    quarter: 230067,
);

$response = $sdk->invoices->quarterly->publicApiV1InvoicesQuarterlySendEmail(
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.quarterly.send_email" method="post" path="/invoices/quarterly/send-email" example="invalid_api_key" -->
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

$body = new Components\QuarterlyDownloadV1Request(
    year: 96908,
    quarter: 230067,
);

$response = $sdk->invoices->quarterly->publicApiV1InvoicesQuarterlySendEmail(
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.quarterly.send_email" method="post" path="/invoices/quarterly/send-email" example="missing_api_key" -->
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

$body = new Components\QuarterlyDownloadV1Request(
    year: 96908,
    quarter: 230067,
);

$response = $sdk->invoices->quarterly->publicApiV1InvoicesQuarterlySendEmail(
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
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | [Components\QuarterlyDownloadV1Request](../../Models/Components/QuarterlyDownloadV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Unique key generated by the client to ensure idempotency on retries. It lets you safely resend the same request: the first response is cached and returned without re-executing the mutation. It is an **opaque string** to the server; any unique value of up to 64 characters is valid (UUID v7, UUID v4, ULID, nanoid, etc.). UUID v7 is recommended for consistency with the API identifiers. The same key reused with a different body returns `409 idempotency_key_reused`. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1InvoicesQuarterlySendEmailResponse](../../Models/Operations/PublicApiV1InvoicesQuarterlySendEmailResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 409, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |