# Products.Configurations

## Overview

### Available Operations

* [publicApiV1ProductsConfigurationsList](#publicapiv1productsconfigurationslist) - List product commercial combinations
* [publicApiV1ProductsConfigurationsImpactPreview](#publicapiv1productsconfigurationsimpactpreview) - Preview the impact of restricting a catalog

## publicApiV1ProductsConfigurationsList

List the commercial combinations (variant × presentation × option values) declared by a product, each with its canonical `signature`, its optional own price and its activation state. An EMPTY list does not mean the product cannot be sold: it means the product is in LEGACY mode and accepts any cross of its active variants and presentations. As soon as one active combination exists the list becomes an allow-list and every cross not enumerated stops being accepted. Without the `active` filter both active and inactive combinations come back — a deactivated combination still names historical lines, and hiding it would leave already issued documents without a label. Cursor-paginated (`limit`, `starting_after`); a cursor that matches no combination of this product returns 422. A product of another company returns 404 `product_not_found`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.products.configurations.list" method="get" path="/companies/{company}/products/{product}/configurations" example="success" -->
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

$request = new Operations\PublicApiV1ProductsConfigurationsListRequest(
    company: 'Gerlach - Gutkowski',
    product: 'Luxurious Steel Mouse',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->products->configurations->publicApiV1ProductsConfigurationsList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                          | Type                                                                                                                               | Required                                                                                                                           | Description                                                                                                                        |
| ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                         | [Operations\PublicApiV1ProductsConfigurationsListRequest](../../Models/Operations/PublicApiV1ProductsConfigurationsListRequest.md) | :heavy_check_mark:                                                                                                                 | The request object to use for the request.                                                                                         |

### Response

**[?Operations\PublicApiV1ProductsConfigurationsListResponse](../../Models/Operations/PublicApiV1ProductsConfigurationsListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1ProductsConfigurationsImpactPreview

Compute what would be lost if the commercial combinations of a product were replaced by the proposed ones, and mint the `impact_token` that confirms THAT exact change. It is a READ and persists nothing — asking twice over the same catalog returns the same token — but it requires `products:write`, because the only thing its answer does is authorise a destructive write. Send `configurations` as the COMPLETE list of combinations that would remain ACTIVE (an empty list means "retire the allow-list", which returns the product to the cartesian cross), plus `variant_ids`/`presentation_ids` enumerating what SURVIVES on each axis — omitting a key means "that axis is untouched", which is not the same as an empty list. The answer says whether the change needs confirmation at all (`requires_confirmation`), which crosses stop being sellable, and which negotiated prices would be retired and from which price lists. The token is a fingerprint of the current catalog AND of the set of prices at stake, not a credential: if either changes before you confirm, the confirmation is rejected and you ask for a fresh preview. Send it back as `impact_token` when emptying `configurations` on `PATCH /v1/companies/{company}/products/{id}`, or when deleting the variant or presentation whose removal would leave the product with no declared combination.

### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.configurations.impact_preview" method="post" path="/companies/{company}/products/{product}/configurations/impact-preview" example="missing_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\PreviewCatalogConfigurationImpactRequest(
    configurations: [],
);

$response = $sdk->products->configurations->publicApiV1ProductsConfigurationsImpactPreview(
    company: 'King - Schneider',
    product: 'Recycled Rubber Pizza',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.products.configurations.impact_preview" method="post" path="/companies/{company}/products/{product}/configurations/impact-preview" example="success" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\PreviewCatalogConfigurationImpactRequest(
    configurations: [],
);

$response = $sdk->products->configurations->publicApiV1ProductsConfigurationsImpactPreview(
    company: 'Stamm - Bernhard',
    product: 'Luxurious Wooden Tuna',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | N/A                                                                                                                                                                                                                                                                                                                                                                          |                                                                                                                                                                                                                                                                                                                                                                              |
| `product`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | N/A                                                                                                                                                                                                                                                                                                                                                                          |                                                                                                                                                                                                                                                                                                                                                                              |
| `body`                                                                                                                                                                                                                                                                                                                                                                       | [Components\PreviewCatalogConfigurationImpactRequest](../../Models/Components/PreviewCatalogConfigurationImpactRequest.md)                                                                                                                                                                                                                                                   | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | N/A                                                                                                                                                                                                                                                                                                                                                                          |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1ProductsConfigurationsImpactPreviewResponse](../../Models/Operations/PublicApiV1ProductsConfigurationsImpactPreviewResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |