# PurchaseReorderSuggestions

## Overview

### Available Operations

* [publicApiV1PurchaseReorderSuggestionsAccept](#publicapiv1purchasereordersuggestionsaccept) - Accept a reorder suggestion
* [publicApiV1PurchaseReorderSuggestionsList](#publicapiv1purchasereordersuggestionslist) - List reorder suggestions

## publicApiV1PurchaseReorderSuggestionsAccept

Turn a reorder suggestion into purchase orders. The body carries the articles you accept with their quantity and the supplier offer each one is bought under, and the result is one purchase order in DRAFT per supplier, each with a line per accepted article, returned with its public id and its number, plus the articles that were left out with their reason. It is all or nothing: either every order is created or none is. Articles or offers of another company are rejected as if they did not exist, and the orders always belong to the company of the path. It is NOT published as irreversible, because the orders are born in draft and can be deleted — but read this before retrying: a suggestion is not a document with an identity, so accepting the same one twice creates a SECOND set of drafts. Send an `Idempotency-Key` if you may retry; it is not mandatory here, but it is honoured when it travels.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_reorder_suggestions.accept" method="post" path="/companies/{company}/purchase-reorder-suggestions/accept" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1PurchaseReorderSuggestionsAcceptRequest(
    company: 'Konopelski, Stokes and Watsica',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\AcceptPurchaseReorderSuggestionRequest(
        items: [
            new Components\Item(
                productId: 'a4f1b30b-b6ec-45c9-9240-3bede3cf0015',
                quantity: 6706.55,
            ),
        ],
    ),
);

$response = $sdk->purchaseReorderSuggestions->publicApiV1PurchaseReorderSuggestionsAccept(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_reorder_suggestions.accept" method="post" path="/companies/{company}/purchase-reorder-suggestions/accept" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1PurchaseReorderSuggestionsAcceptRequest(
    company: 'Lebsack, Jenkins and Huel',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\AcceptPurchaseReorderSuggestionRequest(
        items: [
            new Components\Item(
                productId: 'a4f1b30b-b6ec-45c9-9240-3bede3cf0015',
                quantity: 6706.55,
            ),
        ],
    ),
);

$response = $sdk->purchaseReorderSuggestions->publicApiV1PurchaseReorderSuggestionsAccept(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_reorder_suggestions.accept" method="post" path="/companies/{company}/purchase-reorder-suggestions/accept" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1PurchaseReorderSuggestionsAcceptRequest(
    company: 'Shanahan and Sons',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\AcceptPurchaseReorderSuggestionRequest(
        items: [
            new Components\Item(
                productId: 'a4f1b30b-b6ec-45c9-9240-3bede3cf0015',
                quantity: 6706.55,
            ),
        ],
    ),
);

$response = $sdk->purchaseReorderSuggestions->publicApiV1PurchaseReorderSuggestionsAccept(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                      | Type                                                                                                                                           | Required                                                                                                                                       | Description                                                                                                                                    |
| ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                     | [Operations\PublicApiV1PurchaseReorderSuggestionsAcceptRequest](../../Models/Operations/PublicApiV1PurchaseReorderSuggestionsAcceptRequest.md) | :heavy_check_mark:                                                                                                                             | The request object to use for the request.                                                                                                     |

### Response

**[?Operations\PublicApiV1PurchaseReorderSuggestionsAcceptResponse](../../Models/Operations/PublicApiV1PurchaseReorderSuggestionsAcceptResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1PurchaseReorderSuggestionsList

Read which articles of the company are below their declared stock threshold and what to buy to bring them back up: per article, the current quantity and the threshold in the base unit, the suggested quantity in the PURCHASE unit of the preferred supplier offer, the supplier, the code of that unit, the conversion factor, the unit cost and the lead time when the offer declares one. An article below its threshold with no active supplier offer is never hidden: it comes back marked as not actionable with its reason written, and the envelope adds how many are actionable and how many are not. It can be narrowed to a single supplier. This is a report calculated over the whole catalog and not a stored collection with a stable order, so it does NOT paginate by cursor: it is returned complete, and its size grows with your catalog — something to keep in mind before polling it.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_reorder_suggestions.list" method="get" path="/companies/{company}/purchase-reorder-suggestions" -->
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



$response = $sdk->purchaseReorderSuggestions->publicApiV1PurchaseReorderSuggestionsList(
    company: 'Nolan Inc',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                       | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                       | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                             | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                            |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                |
| `supplierId`                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Business contact ID (UUID v7) of the supplier whose preferred offers narrow the report. Optional and the ONLY parameter of this operation: there is no cursor, no sorting and no free-text search, because the report is returned whole — it is calculated over your catalog, so its size grows with it, and narrowing it by supplier is what keeps a single call small. A supplier that does not belong to the company in the path yields an EMPTY report, never a rejection. |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                             | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                  | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                   | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf).                                                                               | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                                                                                                           |

### Response

**[?Operations\PublicApiV1PurchaseReorderSuggestionsListResponse](../../Models/Operations/PublicApiV1PurchaseReorderSuggestionsListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |