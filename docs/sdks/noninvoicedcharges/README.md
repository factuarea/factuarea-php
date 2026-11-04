# StripeAutoinvoicing.Accounts.NonInvoicedCharges

## Overview

### Available Operations

* [publicApiV1StripeAutoinvoicingAccountsNonInvoicedChargesList](#publicapiv1stripeautoinvoicingaccountsnoninvoicedchargeslist) - List non-invoiced charges of a connected Stripe account

## publicApiV1StripeAutoinvoicingAccountsNonInvoicedChargesList

Lists charges of a connected Stripe account that a rule deliberately excluded from invoicing, newest first.
- Items expose `amount`, `refunded_amount`, `net_amount`, `reason`, `charge_rule_id` and `occurred_at`.
- Filter with `occurred_from`/`occurred_to` (`YYYY-MM-DD`); an invalid range returns 422.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.non_invoiced_charges.list" method="get" path="/companies/{company}/connected-accounts/{account}/non-invoiced-charges" example="success" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsNonInvoicedChargesListRequest(
    company: '26aac878-0789-46b2-8f88-0da60330271d',
    account: '0a566bdb-e9c0-41ba-a7a7-54284df34b62',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->stripeAutoinvoicing->accounts->nonInvoicedCharges->publicApiV1StripeAutoinvoicingAccountsNonInvoicedChargesList(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                        | Type                                                                                                                                                                             | Required                                                                                                                                                                         | Description                                                                                                                                                                      |
| -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                                                       | [Operations\PublicApiV1StripeAutoinvoicingAccountsNonInvoicedChargesListRequest](../../Models/Operations/PublicApiV1StripeAutoinvoicingAccountsNonInvoicedChargesListRequest.md) | :heavy_check_mark:                                                                                                                                                               | The request object to use for the request.                                                                                                                                       |

### Response

**[?Operations\PublicApiV1StripeAutoinvoicingAccountsNonInvoicedChargesListResponse](../../Models/Operations/PublicApiV1StripeAutoinvoicingAccountsNonInvoicedChargesListResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |