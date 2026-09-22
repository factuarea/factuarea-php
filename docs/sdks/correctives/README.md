# StripeAutoinvoicing.Correctives

## Overview

### Available Operations

* [publicApiV1StripeAutoinvoicingCorrectivesList](#publicapiv1stripeautoinvoicingcorrectiveslist) - List Stripe autoinvoiced correctives

## publicApiV1StripeAutoinvoicingCorrectivesList

List the corrective invoices automatically generated from Stripe refunds (`charge.refunded`), with cursor-based pagination. The public `id` is the corrective invoice (UUID v7); `original_invoice_id` links to the original invoice, and `refund_id` is the originating gateway refund.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.correctives.list" method="get" path="/companies/{company}/stripe-autoinvoicing/correctives" example="success" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingCorrectivesListRequest(
    company: 'Larson, Hickle and Wisoky',
    startingAfter: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a42',
    endingBefore: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a42',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->stripeAutoinvoicing->correctives->publicApiV1StripeAutoinvoicingCorrectivesList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                          | Type                                                                                                                                               | Required                                                                                                                                           | Description                                                                                                                                        |
| -------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                         | [Operations\PublicApiV1StripeAutoinvoicingCorrectivesListRequest](../../Models/Operations/PublicApiV1StripeAutoinvoicingCorrectivesListRequest.md) | :heavy_check_mark:                                                                                                                                 | The request object to use for the request.                                                                                                         |

### Response

**[?Operations\PublicApiV1StripeAutoinvoicingCorrectivesListResponse](../../Models/Operations/PublicApiV1StripeAutoinvoicingCorrectivesListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |