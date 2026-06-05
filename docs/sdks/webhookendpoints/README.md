# WebhookEndpoints

## Overview

### Available Operations

* [publicApiV1WebhookEndpointsCreate](#publicapiv1webhookendpointscreate) - Create a webhook endpoint
* [publicApiV1WebhookEndpointsList](#publicapiv1webhookendpointslist) - List all webhook endpoints
* [publicApiV1WebhookEndpointsDelete](#publicapiv1webhookendpointsdelete) - Delete a webhook endpoint
* [publicApiV1WebhookEndpointsShow](#publicapiv1webhookendpointsshow) - Retrieve a webhook endpoint
* [publicApiV1WebhookEndpointsUpdate](#publicapiv1webhookendpointsupdate) - Update a webhook endpoint
* [publicApiV1WebhookEndpointsPing](#publicapiv1webhookendpointsping) - Ping webhook endpoint
* [publicApiV1WebhookEndpointsRotateSecret](#publicapiv1webhookendpointsrotatesecret) - Rotate webhook secret

## publicApiV1WebhookEndpointsCreate

Create a webhook endpoint that receives event notifications via HTTPS callbacks. The signing `secret` is returned **once** in this response and never again — store it securely.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.webhook_endpoints.create" method="post" path="/webhook_endpoints" example="api_key_revoked" -->
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

$body = new Components\CreateWebhookEndpointRequest(
    url: 'https://angelic-lender.biz',
    metadata: [
        'erp_code' => 'IVA-GEN',
        'ledger_account' => '477000',
    ],
    enabledEvents: [
        Components\CreateWebhookEndpointRequestEnabledEvent::InvoiceMetadataChanged,
    ],
    customHeaders: [
        'Authorization' => 'Bearer integration-token',
        'X-Custom-Auth' => 'tenant-a',
    ],
);

$response = $sdk->webhookEndpoints->publicApiV1WebhookEndpointsCreate(
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.webhook_endpoints.create" method="post" path="/webhook_endpoints" example="invalid_api_key" -->
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

$body = new Components\CreateWebhookEndpointRequest(
    url: 'https://angelic-lender.biz',
    metadata: [
        'erp_code' => 'IVA-GEN',
        'ledger_account' => '477000',
    ],
    enabledEvents: [
        Components\CreateWebhookEndpointRequestEnabledEvent::InvoiceMetadataChanged,
    ],
    customHeaders: [
        'Authorization' => 'Bearer integration-token',
        'X-Custom-Auth' => 'tenant-a',
    ],
);

$response = $sdk->webhookEndpoints->publicApiV1WebhookEndpointsCreate(
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.webhook_endpoints.create" method="post" path="/webhook_endpoints" example="missing_api_key" -->
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

$body = new Components\CreateWebhookEndpointRequest(
    url: 'https://angelic-lender.biz',
    metadata: [
        'erp_code' => 'IVA-GEN',
        'ledger_account' => '477000',
    ],
    enabledEvents: [
        Components\CreateWebhookEndpointRequestEnabledEvent::InvoiceMetadataChanged,
    ],
    customHeaders: [
        'Authorization' => 'Bearer integration-token',
        'X-Custom-Auth' => 'tenant-a',
    ],
);

$response = $sdk->webhookEndpoints->publicApiV1WebhookEndpointsCreate(
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                              | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                       | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | [Components\CreateWebhookEndpointRequest](../../Models/Components/CreateWebhookEndpointRequest.md)                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Unique key generated by the client to ensure idempotency on retries. It lets you safely resend the same request: the first response is cached and returned without re-executing the mutation. It is an **opaque string** to the server; any unique value of up to 64 characters is valid (UUID v7, UUID v4, ULID, nanoid, etc.). UUID v7 is recommended for consistency with the API identifiers. The same key reused with a different body returns `409 idempotency_key_reused`. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1WebhookEndpointsCreateResponse](../../Models/Operations/PublicApiV1WebhookEndpointsCreateResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 409, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1WebhookEndpointsList

List your webhook endpoints with cursor-based pagination.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.webhook_endpoints.list" method="get" path="/webhook_endpoints" -->
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



$response = $sdk->webhookEndpoints->publicApiV1WebhookEndpointsList(
    limit: 25
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                               | Type                                                                                    | Required                                                                                | Description                                                                             |
| --------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------- |
| `limit`                                                                                 | *?int*                                                                                  | :heavy_minus_sign:                                                                      | Number of objects to return. Integer between 1 and 100. Defaults to 25.                 |
| `startingAfter`                                                                         | *?string*                                                                               | :heavy_minus_sign:                                                                      | Cursor for forward pagination. Use the `uuid` of the last object on the previous page.  |
| `endingBefore`                                                                          | *?string*                                                                               | :heavy_minus_sign:                                                                      | Cursor for backward pagination. Use the `uuid` of the first object on the current page. |

### Response

**[?Operations\PublicApiV1WebhookEndpointsListResponse](../../Models/Operations/PublicApiV1WebhookEndpointsListResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 422, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1WebhookEndpointsDelete

Delete a webhook endpoint. In-flight deliveries are not cancelled but no new deliveries are queued.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.webhook_endpoints.delete" method="delete" path="/webhook_endpoints/{webhook_endpoint}" -->
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



$response = $sdk->webhookEndpoints->publicApiV1WebhookEndpointsDelete(
    webhookEndpoint: '<value>'
);

if ($response->statusCode === 200) {
    // handle response
}
```

### Parameters

| Parameter          | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `webhookEndpoint`  | *string*           | :heavy_check_mark: | N/A                |

### Response

**[?Operations\PublicApiV1WebhookEndpointsDeleteResponse](../../Models/Operations/PublicApiV1WebhookEndpointsDeleteResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 409, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1WebhookEndpointsShow

Retrieve a webhook endpoint by its `uuid`. The signing secret is never exposed in this representation.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.webhook_endpoints.show" method="get" path="/webhook_endpoints/{webhook_endpoint}" -->
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



$response = $sdk->webhookEndpoints->publicApiV1WebhookEndpointsShow(
    webhookEndpoint: '<value>'
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter          | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `webhookEndpoint`  | *string*           | :heavy_check_mark: | N/A                |

### Response

**[?Operations\PublicApiV1WebhookEndpointsShowResponse](../../Models/Operations/PublicApiV1WebhookEndpointsShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 429       | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1WebhookEndpointsUpdate

Update a webhook endpoint (URL, description, enabled events, status, IP allowlist).

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.webhook_endpoints.update" method="put" path="/webhook_endpoints/{webhook_endpoint}" example="api_key_revoked" -->
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

$body = new Components\UpdateWebhookEndpointRequest(
    metadata: [
        'erp_code' => 'IVA-GEN',
        'ledger_account' => '477000',
    ],
    customHeaders: [
        'Authorization' => 'Bearer integration-token',
        'X-Custom-Auth' => 'tenant-a',
    ],
);

$response = $sdk->webhookEndpoints->publicApiV1WebhookEndpointsUpdate(
    webhookEndpoint: '<value>',
    body: $body

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.webhook_endpoints.update" method="put" path="/webhook_endpoints/{webhook_endpoint}" example="invalid_api_key" -->
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

$body = new Components\UpdateWebhookEndpointRequest(
    metadata: [
        'erp_code' => 'IVA-GEN',
        'ledger_account' => '477000',
    ],
    customHeaders: [
        'Authorization' => 'Bearer integration-token',
        'X-Custom-Auth' => 'tenant-a',
    ],
);

$response = $sdk->webhookEndpoints->publicApiV1WebhookEndpointsUpdate(
    webhookEndpoint: '<value>',
    body: $body

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.webhook_endpoints.update" method="put" path="/webhook_endpoints/{webhook_endpoint}" example="missing_api_key" -->
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

$body = new Components\UpdateWebhookEndpointRequest(
    metadata: [
        'erp_code' => 'IVA-GEN',
        'ledger_account' => '477000',
    ],
    customHeaders: [
        'Authorization' => 'Bearer integration-token',
        'X-Custom-Auth' => 'tenant-a',
    ],
);

$response = $sdk->webhookEndpoints->publicApiV1WebhookEndpointsUpdate(
    webhookEndpoint: '<value>',
    body: $body

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                           | Type                                                                                                | Required                                                                                            | Description                                                                                         |
| --------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------- |
| `webhookEndpoint`                                                                                   | *string*                                                                                            | :heavy_check_mark:                                                                                  | N/A                                                                                                 |
| `body`                                                                                              | [?Components\UpdateWebhookEndpointRequest](../../Models/Components/UpdateWebhookEndpointRequest.md) | :heavy_minus_sign:                                                                                  | N/A                                                                                                 |

### Response

**[?Operations\PublicApiV1WebhookEndpointsUpdateResponse](../../Models/Operations/PublicApiV1WebhookEndpointsUpdateResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 409, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1WebhookEndpointsPing

Send a test event (`webhook.ping`) to the endpoint to verify it is reachable and the signature handshake works. The synthetic delivery appears in `GET /webhook_endpoints/{webhook_endpoint}/deliveries`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.webhook_endpoints.ping" method="post" path="/webhook_endpoints/{webhook_endpoint}/ping" -->
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



$response = $sdk->webhookEndpoints->publicApiV1WebhookEndpointsPing(
    webhookEndpoint: '<value>',
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
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Unique key generated by the client to ensure idempotency on retries. It lets you safely resend the same request: the first response is cached and returned without re-executing the mutation. It is an **opaque string** to the server; any unique value of up to 64 characters is valid (UUID v7, UUID v4, ULID, nanoid, etc.). UUID v7 is recommended for consistency with the API identifiers. The same key reused with a different body returns `409 idempotency_key_reused`. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1WebhookEndpointsPingResponse](../../Models/Operations/PublicApiV1WebhookEndpointsPingResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 429       | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1WebhookEndpointsRotateSecret

Rotate the signing secret of a webhook endpoint. The new secret is returned **once** in this response. The previous secret remains valid for a 24-hour grace period (see `previous_secret_valid_until`) to allow zero-downtime rotation.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.webhook_endpoints.rotate_secret" method="post" path="/webhook_endpoints/{webhook_endpoint}/rotate_secret" -->
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



$response = $sdk->webhookEndpoints->publicApiV1WebhookEndpointsRotateSecret(
    webhookEndpoint: '<value>',
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
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Unique key generated by the client to ensure idempotency on retries. It lets you safely resend the same request: the first response is cached and returned without re-executing the mutation. It is an **opaque string** to the server; any unique value of up to 64 characters is valid (UUID v7, UUID v4, ULID, nanoid, etc.). UUID v7 is recommended for consistency with the API identifiers. The same key reused with a different body returns `409 idempotency_key_reused`. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1WebhookEndpointsRotateSecretResponse](../../Models/Operations/PublicApiV1WebhookEndpointsRotateSecretResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 409, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |