# ManagedAccounts.Imports

## Overview

### Available Operations

* [publicApiV1ManagedAccountsImportsCreate](#publicapiv1managedaccountsimportscreate) - Apply a bulk import of managed accounts
* [publicApiV1ManagedAccountsImportsTemplate](#publicapiv1managedaccountsimportstemplate) - Download the bulk import template
* [publicApiV1ManagedAccountsImportsPreview](#publicapiv1managedaccountsimportspreview) - Preview a bulk import of managed accounts

## publicApiV1ManagedAccountsImportsCreate

Apply a bulk import of managed accounts and own tax IDs from a file (`multipart/form-data`: `accounts_file`, optional `customers_file` and `options`) and receive **201 Created** with the result of the execution. The import is DECLARATIVE and NOT atomic: every applicable row (`VALID`, `WARNING`, `ALREADY_EXISTS`) is applied on its own, and a row that fails is reported as `BLOCKED` or `ERROR` without undoing the others. A new `managed_account` row provisions a separate account charged on the `managed-accounts` subscription of your account, with the `access_level` of the row (`operate` by default); a new `own_nif` row registers an additional tax ID charged to your account. It never opens a checkout: it charges your existing subscription, and a row that cannot be charged comes out `BLOCKED` with its reason. Re-applying the same file reconciles: existing accounts and tax IDs come out `ALREADY_EXISTS`, their visible name, language and fiscal profile are updated while the account is not claimed, and nothing is duplicated nor charged twice; a different plan for an existing account is only applied with `options.confirm_plan_changes` (otherwise the row is `WARNING`). Every new live `managed_account` row returns its `claim_token` and `claim_url` EXACTLY ONCE, in this response only; with `options.send_email: true` the link is emailed to the holder instead and both are `null`. An idempotent replay returns the same result with every voucher as `null`: vouchers are never stored in the clear, not even in the idempotency cache. The customers of `customers_file` are imported into the tax ID of their row with the existing contact importer, only where your account can `operate` it (`customers_status`), and with `options.apply_customers_to_own_company` the ones without `account_ref` go to your own company in focus (`own_company_customers`). The environment is the one of the key: with a `fact_test_` key the `managed_account` rows create or reconcile TEST accounts, with no charge and no voucher, and the `own_nif` rows are `ERROR` `live_credential_required`. Imports of your account are applied one at a time. No `Location` header is returned: an execution has no public read operation. Limits: 5 MB per file, 100 rows in `accounts_file` and 1,000 in `customers_file`, checked before reading any row. Requires the privileged `accounts:write` scope, which `*` does not cover. Returns 403 `privileged_scope_required` without the account provider privilege granted by Factuarea or when the person who issued the key is no longer an owner or admin of your account, 409 `import_in_progress` while another import of your account is running or while the platform is already applying the maximum number of simultaneous imports (repeating the same file later is safe), 413 `import_file_too_large` for a file over 5 MB, 422 `import_row_limit_exceeded` for too many rows, 422 `import_file_invalid` for an unreadable file or a missing column, 422 on an unknown option, 422 `idempotency_key_required` because applying is irreversible, and 429 above 5 applications per minute or once the daily import quota of your account is exhausted.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.managed_accounts.imports.create" method="post" path="/accounts/imports" example="api_key_expired" -->
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

$body = new Components\ManagedAccountImportV1Request(
    accountsFile: new Components\AccountsFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
);

$response = $sdk->managedAccounts->imports->publicApiV1ManagedAccountsImportsCreate(
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.managed_accounts.imports.create" method="post" path="/accounts/imports" example="api_key_revoked" -->
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

$body = new Components\ManagedAccountImportV1Request(
    accountsFile: new Components\AccountsFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
);

$response = $sdk->managedAccounts->imports->publicApiV1ManagedAccountsImportsCreate(
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.managed_accounts.imports.create" method="post" path="/accounts/imports" example="invalid_api_key" -->
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

$body = new Components\ManagedAccountImportV1Request(
    accountsFile: new Components\AccountsFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
);

$response = $sdk->managedAccounts->imports->publicApiV1ManagedAccountsImportsCreate(
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.managed_accounts.imports.create" method="post" path="/accounts/imports" example="ip_not_allowed" -->
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

$body = new Components\ManagedAccountImportV1Request(
    accountsFile: new Components\AccountsFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
);

$response = $sdk->managedAccounts->imports->publicApiV1ManagedAccountsImportsCreate(
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.managed_accounts.imports.create" method="post" path="/accounts/imports" example="missing_api_key" -->
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

$body = new Components\ManagedAccountImportV1Request(
    accountsFile: new Components\AccountsFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
);

$response = $sdk->managedAccounts->imports->publicApiV1ManagedAccountsImportsCreate(
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Components\ManagedAccountImportV1Request](../../Models/Components/ManagedAccountImportV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1ManagedAccountsImportsCreateResponse](../../Models/Operations/PublicApiV1ManagedAccountsImportsCreateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 409, 413, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1ManagedAccountsImportsTemplate

Download the CSV template of the bulk import of managed accounts: `text/csv` in UTF-8 with a BOM, separated by semicolons, with the closed set of columns (`mode`, `external_ref`, `plan`, `tax_id`, `legal_name`, `address`, `postal_code`, `city`, `province`, `country_code`, `owner_email`, `display_name`, `language`, `access_level`) and an example row marked `# ejemplo`, which the import ignores. `mode` is required on every row (`own_nif` or `managed_account`); a `managed_account` row requires `external_ref`, and `plan` is only allowed in `managed_account` rows (an `own_nif` row inherits the plan of your account). The import also reads comma- and tab-separated files and skips up to 20 lines of preamble before the header row. The template is the same for every provider and reads no data of your account. Requires the privileged `accounts:read` scope, which `*` does not cover: 403 `privileged_scope_required` without the account provider privilege granted by Factuarea.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.managed_accounts.imports.template" method="get" path="/accounts/imports/template" example="template" -->
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



$response = $sdk->managedAccounts->imports->publicApiV1ManagedAccountsImportsTemplate(
    factuareaVersion: LocalDate::parse('2026-06-01')
);

if ($response->res !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1ManagedAccountsImportsTemplateResponse](../../Models/Operations/PublicApiV1ManagedAccountsImportsTemplateResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 400, 401, 403, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1ManagedAccountsImportsPreview

Preview, with NO effect, a bulk import of managed accounts and own tax IDs from a file: upload the same `multipart/form-data` body you would apply (`accounts_file`, optional `customers_file` and `options`) and receive every row with its `status`, its column `errors`, its `plan` and its monthly and prorated amounts, the totals by status and the `cost` by mode (`managed_account`, `own_nif` and `total`). Nothing is written, no account is provisioned and nothing is charged: it is a query sent by POST because it uploads files, so it needs no `Idempotency-Key`. To price the rows it only QUERIES Stripe (the end of the billing period and, for every confirmed plan change, the prorated amount of that change), so it has its own per-route rate limit. Each row declares its `mode`: `managed_account` provisions a SEPARATE account for a client, identified by your `external_ref`, with its `plan` (`emprendedor`, `empresario` or `integral`) and the `access_level` your account keeps on it (`operate` by default); `own_nif` registers the tax ID as an additional tax ID of YOUR account, with the plan of your account. Row statuses: `VALID` and `WARNING` rows would be applied, `ALREADY_EXISTS` rows would be reconciled (a new import of the same file never duplicates nor charges twice), `BLOCKED` rows break a business rule (reason in `code`: plan not self-serve, trial, managed account limit, billing not current, no payment method, tax ID held by another account, additional tax IDs not in your plan or at their limit, `external_ref` in the other environment or no longer managed) and `ERROR` rows are wrong (column `errors`). The environment is the one of the key: with a `fact_test_` key the `managed_account` rows would create TEST accounts, with no charge and no voucher, and the `own_nif` rows are `ERROR` `live_credential_required`. `own_nif` rows also require `companies:write` on the key (`ERROR` `insufficient_scope` otherwise) and a key that reaches the titular company of your account, which pays them (`BLOCKED` `forbidden_action` otherwise). The prorated total announced here is what applying the same files charges: new accounts and tax IDs, and the confirmed plan upgrades of existing accounts (`options.confirm_plan_changes`), priced with the same preview as a single plan change (a downgrade takes effect at the end of the period and adds 0 now). Limits: 5 MB per file, 100 rows in `accounts_file` and 1,000 in `customers_file`. Requires the privileged `accounts:write` scope, which `*` does not cover. Returns 403 `privileged_scope_required` without the account provider privilege granted by Factuarea or when the person who issued the key is no longer an owner or admin of your account, 413 `import_file_too_large` for a file over 5 MB (`error.param` names the file), 422 `import_row_limit_exceeded` for too many rows, 422 `import_file_invalid` for an unreadable file or a missing column, and 422 on an unknown option.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.managed_accounts.imports.preview" method="post" path="/accounts/imports/preview" example="api_key_expired" -->
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

$body = new Components\ManagedAccountImportV1Request(
    accountsFile: new Components\AccountsFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
);

$response = $sdk->managedAccounts->imports->publicApiV1ManagedAccountsImportsPreview(
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.managed_accounts.imports.preview" method="post" path="/accounts/imports/preview" example="api_key_revoked" -->
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

$body = new Components\ManagedAccountImportV1Request(
    accountsFile: new Components\AccountsFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
);

$response = $sdk->managedAccounts->imports->publicApiV1ManagedAccountsImportsPreview(
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.managed_accounts.imports.preview" method="post" path="/accounts/imports/preview" example="invalid_api_key" -->
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

$body = new Components\ManagedAccountImportV1Request(
    accountsFile: new Components\AccountsFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
);

$response = $sdk->managedAccounts->imports->publicApiV1ManagedAccountsImportsPreview(
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.managed_accounts.imports.preview" method="post" path="/accounts/imports/preview" example="ip_not_allowed" -->
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

$body = new Components\ManagedAccountImportV1Request(
    accountsFile: new Components\AccountsFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
);

$response = $sdk->managedAccounts->imports->publicApiV1ManagedAccountsImportsPreview(
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.managed_accounts.imports.preview" method="post" path="/accounts/imports/preview" example="missing_api_key" -->
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

$body = new Components\ManagedAccountImportV1Request(
    accountsFile: new Components\AccountsFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
);

$response = $sdk->managedAccounts->imports->publicApiV1ManagedAccountsImportsPreview(
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
| `body`                                                                                                                                                                                                                                                                                                                                                                       | [Components\ManagedAccountImportV1Request](../../Models/Components/ManagedAccountImportV1Request.md)                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | N/A                                                                                                                                                                                                                                                                                                                                                                          |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1ManagedAccountsImportsPreviewResponse](../../Models/Operations/PublicApiV1ManagedAccountsImportsPreviewResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 413, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |