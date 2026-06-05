# Verifactu

## Overview

### Available Operations

* [publicApiV1VerifactuConfig](#publicapiv1verifactuconfig) - Retrieve VeriFactu config
* [publicApiV1VerifactuStats](#publicapiv1verifactustats) - Get VeriFactu stats

## publicApiV1VerifactuConfig

Get Veri Factu Config V1.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.verifactu.config" method="get" path="/verifactu/config" -->
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



$response = $sdk->verifactu->publicApiV1VerifactuConfig(

);

if ($response->object !== null) {
    // handle response
}
```

### Response

**[?Operations\PublicApiV1VerifactuConfigResponse](../../Models/Operations/PublicApiV1VerifactuConfigResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 429       | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1VerifactuStats

Get Veri Factu Stats V1.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.verifactu.stats" method="get" path="/verifactu/stats" -->
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



$response = $sdk->verifactu->publicApiV1VerifactuStats(

);

if ($response->object !== null) {
    // handle response
}
```

### Response

**[?Operations\PublicApiV1VerifactuStatsResponse](../../Models/Operations/PublicApiV1VerifactuStatsResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 422, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |