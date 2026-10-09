# StripeAutoinvoicing.Accounts.NonInvoicedCharges

## Overview

### Available Operations

* [publicApiV1StripeAutoinvoicingAccountsNonInvoicedChargesList](#publicapiv1stripeautoinvoicingaccountsnoninvoicedchargeslist) - List non-invoiced charges of a connected Stripe account

## publicApiV1StripeAutoinvoicingAccountsNonInvoicedChargesList

List the charges of a connected Stripe account that were deliberately not invoiced because a charge treatment rule excluded them. Each item exposes `id`, `connected_account_id`, `transaction_id`, `amount`, `refunded_amount` (cumulative amount Stripe has refunded, `0` if none), `net_amount` (`amount` minus `refunded_amount`), `currency`, `reason`, `charge_rule_id` (`null` if the rule was deleted), `description`, `occurred_at` and `refunded_at` (date of the latest refund, `null` if none); the three monetary fields use the same format. Filter by period with `occurred_from` and `occurred_to` (`YYYY-MM-DD`, both included, by the date the charge happened); a malformed date or an `occurred_to` earlier than `occurred_from` returns 422. Cursor paginated (`limit` 1-100, `starting_after`, `ending_before`), newest first.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.non_invoiced_charges.list" method="get" path="/connected-accounts/{account}/non-invoiced-charges" example="success" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsNonInvoicedChargesListRequest(
    account: '14667545',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
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

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |