# Account.RequestLogs

## Overview

### Available Operations

* [publicApiV1AccountRequestLogsList](#publicapiv1accountrequestlogslist) - List your account API request logs
* [publicApiV1AccountRequestLogsShow](#publicapiv1accountrequestlogsshow) - Retrieve an account API request log

## publicApiV1AccountRequestLogsList

Inspect the requests made with ANY API key of your account, newest first, in the environment of the credential (a `live` key sees the live traffic and a `test` key the test traffic), so you can debug your integrations without opening a support ticket: what was called, what came back, how long it took and, when a call failed, the error it returned. It takes the same filters as the company listing (`method`, `status_range`, `only_errors`, `api_key_prefix`, `path_search` and `created_at[gte|lte]`) and `starting_after` is its only cursor: `environment` and `ending_before` are not accepted (400 `parameter_unknown`). Rows are purged after 30 days, so this is a debugging window, not an audit trail. The credential must reach every tax ID of the account and the person who issued it must be an owner or admin of the account (403 `forbidden_action` otherwise).

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.account.request_logs.list" method="get" path="/accounts/{account}/request-logs" example="success" -->
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

$request = new Operations\PublicApiV1AccountRequestLogsListRequest(
    account: '55253478',
    startingAfter: '184320',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->account->requestLogs->publicApiV1AccountRequestLogsList(
    request: $request
);

if ($response->apiRequestLogList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1AccountRequestLogsListRequest](../../Models/Operations/PublicApiV1AccountRequestLogsListRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1AccountRequestLogsListResponse](../../Models/Operations/PublicApiV1AccountRequestLogsListResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1AccountRequestLogsShow

Retrieve a single request made with any API key of your account by the `request_id` the API returned in the `X-Request-Id` header of that response, in the environment of the credential. It is an opaque `req_…` string, not a UUID v7, and the body carries the same fields as the listing. A request that does not exist, belongs to another account or environment, or was purged returns 404 `resource_not_found`. It follows the same access rule as the listing.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.account.request_logs.show" method="get" path="/accounts/{account}/request-logs/{request_id}" example="success" -->
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



$response = $sdk->account->requestLogs->publicApiV1AccountRequestLogsShow(
    account: '16500640',
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
| `account`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of your account. Get it from `GET /v1/me` (`data.account.id`).                                                                                                                                                                                                                                                                                   |                                                                                                                                                                                                                                                                                                                                                                              |
| `requestId`                                                                                                                                                                                                                                                                                                                                                                  | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Identifier of the logged request (`req_…`), as returned in the `X-Request-Id` header.                                                                                                                                                                                                                                                                                        |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1AccountRequestLogsShowResponse](../../Models/Operations/PublicApiV1AccountRequestLogsShowResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |