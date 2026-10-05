# Account.NifTransfers

## Overview

### Available Operations

* [publicApiV1AccountNifTransfersAccept](#publicapiv1accountniftransfersaccept) - Accept a tax ID transfer
* [publicApiV1AccountNifTransfersCreate](#publicapiv1accountniftransferscreate) - Issue a tax ID transfer
* [publicApiV1AccountNifTransfersList](#publicapiv1accountniftransferslist) - List issued tax ID transfers
* [publicApiV1AccountNifTransfersRevoke](#publicapiv1accountniftransfersrevoke) - Revoke a tax ID transfer

## publicApiV1AccountNifTransfersAccept

Accept a TAX ID TRANSFER by presenting its `secret`: the tax ID the transfer covers moves to the account in the path, which is the credential account. It is an action on the collection and carries no identifier in the path, because whoever accepts does NOT own the transfer and cannot address it by id — all they hold is a secret someone gave them. The body carries the `secret` and nothing else: the tax ID is named by the TRANSFER, not by whoever presents it, so a `company_id` here is rejected rather than silently ignored. Accepting locks the transfer, re-validates that its tax ID still belongs to the issuing account, moves the membership (together with the sandbox company of the tax ID when the issuing account holds it), prunes the tax ID out of the scope of the issuing account credentials, removes from the tax ID the people of the issuing account who do not also belong to the receiving account (closing their web sessions and the OAuth tokens bound to it), grants `owner` of the tax ID to whoever administers the receiving account if they had no access to it, records the trail and notifies the issuing account owner, all inside ONE transaction: a transfer is accepted at most once even under concurrent requests. The 200 publishes the consumed transfer — its public `id`, the tax ID, its expiry and `status: consumed` — and never the issuing account, the consumption instant or the secret. Requires an administrative role in at least one tax ID of the RECEIVING account: 403 `forbidden_action` otherwise. The incoming tax ID becomes an ADDITIONAL tax ID of the receiving account — except for an account registered without a tax ID, where it becomes its titular and is not charged —, so the additional tax ID gate runs BEFORE anything moves, and a rejection leaves the transfer valid and the tax ID in the issuing account: the credential must reach the titular company of the receiving account, which pays it (403 `forbidden_action` otherwise), with an effective access that allows writing (403 `company_read_only` when it is read-only, `view`), the receiving account must be billed by itself (403 `billing_managed_by_provider` otherwise), hold a current plan (402 `additional_nif_plan_required` otherwise) that allows additional tax IDs — `empresario` or `enterprise`, trial included (403 `additional_nif_not_in_plan` otherwise) — and stay within its cap of active additional tax IDs (422 `business_rule_violation` with subcode `additional_nif_limit_reached`). In live mode with a paid subscription the additional tax ID is charged on acceptance: with no payment method on file it returns 402 `payment_method_required` (with `error.details.payment_setup_url`) and a declined charge returns 402 `additional_nif_charge_failed`; if the move fails after charging, the charge is compensated. Returns 422 `nif_transfer_invalid` when the secret matches no transfer, the transfer was already consumed or revoked, it is addressed to a different account, or its tax ID has already changed account (all with the SAME body: telling them apart would say whether that secret ever existed), 422 `nif_transfer_expired` when the transfer is still unconsumed but its window has passed — a DIFFERENT code on purpose, so the holder knows to ask for another one instead of looking for a mistake on their side —, 422 `business_rule_violation` with subcode `account_must_keep_one_nif` when the issuing account would keep no active tax ID, 409 `resource_conflict` when an account registered without a tax ID already holds business data in its empty company, 422 `idempotency_key_required` because moving a tax ID this way is irreversible, and 404 `account_not_found` when `{account}` is not the credential account.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.account.nif_transfers.accept" method="post" path="/accounts/{account}/nif-transfers/accept" example="factuarea_version_invalid_format" -->
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

$body = new Components\AcceptNifTransferV1Request(
    secret: '<value>',
);

$response = $sdk->account->nifTransfers->publicApiV1AccountNifTransfersAccept(
    account: '93154235',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.account.nif_transfers.accept" method="post" path="/accounts/{account}/nif-transfers/accept" example="retired_active_profile_header" -->
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

$body = new Components\AcceptNifTransferV1Request(
    secret: '<value>',
);

$response = $sdk->account->nifTransfers->publicApiV1AccountNifTransfersAccept(
    account: '52446704',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.account.nif_transfers.accept" method="post" path="/accounts/{account}/nif-transfers/accept" example="unsupported_api_version" -->
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

$body = new Components\AcceptNifTransferV1Request(
    secret: '<value>',
);

$response = $sdk->account->nifTransfers->publicApiV1AccountNifTransfersAccept(
    account: '66353528',
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
| `account`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Public identifier (UUID v7) of your account. Get it from `GET /v1/me` (`data.account.id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | [Components\AcceptNifTransferV1Request](../../Models/Components/AcceptNifTransferV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                      | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |

### Response

**[?Operations\PublicApiV1AccountNifTransfersAcceptResponse](../../Models/Operations/PublicApiV1AccountNifTransfersAcceptResponse.md)**

### Errors

| Error Type                             | Status Code                            | Content Type                           |
| -------------------------------------- | -------------------------------------- | -------------------------------------- |
| Errors\Error                           | 400, 401, 402, 403, 404, 409, 422, 429 | application/json                       |
| Errors\Error                           | 500                                    | application/json                       |
| Errors\APIException                    | 4XX, 5XX                               | \*/\*                                  |

## publicApiV1AccountNifTransfersCreate

Issue a TAX ID TRANSFER over ONE tax ID of your account, named explicitly in `company_id`, and receive **201 Created** with the transfer and its plaintext `secret`. This is the only way a tax ID can move from this account to another one, and the initiative is always the giving side: no operation lets an account ask for someone else's tax ID. The secret is returned EXACTLY ONCE and never persisted — only its irreversible fingerprint is stored, so no later read can return it, today or ever; hand it to the receiving account through a channel you trust. An idempotent replay of this call returns the same transfer with `secret: null`: the secret is never stored, not even in the idempotency cache. `recipient_account_id` is OPTIONAL and closes the transfer to a single account: only that account can accept it, and anyone else presenting the secret gets `nif_transfer_invalid`; without it, whoever holds the secret can accept it. The OWNER company of your account cannot be given away. `expires_at` is OPTIONAL: omit it for the product default (24 hours), or pass an ISO 8601 instant that is in the future and within the product ceiling (7 days). If the secret leaks, revoke the transfer with the revoke operation. No `Location` header is returned, deliberately: a transfer has no individual read operation. Returns 403 `forbidden_action` when the credential holder does not administer that tax ID, 403 `company_read_only` when the effective access of the credential to that tax ID is read-only (`view`), without issuing anything, 404 `profile_not_found` when the tax ID does not exist, belongs to another account, is outside your scope or the credential has no access to it (all with the same body), 422 `business_rule_violation` on a past, too-distant or uninterpretable `expires_at`, on the owner company of the account (`error.param` `company_id`) or on a recipient that does not exist or is your own account (`error.param` `recipient_account_id`, both with the same body), 422 `idempotency_key_required` because issuing is irreversible — once out, the secret is out — and 404 `account_not_found` when `{account}` is not the credential account.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.account.nif_transfers.create" method="post" path="/accounts/{account}/nif-transfers" example="factuarea_version_invalid_format" -->
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

$body = new Components\CreateNifTransferV1Request(
    companyId: '77b702f0-7a64-41e9-a51f-99c0514b498d',
);

$response = $sdk->account->nifTransfers->publicApiV1AccountNifTransfersCreate(
    account: '43900497',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.account.nif_transfers.create" method="post" path="/accounts/{account}/nif-transfers" example="retired_active_profile_header" -->
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

$body = new Components\CreateNifTransferV1Request(
    companyId: '77b702f0-7a64-41e9-a51f-99c0514b498d',
);

$response = $sdk->account->nifTransfers->publicApiV1AccountNifTransfersCreate(
    account: '86018095',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.account.nif_transfers.create" method="post" path="/accounts/{account}/nif-transfers" example="unsupported_api_version" -->
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

$body = new Components\CreateNifTransferV1Request(
    companyId: '77b702f0-7a64-41e9-a51f-99c0514b498d',
);

$response = $sdk->account->nifTransfers->publicApiV1AccountNifTransfersCreate(
    account: '60402971',
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
| `account`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Public identifier (UUID v7) of your account. Get it from `GET /v1/me` (`data.account.id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | [Components\CreateNifTransferV1Request](../../Models/Components/CreateNifTransferV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                      | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |

### Response

**[?Operations\PublicApiV1AccountNifTransfersCreateResponse](../../Models/Operations/PublicApiV1AccountNifTransfersCreateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1AccountNifTransfersList

List the tax ID transfers your account has ISSUED over the tax IDs your credential reaches — the inventory of open cessions — with cursor-based pagination (`limit` 1-100, default 25, plus `starting_after`, or `ending_before` for the previous page) and an optional `status` filter (`active`, `expired`, `revoked`, `consumed`). Transfers RECEIVED from other accounts are never listed: a transfer someone handed you is not yours — you only hold a secret — and publishing them would tell you which other accounts are giving away which tax IDs. Like its member and invitation siblings this listing is narrowed by the tax-ID scope of the credential: a transfer over a tax ID the credential does not reach is not listed — once a transfer is accepted its tax ID leaves your account, so it stops being listed too. It does not go through the administration guard: the `nif_transfers:read` scope is what authorises it. The `status` of each item is DERIVED against a single clock instant for the whole page — the table has no status column — and the `secret` never travels through this read: publishing it would turn a read permission into an effective tax-ID transfer permission. Returns 422 `invalid_param_value` for a `status` outside the closed vocabulary, 400 for an unknown query parameter or a malformed `limit`/`starting_after`, and 404 `account_not_found` when `{account}` is not the credential account.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.account.nif_transfers.list" method="get" path="/accounts/{account}/nif-transfers" -->
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



$response = $sdk->account->nifTransfers->publicApiV1AccountNifTransfersList(
    account: '25799563',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->nifTransferList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `account`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of your account. Get it from `GET /v1/me` (`data.account.id`).                                                                                                                                                                                                                                                                                   |                                                                                                                                                                                                                                                                                                                                                                              |
| `status`                                                                                                                                                                                                                                                                                                                                                                     | [?Operations\PublicApiV1AccountNifTransfersListStatus](../../Models/Operations/PublicApiV1AccountNifTransfersListStatus.md)                                                                                                                                                                                                                                                  | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Only tax ID transfers in this status: `active`, `expired`, `revoked` or `consumed`.                                                                                                                                                                                                                                                                                          |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1AccountNifTransfersListResponse](../../Models/Operations/PublicApiV1AccountNifTransfersListResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1AccountNifTransfersRevoke

Revoke a TAX ID TRANSFER your account issued, before anyone accepts it: from then on its secret no longer moves the tax ID. Use it when a secret leaked or was handed to the wrong party. Name the transfer by the public `id` the listing returns — it cannot be used to accept anything. The body is OPTIONAL and only accepts a free-text `reason` (up to 500 characters) that is kept in the audit trail. The 200 returns the transfer with `status: revoked`. Revoking is idempotent — revoking an already revoked transfer returns the same 200 and changes nothing — and irreversible: a revoked transfer never comes back; issue a new one if needed. Requires an administrative role in the tax ID of the transfer: 403 `forbidden_action` otherwise. Returns 404 `resource_not_found` when the transfer does not exist, was issued by another account or covers a tax ID outside your credential scope (all with the same body, `error.param` `nif_transfer`), 422 `business_rule_violation` when the transfer was already accepted — its tax ID is already in another account and revoking it would not bring it back —, 422 `idempotency_key_required` because revoking is irreversible, and 404 `account_not_found` when `{account}` is not the credential account.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.account.nif_transfers.revoke" method="post" path="/accounts/{account}/nif-transfers/{nif_transfer}/revoke" -->
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

$request = new Operations\PublicApiV1AccountNifTransfersRevokeRequest(
    account: '13103847',
    nifTransfer: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->account->nifTransfers->publicApiV1AccountNifTransfersRevoke(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                        | Type                                                                                                                             | Required                                                                                                                         | Description                                                                                                                      |
| -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                       | [Operations\PublicApiV1AccountNifTransfersRevokeRequest](../../Models/Operations/PublicApiV1AccountNifTransfersRevokeRequest.md) | :heavy_check_mark:                                                                                                               | The request object to use for the request.                                                                                       |

### Response

**[?Operations\PublicApiV1AccountNifTransfersRevokeResponse](../../Models/Operations/PublicApiV1AccountNifTransfersRevokeResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |