# Account

## Overview

### Available Operations

* [publicApiV1AccountShow](#publicapiv1accountshow) - Retrieve account details

## publicApiV1AccountShow

Stripe-like account endpoint: returns the authenticated company together with its plan, add-ons, and the metadata of the API key in use (environment, scopes). Use it to introspect what the current key can do.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.account.show" method="get" path="/account" -->
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



$response = $sdk->account->publicApiV1AccountShow(

);

if ($response->object !== null) {
    // handle response
}
```

### Response

**[?Operations\PublicApiV1AccountShowResponse](../../Models/Operations/PublicApiV1AccountShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 429       | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |