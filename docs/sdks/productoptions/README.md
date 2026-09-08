# Products.ProductOptions

## Overview

### Available Operations

* [publicApiV1ProductsOptionsList](#publicapiv1productsoptionslist) - List product option groups

## publicApiV1ProductsOptionsList

"Options" here are the SELLABLE choices of the configurable catalog (finish, flavour, service level), not the informational `specifications` sheet, which is not chosen and travels inside the product, variant or presentation it belongs to. Each group returns its values with their `price_adjustment`. The `active` filter applies to the GROUPS only: their values always come back in full, active and inactive, because a historical document line has the right to keep the name and adjustment of the value it froze even after the catalog retired it — each value carries its own `is_active` to say whether it can still be chosen. Cursor-paginated (`limit`, `starting_after`); a cursor that matches no group of this product returns 422, never 404. A product of another company returns 404 `product_not_found`, exactly like a product that does not exist.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.options.list" method="get" path="/products/{product}/options" -->
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

$request = new Operations\PublicApiV1ProductsOptionsListRequest(
    product: 'Gorgeous Metal Bike',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->products->productOptions->publicApiV1ProductsOptionsList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                            | Type                                                                                                                 | Required                                                                                                             | Description                                                                                                          |
| -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                           | [Operations\PublicApiV1ProductsOptionsListRequest](../../Models/Operations/PublicApiV1ProductsOptionsListRequest.md) | :heavy_check_mark:                                                                                                   | The request object to use for the request.                                                                           |

### Response

**[?Operations\PublicApiV1ProductsOptionsListResponse](../../Models/Operations/PublicApiV1ProductsOptionsListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |