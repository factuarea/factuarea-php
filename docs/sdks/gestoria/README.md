# Gestoria

## Overview

### Available Operations

* [publicApiV1GestoriaWorkforceSummary](#publicapiv1gestoriaworkforcesummary) - Retrieve the consolidated workforce compliance overview

## publicApiV1GestoriaWorkforceSummary

Return the consolidated time-tracking compliance panel for your whole managed portfolio: one row per `active` managed company, each projected from that company's latest monthly close without recomputation — whether the current (last closable) period is closed, its status (`closed`/`reopened`), the last closed period (`last_closed_year`/`last_closed_month`), and the aggregated `total_balance_minutes`, `total_overtime_minutes` and `employee_count`. Master-scoped: the portfolio is resolved from your API key, never from the payload, and only your own children appear. Unlike the per-company endpoints, which name a single managed company in the `{company}` path segment, this aggregates across children in one call. Returned as `{ "data": [ConsolidatedWorkforce, ...] }`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.gestoria.workforce_summary" method="get" path="/accounts/{account}/gestoria/workforce-summary" example="success" -->
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



$response = $sdk->gestoria->publicApiV1GestoriaWorkforceSummary(
    account: '78119663',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `account`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID) of the account your credential belongs to; read it from `GET /v1/me` (`data.account.id`). Any other value returns 404 `account_not_found`.                                                                                                                                                                                                          |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1GestoriaWorkforceSummaryResponse](../../Models/Operations/PublicApiV1GestoriaWorkforceSummaryResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |