# Account.Webhooks.Deliveries

## Overview

### Available Operations

* [publicApiV1AccountWebhooksDeliveriesList](#publicapiv1accountwebhooksdeliverieslist) - List account webhook deliveries
* [publicApiV1AccountWebhooksDeliveriesRetry](#publicapiv1accountwebhooksdeliveriesretry) - Retry an account webhook delivery
* [publicApiV1AccountWebhooksDeliveriesShow](#publicapiv1accountwebhooksdeliveriesshow) - Retrieve an account webhook delivery

## publicApiV1AccountWebhooksDeliveriesList

List the delivery attempts of one account webhook endpoint, cursor-paginated with `limit` (1-100, default 25) and `starting_after`, optionally filtered by `status` (`pending`, `delivered`, `failed`, `failed_permanently` or `cancelled`). Deliveries come OLDEST FIRST (ascending by `id`, a time-ordered UUID v7), so to look for recent failures filter by `status=failed` (or `failed_permanently`) and keep paginating with `starting_after` until `has_more` is `false`. Deliveries of an endpoint of the `test` environment are recorded but never sent: they stay `pending` with `attempt` 0. Each delivery carries the event it delivers, the tax ID it is about (`company_id`, `null` in `account.*` events that are not about a tax ID), its relationship with your account (`own` or `managed`), the attempt counters, the HTTP response of your server and the signed `payload`. An endpoint the account does not see (unknown, deleted, or of another account or environment) returns 404 `webhook_endpoint_not_found`, never an empty page. `ending_before` is not accepted (400 `parameter_unknown`).

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.account.webhooks.deliveries.list" method="get" path="/accounts/{account}/webhooks/{webhook}/deliveries" example="success" -->
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

$request = new Operations\PublicApiV1AccountWebhooksDeliveriesListRequest(
    account: '45609790',
    webhook: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->account->webhooks->deliveries->publicApiV1AccountWebhooksDeliveriesList(
    request: $request
);

if ($response->accountWebhookDeliveryList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                | Type                                                                                                                                     | Required                                                                                                                                 | Description                                                                                                                              |
| ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                               | [Operations\PublicApiV1AccountWebhooksDeliveriesListRequest](../../Models/Operations/PublicApiV1AccountWebhooksDeliveriesListRequest.md) | :heavy_check_mark:                                                                                                                       | The request object to use for the request.                                                                                               |

### Response

**[?Operations\PublicApiV1AccountWebhooksDeliveriesListResponse](../../Models/Operations/PublicApiV1AccountWebhooksDeliveriesListResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1AccountWebhooksDeliveriesRetry

Re-arm a delivery that did not reach your server: the SAME delivery goes back to `pending` (its attempt counters are kept, and `manual_retry_count` grows) and is sent again with the current signing secret. Only `failed` and `failed_permanently` deliveries can be retried; a `delivered`, `pending` or `cancelled` one returns 422 with the subcode `account_webhook_retry_not_allowed`, and a delivery of a disabled endpoint returns 422 with the subcode `account_webhook_endpoint_not_active`. A delivery or endpoint the account does not see returns 404 `webhook_delivery_not_found`. Returns 202. Requires a live key and an `Idempotency-Key`, and it counts against its own per-route rate limit.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.account.webhooks.deliveries.retry" method="post" path="/accounts/{account}/webhooks/{webhook}/deliveries/{delivery}/retry" example="success" -->
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

$request = new Operations\PublicApiV1AccountWebhooksDeliveriesRetryRequest(
    account: '68378617',
    webhook: '<value>',
    delivery: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->account->webhooks->deliveries->publicApiV1AccountWebhooksDeliveriesRetry(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                  | Type                                                                                                                                       | Required                                                                                                                                   | Description                                                                                                                                |
| ------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                                 | [Operations\PublicApiV1AccountWebhooksDeliveriesRetryRequest](../../Models/Operations/PublicApiV1AccountWebhooksDeliveriesRetryRequest.md) | :heavy_check_mark:                                                                                                                         | The request object to use for the request.                                                                                                 |

### Response

**[?Operations\PublicApiV1AccountWebhooksDeliveriesRetryResponse](../../Models/Operations/PublicApiV1AccountWebhooksDeliveriesRetryResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1AccountWebhooksDeliveriesShow

Retrieve one delivery of an account webhook endpoint by its `id` (UUID v7), with the signed envelope that was sent (`payload`) and the response of your server (`response_status` and a truncated `response_body`). A delivery the account does not see — unknown, or under an endpoint it does not see — returns 404 `webhook_delivery_not_found`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.account.webhooks.deliveries.show" method="get" path="/accounts/{account}/webhooks/{webhook}/deliveries/{delivery}" example="success" -->
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



$response = $sdk->account->webhooks->deliveries->publicApiV1AccountWebhooksDeliveriesShow(
    account: '53747979',
    webhook: '<value>',
    delivery: '<value>',
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
| `webhook`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the webhook endpoint.                                                                                                                                                                                                                                                                                                                         |                                                                                                                                                                                                                                                                                                                                                                              |
| `delivery`                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the webhook delivery.                                                                                                                                                                                                                                                                                                                         |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1AccountWebhooksDeliveriesShowResponse](../../Models/Operations/PublicApiV1AccountWebhooksDeliveriesShowResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |