# StorefrontKeys

## Overview

### Available Operations

* [publicApiV1StorefrontKeysCreate](#publicapiv1storefrontkeyscreate) - Create a publishable storefront key
* [publicApiV1StorefrontKeysList](#publicapiv1storefrontkeyslist) - List publishable storefront keys
* [publicApiV1StorefrontKeysScopes](#publicapiv1storefrontkeysscopes) - List assignable storefront key scopes
* [publicApiV1StorefrontKeysRevoke](#publicapiv1storefrontkeysrevoke) - Revoke a publishable storefront key
* [publicApiV1StorefrontKeysRotateSecret](#publicapiv1storefrontkeysrotatesecret) - Rotate a storefront key secret
* [publicApiV1StorefrontKeysShow](#publicapiv1storefrontkeysshow) - Retrieve a publishable storefront key
* [publicApiV1StorefrontKeysUpdate](#publicapiv1storefrontkeysupdate) - Update a publishable storefront key

## publicApiV1StorefrontKeysCreate

Issue a publishable storefront key for the company in the path and return its plaintext secret EXACTLY ONCE — store it now, no later call returns it. It takes a name, at least one ALLOWED ORIGIN and at least one scope of the shopper lane, plus an optional expiry. The origins are the heart of this credential and not a formality: the key is meant to travel in the browser of a custom-built storefront, so every request it makes is checked against the origins declared here, and an origin with a wildcard, with a path, with a query string or without a scheme is rejected with 422 naming the field. Scopes outside the shopper lane are rejected as well, the ones that manage these very keys included: a key that travels in a browser must not be able to mint another one. When the company already holds as many ACTIVE keys as its quota tier allows, the call is rejected with the business-rule error that says so — revoke one you no longer serve, or raise the tier. Returns the key with its plaintext secret and a location header pointing at it.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront_keys.create" method="post" path="/companies/{company}/storefront-keys" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1StorefrontKeysCreateRequest(
    company: 'Turner and Sons',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateStorefrontKeyRequest(
        name: '<value>',
        allowedOrigins: [
            '<value 1>',
        ],
        scopes: [],
    ),
);

$response = $sdk->storefrontKeys->publicApiV1StorefrontKeysCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront_keys.create" method="post" path="/companies/{company}/storefront-keys" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1StorefrontKeysCreateRequest(
    company: 'Champlin, Wilkinson and Halvorson',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateStorefrontKeyRequest(
        name: '<value>',
        allowedOrigins: [
            '<value 1>',
        ],
        scopes: [],
    ),
);

$response = $sdk->storefrontKeys->publicApiV1StorefrontKeysCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront_keys.create" method="post" path="/companies/{company}/storefront-keys" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1StorefrontKeysCreateRequest(
    company: 'Baumbach Group',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateStorefrontKeyRequest(
        name: '<value>',
        allowedOrigins: [
            '<value 1>',
        ],
        scopes: [],
    ),
);

$response = $sdk->storefrontKeys->publicApiV1StorefrontKeysCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                              | Type                                                                                                                   | Required                                                                                                               | Description                                                                                                            |
| ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                             | [Operations\PublicApiV1StorefrontKeysCreateRequest](../../Models/Operations/PublicApiV1StorefrontKeysCreateRequest.md) | :heavy_check_mark:                                                                                                     | The request object to use for the request.                                                                             |

### Response

**[?Operations\PublicApiV1StorefrontKeysCreateResponse](../../Models/Operations/PublicApiV1StorefrontKeysCreateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1StorefrontKeysList

List the publishable storefront keys of the company in the path, with cursor-based pagination. Each key comes back with its public id, its name, its visible prefix, its allowed origins, its granted scopes, its status, its expiry, when and from which origin it was last used, when it was rotated and when it was revoked with the reason, and whether it is still active. The plaintext secret is NEVER returned here, and neither is its fingerprint — not in the listing, not in the detail and not in an error message: the secret is shown once, at creation and at rotation. Filter by status, by name and by prefix, and sort by creation or by name. Revoked and expired keys are listed too, so filter by status when you only want the ones a storefront can still use.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront_keys.list" method="get" path="/companies/{company}/storefront-keys" -->
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

$request = new Operations\PublicApiV1StorefrontKeysListRequest(
    company: 'Russel Inc',
    sort: Operations\PublicApiV1StorefrontKeysListSort::MinusCreated,
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->storefrontKeys->publicApiV1StorefrontKeysList(
    request: $request
);

if ($response->storefrontKeyList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                          | Type                                                                                                               | Required                                                                                                           | Description                                                                                                        |
| ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                         | [Operations\PublicApiV1StorefrontKeysListRequest](../../Models/Operations/PublicApiV1StorefrontKeysListRequest.md) | :heavy_check_mark:                                                                                                 | The request object to use for the request.                                                                         |

### Response

**[?Operations\PublicApiV1StorefrontKeysListResponse](../../Models/Operations/PublicApiV1StorefrontKeysListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1StorefrontKeysScopes

List the scopes that can be granted to a publishable storefront key, each one with its value and its localized label. Use it to build the scope picker of your own dashboard instead of hard-coding the list. The catalog is CLOSED and holds only scopes of the shopper lane — reading the catalog, the prices and the availability, and writing the cart and the order —, so anything outside it is rejected by the creation and by the update. It is a read of configuration and it takes no parameters.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront_keys.scopes" method="get" path="/companies/{company}/storefront-keys/scopes" -->
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



$response = $sdk->storefrontKeys->publicApiV1StorefrontKeysScopes(
    company: 'Hudson and Sons',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->assignableStorefrontScopes !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                        | Type                                                                                                                                                                                                                                                                                                                                                                                             | Required                                                                                                                                                                                                                                                                                                                                                                                         | Description                                                                                                                                                                                                                                                                                                                                                                                      | Example                                                                                                                                                                                                                                                                                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `company`                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1StorefrontKeysScopesResponse](../../Models/Operations/PublicApiV1StorefrontKeysScopesResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1StorefrontKeysRevoke

Revoke a publishable storefront key. It stops authenticating immediately, so every request the storefront makes with it is rejected from that moment on: this is the call for a key that leaked, or for a storefront that is no longer yours. It accepts a revoke reason, which is kept with the key and published in its detail and its listing. Irreversible: there is NO reactivation — a storefront that has to work again needs a NEW key with its secret deployed —, and revoking one that is already revoked is rejected with the business-rule error. A key of another company answers exactly like one that does not exist. It requires an `Idempotency-Key` when the policy of your credential demands it.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront_keys.revoke" method="post" path="/companies/{company}/storefront-keys/{storefront_key}/revoke" -->
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

$request = new Operations\PublicApiV1StorefrontKeysRevokeRequest(
    company: 'Larkin - Olson',
    storefrontKey: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\RevokeStorefrontKeyRequest(
        reason: 'Rotación anual de credenciales del escaparate.',
    ),
);

$response = $sdk->storefrontKeys->publicApiV1StorefrontKeysRevoke(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                              | Type                                                                                                                   | Required                                                                                                               | Description                                                                                                            |
| ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                             | [Operations\PublicApiV1StorefrontKeysRevokeRequest](../../Models/Operations/PublicApiV1StorefrontKeysRevokeRequest.md) | :heavy_check_mark:                                                                                                     | The request object to use for the request.                                                                             |

### Response

**[?Operations\PublicApiV1StorefrontKeysRevokeResponse](../../Models/Operations/PublicApiV1StorefrontKeysRevokeResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1StorefrontKeysRotateSecret

Replace the secret of a publishable storefront key: the current one stops authenticating IMMEDIATELY and the new one is returned in plaintext exactly once. There is NO grace window, and this is the sentence to read before calling it: the secret of this credential is published in a live storefront, so every page still serving the old one stops working the moment you rotate — have the deployment of the new secret ready BEFORE you call, not after. The public id, the name, the allowed origins and the scopes of the key do not change. Rotating a key that is already revoked is rejected with the business-rule error, and a key of another company answers as not found. Irreversible: no operation of this API brings the previous secret back, so it requires an `Idempotency-Key` when the policy of your credential demands it, and repeating with the same key returns the answer of the first call instead of rotating a second time.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront_keys.rotate_secret" method="post" path="/companies/{company}/storefront-keys/{storefront_key}/rotate-secret" -->
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

$request = new Operations\PublicApiV1StorefrontKeysRotateSecretRequest(
    company: 'Ondricka - Macejkovic',
    storefrontKey: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->storefrontKeys->publicApiV1StorefrontKeysRotateSecret(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                          | Type                                                                                                                               | Required                                                                                                                           | Description                                                                                                                        |
| ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                         | [Operations\PublicApiV1StorefrontKeysRotateSecretRequest](../../Models/Operations/PublicApiV1StorefrontKeysRotateSecretRequest.md) | :heavy_check_mark:                                                                                                                 | The request object to use for the request.                                                                                         |

### Response

**[?Operations\PublicApiV1StorefrontKeysRotateSecretResponse](../../Models/Operations/PublicApiV1StorefrontKeysRotateSecretResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1StorefrontKeysShow

Retrieve one publishable storefront key of the company in the path by its public id, with its name, its visible prefix, its allowed origins, its granted scopes, its status, its expiry, its last use and the origin it came from, the moments it was rotated or revoked with the revoke reason, and whether it is active. The plaintext secret is never included and its fingerprint is never published. A key of another company answers exactly like one that does not exist.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront_keys.show" method="get" path="/companies/{company}/storefront-keys/{storefront_key}" -->
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



$response = $sdk->storefrontKeys->publicApiV1StorefrontKeysShow(
    company: 'Ledner - Sauer',
    storefrontKey: '<value>',
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
| `company`                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `storefrontKey`                                                                                                                                                                                                                                                                                                                                                                                  | *string*                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1StorefrontKeysShowResponse](../../Models/Operations/PublicApiV1StorefrontKeysShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1StorefrontKeysUpdate

Partially update a publishable storefront key: its name, its allowed origins and its granted scopes. Only the fields present in the body change, the ones you omit keep their value, and an empty body is accepted and does nothing, which is what a partial update means. The origins keep the rules of the creation — no wildcard, no path, no query string and never without a scheme — and the scopes keep being restricted to the shopper lane. Retiring an origin takes effect on the very next request: a storefront served from that origin stops being accepted, which is how a domain that is no longer yours is cut off WITHOUT rotating the secret. Narrowing the scopes works the same way and is the cheap way to stop a key from writing while it keeps reading the catalog. A key that has been revoked rejects the update with the business-rule error that says so, and a key of another company answers as not found. Returns the updated key.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.storefront_keys.update" method="patch" path="/companies/{company}/storefront-keys/{storefront_key}" -->
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

$request = new Operations\PublicApiV1StorefrontKeysUpdateRequest(
    company: 'Kessler LLC',
    storefrontKey: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\UpdateStorefrontKeyRequest(
        allowedOrigins: [
            'https://tienda.example.com',
            'https://www.tienda.example.com',
            'https://campana.tienda.example.com',
        ],
    ),
);

$response = $sdk->storefrontKeys->publicApiV1StorefrontKeysUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                              | Type                                                                                                                   | Required                                                                                                               | Description                                                                                                            |
| ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                             | [Operations\PublicApiV1StorefrontKeysUpdateRequest](../../Models/Operations/PublicApiV1StorefrontKeysUpdateRequest.md) | :heavy_check_mark:                                                                                                     | The request object to use for the request.                                                                             |

### Response

**[?Operations\PublicApiV1StorefrontKeysUpdateResponse](../../Models/Operations/PublicApiV1StorefrontKeysUpdateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |