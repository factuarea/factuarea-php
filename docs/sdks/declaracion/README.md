# Verifactu.Declaracion

## Overview

### Available Operations

* [publicApiV1VerifactuDeclaracionHistory](#publicapiv1verifactudeclaracionhistory) - List declaración responsable history
* [publicApiV1VerifactuDeclaracionCurrent](#publicapiv1verifactudeclaracioncurrent) - Retrieve the current declaración responsable

## publicApiV1VerifactuDeclaracionHistory

Get Declaracion Responsable History V1.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.verifactu.declaracion.history" method="get" path="/verifactu/declaracion-responsable/history" -->
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



$response = $sdk->verifactu->declaracion->publicApiV1VerifactuDeclaracionHistory(

);

if ($response->object !== null) {
    // handle response
}
```

### Response

**[?Operations\PublicApiV1VerifactuDeclaracionHistoryResponse](../../Models/Operations/PublicApiV1VerifactuDeclaracionHistoryResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 429       | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1VerifactuDeclaracionCurrent

Get Declaracion Responsable V1.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.verifactu.declaracion.current" method="get" path="/verifactu/declaracion-responsable" -->
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



$response = $sdk->verifactu->declaracion->publicApiV1VerifactuDeclaracionCurrent(

);

if ($response->object !== null) {
    // handle response
}
```

### Response

**[?Operations\PublicApiV1VerifactuDeclaracionCurrentResponse](../../Models/Operations/PublicApiV1VerifactuDeclaracionCurrentResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 429       | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |