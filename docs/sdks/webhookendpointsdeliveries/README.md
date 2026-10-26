# WebhookEndpoints.Deliveries

## Overview

### Available Operations

* [publicApiV1WebhookEndpointsDeliveriesReplay](#publicapiv1webhookendpointsdeliveriesreplay) - Replay webhook delivery

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
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
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
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |