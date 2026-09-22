# Developers.RequestLogs

## Overview

### Available Operations

* [publicApiV1DevelopersRequestLogsList](#publicapiv1developersrequestlogslist) - List your API request logs
* [publicApiV1DevelopersRequestLogsShow](#publicapiv1developersrequestlogsshow) - Retrieve an API request log

## publicApiV1DevelopersRequestLogsList

Inspect the requests your own integration has made against this API, newest first, so you can debug it without opening a support ticket: what you called, what came back, how long it took and, when a call failed, the error it returned. Scoped to the authenticated company. Rows are purged after 30 days, so this is a debugging window, not an audit trail.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.developers.request_logs.list" method="get" path="/companies/{company}/request-logs" example="success" -->
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

$request = new Operations\PublicApiV1DevelopersRequestLogsListRequest(
    company: 'Blick - Gorczany',
    startingAfter: '184320',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->developers->requestLogs->publicApiV1DevelopersRequestLogsList(
    request: $request
);

if ($response->apiRequestLogList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                        | Type                                                                                                                             | Required                                                                                                                         | Description                                                                                                                      |
| -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                       | [Operations\PublicApiV1DevelopersRequestLogsListRequest](../../Models/Operations/PublicApiV1DevelopersRequestLogsListRequest.md) | :heavy_check_mark:                                                                                                               | The request object to use for the request.                                                                                       |

### Response

**[?Operations\PublicApiV1DevelopersRequestLogsListResponse](../../Models/Operations/PublicApiV1DevelopersRequestLogsListResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1DevelopersRequestLogsShow

Retrieve a single request of your own integration by the `request_id` the API returned in the `X-Request-Id` header of that response — the identifier you already have in hand when a call misbehaved, and the one to quote in a support request. It is an opaque `req_…` string, not a UUID v7. The body carries the same fields as the listing.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.developers.request_logs.show" method="get" path="/companies/{company}/request-logs/{request_id}" example="success" -->
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



$response = $sdk->developers->requestLogs->publicApiV1DevelopersRequestLogsShow(
    company: 'Purdy - Kautzer',
    requestId: '<id>',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID) of the company the request acts on. It must be in your credential's scope; read it from `GET /v1/me` (`data.scope[].id`). Never the tax ID.                                                                                                                                                                                                         |                                                                                                                                                                                                                                                                                                                                                                              |
| `requestId`                                                                                                                                                                                                                                                                                                                                                                  | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Identifier of the logged request (`req_…`, not a UUID), as returned in the `X-Request-Id` response header and in `request_id` by the request log list. Request logs are kept for 30 days.                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1DevelopersRequestLogsShowResponse](../../Models/Operations/PublicApiV1DevelopersRequestLogsShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |