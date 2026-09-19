# Stores.ProductLinks

## Overview

### Available Operations

* [publicApiV1StoresProductLinksList](#publicapiv1storesproductlinkslist) - List the product links of a store
* [publicApiV1StoresProductLinksShow](#publicapiv1storesproductlinksshow) - Retrieve a product link of a store

## publicApiV1StoresProductLinksList

List the links between your articles and the products of one connected store, taking the store from the path, with cursor-based pagination. Filter by the status of the link and by your own article, or by the remote identity of the product, of the variant or of the inventory item, which is the key you use to cross the two systems. Each link comes back with your article and variant, the remote identity in three separate fields and exactly as the store returns it, the status, how the link was made, the reason when it is in conflict, and the moments at which the catalog, the price and the inventory were last pushed. A store of another company answers as not found, never as an empty page.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.stores.product_links.list" method="get" path="/companies/{company}/stores/{store}/product-links" -->
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

$request = new Operations\PublicApiV1StoresProductLinksListRequest(
    company: 'Upton and Sons',
    store: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->stores->productLinks->publicApiV1StoresProductLinksList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1StoresProductLinksListRequest](../../Models/Operations/PublicApiV1StoresProductLinksListRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1StoresProductLinksListResponse](../../Models/Operations/PublicApiV1StoresProductLinksListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1StoresProductLinksShow

Retrieve one product link of a connected store by its `id`, resolved by the double key company and store: a link that belongs to another store of yours answers exactly like one that does not exist. It carries your article and variant, the remote identity of the product, of the variant and of the inventory item, the status, how the link was made, the reason when it is in conflict, and the three moments of the last push — catalog, price and inventory —, which is the honest answer to when this was last synchronized. The internal fingerprints the outbound lane uses to decide whether to push again are deliberately NOT published: they are an implementation detail whose algorithm can change, and building your own synchronization on top of them would break without warning.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.stores.product_links.show" method="get" path="/companies/{company}/stores/{store}/product-links/{link}" -->
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

$request = new Operations\PublicApiV1StoresProductLinksShowRequest(
    company: 'Schamberger - Kuhlman',
    store: '<value>',
    link: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->stores->productLinks->publicApiV1StoresProductLinksShow(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1StoresProductLinksShowRequest](../../Models/Operations/PublicApiV1StoresProductLinksShowRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1StoresProductLinksShowResponse](../../Models/Operations/PublicApiV1StoresProductLinksShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |