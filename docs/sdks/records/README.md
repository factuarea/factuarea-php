# Verifactu.Records

## Overview

### Available Operations

* [publicApiV1VerifactuRecordsFindByCsv](#publicapiv1verifacturecordsfindbycsv) - Find a VeriFactu record by AEAT CSV
* [publicApiV1VerifactuRecordsFindByHuella](#publicapiv1verifacturecordsfindbyhuella) - Find a VeriFactu record by hash
* [publicApiV1VerifactuRecordsFindByInvoiceNumber](#publicapiv1verifacturecordsfindbyinvoicenumber) - Find a VeriFactu record by invoice number
* [publicApiV1VerifactuRecordsActivities](#publicapiv1verifacturecordsactivities) - List VeriFactu record activity timeline
* [publicApiV1VerifactuRecordsList](#publicapiv1verifacturecordslist) - List VeriFactu records
* [publicApiV1VerifactuRecordsRetry](#publicapiv1verifacturecordsretry) - Retry VeriFactu transmission
* [publicApiV1VerifactuRecordsShow](#publicapiv1verifacturecordsshow) - Retrieve a VeriFactu record

## publicApiV1VerifactuRecordsFindByCsv

Find Record By Aeat Csv V1.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.verifactu.records.find_by_csv" method="post" path="/verifactu/records/find-by-csv" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

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

$request = new Operations\FindRecordByAeatCsvV1Request(
    aeatCsv: 'ABC123DEF456GHI789',
);

$response = $sdk->verifactu->records->publicApiV1VerifactuRecordsFindByCsv(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                          | Type                                                                                               | Required                                                                                           | Description                                                                                        |
| -------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------- |
| `$request`                                                                                         | [Operations\FindRecordByAeatCsvV1Request](../../Models/Operations/FindRecordByAeatCsvV1Request.md) | :heavy_check_mark:                                                                                 | The request object to use for the request.                                                         |

### Response

**[?Operations\PublicApiV1VerifactuRecordsFindByCsvResponse](../../Models/Operations/PublicApiV1VerifactuRecordsFindByCsvResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 409, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1VerifactuRecordsFindByHuella

Find Record By Huella V1.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.verifactu.records.find_by_huella" method="post" path="/verifactu/records/find-by-huella" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

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

$request = new Operations\FindRecordByHuellaV1Request(
    huella: '3C8F1A2B9D4E5F6071829304A5B6C7D8E9F0A1B2C3D4E5F60718293041526378',
);

$response = $sdk->verifactu->records->publicApiV1VerifactuRecordsFindByHuella(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                        | Type                                                                                             | Required                                                                                         | Description                                                                                      |
| ------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------ |
| `$request`                                                                                       | [Operations\FindRecordByHuellaV1Request](../../Models/Operations/FindRecordByHuellaV1Request.md) | :heavy_check_mark:                                                                               | The request object to use for the request.                                                       |

### Response

**[?Operations\PublicApiV1VerifactuRecordsFindByHuellaResponse](../../Models/Operations/PublicApiV1VerifactuRecordsFindByHuellaResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 409, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1VerifactuRecordsFindByInvoiceNumber

Find Record By Invoice Number V1.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.verifactu.records.find_by_invoice_number" method="post" path="/verifactu/records/find-by-invoice-number" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

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

$request = new Operations\FindRecordByInvoiceNumberV1Request(
    series: 'FAC-2026',
    number: '00042',
);

$response = $sdk->verifactu->records->publicApiV1VerifactuRecordsFindByInvoiceNumber(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                      | Type                                                                                                           | Required                                                                                                       | Description                                                                                                    |
| -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                     | [Operations\FindRecordByInvoiceNumberV1Request](../../Models/Operations/FindRecordByInvoiceNumberV1Request.md) | :heavy_check_mark:                                                                                             | The request object to use for the request.                                                                     |

### Response

**[?Operations\PublicApiV1VerifactuRecordsFindByInvoiceNumberResponse](../../Models/Operations/PublicApiV1VerifactuRecordsFindByInvoiceNumberResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 409, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1VerifactuRecordsActivities

Get Veri Factu Activities V1.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.verifactu.records.activities" method="get" path="/verifactu/records/{record}/activities" -->
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



$response = $sdk->verifactu->records->publicApiV1VerifactuRecordsActivities(
    record: '<value>'
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter          | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `record`           | *string*           | :heavy_check_mark: | N/A                |

### Response

**[?Operations\PublicApiV1VerifactuRecordsActivitiesResponse](../../Models/Operations/PublicApiV1VerifactuRecordsActivitiesResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 422, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1VerifactuRecordsList

List Veri Factu Records V1.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.verifactu.records.list" method="get" path="/verifactu/records" -->
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



$response = $sdk->verifactu->records->publicApiV1VerifactuRecordsList(

);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Response

**[?Operations\PublicApiV1VerifactuRecordsListResponse](../../Models/Operations/PublicApiV1VerifactuRecordsListResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 422, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1VerifactuRecordsRetry

Retry Veri Factu Record V1.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.verifactu.records.retry" method="post" path="/verifactu/records/{record}/retry" -->
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



$response = $sdk->verifactu->records->publicApiV1VerifactuRecordsRetry(
    record: '<value>'
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter          | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `record`           | *string*           | :heavy_check_mark: | N/A                |

### Response

**[?Operations\PublicApiV1VerifactuRecordsRetryResponse](../../Models/Operations/PublicApiV1VerifactuRecordsRetryResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 409, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1VerifactuRecordsShow

Show Veri Factu Record V1.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.verifactu.records.show" method="get" path="/verifactu/records/{record}" -->
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



$response = $sdk->verifactu->records->publicApiV1VerifactuRecordsShow(
    record: '<value>'
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter          | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `record`           | *string*           | :heavy_check_mark: | N/A                |

### Response

**[?Operations\PublicApiV1VerifactuRecordsShowResponse](../../Models/Operations/PublicApiV1VerifactuRecordsShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 429       | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |