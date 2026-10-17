# Account.Members.Grants

## Overview

### Available Operations

* [publicApiV1AccountMembersGrantsList](#publicapiv1accountmembersgrantslist) - List the grants of an account member
* [publicApiV1AccountMembersGrantsDelete](#publicapiv1accountmembersgrantsdelete) - Revoke an account member grant
* [publicApiV1AccountMembersGrantsUpdate](#publicapiv1accountmembersgrantsupdate) - Set an account member grant
* [publicApiV1AccountMembersGrantsShow](#publicapiv1accountmembersgrantsshow) - Retrieve an account member grant

## publicApiV1AccountMembersGrantsList

List the per-tax-ID grants of one member with the canonical list envelope. The collection is complete (bounded by the tax IDs of the account), so it always comes with `has_more: false` and `next_cursor: null`. Only grants on tax IDs your credential reads are published; an `owner` or `admin` returns an empty list because they reach every tax ID without a grant. Returns 404 `member_not_found` when the member does not exist or belongs to another account. Requires the `members:read` scope, which `*` does not cover: the API key must list it explicitly.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.account.members.grants.list" method="get" path="/accounts/{account}/members/{member}/grants" example="success" -->
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



$response = $sdk->account->members->grants->publicApiV1AccountMembersGrantsList(
    account: '34874145',
    member: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->accountMemberGrantList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `account`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of your account. Get it from `GET /v1/me` (`data.account.id`).                                                                                                                                                                                                                                                                                   |                                                                                                                                                                                                                                                                                                                                                                              |
| `member`                                                                                                                                                                                                                                                                                                                                                                     | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the account member.                                                                                                                                                                                                                                                                                                                           |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1AccountMembersGrantsListResponse](../../Models/Operations/PublicApiV1AccountMembersGrantsListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1AccountMembersGrantsDelete

Revoke the grant of a member on one tax ID of the account and return 204. Revoking a grant that does not exist changes nothing and returns the same 204. The member stops reaching that tax ID through the web app, the API and MCP, and its API keys are lowered accordingly; no email or notification is sent. Reversible: set the grant again to restore it. Returns 404 `member_not_found` and 422 `grant_company_not_in_account` when the tax ID is outside the account or your credential scope. Requires the `members:write` scope, which `*` does not cover: the API key must list it explicitly.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.account.members.grants.delete" method="delete" path="/accounts/{account}/members/{member}/grants/{company}" -->
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

$request = new Operations\PublicApiV1AccountMembersGrantsDeleteRequest(
    account: '36306929',
    member: '<value>',
    company: 'Gulgowski, Friesen and Ryan',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->account->members->grants->publicApiV1AccountMembersGrantsDelete(
    request: $request
);

if ($response->statusCode === 200) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                          | Type                                                                                                                               | Required                                                                                                                           | Description                                                                                                                        |
| ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                         | [Operations\PublicApiV1AccountMembersGrantsDeleteRequest](../../Models/Operations/PublicApiV1AccountMembersGrantsDeleteRequest.md) | :heavy_check_mark:                                                                                                                 | The request object to use for the request.                                                                                         |

### Response

**[?Operations\PublicApiV1AccountMembersGrantsDeleteResponse](../../Models/Operations/PublicApiV1AccountMembersGrantsDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1AccountMembersGrantsUpdate

Create or replace the grant of a `member` on one tax ID of the account with the given `level` (`view` or `operate`), and return 200 with the resulting grant. It is a full replacement of a singular sub-resource addressed entirely by its path, hence `PUT`: repeating it with the same level leaves the same state and sends no email or notification. Lowering a grant also lowers the API keys of that member. Returns 404 `member_not_found`, 422 `grant_company_not_in_account` when the tax ID is outside the account or your credential scope, and 422 `grants_only_for_member` when the member is an `owner` or `admin`. Requires the `members:write` scope, which `*` does not cover: the API key must list it explicitly.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.account.members.grants.update" method="put" path="/accounts/{account}/members/{member}/grants/{company}" example="api_key_expired" -->
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

$request = new Operations\PublicApiV1AccountMembersGrantsUpdateRequest(
    account: '22878698',
    member: '<value>',
    company: 'Padberg and Sons',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SetAccountMemberGrantV1Request(
        level: Components\SetAccountMemberGrantV1RequestLevel::View,
    ),
);

$response = $sdk->account->members->grants->publicApiV1AccountMembersGrantsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.account.members.grants.update" method="put" path="/accounts/{account}/members/{member}/grants/{company}" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1AccountMembersGrantsUpdateRequest(
    account: '86401896',
    member: '<value>',
    company: 'Leannon - Feil',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SetAccountMemberGrantV1Request(
        level: Components\SetAccountMemberGrantV1RequestLevel::View,
    ),
);

$response = $sdk->account->members->grants->publicApiV1AccountMembersGrantsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.account.members.grants.update" method="put" path="/accounts/{account}/members/{member}/grants/{company}" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1AccountMembersGrantsUpdateRequest(
    account: '31613933',
    member: '<value>',
    company: 'Bahringer Group',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SetAccountMemberGrantV1Request(
        level: Components\SetAccountMemberGrantV1RequestLevel::View,
    ),
);

$response = $sdk->account->members->grants->publicApiV1AccountMembersGrantsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.account.members.grants.update" method="put" path="/accounts/{account}/members/{member}/grants/{company}" example="ip_not_allowed" -->
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

$request = new Operations\PublicApiV1AccountMembersGrantsUpdateRequest(
    account: '84183246',
    member: '<value>',
    company: 'Ratke, Renner and Kuphal',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SetAccountMemberGrantV1Request(
        level: Components\SetAccountMemberGrantV1RequestLevel::View,
    ),
);

$response = $sdk->account->members->grants->publicApiV1AccountMembersGrantsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.account.members.grants.update" method="put" path="/accounts/{account}/members/{member}/grants/{company}" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1AccountMembersGrantsUpdateRequest(
    account: '19625956',
    member: '<value>',
    company: 'Schamberger - Strosin',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SetAccountMemberGrantV1Request(
        level: Components\SetAccountMemberGrantV1RequestLevel::View,
    ),
);

$response = $sdk->account->members->grants->publicApiV1AccountMembersGrantsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.account.members.grants.update" method="put" path="/accounts/{account}/members/{member}/grants/{company}" example="success" -->
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

$request = new Operations\PublicApiV1AccountMembersGrantsUpdateRequest(
    account: '04840872',
    member: '<value>',
    company: 'Raynor and Sons',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SetAccountMemberGrantV1Request(
        level: Components\SetAccountMemberGrantV1RequestLevel::View,
    ),
);

$response = $sdk->account->members->grants->publicApiV1AccountMembersGrantsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                          | Type                                                                                                                               | Required                                                                                                                           | Description                                                                                                                        |
| ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                         | [Operations\PublicApiV1AccountMembersGrantsUpdateRequest](../../Models/Operations/PublicApiV1AccountMembersGrantsUpdateRequest.md) | :heavy_check_mark:                                                                                                                 | The request object to use for the request.                                                                                         |

### Response

**[?Operations\PublicApiV1AccountMembersGrantsUpdateResponse](../../Models/Operations/PublicApiV1AccountMembersGrantsUpdateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1AccountMembersGrantsShow

Retrieve the grant of one member on one tax ID of the account (`company_id` and `level`). A member that does not exist or belongs to another account, a tax ID outside the account or outside what your credential reads, and a missing grant (including any tax ID of an `owner` or `admin`, which carry no grants) all return the same 404 `member_not_found`, so the response never tells them apart. Requires the `members:read` scope, which `*` does not cover: the API key must list it explicitly.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.account.members.grants.show" method="get" path="/accounts/{account}/members/{member}/grants/{company}" example="success" -->
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



$response = $sdk->account->members->grants->publicApiV1AccountMembersGrantsShow(
    account: '29786960',
    member: '<value>',
    company: 'Keebler Inc',
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
| `member`                                                                                                                                                                                                                                                                                                                                                                     | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the account member.                                                                                                                                                                                                                                                                                                                           |                                                                                                                                                                                                                                                                                                                                                                              |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the account tax ID the grant applies to.                                                                                                                                                                                                                                                                                                      |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1AccountMembersGrantsShowResponse](../../Models/Operations/PublicApiV1AccountMembersGrantsShowResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |