# Products.ProductOptions

## Overview

### Available Operations

* [publicApiV1ProductsOptionsList](#publicapiv1productsoptionslist) - List product option groups

## publicApiV1ProductsOptionsList

List the SELLABLE option groups of a product (finish, flavour, service level) with their values and `price_adjustment`.
- The `active` filter applies to groups only; values always come back in full, each with `is_active`.
- Cursor-paginated (`limit`, `starting_after`); a product of another company returns 404 `product_not_found`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.options.list" method="get" path="/companies/{company}/products/{product}/options" example="success" -->
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

$request = new Operations\PublicApiV1ProductsOptionsListRequest(
    company: 'Mayer - Bosco',
    product: 'Gorgeous Metal Bike',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |