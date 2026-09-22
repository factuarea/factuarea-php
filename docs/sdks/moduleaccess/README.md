# Account.Members.ModuleAccess

## Overview

### Available Operations

* [publicApiV1AccountMembersModuleAccessShow](#publicapiv1accountmembersmoduleaccessshow) - Retrieve a member module access level
* [publicApiV1AccountMembersModuleAccessUpdate](#publicapiv1accountmembersmoduleaccessupdate) - Set a member module access level

## publicApiV1AccountMembersModuleAccessShow

Retrieve the module access level of a member in the NIF passed in the `company_id` query parameter. `restrictions` is a DENY list — section to withdrawn capabilities out of `access`, `delete`, `send` and `export` — so an empty object means no restriction at all, never no access. Returns 404 when the member is not in that NIF or outside your credential scope.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.account.members.module_access.show" method="get" path="/accounts/{account}/members/{member}/module-access" -->
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
| `account`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Identificador público de la cuenta, YA resuelto y comparado<br/>                          contra la cuenta de la credencial por el middleware del eje<br/>                          de cuenta. Aquí no se vuelve a resolver.                                                                                                                                                 |                                                                                                                                                                                                                                                                                                                                                                              |
| `member`                                                                                                                                                                                                                                                                                                                                                                     | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Identificador público del miembro (la PERSONA, no una fila de<br/>                         pertenencia).                                                                                                                                                                                                                                                                     |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1AccountMembersModuleAccessShowResponse](../../Models/Operations/PublicApiV1AccountMembersModuleAccessShowResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1AccountMembersModuleAccessUpdate

Replace the FULL set of module restrictions of a member in the NIF named by `company_id`. The body substitutes every previous restriction, so repeating the call leaves the same state and an empty `restrictions` object lifts them all. Returns 422 when restricting yourself, the NIF owner, the last person able to manage users, or a section that cannot be restricted.

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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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
            http: '<YOUR_BEARER_TOKEN_HERE>',
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

### Parameters

| Parameter                                                                                                                                      | Type                                                                                                                                           | Required                                                                                                                                       | Description                                                                                                                                    |
| ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                     | [Operations\PublicApiV1AccountMembersModuleAccessUpdateRequest](../../Models/Operations/PublicApiV1AccountMembersModuleAccessUpdateRequest.md) | :heavy_check_mark:                                                                                                                             | The request object to use for the request.                                                                                                     |

### Response

**[?Operations\PublicApiV1AccountMembersModuleAccessUpdateResponse](../../Models/Operations/PublicApiV1AccountMembersModuleAccessUpdateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |