# Agenda

## Overview

### Available Operations

* [publicApiV1AgendaList](#publicapiv1agendalist) - List agenda items

## publicApiV1AgendaList

Return a combined agenda between `from` and `to` (at most 93 days): due tasks plus due dates, expiries, tax deadlines, absences and holidays.
- `sources` picks the layers; each needs its own read scope, and unreadable layers are left out without error.
- The response `sources` lists the layers served; `assignee_id` filters tasks; one page.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.agenda.list" method="get" path="/companies/{company}/agenda" example="success" -->
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

$request = new Operations\PublicApiV1AgendaListRequest(
    company: 'Crona, Hermiston and Nader',
    from: LocalDate::parse('2026-07-05'),
    to: LocalDate::parse('2024-06-22'),
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->agenda->publicApiV1AgendaList(
    request: $request
);

if ($response->agendaList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                          | Type                                                                                               | Required                                                                                           | Description                                                                                        |
| -------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------- |
| `$request`                                                                                         | [Operations\PublicApiV1AgendaListRequest](../../Models/Operations/PublicApiV1AgendaListRequest.md) | :heavy_check_mark:                                                                                 | The request object to use for the request.                                                         |

### Response

**[?Operations\PublicApiV1AgendaListResponse](../../Models/Operations/PublicApiV1AgendaListResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |