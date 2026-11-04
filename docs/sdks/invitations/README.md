# Account.Invitations

## Overview

### Available Operations

* [publicApiV1AccountInvitationsCancel](#publicapiv1accountinvitationscancel) - Revoke an account invitation
* [publicApiV1AccountInvitationsShow](#publicapiv1accountinvitationsshow) - Retrieve an account invitation
* [publicApiV1AccountInvitationsCreate](#publicapiv1accountinvitationscreate) - Send an account invitation
* [publicApiV1AccountInvitationsList](#publicapiv1accountinvitationslist) - List account invitations
* [publicApiV1AccountInvitationsResend](#publicapiv1accountinvitationsresend) - Resend an account invitation

## publicApiV1AccountInvitationsCancel

Revoke a pending invitation by its `id` (UUID v7): it becomes `revoked` and its link can no longer be accepted.
- It never removes an existing member; returns 204.
- 422 `business_rule_violation` if already accepted or revoked; 404 `invitation_not_found` if unknown or of another account. Requires the `invitations:write` scope, which `*` does not cover: the API key must list it explicitly.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.account.invitations.cancel" method="delete" path="/accounts/{account}/invitations/{invitation}" -->
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



$response = $sdk->account->invitations->publicApiV1AccountInvitationsCancel(
    account: '86319459',
    invitation: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->statusCode === 200) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `account`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Public identifier (UUID v7) of your account. Get it from `GET /v1/me` (`data.account.id`).                                                                                                                                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `invitation`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Public identifier (UUID v7) of the account invitation.                                                                                                                                                                                                                                                                                                                                                                                                                                                                      |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                               | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |

### Response

**[?Operations\PublicApiV1AccountInvitationsCancelResponse](../../Models/Operations/PublicApiV1AccountInvitationsCancelResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1AccountInvitationsShow

Retrieve one invitation by its `id` (UUID v7): `email`, `role`, initial `grants[]`, `send_email`, `external_advisor`, `status` and `expires_at`.
- Grants shown are only those on tax IDs your credential reads.
- The acceptance secret and URL are never returned here.
- An unknown, malformed or foreign invitation returns the same 404 `invitation_not_found`. Requires the `invitations:read` scope, which `*` does not cover: the API key must list it explicitly.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.account.invitations.show" method="get" path="/accounts/{account}/invitations/{invitation}" example="success" -->
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



$response = $sdk->account->invitations->publicApiV1AccountInvitationsShow(
    account: '60914750',
    invitation: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `account`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of your account. Get it from `GET /v1/me` (`data.account.id`).                                                                                                                                                                                                                                                                                   |                                                                                                                                                                                                                                                                                                                                                                              |
| `invitation`                                                                                                                                                                                                                                                                                                                                                                 | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the account invitation.                                                                                                                                                                                                                                                                                                                       |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1AccountInvitationsShowResponse](../../Models/Operations/PublicApiV1AccountInvitationsShowResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1AccountInvitationsCreate

Invite a person by email to join the account with a `role` (`admin` or `member`) and a required `grants` list (may be empty).
- Only a `member` carries grants; an `admin` invite needs a scope covering every tax ID. Returns 201.
- `secret` and `accept_url` appear once, only with `send_email: false`.
- Missing `grants` returns 400 `parameter_missing`. Requires the `invitations:write` scope, which `*` does not cover: the API key must list it explicitly.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.account.invitations.create" method="post" path="/accounts/{account}/invitations" example="api_key_expired" -->
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

$body = new Components\InviteAccountMemberV1Request(
    email: 'Dudley_Bergstrom@yahoo.com',
    role: Components\InviteAccountMemberV1RequestRole::Member,
    grants: [],
);

$response = $sdk->account->invitations->publicApiV1AccountInvitationsCreate(
    account: '51936395',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.account.invitations.create" method="post" path="/accounts/{account}/invitations" example="api_key_revoked" -->
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

$body = new Components\InviteAccountMemberV1Request(
    email: 'Dudley_Bergstrom@yahoo.com',
    role: Components\InviteAccountMemberV1RequestRole::Member,
    grants: [],
);

$response = $sdk->account->invitations->publicApiV1AccountInvitationsCreate(
    account: '55498618',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.account.invitations.create" method="post" path="/accounts/{account}/invitations" example="factuarea_version_invalid_format" -->
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

$body = new Components\InviteAccountMemberV1Request(
    email: 'Dudley_Bergstrom@yahoo.com',
    role: Components\InviteAccountMemberV1RequestRole::Member,
    grants: [],
);

$response = $sdk->account->invitations->publicApiV1AccountInvitationsCreate(
    account: '37886fda-1d82-4f4a-a6f9-3cb5c86b2434',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.account.invitations.create" method="post" path="/accounts/{account}/invitations" example="invalid_api_key" -->
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

$body = new Components\InviteAccountMemberV1Request(
    email: 'Dudley_Bergstrom@yahoo.com',
    role: Components\InviteAccountMemberV1RequestRole::Member,
    grants: [],
);

$response = $sdk->account->invitations->publicApiV1AccountInvitationsCreate(
    account: '91301624',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.account.invitations.create" method="post" path="/accounts/{account}/invitations" example="ip_not_allowed" -->
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

$body = new Components\InviteAccountMemberV1Request(
    email: 'Dudley_Bergstrom@yahoo.com',
    role: Components\InviteAccountMemberV1RequestRole::Member,
    grants: [],
);

$response = $sdk->account->invitations->publicApiV1AccountInvitationsCreate(
    account: '27738547',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.account.invitations.create" method="post" path="/accounts/{account}/invitations" example="missing_api_key" -->
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

$body = new Components\InviteAccountMemberV1Request(
    email: 'Dudley_Bergstrom@yahoo.com',
    role: Components\InviteAccountMemberV1RequestRole::Member,
    grants: [],
);

$response = $sdk->account->invitations->publicApiV1AccountInvitationsCreate(
    account: '55213315',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.account.invitations.create" method="post" path="/accounts/{account}/invitations" example="retired_active_profile_header" -->
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

$body = new Components\InviteAccountMemberV1Request(
    email: 'Dudley_Bergstrom@yahoo.com',
    role: Components\InviteAccountMemberV1RequestRole::Member,
    grants: [],
);

$response = $sdk->account->invitations->publicApiV1AccountInvitationsCreate(
    account: '7411a1ab-6740-4602-ae68-8325529f1412',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.account.invitations.create" method="post" path="/accounts/{account}/invitations" example="success" -->
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

$body = new Components\InviteAccountMemberV1Request(
    email: 'Dudley_Bergstrom@yahoo.com',
    role: Components\InviteAccountMemberV1RequestRole::Member,
    grants: [],
);

$response = $sdk->account->invitations->publicApiV1AccountInvitationsCreate(
    account: '42040184',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.account.invitations.create" method="post" path="/accounts/{account}/invitations" example="unsupported_api_version" -->
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

$body = new Components\InviteAccountMemberV1Request(
    email: 'Dudley_Bergstrom@yahoo.com',
    role: Components\InviteAccountMemberV1RequestRole::Member,
    grants: [],
);

$response = $sdk->account->invitations->publicApiV1AccountInvitationsCreate(
    account: '01d4493b-a718-4442-abfe-7a28da3a37cd',
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
| `account`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of your account. Get it from `GET /v1/me` (`data.account.id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Components\InviteAccountMemberV1Request](../../Models/Components/InviteAccountMemberV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1AccountInvitationsCreateResponse](../../Models/Operations/PublicApiV1AccountInvitationsCreateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1AccountInvitationsList

List the invitations of the account, cursor-paginated with `limit` (1-100, default 25) and `starting_after` or `ending_before`.
- Filter by `status` (`pending`, `accepted`, `revoked`, `expired`); grants shown are only those on tax IDs your credential reads.
- The acceptance secret is never listed; owner or admin only (403 `forbidden_action`). Requires the `invitations:read` scope, which `*` does not cover: the API key must list it explicitly.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.account.invitations.list" method="get" path="/accounts/{account}/invitations" example="success" -->
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



$response = $sdk->account->invitations->publicApiV1AccountInvitationsList(
    account: '14617626',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->accountInvitationList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `account`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of your account. Get it from `GET /v1/me` (`data.account.id`).                                                                                                                                                                                                                                                                                   |                                                                                                                                                                                                                                                                                                                                                                              |
| `status`                                                                                                                                                                                                                                                                                                                                                                     | [?Operations\PublicApiV1AccountInvitationsListStatus](../../Models/Operations/PublicApiV1AccountInvitationsListStatus.md)                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Only invitations in this status: `pending`, `accepted`, `revoked` or `expired`.                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1AccountInvitationsListResponse](../../Models/Operations/PublicApiV1AccountInvitationsListResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1AccountInvitationsResend

Resend a pending invitation: a NEW acceptance secret is issued, the previous one stops working and expiry is renewed.
- Returns 200; `secret` and `accept_url` appear once, only for a copy-link invitation; `null` on replay.
- An `admin` invite needs a scope covering every tax ID of the account.
- 422 `invitation_expired` if expired; 404 `invitation_not_found`. Requires the `invitations:write` scope, which `*` does not cover: the API key must list it explicitly.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.account.invitations.resend" method="post" path="/accounts/{account}/invitations/{invitation}/resend" example="success" -->
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



$response = $sdk->account->invitations->publicApiV1AccountInvitationsResend(
    account: '45791778',
    invitation: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `account`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of your account. Get it from `GET /v1/me` (`data.account.id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `invitation`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the account invitation.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1AccountInvitationsResendResponse](../../Models/Operations/PublicApiV1AccountInvitationsResendResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |