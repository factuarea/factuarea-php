# Agenda

## Overview

### Available Operations

* [publicApiV1AgendaList](#publicapiv1agendalist) - List agenda items

## publicApiV1AgendaList

Return a combined agenda between `from` and `to` (at most 93 days, both included): due tasks and, from other modules, invoice and purchase invoice due dates, quote and pro forma expiries, recurring invoice runs, tax deadlines, absences and holidays. `sources` chooses the layers. Every layer also needs the read scope of its own resource and its module (for example `invoices:read` for invoice due dates, `absences:read` for absences); layers the key cannot read are left out without an error, and `sources` in the response lists the layers actually served. `assignee_id` (`me` or a member id) filters the task layer. The whole window is returned in one page.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.agenda.list" method="get" path="/agenda" example="success" -->
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

$request = new Operations\PublicApiV1AgendaListRequest(
    from: LocalDate::parse('2026-07-05'),
    to: LocalDate::parse('2024-06-22'),
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
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

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |