# Verifactu.AeatAccess

## Overview

### Available Operations

* [publicApiV1VerifactuAeatAccessList](#publicapiv1verifactuaeataccesslist) - List AEAT access records
* [publicApiV1VerifactuAeatAccessShow](#publicapiv1verifactuaeataccessshow) - Retrieve an AEAT access record

## publicApiV1VerifactuAeatAccessList

List Aeat Access Records V1.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.verifactu.aeat_access.list" method="get" path="/verifactu/aeat-access/records" -->
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



$response = $sdk->verifactu->aeatAccess->publicApiV1VerifactuAeatAccessList(

);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Response

**[?Operations\PublicApiV1VerifactuAeatAccessListResponse](../../Models/Operations/PublicApiV1VerifactuAeatAccessListResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 422, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1VerifactuAeatAccessShow

Show Aeat Access Record V1.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.verifactu.aeat_access.show" method="get" path="/verifactu/aeat-access/records/{record}" -->
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



$response = $sdk->verifactu->aeatAccess->publicApiV1VerifactuAeatAccessShow(
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

**[?Operations\PublicApiV1VerifactuAeatAccessShowResponse](../../Models/Operations/PublicApiV1VerifactuAeatAccessShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 429       | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |