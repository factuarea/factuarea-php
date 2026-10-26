# Products.BaseUnitReplacement

## Overview

### Available Operations

* [publicApiV1ProductsBaseUnitReplacementPreview](#publicapiv1productsbaseunitreplacementpreview) - Preview a base unit replacement
* [publicApiV1ProductsBaseUnitReplacementCreate](#publicapiv1productsbaseunitreplacementcreate) - Replace a product by changing its base unit

## publicApiV1ProductsBaseUnitReplacementPreview

Compute, without writing anything, what happens when a product is replaced by a new one with a different base unit, and mint the `impact_token` that confirms THAT exact change. The base unit of a product with history (stock, movements, presentations, supplier offers or variants with stock) cannot be edited in place: its `base_unit_change.mode` is `replacement` and the way to change it is to replace the product. It is a READ — asking twice over the same catalog returns the same token — but it requires `products:write`, because the only thing its answer does is authorise an irreversible write. Send `target_base_unit` (one of the nine measure units; a packaging unit such as `XPK` or `XBX` can only be a presentation), `equivalence` (how many units of the CURRENT base equal ONE unit of the NEW base, e.g. `0.001` when moving from kg to g because 1 g = 0.001 kg; required between a count unit and a measure unit, in either direction; calculated by itself inside the same measure family; there is none between two different measure families), an optional `base_price` to confirm, and `presentations` with a `keep` or `retire` decision per presentation of the old product (the ones you omit get the proposed decision). The answer lists every element of the plan in `items`, each with its `before`, its `after`, its `group` (`review`, `converted` or `carried`) and its `flags` (`precision_loss`, `no_equivalence`, `fraction`); `items[].ref` is the public id of the affected element, and `null` for the product itself and for price-list entries and pack components, which have no public id. `pending_documents` lists the unissued sales documents that will keep pointing at the old product. The token is a fingerprint of the plan, not a credential: if the catalog, the price lists or the packs change before you confirm, the confirmation is rejected with 422 `base_unit_replacement_preview_stale` and you ask for a fresh preview. A product of another company returns 404 `product_not_found`.

### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.base_unit_replacement.preview" method="post" path="/companies/{company}/products/{product}/base-unit-replacement/preview" example="missing_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\PreviewProductBaseUnitReplacementRequest(
    targetBaseUnit: Components\PreviewProductBaseUnitReplacementRequestTargetBaseUnit::Hur,
);

$response = $sdk->products->baseUnitReplacement->publicApiV1ProductsBaseUnitReplacementPreview(
    company: '81803810-6bd9-48fc-8c70-b22c08fb256d',
    product: '91d154aa-c223-4f9e-93ae-7868b8aa2f21',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.products.base_unit_replacement.preview" method="post" path="/companies/{company}/products/{product}/base-unit-replacement/preview" example="success" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\PreviewProductBaseUnitReplacementRequest(
    targetBaseUnit: Components\PreviewProductBaseUnitReplacementRequestTargetBaseUnit::Hur,
);

$response = $sdk->products->baseUnitReplacement->publicApiV1ProductsBaseUnitReplacementPreview(
    company: '845d350e-cc4e-464f-b48a-e629c0c9d9ba',
    product: '9b46318e-3baa-4eb2-af82-7535a578898a',
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
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `product`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the product.                                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `body`                                                                                                                                                                                                                                                                                                                                                                       | [Components\PreviewProductBaseUnitReplacementRequest](../../Models/Components/PreviewProductBaseUnitReplacementRequest.md)                                                                                                                                                                                                                                                   | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | N/A                                                                                                                                                                                                                                                                                                                                                                          |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1ProductsBaseUnitReplacementPreviewResponse](../../Models/Operations/PublicApiV1ProductsBaseUnitReplacementPreviewResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ProductsBaseUnitReplacementCreate

Replace a product by a NEW one with a different base unit and answer 201 with the new product, whose `replaces` points at the old one. The stock, the presentations you keep, the variants, the supplier offers, the price-list entries and the pack components are converted and moved to the new product; the old one is deactivated, keeps its whole history and its `replaced_by` points at the new one, and it can never be reactivated. It is IRREVERSIBLE: there is no inverse operation, so `Idempotency-Key` is mandatory and retrying with the same key returns the same response without creating a second product. The body is the one of the preview plus the mandatory `impact_token` of a current preview with the same request. It does not consume plan quota: the old product stops counting and the net usage is zero. When the conversion loses precision or there is no equivalence, `base_price` must be confirmed (422 `base_unit_replacement_price_confirmation_required`). Rejections: 422 `business_rule_violation` with subcode `product_already_replaced`, `base_unit_replacement_conversion_unavailable` or `base_unit_replacement_blocked_by_packs`; 422 `parameter_invalid_value` with subcode `base_unit_replacement_same_unit` (the target is already the base unit of the product), `base_unit_replacement_equivalence_invalid`, `base_unit_replacement_price_confirmation_required`, `base_unit_replacement_preview_stale` or `base_unit_replacement_decision_invalid`. The `product.replaced` event is published with the new product as its object. A product of another company returns 404 `product_not_found`.

### Example Usage: created

<!-- UsageSnippet language="php" operationID="public-api.v1.products.base_unit_replacement.create" method="post" path="/companies/{company}/products/{product}/base-unit-replacement" example="created" -->
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

$request = new Operations\PublicApiV1ProductsBaseUnitReplacementCreateRequest(
    company: '76d712d7-7d66-487b-b73d-93c10a09662e',
    product: '551c6af3-775d-4450-993e-89abf5e5e083',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReplaceProductBaseUnitRequest(
        targetBaseUnit: Components\ReplaceProductBaseUnitRequestTargetBaseUnit::Mlt,
        impactToken: '<value>',
    ),
);

$response = $sdk->products->baseUnitReplacement->publicApiV1ProductsBaseUnitReplacementCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.products.base_unit_replacement.create" method="post" path="/companies/{company}/products/{product}/base-unit-replacement" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1ProductsBaseUnitReplacementCreateRequest(
    company: '1db262d8-c1f4-4f5b-8e99-e35b6108e9ce',
    product: '3afe2290-d948-461a-9457-fd7bfb15aafa',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReplaceProductBaseUnitRequest(
        targetBaseUnit: Components\ReplaceProductBaseUnitRequestTargetBaseUnit::Mlt,
        impactToken: '<value>',
    ),
);

$response = $sdk->products->baseUnitReplacement->publicApiV1ProductsBaseUnitReplacementCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                        | Type                                                                                                                                             | Required                                                                                                                                         | Description                                                                                                                                      |
| ------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                                       | [Operations\PublicApiV1ProductsBaseUnitReplacementCreateRequest](../../Models/Operations/PublicApiV1ProductsBaseUnitReplacementCreateRequest.md) | :heavy_check_mark:                                                                                                                               | The request object to use for the request.                                                                                                       |

### Response

**[?Operations\PublicApiV1ProductsBaseUnitReplacementCreateResponse](../../Models/Operations/PublicApiV1ProductsBaseUnitReplacementCreateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |