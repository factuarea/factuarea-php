# Projects.TimeInvoices

## Overview

### Available Operations

* [publicApiV1ProjectsTimeInvoicesCreate](#publicapiv1projectstimeinvoicescreate) - Invoice project time
* [publicApiV1ProjectsTimeInvoicesPreview](#publicapiv1projectstimeinvoicespreview) - Preview a project time invoice

## publicApiV1ProjectsTimeInvoicesCreate

Turn the selected billable time into a DRAFT invoice for the project billing contact, grouped by `grouping`, and mark those entries as invoiced. Requires the `invoices:write` scope (it creates an invoice), not a projects scope. The draft is numbered when issued from the invoice API. Entries already invoiced return 422 `task_time_entry_invoiced`; if the plan has used up its invoices or yearly documents, 402 `limit_exceeded` and nothing is created. Sending the same `batch_id` again with the same request returns the same invoice; reusing a `batch_id` with a different project selection, grouping or entries (or after its invoice was deleted) returns 409 `idempotency_key_reused`. Deleting the draft invoice releases the entries.

### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.time_invoices.create" method="post" path="/projects/{project}/time-invoices" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1ProjectsTimeInvoicesCreateRequest(
    project: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\InvoiceTaskTimeV1Request(
        grouping: Components\InvoiceTaskTimeV1RequestGrouping::PerEntry,
    ),
);

$response = $sdk->projects->timeInvoices->publicApiV1ProjectsTimeInvoicesCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.time_invoices.create" method="post" path="/projects/{project}/time-invoices" example="success" -->
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

$request = new Operations\PublicApiV1ProjectsTimeInvoicesCreateRequest(
    project: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\InvoiceTaskTimeV1Request(
        grouping: Components\InvoiceTaskTimeV1RequestGrouping::PerEntry,
    ),
);

$response = $sdk->projects->timeInvoices->publicApiV1ProjectsTimeInvoicesCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                          | Type                                                                                                                               | Required                                                                                                                           | Description                                                                                                                        |
| ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                         | [Operations\PublicApiV1ProjectsTimeInvoicesCreateRequest](../../Models/Operations/PublicApiV1ProjectsTimeInvoicesCreateRequest.md) | :heavy_check_mark:                                                                                                                 | The request object to use for the request.                                                                                         |

### Response

**[?Operations\PublicApiV1ProjectsTimeInvoicesCreateResponse](../../Models/Operations/PublicApiV1ProjectsTimeInvoicesCreateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 401, 402, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1ProjectsTimeInvoicesPreview

Preview the invoice lines that invoicing the selected time would produce, without creating anything: select either `entry_ids` or a `period`, and a `grouping`. Entries that cannot be invoiced (not billable, already invoiced, without a price or whose invoice line amounts would exceed the invoice limits) return 422 `task_time_not_invoiceable`. A read-only POST: it does not need an `Idempotency-Key`.

### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.time_invoices.preview" method="post" path="/projects/{project}/time-invoices/preview" example="missing_api_key" -->
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

$body = new Components\PreviewTaskTimeInvoiceV1Request(
    grouping: Components\PreviewTaskTimeInvoiceV1RequestGrouping::PerTask,
);

$response = $sdk->projects->timeInvoices->publicApiV1ProjectsTimeInvoicesPreview(
    project: '<value>',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.time_invoices.preview" method="post" path="/projects/{project}/time-invoices/preview" example="success" -->
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

$body = new Components\PreviewTaskTimeInvoiceV1Request(
    grouping: Components\PreviewTaskTimeInvoiceV1RequestGrouping::PerTask,
);

$response = $sdk->projects->timeInvoices->publicApiV1ProjectsTimeInvoicesPreview(
    project: '<value>',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                        | Type                                                                                                                                                                                                                                                                                                                                                                                             | Required                                                                                                                                                                                                                                                                                                                                                                                         | Description                                                                                                                                                                                                                                                                                                                                                                                      | Example                                                                                                                                                                                                                                                                                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `project`                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `body`                                                                                                                                                                                                                                                                                                                                                                                           | [Components\PreviewTaskTimeInvoiceV1Request](../../Models/Components/PreviewTaskTimeInvoiceV1Request.md)                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1ProjectsTimeInvoicesPreviewResponse](../../Models/Operations/PublicApiV1ProjectsTimeInvoicesPreviewResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |