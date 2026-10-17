# StripeAutoinvoicing.Accounts.ChargeRules

## Overview

### Available Operations

* [publicApiV1StripeAutoinvoicingAccountsChargeRulesCreate](#publicapiv1stripeautoinvoicingaccountschargerulescreate) - Create a charge treatment rule
* [publicApiV1StripeAutoinvoicingAccountsChargeRulesList](#publicapiv1stripeautoinvoicingaccountschargeruleslist) - List charge treatment rules of a connected Stripe account
* [publicApiV1StripeAutoinvoicingAccountsChargeRulesDelete](#publicapiv1stripeautoinvoicingaccountschargerulesdelete) - Delete a charge treatment rule
* [publicApiV1StripeAutoinvoicingAccountsChargeRulesUpdate](#publicapiv1stripeautoinvoicingaccountschargerulesupdate) - Update a charge treatment rule
* [publicApiV1StripeAutoinvoicingAccountsChargeRulesReorder](#publicapiv1stripeautoinvoicingaccountschargerulesreorder) - Reorder the charge treatment rules

## publicApiV1StripeAutoinvoicingAccountsChargeRulesCreate

Create a charge treatment rule at the end of the account's list. A rule matches a charge by `condition` and either invoices it (optionally with a `concept` and a `vat_rate`) or excludes it from invoicing with a `non_invoice_reason` (`indemnity_not_subject`, `multipurpose_voucher` or `invoiced_elsewhere`). Responds 201 with the rule. Fails with `charge_rule_invalid` when a field breaks the contract and with `charge_rule_limit_reached` when the account already has the maximum number of rules.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.create" method="post" path="/companies/{company}/connected-accounts/{account}/charge-rules" example="api_key_expired" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesCreateRequest(
    company: '8f8b7bdf-6fdd-404a-8992-01514665d622',
    account: '42241c2e-6084-4508-a40e-8f36433cfea8',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ChargeRuleRequest(
        condition: new Components\ChargeRuleRequestCondition(
            type: Components\ChargeRuleRequestType::PaymentLink,
            value: '<value>',
        ),
        outcome: Components\ChargeRuleRequestOutcome::Invoice,
    ),
);

$response = $sdk->stripeAutoinvoicing->accounts->chargeRules->publicApiV1StripeAutoinvoicingAccountsChargeRulesCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.create" method="post" path="/companies/{company}/connected-accounts/{account}/charge-rules" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesCreateRequest(
    company: 'fe05fcfb-afc3-442d-9488-95d6667f15ae',
    account: '8d211275-3a8b-4fe8-9fd6-890b9a48c24b',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ChargeRuleRequest(
        condition: new Components\ChargeRuleRequestCondition(
            type: Components\ChargeRuleRequestType::PaymentLink,
            value: '<value>',
        ),
        outcome: Components\ChargeRuleRequestOutcome::Invoice,
    ),
);

$response = $sdk->stripeAutoinvoicing->accounts->chargeRules->publicApiV1StripeAutoinvoicingAccountsChargeRulesCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.create" method="post" path="/companies/{company}/connected-accounts/{account}/charge-rules" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesCreateRequest(
    company: 'd75df265-6887-4b8a-8c37-b29c10276114',
    account: '777f00dd-a96b-47cd-8c4e-5f8188a714e8',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ChargeRuleRequest(
        condition: new Components\ChargeRuleRequestCondition(
            type: Components\ChargeRuleRequestType::PaymentLink,
            value: '<value>',
        ),
        outcome: Components\ChargeRuleRequestOutcome::Invoice,
    ),
);

$response = $sdk->stripeAutoinvoicing->accounts->chargeRules->publicApiV1StripeAutoinvoicingAccountsChargeRulesCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.create" method="post" path="/companies/{company}/connected-accounts/{account}/charge-rules" example="ip_not_allowed" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesCreateRequest(
    company: '24684f5b-9188-4453-a868-a4e0d803adee',
    account: 'f28d89cd-f9c6-41a3-9b6b-ea659e7a074c',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ChargeRuleRequest(
        condition: new Components\ChargeRuleRequestCondition(
            type: Components\ChargeRuleRequestType::PaymentLink,
            value: '<value>',
        ),
        outcome: Components\ChargeRuleRequestOutcome::Invoice,
    ),
);

$response = $sdk->stripeAutoinvoicing->accounts->chargeRules->publicApiV1StripeAutoinvoicingAccountsChargeRulesCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.create" method="post" path="/companies/{company}/connected-accounts/{account}/charge-rules" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesCreateRequest(
    company: 'db9e494d-2e21-49a6-9a32-fae2f0f9c35f',
    account: '89f586ff-f3b6-4c33-8c1c-d40878910f9a',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ChargeRuleRequest(
        condition: new Components\ChargeRuleRequestCondition(
            type: Components\ChargeRuleRequestType::PaymentLink,
            value: '<value>',
        ),
        outcome: Components\ChargeRuleRequestOutcome::Invoice,
    ),
);

$response = $sdk->stripeAutoinvoicing->accounts->chargeRules->publicApiV1StripeAutoinvoicingAccountsChargeRulesCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.create" method="post" path="/companies/{company}/connected-accounts/{account}/charge-rules" example="success" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesCreateRequest(
    company: '9c1f0b5e-15fa-4902-85a1-c890badf4640',
    account: 'a2c8893a-656d-4ea2-a522-bc41b4db4efd',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ChargeRuleRequest(
        condition: new Components\ChargeRuleRequestCondition(
            type: Components\ChargeRuleRequestType::PaymentLink,
            value: '<value>',
        ),
        outcome: Components\ChargeRuleRequestOutcome::Invoice,
    ),
);

$response = $sdk->stripeAutoinvoicing->accounts->chargeRules->publicApiV1StripeAutoinvoicingAccountsChargeRulesCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                              | Type                                                                                                                                                                   | Required                                                                                                                                                               | Description                                                                                                                                                            |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                                             | [Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesCreateRequest](../../Models/Operations/PublicApiV1StripeAutoinvoicingAccountsChargeRulesCreateRequest.md) | :heavy_check_mark:                                                                                                                                                     | The request object to use for the request.                                                                                                                             |

### Response

**[?Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesCreateResponse](../../Models/Operations/PublicApiV1StripeAutoinvoicingAccountsChargeRulesCreateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1StripeAutoinvoicingAccountsChargeRulesList

List the charge treatment rules of a connected Stripe account, ordered by `position` (the first matching rule decides how a charge is treated). Each rule exposes its `id` (UUID v7), `condition` (`type`: `metadata_equals`, `description_contains` or `payment_link`, with `key` and `value`), `outcome` (`invoice` or `do_not_invoice`), `concept`, `vat_rate` and `non_invoice_reason`. Returns 404 if the account does not exist or belongs to another company.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.list" method="get" path="/companies/{company}/connected-accounts/{account}/charge-rules" example="success" -->
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



$response = $sdk->stripeAutoinvoicing->accounts->chargeRules->publicApiV1StripeAutoinvoicingAccountsChargeRulesList(
    company: '2b8817d0-b308-4920-8405-4dc8d4f5bea6',
    account: 'bf256ac5-aac8-493e-b801-bb388b5ca3f2',
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
| `account`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the connected payment account.                                                                                                                                                                                                                                                                                                                |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesListResponse](../../Models/Operations/PublicApiV1StripeAutoinvoicingAccountsChargeRulesListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1StripeAutoinvoicingAccountsChargeRulesDelete

Delete a charge treatment rule. Charges already excluded by it keep their record in the non-invoiced charges list. Responds 204 with no body. Returns 404 `charge_rule_not_found` if the rule does not exist in that account and company.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.delete" method="delete" path="/companies/{company}/connected-accounts/{account}/charge-rules/{rule}" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesDeleteRequest(
    company: '223edd82-ddbe-494a-bbf3-9e1eaee0502c',
    account: 'c8df2d73-723d-43e2-8f09-7c836d7d9169',
    rule: '4fa8b754-f4bb-4ab2-b0b6-2b74e44e9bc4',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->stripeAutoinvoicing->accounts->chargeRules->publicApiV1StripeAutoinvoicingAccountsChargeRulesDelete(
    request: $request
);

if ($response->statusCode === 200) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                              | Type                                                                                                                                                                   | Required                                                                                                                                                               | Description                                                                                                                                                            |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                                             | [Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesDeleteRequest](../../Models/Operations/PublicApiV1StripeAutoinvoicingAccountsChargeRulesDeleteRequest.md) | :heavy_check_mark:                                                                                                                                                     | The request object to use for the request.                                                                                                                             |

### Response

**[?Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesDeleteResponse](../../Models/Operations/PublicApiV1StripeAutoinvoicingAccountsChargeRulesDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1StripeAutoinvoicingAccountsChargeRulesUpdate

Replace a charge treatment rule: `condition`, `outcome`, `concept`, `vat_rate` and `non_invoice_reason` are all sent again; its `position` does not change (use the reorder operation). Returns 404 `charge_rule_not_found` if the rule does not exist in that account and company, and 422 `charge_rule_invalid` when a field breaks the contract.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.update" method="put" path="/companies/{company}/connected-accounts/{account}/charge-rules/{rule}" example="api_key_expired" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesUpdateRequest(
    company: '8b1540f4-82f8-4d0e-8a93-44432b08cf67',
    account: '4d43c288-c680-498a-a9ee-3c5aeccbf40c',
    rule: '39eea41a-ec10-44d2-8c8b-f2a06a02c1bb',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ChargeRuleRequest(
        condition: new Components\ChargeRuleRequestCondition(
            type: Components\ChargeRuleRequestType::MetadataEquals,
            value: '<value>',
        ),
        outcome: Components\ChargeRuleRequestOutcome::Invoice,
    ),
);

$response = $sdk->stripeAutoinvoicing->accounts->chargeRules->publicApiV1StripeAutoinvoicingAccountsChargeRulesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.update" method="put" path="/companies/{company}/connected-accounts/{account}/charge-rules/{rule}" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesUpdateRequest(
    company: 'ef487a60-51ec-40c5-b653-3a3deac7981a',
    account: '4cedb186-ebaa-4866-8d4b-3130fc10ceb0',
    rule: '17d6475a-f709-4a03-b7c6-e470c18cf704',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ChargeRuleRequest(
        condition: new Components\ChargeRuleRequestCondition(
            type: Components\ChargeRuleRequestType::MetadataEquals,
            value: '<value>',
        ),
        outcome: Components\ChargeRuleRequestOutcome::Invoice,
    ),
);

$response = $sdk->stripeAutoinvoicing->accounts->chargeRules->publicApiV1StripeAutoinvoicingAccountsChargeRulesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.update" method="put" path="/companies/{company}/connected-accounts/{account}/charge-rules/{rule}" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesUpdateRequest(
    company: 'ce7abe83-03e4-4210-b8f7-aa339699312d',
    account: '11a7a349-c807-4b61-98cb-3ee6a1e33728',
    rule: '9da97539-18d7-4997-bd38-e440dc986b7b',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ChargeRuleRequest(
        condition: new Components\ChargeRuleRequestCondition(
            type: Components\ChargeRuleRequestType::MetadataEquals,
            value: '<value>',
        ),
        outcome: Components\ChargeRuleRequestOutcome::Invoice,
    ),
);

$response = $sdk->stripeAutoinvoicing->accounts->chargeRules->publicApiV1StripeAutoinvoicingAccountsChargeRulesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.update" method="put" path="/companies/{company}/connected-accounts/{account}/charge-rules/{rule}" example="ip_not_allowed" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesUpdateRequest(
    company: 'f8ea65e1-dbe9-4366-bf09-b5daeb72322e',
    account: '3d18578a-001c-4a9b-9792-fe3aae281474',
    rule: '40f7ec24-8b79-477b-a029-45902ed583a0',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ChargeRuleRequest(
        condition: new Components\ChargeRuleRequestCondition(
            type: Components\ChargeRuleRequestType::MetadataEquals,
            value: '<value>',
        ),
        outcome: Components\ChargeRuleRequestOutcome::Invoice,
    ),
);

$response = $sdk->stripeAutoinvoicing->accounts->chargeRules->publicApiV1StripeAutoinvoicingAccountsChargeRulesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.update" method="put" path="/companies/{company}/connected-accounts/{account}/charge-rules/{rule}" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesUpdateRequest(
    company: '8480e0dd-c116-4b60-a921-0690508214e1',
    account: '57fc84ea-6d45-4b3e-a8ea-9a124b69506e',
    rule: '3c1e9d42-0185-4eca-8ff0-bef2d06f14bc',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ChargeRuleRequest(
        condition: new Components\ChargeRuleRequestCondition(
            type: Components\ChargeRuleRequestType::MetadataEquals,
            value: '<value>',
        ),
        outcome: Components\ChargeRuleRequestOutcome::Invoice,
    ),
);

$response = $sdk->stripeAutoinvoicing->accounts->chargeRules->publicApiV1StripeAutoinvoicingAccountsChargeRulesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.update" method="put" path="/companies/{company}/connected-accounts/{account}/charge-rules/{rule}" example="success" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesUpdateRequest(
    company: '27517b7c-e432-4642-8788-238c097d32fc',
    account: 'd850051a-b5ba-4445-b187-e91518aa76ad',
    rule: '6048bee4-57bb-49ee-9763-7eeabb47d561',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ChargeRuleRequest(
        condition: new Components\ChargeRuleRequestCondition(
            type: Components\ChargeRuleRequestType::MetadataEquals,
            value: '<value>',
        ),
        outcome: Components\ChargeRuleRequestOutcome::Invoice,
    ),
);

$response = $sdk->stripeAutoinvoicing->accounts->chargeRules->publicApiV1StripeAutoinvoicingAccountsChargeRulesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                              | Type                                                                                                                                                                   | Required                                                                                                                                                               | Description                                                                                                                                                            |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                                             | [Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesUpdateRequest](../../Models/Operations/PublicApiV1StripeAutoinvoicingAccountsChargeRulesUpdateRequest.md) | :heavy_check_mark:                                                                                                                                                     | The request object to use for the request.                                                                                                                             |

### Response

**[?Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesUpdateResponse](../../Models/Operations/PublicApiV1StripeAutoinvoicingAccountsChargeRulesUpdateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1StripeAutoinvoicingAccountsChargeRulesReorder

Reorder the rules of a connected Stripe account by sending every rule `id` in `rule_ids` in the new order (the first one is evaluated first). The list must contain exactly the account's rules, with no repeats: otherwise it fails with 422 `charge_rule_invalid` and `param` `rule_ids`. Returns the rules in their new order.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.reorder" method="put" path="/companies/{company}/connected-accounts/{account}/charge-rules/order" example="api_key_expired" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesReorderRequest(
    company: '042ee16e-168d-4407-a106-45584d5867f8',
    account: '46812f9b-d73b-4709-a875-03eab3f432c9',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReorderChargeRulesRequest(
        ruleIds: [
            '0a3f6d58-1c3c-4a20-b328-7ac1ed3106de',
        ],
    ),
);

$response = $sdk->stripeAutoinvoicing->accounts->chargeRules->publicApiV1StripeAutoinvoicingAccountsChargeRulesReorder(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.reorder" method="put" path="/companies/{company}/connected-accounts/{account}/charge-rules/order" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesReorderRequest(
    company: '40fe1c84-9fea-49f3-ba27-20e914335ffb',
    account: 'dcebc0ac-5f57-4022-b2c7-1d5a4b0b01dc',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReorderChargeRulesRequest(
        ruleIds: [
            '0a3f6d58-1c3c-4a20-b328-7ac1ed3106de',
        ],
    ),
);

$response = $sdk->stripeAutoinvoicing->accounts->chargeRules->publicApiV1StripeAutoinvoicingAccountsChargeRulesReorder(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.reorder" method="put" path="/companies/{company}/connected-accounts/{account}/charge-rules/order" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesReorderRequest(
    company: 'fd0377d3-0927-4b92-8c46-6b9cca8fafab',
    account: '70e8cb70-b112-4980-93d4-5f04c1bbce58',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReorderChargeRulesRequest(
        ruleIds: [
            '0a3f6d58-1c3c-4a20-b328-7ac1ed3106de',
        ],
    ),
);

$response = $sdk->stripeAutoinvoicing->accounts->chargeRules->publicApiV1StripeAutoinvoicingAccountsChargeRulesReorder(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.reorder" method="put" path="/companies/{company}/connected-accounts/{account}/charge-rules/order" example="ip_not_allowed" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesReorderRequest(
    company: 'f802d32d-6c07-4d99-9857-4ba5c8a3a6f7',
    account: 'f9602d00-d697-44cc-bd81-ad28696725de',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReorderChargeRulesRequest(
        ruleIds: [
            '0a3f6d58-1c3c-4a20-b328-7ac1ed3106de',
        ],
    ),
);

$response = $sdk->stripeAutoinvoicing->accounts->chargeRules->publicApiV1StripeAutoinvoicingAccountsChargeRulesReorder(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.reorder" method="put" path="/companies/{company}/connected-accounts/{account}/charge-rules/order" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesReorderRequest(
    company: '7aa501d3-852b-467d-afbf-5b9bddaff979',
    account: 'd736aece-9216-47b6-bc33-5ac99366ddd0',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReorderChargeRulesRequest(
        ruleIds: [
            '0a3f6d58-1c3c-4a20-b328-7ac1ed3106de',
        ],
    ),
);

$response = $sdk->stripeAutoinvoicing->accounts->chargeRules->publicApiV1StripeAutoinvoicingAccountsChargeRulesReorder(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.reorder" method="put" path="/companies/{company}/connected-accounts/{account}/charge-rules/order" example="success" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesReorderRequest(
    company: 'f4a2f88f-7055-485d-baeb-f367a943ae3d',
    account: '7ab1dc2b-ff38-4b94-82af-be2f4206e5cc',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReorderChargeRulesRequest(
        ruleIds: [
            '0a3f6d58-1c3c-4a20-b328-7ac1ed3106de',
        ],
    ),
);

$response = $sdk->stripeAutoinvoicing->accounts->chargeRules->publicApiV1StripeAutoinvoicingAccountsChargeRulesReorder(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                | Type                                                                                                                                                                     | Required                                                                                                                                                                 | Description                                                                                                                                                              |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                                                               | [Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesReorderRequest](../../Models/Operations/PublicApiV1StripeAutoinvoicingAccountsChargeRulesReorderRequest.md) | :heavy_check_mark:                                                                                                                                                       | The request object to use for the request.                                                                                                                               |

### Response

**[?Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesReorderResponse](../../Models/Operations/PublicApiV1StripeAutoinvoicingAccountsChargeRulesReorderResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |