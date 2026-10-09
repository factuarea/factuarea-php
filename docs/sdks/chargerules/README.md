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

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.create" method="post" path="/connected-accounts/{account}/charge-rules" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesCreateRequest(
    account: '89232006',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
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

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.create" method="post" path="/connected-accounts/{account}/charge-rules" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesCreateRequest(
    account: '61756076',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
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

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.create" method="post" path="/connected-accounts/{account}/charge-rules" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesCreateRequest(
    account: '03966013',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
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

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.create" method="post" path="/connected-accounts/{account}/charge-rules" example="success" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesCreateRequest(
    account: '57090739',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
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
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1StripeAutoinvoicingAccountsChargeRulesList

List the charge treatment rules of a connected Stripe account, ordered by `position` (the first matching rule decides how a charge is treated). Each rule exposes its `id` (UUID v7), `condition` (`type`: `metadata_equals`, `description_contains` or `payment_link`, with `key` and `value`), `outcome` (`invoice` or `do_not_invoice`), `concept`, `vat_rate` and `non_invoice_reason`. Returns 404 if the account does not exist or belongs to another company.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.list" method="get" path="/connected-accounts/{account}/charge-rules" example="success" -->
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



$response = $sdk->stripeAutoinvoicing->accounts->chargeRules->publicApiV1StripeAutoinvoicingAccountsChargeRulesList(
    account: '17550480',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                        | Type                                                                                                                                                                                                                                                                                                                                                                                             | Required                                                                                                                                                                                                                                                                                                                                                                                         | Description                                                                                                                                                                                                                                                                                                                                                                                      | Example                                                                                                                                                                                                                                                                                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `account`                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesListResponse](../../Models/Operations/PublicApiV1StripeAutoinvoicingAccountsChargeRulesListResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1StripeAutoinvoicingAccountsChargeRulesDelete

Delete a charge treatment rule. Charges already excluded by it keep their record in the non-invoiced charges list. Responds 204 with no body. Returns 404 `charge_rule_not_found` if the rule does not exist in that account and company.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.delete" method="delete" path="/connected-accounts/{account}/charge-rules/{rule}" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesDeleteRequest(
    account: '11298851',
    rule: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
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

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 409, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1StripeAutoinvoicingAccountsChargeRulesUpdate

Replace a charge treatment rule: `condition`, `outcome`, `concept`, `vat_rate` and `non_invoice_reason` are all sent again; its `position` does not change (use the reorder operation). Returns 404 `charge_rule_not_found` if the rule does not exist in that account and company, and 422 `charge_rule_invalid` when a field breaks the contract.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.update" method="put" path="/connected-accounts/{account}/charge-rules/{rule}" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesUpdateRequest(
    account: '48119718',
    rule: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
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

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.update" method="put" path="/connected-accounts/{account}/charge-rules/{rule}" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesUpdateRequest(
    account: '55125705',
    rule: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
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

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.update" method="put" path="/connected-accounts/{account}/charge-rules/{rule}" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesUpdateRequest(
    account: '82213314',
    rule: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
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

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.update" method="put" path="/connected-accounts/{account}/charge-rules/{rule}" example="success" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesUpdateRequest(
    account: '14304747',
    rule: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
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
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1StripeAutoinvoicingAccountsChargeRulesReorder

Reorder the rules of a connected Stripe account by sending every rule `id` in `rule_ids` in the new order (the first one is evaluated first). The list must contain exactly the account's rules, with no repeats: otherwise it fails with 422 `charge_rule_invalid` and `param` `rule_ids`. Returns the rules in their new order.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.reorder" method="put" path="/connected-accounts/{account}/charge-rules/order" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesReorderRequest(
    account: '22682894',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
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

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.reorder" method="put" path="/connected-accounts/{account}/charge-rules/order" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesReorderRequest(
    account: '87914465',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
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

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.reorder" method="put" path="/connected-accounts/{account}/charge-rules/order" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesReorderRequest(
    account: '40335386',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
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

<!-- UsageSnippet language="php" operationID="public-api.v1.stripe_autoinvoicing.accounts.charge_rules.reorder" method="put" path="/connected-accounts/{account}/charge-rules/order" example="success" -->
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

$request = new Operations\PublicApiV1StripeAutoinvoicingAccountsChargeRulesReorderRequest(
    account: '93619559',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
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
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |