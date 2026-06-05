# EventCatalog

## Overview

### Available Operations

* [publicApiV1EventCatalogList](#publicapiv1eventcataloglist) - List event types

## publicApiV1EventCatalogList

List the closed catalog of event types that Factuarea can emit to webhook endpoints. Use it to populate a subscription UI instead of hard-coding event names. Each entry exposes its `name`, `category` (derived from the `<category>.*` prefix), a Spanish `description`, and a `status` (`available` if emitted today, `coming_soon` if reserved for a future release).

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.event_catalog.list" method="get" path="/event-catalog" -->
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



$response = $sdk->eventCatalog->publicApiV1EventCatalogList(

);

if ($response->object !== null) {
    // handle response
}
```

### Response

**[?Operations\PublicApiV1EventCatalogListResponse](../../Models/Operations/PublicApiV1EventCatalogListResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 429       | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |