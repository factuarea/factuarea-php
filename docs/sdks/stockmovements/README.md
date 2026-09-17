# Products.StockMovements

## Overview

### Available Operations

* [publicApiV1ProductsStockMovementsList](#publicapiv1productsstockmovementslist) - List stock movements of a product

## publicApiV1ProductsStockMovementsList

Read the append-only stock ledger of a product, from the most recently applied movement to the oldest, so an integration can audit where a balance came from and reconcile inventory against its own system. Each row carries the signed `delta` and the `stock_after` balance in BASE units as decimal strings, the reason, the source document, the acting user and the managing variant. `stock_after` is the balance at the instant the movement was applied and is derived from the COMPLETE ledger, so it does not change when `direction` hides the interleaved rows. Paginated by cursor (`limit` + `starting_after`); optional `direction=in|out` narrows it to stock in or stock out.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.stock_movements.list" method="get" path="/products/{product}/stock-movements" example="success" -->
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

$request = new Operations\PublicApiV1ProductsStockMovementsListRequest(
    product: 'Refined Plastic Computer',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->products->stockMovements->publicApiV1ProductsStockMovementsList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                          | Type                                                                                                                               | Required                                                                                                                           | Description                                                                                                                        |
| ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                         | [Operations\PublicApiV1ProductsStockMovementsListRequest](../../Models/Operations/PublicApiV1ProductsStockMovementsListRequest.md) | :heavy_check_mark:                                                                                                                 | The request object to use for the request.                                                                                         |

### Response

**[?Operations\PublicApiV1ProductsStockMovementsListResponse](../../Models/Operations/PublicApiV1ProductsStockMovementsListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |