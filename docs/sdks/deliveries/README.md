# WebhookEndpoints.Deliveries

## Overview

### Available Operations

* [publicApiV1WebhookEndpointsDeliveriesList](#publicapiv1webhookendpointsdeliverieslist) - List webhook deliveries
* [publicApiV1WebhookEndpointsDeliveriesReplay](#publicapiv1webhookendpointsdeliveriesreplay) - Replay webhook delivery
* [publicApiV1WebhookEndpointsDeliveriesShow](#publicapiv1webhookendpointsdeliveriesshow) - Retrieve webhook delivery

## publicApiV1WebhookEndpointsDeliveriesList

List delivery attempts for a webhook endpoint with cursor-based pagination. Each delivery captures the HTTP response status, body (truncated), duration, and retry schedule.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.webhook_endpoints.deliveries.list" method="get" path="/companies/{company}/webhook-endpoints/{webhook_endpoint}/deliveries" example="success" -->
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

$request = new Operations\PublicApiV1WebhookEndpointsDeliveriesListRequest(
    company: 'Jenkins - Altenwerth',
    webhookEndpoint: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->webhookEndpoints->deliveries->publicApiV1WebhookEndpointsDeliveriesList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                  | Type                                                                                                                                       | Required                                                                                                                                   | Description                                                                                                                                |
| ------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                                 | [Operations\PublicApiV1WebhookEndpointsDeliveriesListRequest](../../Models/Operations/PublicApiV1WebhookEndpointsDeliveriesListRequest.md) | :heavy_check_mark:                                                                                                                         | The request object to use for the request.                                                                                                 |

### Response

**[?Operations\PublicApiV1WebhookEndpointsDeliveriesListResponse](../../Models/Operations/PublicApiV1WebhookEndpointsDeliveriesListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1WebhookEndpointsDeliveriesReplay

Re-queue a webhook delivery. A new delivery attempt is created (with `attempt: 1`) for the same event/endpoint pair.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.webhook_endpoints.deliveries.replay" method="post" path="/companies/{company}/webhook-endpoints/{webhook_endpoint}/deliveries/{delivery}/replay" example="success" -->
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

$request = new Operations\PublicApiV1WebhookEndpointsDeliveriesReplayRequest(
    company: 'Considine, Dooley and Conroy',
    webhookEndpoint: '<value>',
    delivery: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->webhookEndpoints->deliveries->publicApiV1WebhookEndpointsDeliveriesReplay(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                      | Type                                                                                                                                           | Required                                                                                                                                       | Description                                                                                                                                    |
| ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                     | [Operations\PublicApiV1WebhookEndpointsDeliveriesReplayRequest](../../Models/Operations/PublicApiV1WebhookEndpointsDeliveriesReplayRequest.md) | :heavy_check_mark:                                                                                                                             | The request object to use for the request.                                                                                                     |

### Response

**[?Operations\PublicApiV1WebhookEndpointsDeliveriesReplayResponse](../../Models/Operations/PublicApiV1WebhookEndpointsDeliveriesReplayResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1WebhookEndpointsDeliveriesShow

Retrieve a single delivery attempt by its `id`, including the full event payload that was delivered.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.webhook_endpoints.deliveries.show" method="get" path="/companies/{company}/webhook-endpoints/{webhook_endpoint}/deliveries/{delivery}" example="success" -->
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



$response = $sdk->webhookEndpoints->deliveries->publicApiV1WebhookEndpointsDeliveriesShow(
    company: 'Price - VonRueden',
    webhookEndpoint: '<value>',
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
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `webhookEndpoint`                                                                                                                                                                                                                                                                                                                                                            | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the webhook endpoint.                                                                                                                                                                                                                                                                                                                         |                                                                                                                                                                                                                                                                                                                                                                              |
| `delivery`                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the webhook delivery.                                                                                                                                                                                                                                                                                                                         |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1WebhookEndpointsDeliveriesShowResponse](../../Models/Operations/PublicApiV1WebhookEndpointsDeliveriesShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |