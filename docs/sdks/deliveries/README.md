# WebhookEndpoints.Deliveries

## Overview

### Available Operations

* [publicApiV1WebhookEndpointsDeliveriesList](#publicapiv1webhookendpointsdeliverieslist) - List webhook deliveries
* [publicApiV1WebhookEndpointsDeliveriesReplay](#publicapiv1webhookendpointsdeliveriesreplay) - Replay webhook delivery
* [publicApiV1WebhookEndpointsDeliveriesShow](#publicapiv1webhookendpointsdeliveriesshow) - Retrieve webhook delivery

## publicApiV1WebhookEndpointsDeliveriesList

List delivery attempts for a webhook endpoint with cursor-based pagination. Each delivery captures the HTTP response status, body (truncated), duration, and retry schedule.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.webhook_endpoints.deliveries.list" method="get" path="/webhook_endpoints/{webhook_endpoint}/deliveries" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

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
    webhookEndpoint: '<value>',
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

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 422, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1WebhookEndpointsDeliveriesReplay

Re-queue a webhook delivery. A new delivery attempt is created (with `attempt: 1`) for the same event/endpoint pair.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.webhook_endpoints.deliveries.replay" method="post" path="/webhook_endpoints/{webhook_endpoint}/deliveries/{delivery}/replay" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();



$response = $sdk->webhookEndpoints->deliveries->publicApiV1WebhookEndpointsDeliveriesReplay(
    webhookEndpoint: '<value>',
    delivery: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                              | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                       | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `webhookEndpoint`                                                                                                                                                                                                                                                                                                                                                                                                                                                                 | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `delivery`                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Unique key generated by the client to ensure idempotency on retries. It lets you safely resend the same request: the first response is cached and returned without re-executing the mutation. It is an **opaque string** to the server; any unique value of up to 64 characters is valid (UUID v7, UUID v4, ULID, nanoid, etc.). UUID v7 is recommended for consistency with the API identifiers. The same key reused with a different body returns `409 idempotency_key_reused`. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1WebhookEndpointsDeliveriesReplayResponse](../../Models/Operations/PublicApiV1WebhookEndpointsDeliveriesReplayResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 409, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1WebhookEndpointsDeliveriesShow

Retrieve a single delivery attempt by its `uuid`, including the full event payload that was delivered.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.webhook_endpoints.deliveries.show" method="get" path="/webhook_endpoints/{webhook_endpoint}/deliveries/{delivery}" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

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
    webhookEndpoint: '<value>',
    delivery: '<value>'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter          | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `webhookEndpoint`  | *string*           | :heavy_check_mark: | N/A                |
| `delivery`         | *string*           | :heavy_check_mark: | N/A                |

### Response

**[?Operations\PublicApiV1WebhookEndpointsDeliveriesShowResponse](../../Models/Operations/PublicApiV1WebhookEndpointsDeliveriesShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 429       | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |