# PurchaseInvoices.Match

## Overview

### Available Operations

* [publicApiV1PurchaseInvoicesMatchAccept](#publicapiv1purchaseinvoicesmatchaccept) - Accept the deviation of a purchase invoice match
* [publicApiV1PurchaseInvoicesMatchLink](#publicapiv1purchaseinvoicesmatchlink) - Link a purchase invoice to a purchase order
* [publicApiV1PurchaseInvoicesMatchReject](#publicapiv1purchaseinvoicesmatchreject) - Reject a purchase invoice match

## publicApiV1PurchaseInvoicesMatchAccept

Accept the deviation the three-way match found and settle the match as matched. The body carries the reason in writing and it is required: deciding to pay a supplier something different from what was agreed is recorded with its justification. The response is the match of that invoice with its resulting state and its line detail. Like its two siblings, it needs the WRITE scope of the PURCHASE ORDER and not the one of the invoice. Irreversible, and this is the part to read before calling it: accepting marks the purchase order as BILLED, and billed is a terminal state with no way out — no published operation, not unlinking and not cancelling the invoice, takes the order back to where it was. It requires an `Idempotency-Key` when the policy of your credential demands it, and repeating it with the same key returns the result of the first call without producing a second effect.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_invoices.match.accept" method="post" path="/companies/{company}/purchase-invoices/{purchase_invoice}/match/accept" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1PurchaseInvoicesMatchAcceptRequest(
    company: 'Hirthe Inc',
    purchaseInvoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ResolvePurchaseInvoiceMatchRequest(
        reason: '<value>',
    ),
);

$response = $sdk->purchaseInvoices->match->publicApiV1PurchaseInvoicesMatchAccept(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_invoices.match.accept" method="post" path="/companies/{company}/purchase-invoices/{purchase_invoice}/match/accept" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1PurchaseInvoicesMatchAcceptRequest(
    company: 'Spinka - Deckow',
    purchaseInvoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ResolvePurchaseInvoiceMatchRequest(
        reason: '<value>',
    ),
);

$response = $sdk->purchaseInvoices->match->publicApiV1PurchaseInvoicesMatchAccept(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_invoices.match.accept" method="post" path="/companies/{company}/purchase-invoices/{purchase_invoice}/match/accept" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1PurchaseInvoicesMatchAcceptRequest(
    company: 'Dooley, Mraz and Feeney',
    purchaseInvoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ResolvePurchaseInvoiceMatchRequest(
        reason: '<value>',
    ),
);

$response = $sdk->purchaseInvoices->match->publicApiV1PurchaseInvoicesMatchAccept(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                            | Type                                                                                                                                 | Required                                                                                                                             | Description                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                           | [Operations\PublicApiV1PurchaseInvoicesMatchAcceptRequest](../../Models/Operations/PublicApiV1PurchaseInvoicesMatchAcceptRequest.md) | :heavy_check_mark:                                                                                                                   | The request object to use for the request.                                                                                           |

### Response

**[?Operations\PublicApiV1PurchaseInvoicesMatchAcceptResponse](../../Models/Operations/PublicApiV1PurchaseInvoicesMatchAcceptResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1PurchaseInvoicesMatchLink

Link a supplier invoice to a purchase order so the three-way match can be evaluated: what was ordered, what arrived in posted goods receipts and what the invoice bills. The body carries the purchase order by its public id. The response is the match of that invoice with the deviation broken down line by line — the typed reason and its magnitude — so the outcome of the decision is read without a second call, and the invoice does not move forward on its own. The scope is not the one the path suggests: this operation needs the WRITE scope of the PURCHASE ORDER, because matching is a capability of the order and it is the module of the order that a company may not have contracted; a credential holding only the write scope of purchase invoices is rejected. Linking against an order of another company returns 404, identical to an order that does not exist. Not irreversible: the link is undone from the application. Be aware of what this version publishes and what it does not — unlinking is not published, so you can link through the API and you cannot undo that link through the API.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_invoices.match.link" method="post" path="/companies/{company}/purchase-invoices/{purchase_invoice}/match/link" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1PurchaseInvoicesMatchLinkRequest(
    company: 'Brown - Rippin',
    purchaseInvoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\LinkPurchaseInvoiceToPurchaseOrderRequest(
        purchaseOrderId: 'cfa9e0d9-bbb8-49bc-b83f-3cb6c733eefc',
    ),
);

$response = $sdk->purchaseInvoices->match->publicApiV1PurchaseInvoicesMatchLink(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_invoices.match.link" method="post" path="/companies/{company}/purchase-invoices/{purchase_invoice}/match/link" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1PurchaseInvoicesMatchLinkRequest(
    company: 'Considine - Runolfsson',
    purchaseInvoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\LinkPurchaseInvoiceToPurchaseOrderRequest(
        purchaseOrderId: 'cfa9e0d9-bbb8-49bc-b83f-3cb6c733eefc',
    ),
);

$response = $sdk->purchaseInvoices->match->publicApiV1PurchaseInvoicesMatchLink(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_invoices.match.link" method="post" path="/companies/{company}/purchase-invoices/{purchase_invoice}/match/link" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1PurchaseInvoicesMatchLinkRequest(
    company: 'O\'Hara Inc',
    purchaseInvoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\LinkPurchaseInvoiceToPurchaseOrderRequest(
        purchaseOrderId: 'cfa9e0d9-bbb8-49bc-b83f-3cb6c733eefc',
    ),
);

$response = $sdk->purchaseInvoices->match->publicApiV1PurchaseInvoicesMatchLink(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                        | Type                                                                                                                             | Required                                                                                                                         | Description                                                                                                                      |
| -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                       | [Operations\PublicApiV1PurchaseInvoicesMatchLinkRequest](../../Models/Operations/PublicApiV1PurchaseInvoicesMatchLinkRequest.md) | :heavy_check_mark:                                                                                                               | The request object to use for the request.                                                                                       |

### Response

**[?Operations\PublicApiV1PurchaseInvoicesMatchLinkResponse](../../Models/Operations/PublicApiV1PurchaseInvoicesMatchLinkResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1PurchaseInvoicesMatchReject

Reject the three-way match of a supplier invoice. The body carries the reason in writing and it is required. The response is the match of that invoice with its resulting state and its line detail. Like its two siblings, it needs the WRITE scope of the PURCHASE ORDER and not the one of the invoice. The invoice stops being able to move forward, but cancelling it stays open, so this one is not published as irreversible. Rejecting the match of an invoice of another company returns 404, identical to one that does not exist.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_invoices.match.reject" method="post" path="/companies/{company}/purchase-invoices/{purchase_invoice}/match/reject" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1PurchaseInvoicesMatchRejectRequest(
    company: 'Heidenreich LLC',
    purchaseInvoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ResolvePurchaseInvoiceMatchRequest(
        reason: '<value>',
    ),
);

$response = $sdk->purchaseInvoices->match->publicApiV1PurchaseInvoicesMatchReject(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_invoices.match.reject" method="post" path="/companies/{company}/purchase-invoices/{purchase_invoice}/match/reject" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1PurchaseInvoicesMatchRejectRequest(
    company: 'Ratke, Dare and Cormier',
    purchaseInvoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ResolvePurchaseInvoiceMatchRequest(
        reason: '<value>',
    ),
);

$response = $sdk->purchaseInvoices->match->publicApiV1PurchaseInvoicesMatchReject(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_invoices.match.reject" method="post" path="/companies/{company}/purchase-invoices/{purchase_invoice}/match/reject" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1PurchaseInvoicesMatchRejectRequest(
    company: 'Treutel Inc',
    purchaseInvoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ResolvePurchaseInvoiceMatchRequest(
        reason: '<value>',
    ),
);

$response = $sdk->purchaseInvoices->match->publicApiV1PurchaseInvoicesMatchReject(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                            | Type                                                                                                                                 | Required                                                                                                                             | Description                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                           | [Operations\PublicApiV1PurchaseInvoicesMatchRejectRequest](../../Models/Operations/PublicApiV1PurchaseInvoicesMatchRejectRequest.md) | :heavy_check_mark:                                                                                                                   | The request object to use for the request.                                                                                           |

### Response

**[?Operations\PublicApiV1PurchaseInvoicesMatchRejectResponse](../../Models/Operations/PublicApiV1PurchaseInvoicesMatchRejectResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |