# ManagedAccounts

## Overview

### Available Operations

* [publicApiV1ManagedAccountsList](#publicapiv1managedaccountslist) - List managed accounts
* [publicApiV1ManagedAccountsCreate](#publicapiv1managedaccountscreate) - Provision a managed account
* [publicApiV1ManagedAccountsShow](#publicapiv1managedaccountsshow) - Retrieve a managed account

## publicApiV1ManagedAccountsList

List the accounts your account provisioned, ONLY in the environment of the key: a `fact_test_` key lists your test managed accounts and a live key the live ones. Without `status` it lists only the managements in force. Filter by `status` (`provisioned`, `claimed` or `active` filter by the status of the account; `ended` returns the ones whose management ended, each with ONLY its `id`, your `external_ref` and the `management` block: your account no longer sees the current name, status, plan or tax IDs of an account it stopped managing) and by `external_ref` (zero or one result), cursor-paginated with `limit` (1-100, default 25) and `starting_after`. No item carries a claim voucher. Requires the privileged `accounts:read` scope, which `*` does not cover (403 `privileged_scope_required` otherwise). Returns 422 for a `status` outside the vocabulary or an `external_ref` longer than 128 characters, and 400 for an unknown query parameter or a malformed `limit`/`starting_after`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.managed_accounts.list" method="get" path="/accounts" -->
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

$request = new Operations\PublicApiV1ManagedAccountsListRequest(
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->managedAccounts->publicApiV1ManagedAccountsList(
    request: $request
);

if ($response->managedAccountList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                            | Type                                                                                                                 | Required                                                                                                             | Description                                                                                                          |
| -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                           | [Operations\PublicApiV1ManagedAccountsListRequest](../../Models/Operations/PublicApiV1ManagedAccountsListRequest.md) | :heavy_check_mark:                                                                                                   | The request object to use for the request.                                                                           |

### Response

**[?Operations\PublicApiV1ManagedAccountsListResponse](../../Models/Operations/PublicApiV1ManagedAccountsListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1ManagedAccountsCreate

Provision a SEPARATE account for one of your clients, with its tax ID (`tax_id`, `legal_name`), its plan (`emprendedor` or `empresario`) and the `access_level` your account keeps on it (`none` by default, `view` or `operate`). Your account pays it: in live mode the account is charged on the `managed-accounts` subscription of your account. `external_ref` is YOUR reference and makes the call idempotent by itself: provisioning an `external_ref` you already provisioned returns 200 with that same account, creates nothing and issues no claim voucher (use the claim-token operation to issue a new one). A new account returns **201 Created** with the claim voucher the holder uses to take the account: with `send_email: false` (the default) `claim_token` and `claim_url` are returned EXACTLY ONCE, for you to deliver; with `send_email: true` the link travels only in the email to `owner_email` and both fields are `null`. An idempotent replay returns them as `null` too: the voucher is never stored in the clear, not even in the idempotency cache. `owner_email` is required with a live key and whenever `send_email` is `true`. The environment is the one of the key: a `fact_test_` key creates a TEST account, with no charge, no voucher (`claim_token` and `claim_url` are `null`) and outside your managed account limit. Requires the privileged `accounts:write` scope, which `*` does not cover: without the account provider privilege granted by Factuarea, or when the person who issued the key is no longer an owner or admin of your account, it returns 403 `privileged_scope_required`. Returns 402 `payment_method_required` when your account has no payment method to pay a live managed account, 409 `provisioning_account_claimed` when the `external_ref` names an account its holder already claimed, 409 `managed_account_external_ref_in_other_environment` when it names a managed account of the other environment, 422 `managed_account_plan_not_self_serve` for `enterprise` (it is only assigned by Factuarea by contract), 422 `managed_account_provider_in_trial` for a paid plan while your account is in trial (only test accounts), 422 `managed_accounts_limit_reached` at the managed account limit of your account, and 422 `idempotency_key_required` because provisioning is irreversible.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.managed_accounts.create" method="post" path="/accounts" example="factuarea_version_invalid_format" -->
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

$body = new Components\ProvisionManagedAccountV1Request(
    externalRef: '<value>',
    plan: Components\ProvisionManagedAccountV1RequestPlan::Enterprise,
    taxId: '<id>',
    legalName: '<value>',
);

$response = $sdk->managedAccounts->publicApiV1ManagedAccountsCreate(
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.managed_accounts.create" method="post" path="/accounts" example="retired_active_profile_header" -->
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

$body = new Components\ProvisionManagedAccountV1Request(
    externalRef: '<value>',
    plan: Components\ProvisionManagedAccountV1RequestPlan::Enterprise,
    taxId: '<id>',
    legalName: '<value>',
);

$response = $sdk->managedAccounts->publicApiV1ManagedAccountsCreate(
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.managed_accounts.create" method="post" path="/accounts" example="unsupported_api_version" -->
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

$body = new Components\ProvisionManagedAccountV1Request(
    externalRef: '<value>',
    plan: Components\ProvisionManagedAccountV1RequestPlan::Enterprise,
    taxId: '<id>',
    legalName: '<value>',
);

$response = $sdk->managedAccounts->publicApiV1ManagedAccountsCreate(
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
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | [Components\ProvisionManagedAccountV1Request](../../Models/Components/ProvisionManagedAccountV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                      | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |

### Response

**[?Operations\PublicApiV1ManagedAccountsCreateResponse](../../Models/Operations/PublicApiV1ManagedAccountsCreateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 402, 403, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1ManagedAccountsShow

Retrieve one of your managed accounts by its `id` (UUID v7): status of the account, environment, plan and pending plan, your `access_level`, its tax IDs, whether its holder already claimed it and the status of the management. It works with any `access_level`, `none` included: provisioning operations only require the management in force. The account axis admits only a managed account provisioned by the credential account, with the management in force and in the same environment as the key; your own account, a foreign or non-existent one, one of the other environment and one whose management ended all return the same 404 `account_not_found`. Requires the privileged `accounts:read` scope, which `*` does not cover (403 `privileged_scope_required` otherwise).

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.managed_accounts.show" method="get" path="/accounts/{account}" -->
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



$response = $sdk->managedAccounts->publicApiV1ManagedAccountsShow(
    account: '21938776',
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
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1ManagedAccountsShowResponse](../../Models/Operations/PublicApiV1ManagedAccountsShowResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |