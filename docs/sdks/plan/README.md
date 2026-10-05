# ManagedAccounts.Plan

## Overview

### Available Operations

* [publicApiV1ManagedAccountsPlanUpdate](#publicapiv1managedaccountsplanupdate) - Change the plan of a managed account
* [publicApiV1ManagedAccountsPlanPreview](#publicapiv1managedaccountsplanpreview) - Preview a managed account plan change

## publicApiV1ManagedAccountsPlanUpdate

Change the plan of a managed account your account pays, also after the holder claimed it: an upgrade takes effect at once and is charged immediately in one update of your subscription; a downgrade stays pending until the end of the period (`pending_plan`). Preview the amounts first with the preview operation. Returns 200 with the managed account. Returns 422 `managed_account_plan_not_self_serve` for `enterprise` (only assigned by Factuarea by contract) and 422 `idempotency_key_required` because changing the plan is irreversible. It requires your `access_level` on the managed account to be `operate`: with `view` or `none` it returns 403 `forbidden_action` without changing anything (raise the level first). The account axis admits only a managed account provisioned by the credential account, with the management in force and in the same environment as the key; your own account, a foreign or non-existent one, one of the other environment and one whose management ended all return the same 404 `account_not_found`. A managed account whose holder took over the payment also returns 404 `account_not_found`. Requires the privileged `accounts:write` scope, which `*` does not cover: without the account provider privilege granted by Factuarea, or when the person who issued the key is no longer an owner or admin of your account, it returns 403 `privileged_scope_required`.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.managed_accounts.plan.update" method="patch" path="/accounts/{account}/plan" example="factuarea_version_invalid_format" -->
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

$body = new Components\ChangeManagedAccountPlanV1Request(
    plan: Components\ChangeManagedAccountPlanV1RequestPlan::Emprendedor,
);

$response = $sdk->managedAccounts->plan->publicApiV1ManagedAccountsPlanUpdate(
    account: '72461382',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.managed_accounts.plan.update" method="patch" path="/accounts/{account}/plan" example="retired_active_profile_header" -->
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

$body = new Components\ChangeManagedAccountPlanV1Request(
    plan: Components\ChangeManagedAccountPlanV1RequestPlan::Emprendedor,
);

$response = $sdk->managedAccounts->plan->publicApiV1ManagedAccountsPlanUpdate(
    account: '30476289',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.managed_accounts.plan.update" method="patch" path="/accounts/{account}/plan" example="unsupported_api_version" -->
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

$body = new Components\ChangeManagedAccountPlanV1Request(
    plan: Components\ChangeManagedAccountPlanV1RequestPlan::Emprendedor,
);

$response = $sdk->managedAccounts->plan->publicApiV1ManagedAccountsPlanUpdate(
    account: '30594719',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `account`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Public identifier (UUID v7) of the managed account provisioned by your account (its `id` in `GET /v1/accounts`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | [Components\ChangeManagedAccountPlanV1Request](../../Models/Components/ChangeManagedAccountPlanV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                      | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |

### Response

**[?Operations\PublicApiV1ManagedAccountsPlanUpdateResponse](../../Models/Operations/PublicApiV1ManagedAccountsPlanUpdateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ManagedAccountsPlanPreview

Preview, with no effect, changing the plan of a managed account your account pays to the `plan` in the query (`emprendedor` or `empresario`): `effective` is `immediate` for an upgrade (charged at once, `prorated_amount_cents`) and `period_end` for a downgrade, which stays pending until `effective_at`; `next_invoice_amount_cents` is the monthly fee of the requested plan on the next periodic invoice. Amounts are integer cents, taxable base without VAT. A managed account that is not charged to your account (a test one, or one blocked by its payer) has no real charge: both amounts are 0 and the change is immediate. Returns 422 `managed_account_plan_not_self_serve` for `enterprise`. Unlike retrieving the managed account, it requires your `access_level` on it to be `operate`: with `view` or `none` it returns 403 `forbidden_action` (raise the level first). The account axis admits only a managed account provisioned by the credential account, with the management in force and in the same environment as the key; your own account, a foreign or non-existent one, one of the other environment and one whose management ended all return the same 404 `account_not_found`. A managed account whose holder took over the payment also returns 404 `account_not_found`. Requires the privileged `accounts:read` scope, which `*` does not cover (403 `privileged_scope_required` otherwise).

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.managed_accounts.plan.preview" method="get" path="/accounts/{account}/plan/preview" -->
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



$response = $sdk->managedAccounts->plan->publicApiV1ManagedAccountsPlanPreview(
    account: '78553310',
    plan: Operations\Plan::Emprendedor,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `account`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the managed account provisioned by your account (its `id` in `GET /v1/accounts`).                                                                                                                                                                                                                                                             |                                                                                                                                                                                                                                                                                                                                                                              |
| `plan`                                                                                                                                                                                                                                                                                                                                                                       | [Operations\Plan](../../Models/Operations/Plan.md)                                                                                                                                                                                                                                                                                                                           | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Requested plan: `emprendedor` or `empresario` (`enterprise` returns 422 `managed_account_plan_not_self_serve`).                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1ManagedAccountsPlanPreviewResponse](../../Models/Operations/PublicApiV1ManagedAccountsPlanPreviewResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |