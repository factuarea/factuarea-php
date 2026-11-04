# Projects.TimeInvoices

## Overview

### Available Operations

* [publicApiV1ProjectsTimeInvoicesCreate](#publicapiv1projectstimeinvoicescreate) - Invoice project time
* [publicApiV1ProjectsTimeInvoicesPreview](#publicapiv1projectstimeinvoicespreview) - Preview a project time invoice

## publicApiV1ProjectsTimeInvoicesCreate

Turn the selected billable time into a DRAFT invoice for the project billing contact and mark the entries as invoiced.
- Requires `invoices:write`; already invoiced entries return 422 `task_time_entry_invoiced`.
- Resending the same `batch_id` and request returns the same invoice; deleting the draft releases the entries.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.time_invoices.create" method="post" path="/companies/{company}/projects/{project}/time-invoices" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1ProjectsTimeInvoicesCreateRequest(
    company: '6f57620a-5992-486d-a165-63d382c66c05',
    project: '50464952-7fa3-41b5-b5b2-9b35f78649b1',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.time_invoices.create" method="post" path="/companies/{company}/projects/{project}/time-invoices" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1ProjectsTimeInvoicesCreateRequest(
    company: 'Schneider Group',
    project: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.time_invoices.create" method="post" path="/companies/{company}/projects/{project}/time-invoices" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1ProjectsTimeInvoicesCreateRequest(
    company: '903ac27e-8a3f-4f12-951f-0a00bd758159',
    project: '94975f61-a721-4f8c-af22-5a0149931f81',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.time_invoices.create" method="post" path="/companies/{company}/projects/{project}/time-invoices" example="success" -->
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

$request = new Operations\PublicApiV1ProjectsTimeInvoicesCreateRequest(
    company: 'Bartoletti Group',
    project: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.time_invoices.create" method="post" path="/companies/{company}/projects/{project}/time-invoices" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1ProjectsTimeInvoicesCreateRequest(
    company: '583e32b8-0747-45f0-aafe-2a0ce9d7bedb',
    project: 'd8a4902f-d524-4a8c-8d2f-c5b622692b8c',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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

| Error Type                             | Status Code                            | Content Type                           |
| -------------------------------------- | -------------------------------------- | -------------------------------------- |
| Errors\Error                           | 400, 401, 402, 403, 404, 409, 422, 429 | application/json                       |
| Errors\Error                           | 500                                    | application/json                       |
| Errors\APIException                    | 4XX, 5XX                               | \*/\*                                  |

## publicApiV1ProjectsTimeInvoicesPreview

Preview the invoice lines that invoicing the selected time would produce, without creating anything.
- Entries that cannot be invoiced return 422 `task_time_not_invoiceable`.
- Read-only POST: no `Idempotency-Key` needed.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.time_invoices.preview" method="post" path="/companies/{company}/projects/{project}/time-invoices/preview" example="factuarea_version_invalid_format" -->
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

$body = new Components\PreviewTaskTimeInvoiceV1Request(
    grouping: Components\PreviewTaskTimeInvoiceV1RequestGrouping::PerTask,
);

$response = $sdk->projects->timeInvoices->publicApiV1ProjectsTimeInvoicesPreview(
    company: '9e9e66df-bae4-4aaa-9275-7301a5eaee9d',
    project: 'f8ad002f-da8c-4197-a0be-05c708c23f25',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.time_invoices.preview" method="post" path="/companies/{company}/projects/{project}/time-invoices/preview" example="missing_api_key" -->
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

$body = new Components\PreviewTaskTimeInvoiceV1Request(
    grouping: Components\PreviewTaskTimeInvoiceV1RequestGrouping::PerTask,
);

$response = $sdk->projects->timeInvoices->publicApiV1ProjectsTimeInvoicesPreview(
    company: 'Cremin - Wiza',
    project: '<value>',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.time_invoices.preview" method="post" path="/companies/{company}/projects/{project}/time-invoices/preview" example="retired_active_profile_header" -->
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

$body = new Components\PreviewTaskTimeInvoiceV1Request(
    grouping: Components\PreviewTaskTimeInvoiceV1RequestGrouping::PerTask,
);

$response = $sdk->projects->timeInvoices->publicApiV1ProjectsTimeInvoicesPreview(
    company: '05e22ff3-6c3d-4a13-bce4-e02a98b694f6',
    project: '2847b89c-0219-41da-bcff-bbc4eab7dfad',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.time_invoices.preview" method="post" path="/companies/{company}/projects/{project}/time-invoices/preview" example="success" -->
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

$body = new Components\PreviewTaskTimeInvoiceV1Request(
    grouping: Components\PreviewTaskTimeInvoiceV1RequestGrouping::PerTask,
);

$response = $sdk->projects->timeInvoices->publicApiV1ProjectsTimeInvoicesPreview(
    company: 'Hilll - Volkman',
    project: '<value>',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.projects.time_invoices.preview" method="post" path="/companies/{company}/projects/{project}/time-invoices/preview" example="unsupported_api_version" -->
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

$body = new Components\PreviewTaskTimeInvoiceV1Request(
    grouping: Components\PreviewTaskTimeInvoiceV1RequestGrouping::PerTask,
);

$response = $sdk->projects->timeInvoices->publicApiV1ProjectsTimeInvoicesPreview(
    company: 'bf9feb43-7d09-48d1-8ca2-3138e624573b',
    project: 'ba5f4b0c-9c3f-4bd2-bff3-7b2922093bf2',
    body: $body,
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
| `project`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the project.                                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `body`                                                                                                                                                                                                                                                                                                                                                                       | [Components\PreviewTaskTimeInvoiceV1Request](../../Models/Components/PreviewTaskTimeInvoiceV1Request.md)                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | N/A                                                                                                                                                                                                                                                                                                                                                                          |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1ProjectsTimeInvoicesPreviewResponse](../../Models/Operations/PublicApiV1ProjectsTimeInvoicesPreviewResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |