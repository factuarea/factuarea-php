# Verifactu.Chain

## Overview

### Available Operations

* [publicApiV1VerifactuChainValidate](#publicapiv1verifactuchainvalidate) - Validate the VeriFactu hash chain

## publicApiV1VerifactuChainValidate

Validate Veri Factu Chain V1.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.verifactu.chain.validate" method="get" path="/verifactu/chain/validate" -->
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



$response = $sdk->verifactu->chain->publicApiV1VerifactuChainValidate(

);

if ($response->object !== null) {
    // handle response
}
```

### Response

**[?Operations\PublicApiV1VerifactuChainValidateResponse](../../Models/Operations/PublicApiV1VerifactuChainValidateResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 429       | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |