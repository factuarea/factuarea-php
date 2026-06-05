# Events

## Overview

### Available Operations

* [publicApiV1EventsList](#publicapiv1eventslist) - List all events
* [publicApiV1EventsShow](#publicapiv1eventsshow) - Retrieve an event

## publicApiV1EventsList

List events in your event log with cursor-based pagination. Each event records something that happened in your account (an invoice was paid, a quote accepted, …) and is the same object delivered to your webhook endpoints. Supports filtering by `type[in]` and `created[gte|lte]`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.events.list" method="get" path="/events" -->
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

$request = new Operations\PublicApiV1EventsListRequest();

$response = $sdk->events->publicApiV1EventsList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                          | Type                                                                                               | Required                                                                                           | Description                                                                                        |
| -------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------- |
| `$request`                                                                                         | [Operations\PublicApiV1EventsListRequest](../../Models/Operations/PublicApiV1EventsListRequest.md) | :heavy_check_mark:                                                                                 | The request object to use for the request.                                                         |

### Response

**[?Operations\PublicApiV1EventsListResponse](../../Models/Operations/PublicApiV1EventsListResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 422, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1EventsShow

Retrieve a single event by its `id` (format `evt_<ulid>`, an opaque identifier). Useful for auditing and replaying webhook payloads. Returns `404 not_found` if the event does not exist or belongs to another company.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.events.show" method="get" path="/events/{event}" -->
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



$response = $sdk->events->publicApiV1EventsShow(
    event: '<value>'
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter          | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `event`            | *string*           | :heavy_check_mark: | N/A                |

### Response

**[?Operations\PublicApiV1EventsShowResponse](../../Models/Operations/PublicApiV1EventsShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |