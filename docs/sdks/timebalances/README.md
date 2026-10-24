# TimeBalances

## Overview

### Available Operations

* [publicApiV1TimeBalancesEmployee](#publicapiv1timebalancesemployee) - Retrieve an employee’s time balance for a period
* [publicApiV1TimeBalancesMonthlySheet](#publicapiv1timebalancesmonthlysheet) - Retrieve an employee’s monthly time sheet
* [publicApiV1TimeBalancesTeamSummary](#publicapiv1timebalancesteamsummary) - Retrieve the team time balance summary

## publicApiV1TimeBalancesEmployee

Return an employee's time balance for a period: expected vs worked minutes, balance and overtime per day, and period totals (minutes).
- `from` and `to` (`YYYY-MM-DD`) are required; `to` before `from` returns 422.
- Days flagged `is_unscheduled` or `is_outside_employment` have expected, balance and overtime 0. Computed resource: exposes `employee_id`, no `id`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.time_balances.employee" method="get" path="/companies/{company}/time-balances/employee/{employee}" example="success" -->
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

$request = new Operations\PublicApiV1TimeBalancesEmployeeRequest(
    company: 'Kreiger, Corwin and Deckow',
    employee: '<value>',
    from: LocalDate::parse('2026-12-05'),
    to: LocalDate::parse('2025-06-06'),
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->timeBalances->publicApiV1TimeBalancesEmployee(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                              | Type                                                                                                                   | Required                                                                                                               | Description                                                                                                            |
| ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                             | [Operations\PublicApiV1TimeBalancesEmployeeRequest](../../Models/Operations/PublicApiV1TimeBalancesEmployeeRequest.md) | :heavy_check_mark:                                                                                                     | The request object to use for the request.                                                                             |

### Response

**[?Operations\PublicApiV1TimeBalancesEmployeeResponse](../../Models/Operations/PublicApiV1TimeBalancesEmployeeResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1TimeBalancesMonthlySheet

Return the live monthly time sheet of an employee for the open period: expected vs worked minutes, balance and overtime per day, and totals (minutes).
- `employee_id` is required; `month` (`YYYY-MM`) defaults to the current month.
- `total_*` project the whole month; `to_date` holds the closed days up to `through_date`. Computed resource: no `id`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.time_balances.monthly_sheet" method="get" path="/companies/{company}/time-balances/monthly-sheet" example="success" -->
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



$response = $sdk->timeBalances->publicApiV1TimeBalancesMonthlySheet(
    company: 'Jacobs, Hilpert and Schinner',
    employeeId: 'b65c4601-9a14-4ff6-bafb-bdfae2d9d19b',
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
| `employeeId`                                                                                                                                                                                                                                                                                                                                                                 | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Employee ID (UUID v7) whose monthly sheet to retrieve.                                                                                                                                                                                                                                                                                                                       |                                                                                                                                                                                                                                                                                                                                                                              |
| `month`                                                                                                                                                                                                                                                                                                                                                                      | *?string*                                                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Sheet month in YYYY-MM format; defaults to the current month.                                                                                                                                                                                                                                                                                                                |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1TimeBalancesMonthlySheetResponse](../../Models/Operations/PublicApiV1TimeBalancesMonthlySheetResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1TimeBalancesTeamSummary

Return the team time balance summary for a month: one row per active employee with a schedule, with expected, worked, balance and overtime minutes.
- `month` (`YYYY-MM`) defaults to the current month.
- `total_*` cover the whole month (a projection in the current one); `to_date` accumulates closed days. Computed resource: no `id`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.time_balances.team_summary" method="get" path="/companies/{company}/time-balances/team-summary" example="success" -->
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



$response = $sdk->timeBalances->publicApiV1TimeBalancesTeamSummary(
    company: 'Paucek, Considine and Haag',
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
| `month`                                                                                                                                                                                                                                                                                                                                                                      | *?string*                                                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Summary month in YYYY-MM format; defaults to the current month.                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1TimeBalancesTeamSummaryResponse](../../Models/Operations/PublicApiV1TimeBalancesTeamSummaryResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |