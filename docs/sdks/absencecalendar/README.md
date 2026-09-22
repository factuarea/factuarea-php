# AbsenceCalendar

## Overview

### Available Operations

* [publicApiV1AbsenceCalendarShow](#publicapiv1absencecalendarshow) - Get the team absence calendar

## publicApiV1AbsenceCalendarShow

Return the monthly absence calendar of your team for a given `year` and `month`: every active employee with their approved absences of that month (each coloured by its absence type) and the public holidays that apply, kept separate from the absences. Optionally scoped to a single `employee_id` (UUID v7). A computed resource: it exposes `employee_id` per member, never an `id`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.absence-calendar.show" method="get" path="/companies/{company}/absence-calendar" example="success" -->
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

$request = new Operations\PublicApiV1AbsenceCalendarShowRequest(
    company: 'Jenkins, Jacobi and Gerlach',
    year: 159448,
    month: 614831,
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->absenceCalendar->publicApiV1AbsenceCalendarShow(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                            | Type                                                                                                                 | Required                                                                                                             | Description                                                                                                          |
| -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                           | [Operations\PublicApiV1AbsenceCalendarShowRequest](../../Models/Operations/PublicApiV1AbsenceCalendarShowRequest.md) | :heavy_check_mark:                                                                                                   | The request object to use for the request.                                                                           |

### Response

**[?Operations\PublicApiV1AbsenceCalendarShowResponse](../../Models/Operations/PublicApiV1AbsenceCalendarShowResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |