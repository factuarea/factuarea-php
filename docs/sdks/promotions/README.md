# Promotions

## Overview

### Available Operations

* [publicApiV1PromotionsCancel](#publicapiv1promotionscancel) - Cancel a promotion
* [publicApiV1PromotionsCreate](#publicapiv1promotionscreate) - Create a promotion
* [publicApiV1PromotionsList](#publicapiv1promotionslist) - List all promotions
* [publicApiV1PromotionsDelete](#publicapiv1promotionsdelete) - Delete a promotion
* [publicApiV1PromotionsShow](#publicapiv1promotionsshow) - Retrieve a promotion
* [publicApiV1PromotionsUpdate](#publicapiv1promotionsupdate) - Update a promotion

## publicApiV1PromotionsCancel

Cancel a scheduled promotion, or end an active one right now. Returns the promotion, now `cancelled`.
- An ended or already cancelled promotion is read-only: 422 `promotion_read_only`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.promotions.cancel" method="post" path="/companies/{company}/promotions/{promotion}/cancel" example="success" -->
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



$response = $sdk->promotions->publicApiV1PromotionsCancel(
    company: 'f251c921-54cd-425e-96fd-aab41171b468',
    promotion: '31da618f-407c-4cc6-8e4b-a0f9d12ce784',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                   |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `promotion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Public identifier (UUID v7) of the promotion.                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                               | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |

### Response

**[?Operations\PublicApiV1PromotionsCancelResponse](../../Models/Operations/PublicApiV1PromotionsCancelResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1PromotionsCreate

Schedule a promotion: a discount on products, variants, presentations, configurations or packs, between two days of the company time zone (end day included).
- Send `channels` (`documents`, `web` or both) and at least one item with `target_type`, `target_id`, `mode` (`percent_off` or `fixed`), `value` and, for `fixed`, `basis` (`net` or `gross`).
- A failing item answers 422 `invalid_promotion_item` (`param` `items.<n>.…`) or `duplicate_promotion_item`, and nothing is created.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.promotions.create" method="post" path="/companies/{company}/promotions" example="factuarea_version_invalid_format" -->
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

$body = new Components\CreatePromotionV1Request(
    name: '<value>',
    startsOn: LocalDate::parse('2026-10-29'),
    endsOn: LocalDate::parse('2024-10-11'),
    channels: new Components\CreatePromotionV1RequestChannels(
        documents: true,
        web: true,
    ),
    items: [
        new Components\CreatePromotionV1RequestItem(
            targetType: Components\CreatePromotionV1RequestTargetType::Product,
            targetId: 'bade81b9-2823-4b93-af44-f920c5919cf8',
            mode: Components\CreatePromotionV1RequestMode::Fixed,
            value: 793.63,
        ),
    ],
);

$response = $sdk->promotions->publicApiV1PromotionsCreate(
    company: '8e3185d2-de5d-4059-970f-d0169b7662a2',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.promotions.create" method="post" path="/companies/{company}/promotions" example="retired_active_profile_header" -->
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

$body = new Components\CreatePromotionV1Request(
    name: '<value>',
    startsOn: LocalDate::parse('2026-10-29'),
    endsOn: LocalDate::parse('2024-10-11'),
    channels: new Components\CreatePromotionV1RequestChannels(
        documents: true,
        web: true,
    ),
    items: [
        new Components\CreatePromotionV1RequestItem(
            targetType: Components\CreatePromotionV1RequestTargetType::Product,
            targetId: 'bade81b9-2823-4b93-af44-f920c5919cf8',
            mode: Components\CreatePromotionV1RequestMode::Fixed,
            value: 793.63,
        ),
    ],
);

$response = $sdk->promotions->publicApiV1PromotionsCreate(
    company: '67f12eaf-2cb0-4a35-b224-b86bc3496765',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.promotions.create" method="post" path="/companies/{company}/promotions" example="success" -->
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

$body = new Components\CreatePromotionV1Request(
    name: '<value>',
    startsOn: LocalDate::parse('2026-10-29'),
    endsOn: LocalDate::parse('2024-10-11'),
    channels: new Components\CreatePromotionV1RequestChannels(
        documents: true,
        web: true,
    ),
    items: [
        new Components\CreatePromotionV1RequestItem(
            targetType: Components\CreatePromotionV1RequestTargetType::Product,
            targetId: 'bade81b9-2823-4b93-af44-f920c5919cf8',
            mode: Components\CreatePromotionV1RequestMode::Fixed,
            value: 793.63,
        ),
    ],
);

$response = $sdk->promotions->publicApiV1PromotionsCreate(
    company: '7243a418-38cd-44f8-9c41-478f29e3fc1e',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.promotions.create" method="post" path="/companies/{company}/promotions" example="unsupported_api_version" -->
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

$body = new Components\CreatePromotionV1Request(
    name: '<value>',
    startsOn: LocalDate::parse('2026-10-29'),
    endsOn: LocalDate::parse('2024-10-11'),
    channels: new Components\CreatePromotionV1RequestChannels(
        documents: true,
        web: true,
    ),
    items: [
        new Components\CreatePromotionV1RequestItem(
            targetType: Components\CreatePromotionV1RequestTargetType::Product,
            targetId: 'bade81b9-2823-4b93-af44-f920c5919cf8',
            mode: Components\CreatePromotionV1RequestMode::Fixed,
            value: 793.63,
        ),
    ],
);

$response = $sdk->promotions->publicApiV1PromotionsCreate(
    company: '8a0646d4-ec50-47a2-b995-6d4bfea4affc',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                   |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [Components\CreatePromotionV1Request](../../Models/Components/CreatePromotionV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                                  | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                               | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |

### Response

**[?Operations\PublicApiV1PromotionsCreateResponse](../../Models/Operations/PublicApiV1PromotionsCreateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1PromotionsList

List the promotions of the company with cursor-based pagination, oldest first, each one with its items.
- Filter by `status` (`scheduled`, `active`, `ended` or `cancelled`), `channel` (`documents` or `web`), `starts_at[gte|lte]`, `ends_at[gte|lte]` and `updated[gte]`.
- Dates are ISO 8601; a day without time is read in the company time zone.
- `GET /promotions?status=active&channel=web` returns what a web shop shows today.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.promotions.list" method="get" path="/companies/{company}/promotions" example="success" -->
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

$request = new Operations\PublicApiV1PromotionsListRequest(
    company: 'dc35d54c-f29e-4fea-adb9-9235b58e853b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->promotions->publicApiV1PromotionsList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                  | Type                                                                                                       | Required                                                                                                   | Description                                                                                                |
| ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                 | [Operations\PublicApiV1PromotionsListRequest](../../Models/Operations/PublicApiV1PromotionsListRequest.md) | :heavy_check_mark:                                                                                         | The request object to use for the request.                                                                 |

### Response

**[?Operations\PublicApiV1PromotionsListResponse](../../Models/Operations/PublicApiV1PromotionsListResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1PromotionsDelete

Delete a scheduled promotion permanently. Returns 204 with no body.
- An active promotion is part of the price history: 422 `promotion_started` (cancel it instead).
- An ended or cancelled promotion is read-only: 422 `promotion_read_only`.
- It cannot be undone, so the request needs an `Idempotency-Key`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.promotions.delete" method="delete" path="/companies/{company}/promotions/{promotion}" -->
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



$response = $sdk->promotions->publicApiV1PromotionsDelete(
    company: '762369d8-046e-4f41-b4dd-a1c17eaaa9db',
    promotion: '777e3d0d-739a-49a5-accb-73ae366b419a',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->statusCode === 200) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `promotion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the promotion.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1PromotionsDeleteResponse](../../Models/Operations/PublicApiV1PromotionsDeleteResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1PromotionsShow

Retrieve a promotion by its `id`, with its items: regular price, promotion price and legal previous price of each one.
- Returns 404 `promotion_not_found` for an unknown id or one of another company.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.promotions.show" method="get" path="/companies/{company}/promotions/{promotion}" example="success" -->
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



$response = $sdk->promotions->publicApiV1PromotionsShow(
    company: '8082fb67-4d98-4f0c-98b9-b98daffb902c',
    promotion: '09fbd54c-d8dc-464c-aeee-befde7d392c8',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `promotion`                                                                                                                                                                                                                                                                                                                                                                  | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the promotion.                                                                                                                                                                                                                                                                                                                                |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1PromotionsShowResponse](../../Models/Operations/PublicApiV1PromotionsShowResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1PromotionsUpdate

Update a promotion. Only the fields you send change; `label` sent as `null` is cleared and `items` replaces the previous items as a whole.
- A scheduled promotion accepts every field.
- An active promotion only accepts a new `ends_on` (to extend it); anything else is 422 `promotion_started`.
- An ended or cancelled promotion is read-only: 422 `promotion_read_only`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.promotions.update" method="patch" path="/companies/{company}/promotions/{promotion}" example="success" -->
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

$request = new Operations\PublicApiV1PromotionsUpdateRequest(
    company: '86605339-ef5a-4ee5-8a2c-38710ef00f61',
    promotion: '4002009d-0114-40b5-be5f-bc0c8242924c',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->promotions->publicApiV1PromotionsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                      | Type                                                                                                           | Required                                                                                                       | Description                                                                                                    |
| -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                     | [Operations\PublicApiV1PromotionsUpdateRequest](../../Models/Operations/PublicApiV1PromotionsUpdateRequest.md) | :heavy_check_mark:                                                                                             | The request object to use for the request.                                                                     |

### Response

**[?Operations\PublicApiV1PromotionsUpdateResponse](../../Models/Operations/PublicApiV1PromotionsUpdateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |