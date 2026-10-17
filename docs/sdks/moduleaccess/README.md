# Account.Members.ModuleAccess

## Overview

### Available Operations

* [publicApiV1AccountMembersModuleAccessShow](#publicapiv1accountmembersmoduleaccessshow) - Retrieve a member module access level
* [publicApiV1AccountMembersModuleAccessUpdate](#publicapiv1accountmembersmoduleaccessupdate) - Set a member module access level

## publicApiV1AccountMembersModuleAccessShow

Retrieve the module access of an account member in the tax ID given by the required `company_id` query parameter.
- `restrictions` is a DENY list per section; an empty object means no restriction.
- `grants` lists administrator-only sections granted; always empty for an `owner` or `admin`.
- Unknown member or tax ID returns the same 404 `member_not_found`. Requires the `members:read` scope, which `*` does not cover: the API key must list it explicitly.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.account.members.module_access.show" method="get" path="/accounts/{account}/members/{member}/module-access" example="success" -->
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



$response = $sdk->account->members->moduleAccess->publicApiV1AccountMembersModuleAccessShow(
    account: '25138705',
    member: '<value>',
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
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1AccountMembersModuleAccessShowResponse](../../Models/Operations/PublicApiV1AccountMembersModuleAccessShowResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1AccountMembersModuleAccessUpdate

Replace the FULL set of module restrictions of a member in the tax ID named by `company_id`, and optionally its `grants`.
- The body replaces every previous restriction; an empty `restrictions` lifts them all. Sent `grants` replace the old ones.
- 422 `business_rule_violation` for restricting yourself, the owner or an unrestrictable section; 404 `member_not_found`. Requires the `members:write` scope, which `*` does not cover: the API key must list it explicitly.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.account.members.module_access.update" method="patch" path="/accounts/{account}/members/{member}/module-access" example="api_key_expired" -->
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

$request = new Operations\PublicApiV1AccountMembersModuleAccessUpdateRequest(
    account: '72245285',
    member: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SetAccountMemberModuleAccessV1Request(
        companyId: '5651a96a-f324-41f9-b9b8-80ed3b5b9b9d',
        restrictions: [],
    ),
);

$response = $sdk->account->members->moduleAccess->publicApiV1AccountMembersModuleAccessUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.account.members.module_access.update" method="patch" path="/accounts/{account}/members/{member}/module-access" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1AccountMembersModuleAccessUpdateRequest(
    account: '59823514',
    member: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SetAccountMemberModuleAccessV1Request(
        companyId: '5651a96a-f324-41f9-b9b8-80ed3b5b9b9d',
        restrictions: [],
    ),
);

$response = $sdk->account->members->moduleAccess->publicApiV1AccountMembersModuleAccessUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.account.members.module_access.update" method="patch" path="/accounts/{account}/members/{member}/module-access" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1AccountMembersModuleAccessUpdateRequest(
    account: '36c33694-d91b-4b27-b4ac-b312df93e013',
    member: '32a2da40-9cfc-4103-8c58-4edfec9ef99c',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SetAccountMemberModuleAccessV1Request(
        companyId: '5651a96a-f324-41f9-b9b8-80ed3b5b9b9d',
        restrictions: [],
    ),
);

$response = $sdk->account->members->moduleAccess->publicApiV1AccountMembersModuleAccessUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.account.members.module_access.update" method="patch" path="/accounts/{account}/members/{member}/module-access" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1AccountMembersModuleAccessUpdateRequest(
    account: '19663302',
    member: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SetAccountMemberModuleAccessV1Request(
        companyId: '5651a96a-f324-41f9-b9b8-80ed3b5b9b9d',
        restrictions: [],
    ),
);

$response = $sdk->account->members->moduleAccess->publicApiV1AccountMembersModuleAccessUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.account.members.module_access.update" method="patch" path="/accounts/{account}/members/{member}/module-access" example="ip_not_allowed" -->
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

$request = new Operations\PublicApiV1AccountMembersModuleAccessUpdateRequest(
    account: '17714267',
    member: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SetAccountMemberModuleAccessV1Request(
        companyId: '5651a96a-f324-41f9-b9b8-80ed3b5b9b9d',
        restrictions: [],
    ),
);

$response = $sdk->account->members->moduleAccess->publicApiV1AccountMembersModuleAccessUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.account.members.module_access.update" method="patch" path="/accounts/{account}/members/{member}/module-access" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1AccountMembersModuleAccessUpdateRequest(
    account: '90408050',
    member: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SetAccountMemberModuleAccessV1Request(
        companyId: '5651a96a-f324-41f9-b9b8-80ed3b5b9b9d',
        restrictions: [],
    ),
);

$response = $sdk->account->members->moduleAccess->publicApiV1AccountMembersModuleAccessUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.account.members.module_access.update" method="patch" path="/accounts/{account}/members/{member}/module-access" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1AccountMembersModuleAccessUpdateRequest(
    account: '5651a96a-f324-41f9-b9b8-80ed3b5b9b9d',
    member: '0027dd20-4e06-41e1-a901-eaa65038fe45',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SetAccountMemberModuleAccessV1Request(
        companyId: '5651a96a-f324-41f9-b9b8-80ed3b5b9b9d',
        restrictions: [],
    ),
);

$response = $sdk->account->members->moduleAccess->publicApiV1AccountMembersModuleAccessUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.account.members.module_access.update" method="patch" path="/accounts/{account}/members/{member}/module-access" example="success" -->
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

$request = new Operations\PublicApiV1AccountMembersModuleAccessUpdateRequest(
    account: '01488103',
    member: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SetAccountMemberModuleAccessV1Request(
        companyId: '5651a96a-f324-41f9-b9b8-80ed3b5b9b9d',
        restrictions: [],
    ),
);

$response = $sdk->account->members->moduleAccess->publicApiV1AccountMembersModuleAccessUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.account.members.module_access.update" method="patch" path="/accounts/{account}/members/{member}/module-access" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1AccountMembersModuleAccessUpdateRequest(
    account: 'c3fb2d1c-320e-494c-ad17-bec06739e2d4',
    member: '16b56866-2f42-482c-bffa-9e96d1df02a8',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SetAccountMemberModuleAccessV1Request(
        companyId: '5651a96a-f324-41f9-b9b8-80ed3b5b9b9d',
        restrictions: [],
    ),
);

$response = $sdk->account->members->moduleAccess->publicApiV1AccountMembersModuleAccessUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                      | Type                                                                                                                                           | Required                                                                                                                                       | Description                                                                                                                                    |
| ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                     | [Operations\PublicApiV1AccountMembersModuleAccessUpdateRequest](../../Models/Operations/PublicApiV1AccountMembersModuleAccessUpdateRequest.md) | :heavy_check_mark:                                                                                                                             | The request object to use for the request.                                                                                                     |

### Response

**[?Operations\PublicApiV1AccountMembersModuleAccessUpdateResponse](../../Models/Operations/PublicApiV1AccountMembersModuleAccessUpdateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |