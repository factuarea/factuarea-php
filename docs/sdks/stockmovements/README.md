# Products.StockMovements

## Overview

### Available Operations

* [publicApiV1ProductsStockMovementsList](#publicapiv1productsstockmovementslist) - List stock movements of a product

## publicApiV1ProductsStockMovementsList

Read the append-only stock ledger of a product, newest movement first.
- Rows carry signed `delta` and `stock_after` (base units); `stock_after` ignores `direction`.
- Cursor-paginated (`limit` + `starting_after`); `direction=in|out` narrows the rows.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.stock_movements.list" method="get" path="/companies/{company}/products/{product}/stock-movements" example="success" -->
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
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1ProductsStockMovementsListRequest(
    company: 'McGlynn, Hegmann and McLaughlin',
    product: 'Refined Plastic Computer',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |