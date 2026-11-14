# Invoices

## Overview

### Available Operations

* [publicApiV1InvoicesAnnul](#publicapiv1invoicesannul) - Annul an invoice
* [publicApiV1InvoicesAssignPresentation](#publicapiv1invoicesassignpresentation) - Assign the invoice visual template
* [publicApiV1InvoicesGetPresentation](#publicapiv1invoicesgetpresentation) - Get the invoice presentation
* [publicApiV1InvoicesAssignRealNumber](#publicapiv1invoicesassignrealnumber) - Assign a real invoice number
* [publicApiV1InvoicesBulkCreate](#publicapiv1invoicesbulkcreate) - Bulk create invoices
* [publicApiV1InvoicesBulkDelete](#publicapiv1invoicesbulkdelete) - Bulk delete invoices
* [publicApiV1InvoicesBulkPdf](#publicapiv1invoicesbulkpdf) - Bulk download invoice PDFs
* [publicApiV1InvoicesBulkSend](#publicapiv1invoicesbulksend) - Bulk send invoices
* [publicApiV1InvoicesBulkStatus](#publicapiv1invoicesbulkstatus) - Bulk change invoice status
* [publicApiV1InvoicesCanAnnul](#publicapiv1invoicescanannul) - Check annulment eligibility
* [publicApiV1InvoicesSimplifiedEligibility](#publicapiv1invoicessimplifiedeligibility) - Check simplified invoice eligibility
* [publicApiV1InvoicesCorrective](#publicapiv1invoicescorrective) - Generate corrective invoice
* [publicApiV1InvoicesCreate](#publicapiv1invoicescreate) - Create an invoice
* [publicApiV1InvoicesList](#publicapiv1invoiceslist) - List all invoices
* [publicApiV1InvoicesVerifactuCreate](#publicapiv1invoicesverifactucreate) - Force-create VeriFactu record for invoice
* [publicApiV1InvoicesVerifactuGet](#publicapiv1invoicesverifactuget) - Retrieve invoice VeriFactu record
* [publicApiV1InvoicesCreateRecurring](#publicapiv1invoicescreaterecurring) - Create a recurring invoice from an invoice
* [publicApiV1InvoicesDelete](#publicapiv1invoicesdelete) - Delete an invoice
* [publicApiV1InvoicesShow](#publicapiv1invoicesshow) - Retrieve an invoice
* [publicApiV1InvoicesUpdate](#publicapiv1invoicesupdate) - Update an invoice
* [publicApiV1InvoicesFacturae](#publicapiv1invoicesfacturae) - Download current or retained Facturae XML
* [publicApiV1InvoicesPdf](#publicapiv1invoicespdf) - Download invoice PDF
* [publicApiV1InvoicesDuplicate](#publicapiv1invoicesduplicate) - Duplicate an invoice
* [publicApiV1InvoicesExportExcel](#publicapiv1invoicesexportexcel) - Export invoices to a spreadsheet
* [publicApiV1InvoicesFindByExternalId](#publicapiv1invoicesfindbyexternalid) - Find an invoice by external ID
* [publicApiV1InvoicesFindByNumber](#publicapiv1invoicesfindbynumber) - Find an invoice by number
* [publicApiV1InvoicesPdfLink](#publicapiv1invoicespdflink) - Generate temporary PDF link
* [publicApiV1InvoicesPublicLinkGet](#publicapiv1invoicespubliclinkget) - Retrieve invoice public link
* [publicApiV1InvoicesPublicLinkUpdate](#publicapiv1invoicespubliclinkupdate) - Update invoice public link
* [publicApiV1InvoicesStats](#publicapiv1invoicesstats) - Get invoice statistics
* [publicApiV1InvoicesIssue](#publicapiv1invoicesissue) - Issue an invoice
* [publicApiV1InvoicesListPresentations](#publicapiv1invoiceslistpresentations) - List retained invoice presentations
* [publicApiV1InvoicesActivities](#publicapiv1invoicesactivities) - List invoice activity
* [publicApiV1InvoicesCorrectives](#publicapiv1invoicescorrectives) - List corrective invoices
* [publicApiV1InvoicesPaymentsList](#publicapiv1invoicespaymentslist) - List invoice payments
* [publicApiV1InvoicesPaymentsCreate](#publicapiv1invoicespaymentscreate) - Register a payment
* [publicApiV1InvoicesStatuses](#publicapiv1invoicesstatuses) - List invoice statuses
* [publicApiV1InvoicesMarkPaid](#publicapiv1invoicesmarkpaid) - Mark invoice as paid
* [publicApiV1InvoicesMarkSent](#publicapiv1invoicesmarksent) - Mark an invoice as sent
* [publicApiV1InvoicesPdfPreview](#publicapiv1invoicespdfpreview) - Preview an invoice draft PDF
* [publicApiV1InvoicesReminderPreview](#publicapiv1invoicesreminderpreview) - Preview a payment reminder email
* [publicApiV1InvoicesPaymentReceipt](#publicapiv1invoicespaymentreceipt) - Download the receipt PDF of a payment
* [publicApiV1InvoicesReschedule](#publicapiv1invoicesreschedule) - Reschedule an invoice
* [publicApiV1InvoicesPaymentsRevert](#publicapiv1invoicespaymentsrevert) - Revert an invoice payment
* [publicApiV1InvoicesRevisePresentation](#publicapiv1invoicesrevisepresentation) - Create an invoice presentation revision
* [publicApiV1InvoicesSchedule](#publicapiv1invoicesschedule) - Schedule an invoice
* [publicApiV1InvoicesSend](#publicapiv1invoicessend) - Send invoice by email
* [publicApiV1InvoicesSendReminder](#publicapiv1invoicessendreminder) - Send a payment reminder
* [publicApiV1InvoicesSubstituteSimplified](#publicapiv1invoicessubstitutesimplified) - Substitute simplified invoices with full invoice
* [publicApiV1InvoicesUnschedule](#publicapiv1invoicesunschedule) - Unschedule an invoice
* [publicApiV1InvoicesUnsend](#publicapiv1invoicesunsend) - Unsend an invoice
* [publicApiV1InvoicesVoid](#publicapiv1invoicesvoid) - Void an invoice

## publicApiV1InvoicesAnnul

Withdraw an issued invoice with a required `reason` (3-500 chars); `void` does the same.
- Irreversible: status `annulled`, number never reused.
- VeriFactu cancellation to AEAT is asynchronous.

**Signing certificate (NO VERI*FACTU).** Only in that mode, a missing or invalid company certificate is rejected before the invoice is annulled: 422 `verifactu_not_eligible`, `error.subcode: signing_certificate_unavailable`; nothing is consumed, so fix it and repeat the call.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.annul" method="post" path="/companies/{company}/invoices/{invoice}/annul" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1InvoicesAnnulRequest(
    company: 'b7293eda-1bef-43e4-acd4-d9da19a51d1a',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AnnulInvoiceV1Request(
        reason: '<value>',
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesAnnul(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.annul" method="post" path="/companies/{company}/invoices/{invoice}/annul" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1InvoicesAnnulRequest(
    company: '88bdbf25-c474-4f47-8f04-6c99d1e95ca9',
    invoice: '1fe2174a-cc2e-4329-827b-fdbf5b35d945',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AnnulInvoiceV1Request(
        reason: '<value>',
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesAnnul(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.annul" method="post" path="/companies/{company}/invoices/{invoice}/annul" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1InvoicesAnnulRequest(
    company: 'dae8895a-643c-4c90-9fbd-976930390137',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AnnulInvoiceV1Request(
        reason: '<value>',
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesAnnul(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.annul" method="post" path="/companies/{company}/invoices/{invoice}/annul" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1InvoicesAnnulRequest(
    company: 'Blanda LLC',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AnnulInvoiceV1Request(
        reason: '<value>',
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesAnnul(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.annul" method="post" path="/companies/{company}/invoices/{invoice}/annul" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1InvoicesAnnulRequest(
    company: 'b7293eda-1bef-43e4-acd4-d9da19a51d1a',
    invoice: 'dae8895a-643c-4c90-9fbd-976930390137',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AnnulInvoiceV1Request(
        reason: '<value>',
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesAnnul(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.annul" method="post" path="/companies/{company}/invoices/{invoice}/annul" example="success" -->
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

$request = new Operations\PublicApiV1InvoicesAnnulRequest(
    company: 'Upton - Steuber',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AnnulInvoiceV1Request(
        reason: '<value>',
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesAnnul(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.annul" method="post" path="/companies/{company}/invoices/{invoice}/annul" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1InvoicesAnnulRequest(
    company: 'ffd574bb-b459-4896-8046-94c5434f6f58',
    invoice: 'dae4f5cc-a01d-453b-99b1-0a7621b8cd29',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AnnulInvoiceV1Request(
        reason: '<value>',
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesAnnul(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                | Type                                                                                                     | Required                                                                                                 | Description                                                                                              |
| -------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                               | [Operations\PublicApiV1InvoicesAnnulRequest](../../Models/Operations/PublicApiV1InvoicesAnnulRequest.md) | :heavy_check_mark:                                                                                       | The request object to use for the request.                                                               |

### Response

**[?Operations\PublicApiV1InvoicesAnnulResponse](../../Models/Operations/PublicApiV1InvoicesAnnulResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1InvoicesAssignPresentation

Set an explicit tenant template or null inheritance without changing numbering, totals, references, payment terms or the existing capture. Supply the visual selection version and/or the strong validator from this exact presentation URI. A successful replay preserves the original result; current actor authority is still required.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.assign_presentation" method="patch" path="/companies/{company}/invoices/{invoice}/presentation" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1InvoicesAssignPresentationRequest(
    company: '55ab1950-94d5-47cb-80dc-405c4be01d6c',
    invoice: 'f28f1c5b-3ecb-44f8-9460-4d9d8649861f',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AssignDocumentPresentationRequest(
        templateId: null,
        expectedVersion: 1,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesAssignPresentation(
    request: $request
);

if ($response->documentPresentationStateEnvelope !== null) {
    // handle response
}
```
### Example Usage: inherit

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.assign_presentation" method="patch" path="/companies/{company}/invoices/{invoice}/presentation" example="inherit" -->
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

$request = new Operations\PublicApiV1InvoicesAssignPresentationRequest(
    company: '37647b72-70b6-40c6-93b5-57fc3e4337bb',
    invoice: 'f6ad863b-793c-4487-8989-55506e108c7a',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AssignDocumentPresentationRequest(
        templateId: null,
        expectedVersion: 1,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesAssignPresentation(
    request: $request
);

if ($response->documentPresentationStateEnvelope !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.assign_presentation" method="patch" path="/companies/{company}/invoices/{invoice}/presentation" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1InvoicesAssignPresentationRequest(
    company: 'f116aac9-929e-4f44-bb68-aedd620da264',
    invoice: '0ae4437f-8d97-43c5-a61c-be44d4b77433',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AssignDocumentPresentationRequest(
        templateId: null,
        expectedVersion: 1,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesAssignPresentation(
    request: $request
);

if ($response->documentPresentationStateEnvelope !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.assign_presentation" method="patch" path="/companies/{company}/invoices/{invoice}/presentation" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1InvoicesAssignPresentationRequest(
    company: '9cb6eaff-79b9-4e06-99dc-5af720cea3ec',
    invoice: '0c412546-5517-4233-b8a2-370e8488d6f9',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\AssignDocumentPresentationRequest(
        templateId: null,
        expectedVersion: 1,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesAssignPresentation(
    request: $request
);

if ($response->documentPresentationStateEnvelope !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                          | Type                                                                                                                               | Required                                                                                                                           | Description                                                                                                                        |
| ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                         | [Operations\PublicApiV1InvoicesAssignPresentationRequest](../../Models/Operations/PublicApiV1InvoicesAssignPresentationRequest.md) | :heavy_check_mark:                                                                                                                 | The request object to use for the request.                                                                                         |

### Response

**[?Operations\PublicApiV1InvoicesAssignPresentationResponse](../../Models/Operations/PublicApiV1InvoicesAssignPresentationResponse.md)**

### Errors

| Error Type                                  | Status Code                                 | Content Type                                |
| ------------------------------------------- | ------------------------------------------- | ------------------------------------------- |
| Errors\Error                                | 400, 401, 403, 404, 409, 412, 422, 428, 429 | application/json                            |
| Errors\Error                                | 500                                         | application/json                            |
| Errors\APIException                         | 4XX, 5XX                                    | \*/\*                                       |

## publicApiV1InvoicesGetPresentation

Inspect the current visual selection and retained capture independently. Returns a virtual state before the first visual action. Reads never initialize templates, capture, publish, render or refresh legal content.

### Example Usage: legal_source_changed

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.get_presentation" method="get" path="/companies/{company}/invoices/{invoice}/presentation" example="legal_source_changed" -->
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



$response = $sdk->invoices->publicApiV1InvoicesGetPresentation(
    company: 'd4c55ee9-5061-4fca-9443-00befa3502a0',
    invoice: '50b4e5f9-e0cb-48c5-a7c8-264043d10240',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->documentPresentationStateEnvelope !== null) {
    // handle response
}
```
### Example Usage: retained_capture

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.get_presentation" method="get" path="/companies/{company}/invoices/{invoice}/presentation" example="retained_capture" -->
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



$response = $sdk->invoices->publicApiV1InvoicesGetPresentation(
    company: 'a858755e-a2a7-4ec9-9520-adaf76a6e155',
    invoice: '6be96059-d1e9-441c-ac1c-7adf71c942d8',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->documentPresentationStateEnvelope !== null) {
    // handle response
}
```
### Example Usage: series_candidate_requires_confirmation

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.get_presentation" method="get" path="/companies/{company}/invoices/{invoice}/presentation" example="series_candidate_requires_confirmation" -->
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



$response = $sdk->invoices->publicApiV1InvoicesGetPresentation(
    company: '54e5ea60-52f1-44d5-86b8-167f0799fbfe',
    invoice: '063b4a69-7fa4-4992-8aa8-85e42d81584f',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->documentPresentationStateEnvelope !== null) {
    // handle response
}
```
### Example Usage: virtual_legacy_head

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.get_presentation" method="get" path="/companies/{company}/invoices/{invoice}/presentation" example="virtual_legacy_head" -->
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



$response = $sdk->invoices->publicApiV1InvoicesGetPresentation(
    company: '9cf893ad-2cc6-461b-8330-20045008cf4a',
    invoice: 'dc230cda-a8d2-463d-8ed0-752faae89111',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->documentPresentationStateEnvelope !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `invoice`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the invoice.                                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1InvoicesGetPresentationResponse](../../Models/Operations/PublicApiV1InvoicesGetPresentationResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1InvoicesAssignRealNumber

Promotes a draft to a definitive invoice by assigning its real series number. In VeriFactu-enabled companies the same happens automatically on send.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.assign_real_number" method="post" path="/companies/{company}/invoices/{invoice}/assign-real-number" example="success" -->
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



$response = $sdk->invoices->publicApiV1InvoicesAssignRealNumber(
    company: 'Douglas - Rowe',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `invoice`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the invoice.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1InvoicesAssignRealNumberResponse](../../Models/Operations/PublicApiV1InvoicesAssignRealNumberResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1InvoicesBulkCreate

Create up to 100 invoices in one call, each a full invoice payload; returns a `BulkCreateResult`.
- `dry_run=true` validates every row without persisting and classifies each in `results[]`.
- `dry_run=false` creates only the valid rows and reports the rest in `failures[]`.
- Rows may carry `payment_terms`; bulk never upserts, and `invalid_invoice_payment_terms` is reported per row.
- Rows are drafts, never issued here: `issued_on` is the draft date, and an issued invoice takes the date of the day of issue.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_create" method="post" path="/companies/{company}/invoices/bulk-create" example="api_key_revoked" -->
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

$body = new Components\BulkCreateInvoicesV1Request(
    invoices: [],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkCreate(
    company: '3cd8f73d-dcc1-4f70-8e27-c6cd7316e5f8',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: default_example

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_create" method="post" path="/companies/{company}/invoices/bulk-create" example="default_example" -->
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

$body = new Components\BulkCreateInvoicesV1Request(
    invoices: [
        new Components\BulkCreateInvoicesV1RequestCreateInvoiceRequest(
            lines: [
                new Components\BulkCreateInvoicesV1RequestLine(
                    description: 'Servicio de consultoria',
                    quantity: 2,
                    unitPrice: 150,
                    taxRateId: '019e5584-7a7f-70fd-b9cb-9466c3ee12a8',
                ),
            ],
            clientId: 'f58fbf7f-333f-499b-affc-c0d396cbd83f',
            seriesId: '019e5584-7a72-7038-a8f6-561ed180b699',
            issuedOn: LocalDate::parse('2026-06-01'),
            dueOn: LocalDate::parse('2026-07-01'),
            externalId: 'ERP-2026-0042',
        ),
        new Components\BulkCreateInvoicesV1RequestCreateInvoiceRequest(
            lines: [
                new Components\BulkCreateInvoicesV1RequestLine(
                    description: 'Mantenimiento mensual',
                    quantity: 1,
                    unitPrice: 320.5,
                    taxRateId: '019e5584-7a7f-70fd-b9cb-9466c3ee12a8',
                ),
            ],
            clientId: '019e5584-7a6d-7239-9a1e-1d32be7a8c44',
            seriesId: '019e5584-7a72-7038-a8f6-561ed180b699',
            issuedOn: LocalDate::parse('2026-06-01'),
            dueOn: LocalDate::parse('2026-07-01'),
            externalId: 'ERP-2026-0043',
        ),
    ],
    dryRun: false,
);

$response = $sdk->invoices->publicApiV1InvoicesBulkCreate(
    company: 'b6006f7a-236f-478f-8de0-2f8fd4708c3e',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: dry_run

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_create" method="post" path="/companies/{company}/invoices/bulk-create" example="dry_run" -->
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

$body = new Components\BulkCreateInvoicesV1Request(
    invoices: [],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkCreate(
    company: 'Schuppe - Stanton',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_create" method="post" path="/companies/{company}/invoices/bulk-create" example="factuarea_version_invalid_format" -->
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

$body = new Components\BulkCreateInvoicesV1Request(
    invoices: [],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkCreate(
    company: '934c7e7f-3e06-49c0-b3d7-5f9218af42de',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_create" method="post" path="/companies/{company}/invoices/bulk-create" example="invalid_api_key" -->
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

$body = new Components\BulkCreateInvoicesV1Request(
    invoices: [],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkCreate(
    company: '134023b6-006f-47a2-936f-78fde02f8fd4',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_payment_terms_create

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_create" method="post" path="/companies/{company}/invoices/bulk-create" example="invalid_payment_terms_create" -->
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

$body = new Components\BulkCreateInvoicesV1Request(
    invoices: [],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkCreate(
    company: 'ac10ee26-7016-4863-8507-202012426e5f',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_payment_terms_dry_run

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_create" method="post" path="/companies/{company}/invoices/bulk-create" example="invalid_payment_terms_dry_run" -->
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

$body = new Components\BulkCreateInvoicesV1Request(
    invoices: [],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkCreate(
    company: '07fac90f-5a9b-4bbd-8cd0-5e7d9b7b93a8',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_create" method="post" path="/companies/{company}/invoices/bulk-create" example="missing_api_key" -->
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

$body = new Components\BulkCreateInvoicesV1Request(
    invoices: [],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkCreate(
    company: 'Ruecker, Breitenberg and Zemlak',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_create" method="post" path="/companies/{company}/invoices/bulk-create" example="retired_active_profile_header" -->
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

$body = new Components\BulkCreateInvoicesV1Request(
    invoices: [],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkCreate(
    company: '18069030-d611-4c34-8b85-87646c4585cd',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: row_tax_rate_mismatch

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_create" method="post" path="/companies/{company}/invoices/bulk-create" example="row_tax_rate_mismatch" -->
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

$body = new Components\BulkCreateInvoicesV1Request(
    invoices: [],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkCreate(
    company: '381a5f10-ba2c-4886-9cad-bcd94b798cff',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_create" method="post" path="/companies/{company}/invoices/bulk-create" example="success" -->
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

$body = new Components\BulkCreateInvoicesV1Request(
    invoices: [],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkCreate(
    company: 'Wisozk Inc',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: three_instalments_per_row

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_create" method="post" path="/companies/{company}/invoices/bulk-create" example="three_instalments_per_row" -->
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

$body = new Components\BulkCreateInvoicesV1Request(
    invoices: [
        new Components\BulkCreateInvoicesV1RequestCreateInvoiceRequest(
            lines: [
                new Components\BulkCreateInvoicesV1RequestLine(
                    description: 'Servicio de consultoria',
                    quantity: 2,
                    unitPrice: 150,
                    taxRateId: '019e5584-7a7f-70fd-b9cb-9466c3ee12a8',
                ),
            ],
            paymentTerms: [
                new Components\InvoiceInstalmentInput(
                    dueDate: LocalDate::parse('2026-02-15'),
                    amount: '40.00',
                    paymentMeans: Components\InvoiceInstalmentInputPaymentMeans::One,
                    account: null,
                ),
                new Components\InvoiceInstalmentInput(
                    dueDate: LocalDate::parse('2026-03-15'),
                    amount: '30.00',
                    paymentMeans: Components\InvoiceInstalmentInputPaymentMeans::Two,
                    account: new Components\InvoicePaymentAccountInput(
                        iban: 'GB82WEST12345698765432',
                        bic: 'NWBKGB2L',
                    ),
                ),
                new Components\InvoiceInstalmentInput(
                    dueDate: LocalDate::parse('2026-04-15'),
                    amount: '51.00',
                    paymentMeans: Components\InvoiceInstalmentInputPaymentMeans::Four,
                    account: new Components\InvoicePaymentAccountInput(
                        iban: 'ES9121000418450200051332',
                        bic: 'CAIXESBBXXX',
                    ),
                ),
            ],
            clientId: 'f58fbf7f-333f-499b-affc-c0d396cbd83f',
            seriesId: '019e5584-7a72-7038-a8f6-561ed180b699',
            issuedOn: LocalDate::parse('2026-06-01'),
            dueOn: LocalDate::parse('2026-04-15'),
            externalId: 'ERP-2026-0042',
        ),
    ],
    dryRun: true,
);

$response = $sdk->invoices->publicApiV1InvoicesBulkCreate(
    company: '3cd8f73d-dcc1-4f70-8e27-c6cd7316e5f8',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_create" method="post" path="/companies/{company}/invoices/bulk-create" example="unsupported_api_version" -->
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

$body = new Components\BulkCreateInvoicesV1Request(
    invoices: [],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkCreate(
    company: 'fa3018b6-3c46-4395-84e1-219a9283734d',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Components\BulkCreateInvoicesV1Request](../../Models/Components/BulkCreateInvoicesV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1InvoicesBulkCreateResponse](../../Models/Operations/PublicApiV1InvoicesBulkCreateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1InvoicesBulkDelete

Delete up to 100 draft invoices (`ids`, UUID v7) with partial success; one failure never aborts the batch.
- An issued invoice fails with `resource_not_deletable`; use `void` instead.
- Returns a `BulkPartialSuccessResult` with `total`, `successful`, `failed` and `failures`.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_delete" method="post" path="/companies/{company}/invoices/bulk-delete" example="api_key_revoked" -->
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

$body = new Components\BulkDeleteInvoicesV1Request(
    ids: [
        '4b0b7709-e2c0-4f04-9b63-2e7d196a58f7',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkDelete(
    company: '54b0b770-9e2c-40f0-94b6-32e7d196a58f',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: bulk_delete_ids

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_delete" method="post" path="/companies/{company}/invoices/bulk-delete" example="bulk_delete_ids" -->
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

$body = new Components\BulkDeleteInvoicesV1Request(
    ids: [
        '019e5584-7c1b-7260-bc52-34613ad04c09',
        '019e5584-7c1c-71b6-b825-01f5443bae47',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkDelete(
    company: 'Goyette LLC',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_delete" method="post" path="/companies/{company}/invoices/bulk-delete" example="factuarea_version_invalid_format" -->
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

$body = new Components\BulkDeleteInvoicesV1Request(
    ids: [
        '4b0b7709-e2c0-4f04-9b63-2e7d196a58f7',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkDelete(
    company: '5c614f6a-45c0-4ac4-8b36-819203b46cc6',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_delete" method="post" path="/companies/{company}/invoices/bulk-delete" example="invalid_api_key" -->
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

$body = new Components\BulkDeleteInvoicesV1Request(
    ids: [
        '4b0b7709-e2c0-4f04-9b63-2e7d196a58f7',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkDelete(
    company: '5c614f6a-45c0-4ac4-8b36-819203b46cc6',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_delete" method="post" path="/companies/{company}/invoices/bulk-delete" example="missing_api_key" -->
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

$body = new Components\BulkDeleteInvoicesV1Request(
    ids: [
        '4b0b7709-e2c0-4f04-9b63-2e7d196a58f7',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkDelete(
    company: 'Becker LLC',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_delete" method="post" path="/companies/{company}/invoices/bulk-delete" example="retired_active_profile_header" -->
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

$body = new Components\BulkDeleteInvoicesV1Request(
    ids: [
        '4b0b7709-e2c0-4f04-9b63-2e7d196a58f7',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkDelete(
    company: '54b0b770-9e2c-40f0-94b6-32e7d196a58f',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_delete" method="post" path="/companies/{company}/invoices/bulk-delete" example="success" -->
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

$body = new Components\BulkDeleteInvoicesV1Request(
    ids: [
        '4b0b7709-e2c0-4f04-9b63-2e7d196a58f7',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkDelete(
    company: 'Rodriguez - Kulas',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_delete" method="post" path="/companies/{company}/invoices/bulk-delete" example="unsupported_api_version" -->
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

$body = new Components\BulkDeleteInvoicesV1Request(
    ids: [
        '4b0b7709-e2c0-4f04-9b63-2e7d196a58f7',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkDelete(
    company: '5ea014a1-52fd-4239-883e-09c738d44f4c',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Components\BulkDeleteInvoicesV1Request](../../Models/Components/BulkDeleteInvoicesV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1InvoicesBulkDeleteResponse](../../Models/Operations/PublicApiV1InvoicesBulkDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1InvoicesBulkPdf

Packages the PDFs of up to 50 invoices (by id) into a single ZIP. Ids that are not found or have no generable PDF do not abort the request: the ZIP carries only the valid ones and the per-resource counts travel in the `X-Bulk-*` response headers.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_pdf" method="post" path="/companies/{company}/invoices/bulk-pdf" example="api_key_revoked" -->
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

$body = new Components\BulkPdfInvoicesV1Request(
    ids: [
        'fce2bffa-ccbb-46ea-aedc-2255002fbfe7',
        '4a4441bd-c5d3-4482-9e5e-405f63d94e25',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkPdf(
    company: '8fce2bff-accb-4b6e-9aed-c2255002fbfe',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->bytes !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_pdf" method="post" path="/companies/{company}/invoices/bulk-pdf" example="factuarea_version_invalid_format" -->
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

$body = new Components\BulkPdfInvoicesV1Request(
    ids: [
        'fce2bffa-ccbb-46ea-aedc-2255002fbfe7',
        '4a4441bd-c5d3-4482-9e5e-405f63d94e25',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkPdf(
    company: '13b70ca4-d92d-4d53-ae30-20fc7994fd4a',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->bytes !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_pdf" method="post" path="/companies/{company}/invoices/bulk-pdf" example="invalid_api_key" -->
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

$body = new Components\BulkPdfInvoicesV1Request(
    ids: [
        'fce2bffa-ccbb-46ea-aedc-2255002fbfe7',
        '4a4441bd-c5d3-4482-9e5e-405f63d94e25',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkPdf(
    company: 'b4a4441b-dc5d-4348-92e5-e405f63d94e2',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->bytes !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_pdf" method="post" path="/companies/{company}/invoices/bulk-pdf" example="missing_api_key" -->
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

$body = new Components\BulkPdfInvoicesV1Request(
    ids: [
        'fce2bffa-ccbb-46ea-aedc-2255002fbfe7',
        '4a4441bd-c5d3-4482-9e5e-405f63d94e25',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkPdf(
    company: 'Williamson and Sons',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->bytes !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_pdf" method="post" path="/companies/{company}/invoices/bulk-pdf" example="retired_active_profile_header" -->
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

$body = new Components\BulkPdfInvoicesV1Request(
    ids: [
        'fce2bffa-ccbb-46ea-aedc-2255002fbfe7',
        '4a4441bd-c5d3-4482-9e5e-405f63d94e25',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkPdf(
    company: 'c5ea8e8d-f876-47ef-baa1-db75adbc80fe',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->bytes !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_pdf" method="post" path="/companies/{company}/invoices/bulk-pdf" example="unsupported_api_version" -->
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

$body = new Components\BulkPdfInvoicesV1Request(
    ids: [
        'fce2bffa-ccbb-46ea-aedc-2255002fbfe7',
        '4a4441bd-c5d3-4482-9e5e-405f63d94e25',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkPdf(
    company: 'd89bc588-7661-4a44-be45-cc1b18c13fd5',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->bytes !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Components\BulkPdfInvoicesV1Request](../../Models/Components/BulkPdfInvoicesV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1InvoicesBulkPdfResponse](../../Models/Operations/PublicApiV1InvoicesBulkPdfResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 413, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1InvoicesBulkSend

Sends up to 200 invoices by email (queued) in one call, reusing the single-send path per id. Returns a `BulkPartialSuccessResult` with `total`, `successful`, `failed` and a `failures` list (`id` + `error_code` + Spanish `error_message`) for each invoice not sent (not found, terminal status, no resolvable recipient, or a draft the series date order rejects: `business_rule_violation`). Drafts are issued first, oldest first, not in `ids` order, dated the day of issue; with revisioned presentation disabled, that rejection aborts the call with 422 `issue_date_before_series_last_issue_date`.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_send" method="post" path="/companies/{company}/invoices/bulk-send" example="api_key_revoked" -->
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

$body = new Components\BulkSendInvoicesV1Request(
    ids: [
        '8eafd323-ce40-4e4e-90d1-54fcb534b509',
        '096cc252-19e9-4eae-872f-21c9954301b2',
        'fd36c089-a475-4c6a-92d8-536c6f15dbb7',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkSend(
    company: 'f8eafd32-3ce4-40e4-ae0d-154fcb534b50',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_send" method="post" path="/companies/{company}/invoices/bulk-send" example="factuarea_version_invalid_format" -->
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

$body = new Components\BulkSendInvoicesV1Request(
    ids: [
        '8eafd323-ce40-4e4e-90d1-54fcb534b509',
        '096cc252-19e9-4eae-872f-21c9954301b2',
        'fd36c089-a475-4c6a-92d8-536c6f15dbb7',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkSend(
    company: '2fd36c08-9a47-45c6-9a2d-8536c6f15dbb',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_send" method="post" path="/companies/{company}/invoices/bulk-send" example="invalid_api_key" -->
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

$body = new Components\BulkSendInvoicesV1Request(
    ids: [
        '8eafd323-ce40-4e4e-90d1-54fcb534b509',
        '096cc252-19e9-4eae-872f-21c9954301b2',
        'fd36c089-a475-4c6a-92d8-536c6f15dbb7',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkSend(
    company: '6096cc25-219e-49ea-8e72-f21c9954301b',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_send" method="post" path="/companies/{company}/invoices/bulk-send" example="missing_api_key" -->
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

$body = new Components\BulkSendInvoicesV1Request(
    ids: [
        '8eafd323-ce40-4e4e-90d1-54fcb534b509',
        '096cc252-19e9-4eae-872f-21c9954301b2',
        'fd36c089-a475-4c6a-92d8-536c6f15dbb7',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkSend(
    company: 'Skiles, Doyle and Corkery',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: pdf_engine_unreachable_row

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_send" method="post" path="/companies/{company}/invoices/bulk-send" example="pdf_engine_unreachable_row" -->
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

$body = new Components\BulkSendInvoicesV1Request(
    ids: [
        '8eafd323-ce40-4e4e-90d1-54fcb534b509',
        '096cc252-19e9-4eae-872f-21c9954301b2',
        'fd36c089-a475-4c6a-92d8-536c6f15dbb7',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkSend(
    company: 'f8eafd32-3ce4-40e4-ae0d-154fcb534b50',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_send" method="post" path="/companies/{company}/invoices/bulk-send" example="retired_active_profile_header" -->
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

$body = new Components\BulkSendInvoicesV1Request(
    ids: [
        '8eafd323-ce40-4e4e-90d1-54fcb534b509',
        '096cc252-19e9-4eae-872f-21c9954301b2',
        'fd36c089-a475-4c6a-92d8-536c6f15dbb7',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkSend(
    company: '6096cc25-219e-49ea-8e72-f21c9954301b',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_send" method="post" path="/companies/{company}/invoices/bulk-send" example="success" -->
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

$body = new Components\BulkSendInvoicesV1Request(
    ids: [
        '8eafd323-ce40-4e4e-90d1-54fcb534b509',
        '096cc252-19e9-4eae-872f-21c9954301b2',
        'fd36c089-a475-4c6a-92d8-536c6f15dbb7',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkSend(
    company: 'Luettgen, Terry and Pfeffer',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_send" method="post" path="/companies/{company}/invoices/bulk-send" example="unsupported_api_version" -->
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

$body = new Components\BulkSendInvoicesV1Request(
    ids: [
        '8eafd323-ce40-4e4e-90d1-54fcb534b509',
        '096cc252-19e9-4eae-872f-21c9954301b2',
        'fd36c089-a475-4c6a-92d8-536c6f15dbb7',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesBulkSend(
    company: '6ed21533-6ef1-444f-8bc7-dea215c97e7b',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Components\BulkSendInvoicesV1Request](../../Models/Components/BulkSendInvoicesV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Bulk invoice send accepts an optional UUID-keyed map of complete seven-field presentation preconditions. When revisioned presentation is enabled for the company, every DRAFT row must carry its precondition because sending issues it: a draft row without one is not issued or sent and is reported in `failures[]` with 428 `pdf_presentation_precondition_required`, while the other rows continue. Rows that are not drafts are re-sent as they are. With revisioned presentation disabled the precondition fields are ignored.                                                                                                                                                                                                                                                                   |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1InvoicesBulkSendResponse](../../Models/Operations/PublicApiV1InvoicesBulkSendResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1InvoicesBulkStatus

Move up to 50 invoices to `issued`, `sent` (alias of `issued`) or `paid` in one call, with partial success.
- `paid` requires `payment_date` (not in the future), applied to every invoice.
- Issuing goes oldest date first, not in `ids` order; each invoice takes the date of the day of issue (an earlier `issued_on` moves to an empty `operation_on`).
- Failures, in a `BulkPartialSuccessResult`, are `resource_not_found`, `invalid_status_transition` or `business_rule_violation` (a later issued invoice in the series: no number consumed, no row subcode).

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_status" method="post" path="/companies/{company}/invoices/bulk-status" example="api_key_revoked" -->
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

$body = new Components\BulkStatusInvoicesV1Request(
    ids: [
        '4478cc0a-aa8f-463b-9e59-ced0c92e4953',
        'ca46b65f-f091-4dfc-99ba-70033872b892',
        '649a205f-a3a6-493c-9448-d44c464fe067',
    ],
    newStatus: Components\BulkStatusInvoicesV1RequestNewStatus::Paid,
);

$response = $sdk->invoices->publicApiV1InvoicesBulkStatus(
    company: 'fe4478cc-0aaa-48f6-93be-59ced0c92e49',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_status" method="post" path="/companies/{company}/invoices/bulk-status" example="factuarea_version_invalid_format" -->
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

$body = new Components\BulkStatusInvoicesV1Request(
    ids: [
        'e4478cc0-aaa8-4f63-8be5-9ced0c92e495',
        '7ca46b65-ff09-41df-8c9b-a70033872b89',
        '6649a205-fa3a-4693-9c44-8d44c464fe06',
    ],
    newStatus: Components\BulkStatusInvoicesV1RequestNewStatus::Sent,
);

$response = $sdk->invoices->publicApiV1InvoicesBulkStatus(
    company: '26649a20-5fa3-4a69-93c4-48d44c464fe0',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_status" method="post" path="/companies/{company}/invoices/bulk-status" example="invalid_api_key" -->
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

$body = new Components\BulkStatusInvoicesV1Request(
    ids: [
        '4478cc0a-aa8f-463b-9e59-ced0c92e4953',
        'ca46b65f-f091-4dfc-99ba-70033872b892',
        '649a205f-a3a6-493c-9448-d44c464fe067',
    ],
    newStatus: Components\BulkStatusInvoicesV1RequestNewStatus::Paid,
);

$response = $sdk->invoices->publicApiV1InvoicesBulkStatus(
    company: '37ca46b6-5ff0-491d-afc9-ba70033872b8',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_status" method="post" path="/companies/{company}/invoices/bulk-status" example="missing_api_key" -->
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

$body = new Components\BulkStatusInvoicesV1Request(
    ids: [
        'e4478cc0-aaa8-4f63-8be5-9ced0c92e495',
        '7ca46b65-ff09-41df-8c9b-a70033872b89',
        '6649a205-fa3a-4693-9c44-8d44c464fe06',
    ],
    newStatus: Components\BulkStatusInvoicesV1RequestNewStatus::Sent,
);

$response = $sdk->invoices->publicApiV1InvoicesBulkStatus(
    company: 'Lesch and Sons',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: pdf_engine_unreachable_row

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_status" method="post" path="/companies/{company}/invoices/bulk-status" example="pdf_engine_unreachable_row" -->
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

$body = new Components\BulkStatusInvoicesV1Request(
    ids: [
        'e4478cc0-aaa8-4f63-8be5-9ced0c92e495',
        '7ca46b65-ff09-41df-8c9b-a70033872b89',
        '6649a205-fa3a-4693-9c44-8d44c464fe06',
    ],
    newStatus: Components\BulkStatusInvoicesV1RequestNewStatus::Sent,
);

$response = $sdk->invoices->publicApiV1InvoicesBulkStatus(
    company: 'fe4478cc-0aaa-48f6-93be-59ced0c92e49',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_status" method="post" path="/companies/{company}/invoices/bulk-status" example="retired_active_profile_header" -->
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

$body = new Components\BulkStatusInvoicesV1Request(
    ids: [
        'e4478cc0-aaa8-4f63-8be5-9ced0c92e495',
        '7ca46b65-ff09-41df-8c9b-a70033872b89',
        '6649a205-fa3a-4693-9c44-8d44c464fe06',
    ],
    newStatus: Components\BulkStatusInvoicesV1RequestNewStatus::Sent,
);

$response = $sdk->invoices->publicApiV1InvoicesBulkStatus(
    company: '37ca46b6-5ff0-491d-afc9-ba70033872b8',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_status" method="post" path="/companies/{company}/invoices/bulk-status" example="success" -->
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

$body = new Components\BulkStatusInvoicesV1Request(
    ids: [
        'e4478cc0-aaa8-4f63-8be5-9ced0c92e495',
        '7ca46b65-ff09-41df-8c9b-a70033872b89',
        '6649a205-fa3a-4693-9c44-8d44c464fe06',
    ],
    newStatus: Components\BulkStatusInvoicesV1RequestNewStatus::Sent,
);

$response = $sdk->invoices->publicApiV1InvoicesBulkStatus(
    company: 'Turcotte, Fritsch and Hagenes',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.bulk_status" method="post" path="/companies/{company}/invoices/bulk-status" example="unsupported_api_version" -->
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

$body = new Components\BulkStatusInvoicesV1Request(
    ids: [
        'e4478cc0-aaa8-4f63-8be5-9ced0c92e495',
        '7ca46b65-ff09-41df-8c9b-a70033872b89',
        '6649a205-fa3a-4693-9c44-8d44c464fe06',
    ],
    newStatus: Components\BulkStatusInvoicesV1RequestNewStatus::Sent,
);

$response = $sdk->invoices->publicApiV1InvoicesBulkStatus(
    company: '770f8cbf-752c-4379-8c75-724f022a3cf2',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Components\BulkStatusInvoicesV1Request](../../Models/Components/BulkStatusInvoicesV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Bulk invoice status changes accept an optional UUID-keyed map of complete seven-field presentation preconditions. When revisioned presentation is enabled for the company and the change issues invoices (`issued` or the legacy `sent`), every draft row must carry its precondition: a draft row without one is not issued and is reported in `failures[]` with 428 `pdf_presentation_precondition_required`, while the other rows continue. With revisioned presentation disabled the precondition fields are ignored.                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1InvoicesBulkStatusResponse](../../Models/Operations/PublicApiV1InvoicesBulkStatusResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1InvoicesCanAnnul

Check whether an invoice can be annulled and whether a VeriFactu cancellation record will be created; call before `/annul`.
- `can_annul` is the verdict without `revert_collections`.
- If only live payments block it, `requires_collection_reversal` is `true` with `active_collections_amount`.

### Example Usage: requires_collection_reversal

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.can_annul" method="get" path="/companies/{company}/invoices/{invoice}/can-annul" example="requires_collection_reversal" -->
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



$response = $sdk->invoices->publicApiV1InvoicesCanAnnul(
    company: 'O\'Conner - Schaden',
    invoice: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.can_annul" method="get" path="/companies/{company}/invoices/{invoice}/can-annul" example="success" -->
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



$response = $sdk->invoices->publicApiV1InvoicesCanAnnul(
    company: 'Hermiston - Daniel',
    invoice: '<value>',
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
| `invoice`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the invoice.                                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1InvoicesCanAnnulResponse](../../Models/Operations/PublicApiV1InvoicesCanAnnulResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1InvoicesSimplifiedEligibility

Determines if an invoice may be issued as simplified (F2) under Real Decreto 1619/2012 art. 4 based on amount and counterparty data.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.simplified_eligibility" method="post" path="/companies/{company}/invoices/simplified-eligibility" example="api_key_revoked" -->
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

$body = new Components\SimplifiedInvoiceEligibilityV1Request(
    total: 725.14,
);

$response = $sdk->invoices->publicApiV1InvoicesSimplifiedEligibility(
    company: '1f805cc9-bafe-46c0-bf13-a99b4a45e303',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.simplified_eligibility" method="post" path="/companies/{company}/invoices/simplified-eligibility" example="factuarea_version_invalid_format" -->
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

$body = new Components\SimplifiedInvoiceEligibilityV1Request(
    total: 725.14,
);

$response = $sdk->invoices->publicApiV1InvoicesSimplifiedEligibility(
    company: '1d02ebc7-acdb-41d5-b00f-6aa1788908f5',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.simplified_eligibility" method="post" path="/companies/{company}/invoices/simplified-eligibility" example="invalid_api_key" -->
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

$body = new Components\SimplifiedInvoiceEligibilityV1Request(
    total: 725.14,
);

$response = $sdk->invoices->publicApiV1InvoicesSimplifiedEligibility(
    company: '1d02ebc7-acdb-41d5-b00f-6aa1788908f5',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.simplified_eligibility" method="post" path="/companies/{company}/invoices/simplified-eligibility" example="missing_api_key" -->
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

$body = new Components\SimplifiedInvoiceEligibilityV1Request(
    total: 725.14,
);

$response = $sdk->invoices->publicApiV1InvoicesSimplifiedEligibility(
    company: 'Mueller, Rempel and O\'Keefe',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.simplified_eligibility" method="post" path="/companies/{company}/invoices/simplified-eligibility" example="retired_active_profile_header" -->
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

$body = new Components\SimplifiedInvoiceEligibilityV1Request(
    total: 725.14,
);

$response = $sdk->invoices->publicApiV1InvoicesSimplifiedEligibility(
    company: '1f805cc9-bafe-46c0-bf13-a99b4a45e303',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.simplified_eligibility" method="post" path="/companies/{company}/invoices/simplified-eligibility" example="success" -->
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

$body = new Components\SimplifiedInvoiceEligibilityV1Request(
    total: 725.14,
);

$response = $sdk->invoices->publicApiV1InvoicesSimplifiedEligibility(
    company: 'Hartmann - Schinner',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.simplified_eligibility" method="post" path="/companies/{company}/invoices/simplified-eligibility" example="unsupported_api_version" -->
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

$body = new Components\SimplifiedInvoiceEligibilityV1Request(
    total: 725.14,
);

$response = $sdk->invoices->publicApiV1InvoicesSimplifiedEligibility(
    company: 'f71b0621-b05b-4214-9965-a74ef141c7ba',
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
| `body`                                                                                                                                                                                                                                                                                                                                                                       | [Components\SimplifiedInvoiceEligibilityV1Request](../../Models/Components/SimplifiedInvoiceEligibilityV1Request.md)                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | N/A                                                                                                                                                                                                                                                                                                                                                                          |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1InvoicesSimplifiedEligibilityResponse](../../Models/Operations/PublicApiV1InvoicesSimplifiedEligibilityResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1InvoicesCorrective

Issue a corrective invoice amending an issued one; returns 201 with the new invoice.
- Born issued; the original is never modified; AEAT alta is asynchronous.
- 422 `series_invoice_kind_mismatch`.
- `payment_terms` defaults to `[]`; negative instalments must add up exactly to a negative `total_to_pay`.

**Signing certificate (NO VERI*FACTU).** Only in that mode, a missing or invalid company certificate is rejected before the invoice is issued: 422 `verifactu_not_eligible`, `error.subcode: signing_certificate_unavailable`; nothing is consumed, so fix it and repeat the call.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.corrective" method="post" path="/companies/{company}/invoices/{invoice}/corrective" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1InvoicesCorrectiveRequest(
    company: '5932b4e8-2c6a-4102-a13f-5bef06e16d60',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateCorrectiveInvoiceRequest(
        correctionReason: Components\CorrectionReason::Incobrable,
        correctionType: Components\CreateCorrectiveInvoiceRequestCorrectionType::Partial,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesCorrective(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.corrective" method="post" path="/companies/{company}/invoices/{invoice}/corrective" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1InvoicesCorrectiveRequest(
    company: '141db84c-1f7a-4fe0-a92e-5c1aa4d866ec',
    invoice: '2b698de6-a964-406c-be69-fe1c1677f6bd',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateCorrectiveInvoiceRequest(
        lines: [
            new Components\CreateCorrectiveInvoiceRequestLine(
                sourceLineId: '019e0000-0000-7000-8000-0000000000b1',
                description: 'Ajuste del servicio',
                quantity: -1,
                unitPrice: 10,
                taxRate: 21,
            ),
        ],
        correctionReason: Components\CorrectionReason::Otras,
        correctionType: Components\CreateCorrectiveInvoiceRequestCorrectionType::Partial,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesCorrective(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.corrective" method="post" path="/companies/{company}/invoices/{invoice}/corrective" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1InvoicesCorrectiveRequest(
    company: '02d1ec62-a203-450b-95b4-522211a4bde3',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateCorrectiveInvoiceRequest(
        correctionReason: Components\CorrectionReason::Incobrable,
        correctionType: Components\CreateCorrectiveInvoiceRequestCorrectionType::Partial,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesCorrective(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.corrective" method="post" path="/companies/{company}/invoices/{invoice}/corrective" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1InvoicesCorrectiveRequest(
    company: 'Waters Group',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateCorrectiveInvoiceRequest(
        correctionReason: Components\CorrectionReason::ErrorCliente,
        correctionType: Components\CreateCorrectiveInvoiceRequestCorrectionType::Full,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesCorrective(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: r1_error_fundado

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.corrective" method="post" path="/companies/{company}/invoices/{invoice}/corrective" example="r1_error_fundado" -->
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

$request = new Operations\PublicApiV1InvoicesCorrectiveRequest(
    company: 'Mann Group',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateCorrectiveInvoiceRequest(
        correctionReason: Components\CorrectionReason::ErrorFundado,
        correctionType: Components\CreateCorrectiveInvoiceRequestCorrectionType::Full,
        correctionCode: Components\CorrectionCode::R1,
        justification: 'Error fundado de derecho en la cuota repercutida de la factura original; se anula y rectifica la totalidad.',
        notes: 'Rectificativa total por error de derecho',
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesCorrective(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: r2_concurso

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.corrective" method="post" path="/companies/{company}/invoices/{invoice}/corrective" example="r2_concurso" -->
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

$request = new Operations\PublicApiV1InvoicesCorrectiveRequest(
    company: 'Rodriguez - Gottlieb',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateCorrectiveInvoiceRequest(
        correctionReason: Components\CorrectionReason::Concurso,
        correctionType: Components\CreateCorrectiveInvoiceRequestCorrectionType::Full,
        correctionCode: Components\CorrectionCode::R2,
        justification: 'Modificación de la base imponible por auto de declaración de concurso del destinatario (art. 80.Tres LIVA).',
        notes: 'Rectificativa por concurso de acreedores',
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesCorrective(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: r3_incobrable

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.corrective" method="post" path="/companies/{company}/invoices/{invoice}/corrective" example="r3_incobrable" -->
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

$request = new Operations\PublicApiV1InvoicesCorrectiveRequest(
    company: 'Leuschke, Christiansen and Schneider',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateCorrectiveInvoiceRequest(
        correctionReason: Components\CorrectionReason::Incobrable,
        correctionType: Components\CreateCorrectiveInvoiceRequestCorrectionType::Full,
        correctionCode: Components\CorrectionCode::R3,
        justification: 'Crédito total o parcialmente incobrable según los requisitos del art. 80.Cuatro LIVA (reclamación previa documentada).',
        notes: 'Rectificativa por crédito incobrable',
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesCorrective(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: r4_otras

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.corrective" method="post" path="/companies/{company}/invoices/{invoice}/corrective" example="r4_otras" -->
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

$request = new Operations\PublicApiV1InvoicesCorrectiveRequest(
    company: 'O\'Kon Group',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateCorrectiveInvoiceRequest(
        lines: [
            new Components\CreateCorrectiveInvoiceRequestLine(
                description: 'Descuento comercial posterior sobre el servicio de consultoría',
                quantity: -1,
                unitPrice: 15,
                taxRate: 21,
            ),
        ],
        correctionReason: Components\CorrectionReason::Descuento,
        correctionType: Components\CreateCorrectiveInvoiceRequestCorrectionType::Partial,
        correctionCode: Components\CorrectionCode::R4,
        justification: 'Rectificación parcial por descuento posterior a la emisión sobre una de las líneas de la factura original.',
        notes: 'Rectificativa parcial por descuento',
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesCorrective(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: r5_simplificada

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.corrective" method="post" path="/companies/{company}/invoices/{invoice}/corrective" example="r5_simplificada" -->
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

$request = new Operations\PublicApiV1InvoicesCorrectiveRequest(
    company: 'Davis - Bernhard',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateCorrectiveInvoiceRequest(
        correctionReason: Components\CorrectionReason::ErrorImporte,
        correctionType: Components\CreateCorrectiveInvoiceRequestCorrectionType::Full,
        correctionCode: Components\CorrectionCode::R5,
        justification: 'Rectificación del importe de una factura simplificada (F2) emitida a consumidor final; corrige la cuota repercutida.',
        notes: 'Rectificativa de factura simplificada',
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesCorrective(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.corrective" method="post" path="/companies/{company}/invoices/{invoice}/corrective" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1InvoicesCorrectiveRequest(
    company: '5932b4e8-2c6a-4102-a13f-5bef06e16d60',
    invoice: '02d1ec62-a203-450b-95b4-522211a4bde3',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateCorrectiveInvoiceRequest(
        lines: [
            new Components\CreateCorrectiveInvoiceRequestLine(
                sourceLineId: '019e0000-0000-7000-8000-0000000000b1',
                description: 'Ajuste del servicio',
                quantity: -1,
                unitPrice: 10,
                taxRate: 21,
            ),
        ],
        correctionReason: Components\CorrectionReason::Otras,
        correctionType: Components\CreateCorrectiveInvoiceRequestCorrectionType::Partial,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesCorrective(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: substitution

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.corrective" method="post" path="/companies/{company}/invoices/{invoice}/corrective" example="substitution" -->
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

$request = new Operations\PublicApiV1InvoicesCorrectiveRequest(
    company: 'Wuckert - Harvey',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateCorrectiveInvoiceRequest(
        lines: [
            new Components\CreateCorrectiveInvoiceRequestLine(
                description: 'Servicio de consultoría',
                quantity: 1,
                unitPrice: 120,
                taxRate: 21,
            ),
        ],
        correctionReason: Components\CorrectionReason::ErrorImporte,
        correctionType: Components\CreateCorrectiveInvoiceRequestCorrectionType::Full,
        correctionNature: Components\CorrectionNature::S,
        notes: 'Sustituye a la factura original: el precio pactado era 120 €, no 150 €',
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesCorrective(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.corrective" method="post" path="/companies/{company}/invoices/{invoice}/corrective" example="success" -->
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

$request = new Operations\PublicApiV1InvoicesCorrectiveRequest(
    company: 'Walter, Yost and Altenwerth',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateCorrectiveInvoiceRequest(
        lines: [
            new Components\CreateCorrectiveInvoiceRequestLine(
                sourceLineId: '019e0000-0000-7000-8000-0000000000b1',
                description: 'Ajuste del servicio',
                quantity: -1,
                unitPrice: 10,
                taxRate: 21,
            ),
        ],
        correctionReason: Components\CorrectionReason::Otras,
        correctionType: Components\CreateCorrectiveInvoiceRequestCorrectionType::Partial,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesCorrective(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.corrective" method="post" path="/companies/{company}/invoices/{invoice}/corrective" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1InvoicesCorrectiveRequest(
    company: '52242c52-dc3f-45b7-98c1-c02f2e3ff003',
    invoice: '920974df-819c-4839-867f-a3ee7ef22a7a',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateCorrectiveInvoiceRequest(
        lines: [
            new Components\CreateCorrectiveInvoiceRequestLine(
                sourceLineId: '019e0000-0000-7000-8000-0000000000b1',
                description: 'Ajuste del servicio',
                quantity: -1,
                unitPrice: 10,
                taxRate: 21,
            ),
        ],
        correctionReason: Components\CorrectionReason::Otras,
        correctionType: Components\CreateCorrectiveInvoiceRequestCorrectionType::Partial,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesCorrective(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                          | Type                                                                                                               | Required                                                                                                           | Description                                                                                                        |
| ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                         | [Operations\PublicApiV1InvoicesCorrectiveRequest](../../Models/Operations/PublicApiV1InvoicesCorrectiveRequest.md) | :heavy_check_mark:                                                                                                 | The request object to use for the request.                                                                         |

### Response

**[?Operations\PublicApiV1InvoicesCorrectiveResponse](../../Models/Operations/PublicApiV1InvoicesCorrectiveResponse.md)**

### Errors

| Error Type                             | Status Code                            | Content Type                           |
| -------------------------------------- | -------------------------------------- | -------------------------------------- |
| Errors\Error                           | 400, 401, 402, 403, 404, 409, 422, 429 | application/json                       |
| Errors\Error                           | 500                                    | application/json                       |
| Errors\APIException                    | 4XX, 5XX                               | \*/\*                                  |

## publicApiV1InvoicesCreate

Create a `draft` invoice, or issue it at once with `options.issue_directly: true`.
- Send an `Idempotency-Key`; a duplicate `external_id` upserts; `201` is not AEAT acceptance.
- `type: F2`, `payment` or `options.register_verifactu` make it a paid one-call checkout.
- Issuing dates it the day of issue, not `issued_on`.

**Signing certificate (NO VERI*FACTU).** Only in that mode, a missing or invalid company certificate is rejected before the invoice is issued: 422 `verifactu_not_eligible`, `error.subcode: signing_certificate_unavailable`; nothing is consumed, so fix it and repeat the call.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.create" method="post" path="/companies/{company}/invoices" example="api_key_revoked" -->
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

$body = new Components\CreateInvoiceRequest(
    lines: [
        new Components\CreateInvoiceRequestLine(
            description: 'cautiously than fooey briefly dial as robust',
            quantity: 5830.08,
            unitPrice: 482.34,
        ),
    ],
    clientId: '8b4931f3-c449-40ad-ab16-a033daa399fd',
    seriesId: 'fdab8e05-a5ba-4149-b81c-1cbdbd6e4e58',
    issuedOn: LocalDate::parse('2026-09-28'),
    dueOn: LocalDate::parse('2024-01-31'),
    metadata: [
        'erp_code' => 'IVA-GEN',
        'ledger_account' => '477000',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesCreate(
    company: '8b4931f3-c449-40ad-ab16-a033daa399fd',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: b2b_nacional

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.create" method="post" path="/companies/{company}/invoices" example="b2b_nacional" -->
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

$body = new Components\CreateInvoiceRequest(
    lines: [
        new Components\CreateInvoiceRequestLine(
            description: 'Servicio de consultoria',
            quantity: 2,
            unitPrice: 150,
            taxRateId: '019e5584-7a7f-70fd-b9cb-9466c3ee12a8',
            discountPercent: 0,
        ),
    ],
    clientId: 'f58fbf7f-333f-499b-affc-c0d396cbd83f',
    seriesId: '019e5584-7a72-7038-a8f6-561ed180b699',
    issuedOn: LocalDate::parse('2026-06-01'),
    dueOn: LocalDate::parse('2026-07-01'),
    notes: 'Factura creada via API v1',
    metadata: [
        'order_ref' => 'PO-2026-0042',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesCreate(
    company: 'Roberts Inc',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: b2c

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.create" method="post" path="/companies/{company}/invoices" example="b2c" -->
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

$body = new Components\CreateInvoiceRequest(
    lines: [
        new Components\CreateInvoiceRequestLine(
            description: 'Reparación de electrodoméstico',
            quantity: 1,
            unitPrice: 80,
            taxRate: 21,
            regimeKey: Components\CreateInvoiceRequestRegimeKey::One,
        ),
    ],
    clientId: 'f58fbf7f-333f-499b-affc-c0d396cbd83f',
    seriesId: '019e5584-7a72-7038-a8f6-561ed180b699',
    issuedOn: LocalDate::parse('2026-06-01'),
    dueOn: LocalDate::parse('2026-06-01'),
    notes: 'Venta a consumidor final (factura simplificada cuando aplique)',
);

$response = $sdk->invoices->publicApiV1InvoicesCreate(
    company: 'Ferry Group',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: cajero_reenvio

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.create" method="post" path="/companies/{company}/invoices" example="cajero_reenvio" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Utils;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\CreateInvoiceRequest(
    lines: [
        new Components\CreateInvoiceRequestLine(
            description: 'Lavado exprés',
            quantity: 1,
            unitPrice: 5,
            taxRate: 21,
        ),
    ],
    type: Components\CreateInvoiceRequestType::F2,
    pricesIncludeTax: true,
    payment: new Components\CreateInvoiceRequestPayment(
        method: Components\CreateInvoiceRequestMethod::CreditCard,
        paidAt: Utils\Utils::parseDateTime('2026-06-01T10:42:11+02:00'),
        reference: 'TPV-8841-000187',
    ),
    issuedOn: LocalDate::parse('2026-06-01'),
    dueOn: LocalDate::parse('2026-06-01'),
    externalId: 'KIOSK-0042-20260601-000187',
    options: new Components\CreateInvoiceRequestOptions(
        waitForPdf: true,
        registerVerifactu: true,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesCreate(
    company: 'Fisher - Fisher',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: cajero_simplificada_cualificada

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.create" method="post" path="/companies/{company}/invoices" example="cajero_simplificada_cualificada" -->
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

$body = new Components\CreateInvoiceRequest(
    lines: [
        new Components\CreateInvoiceRequestLine(
            description: 'Recarga de taquilla',
            quantity: 1,
            unitPrice: 12.4,
            taxRate: 21,
        ),
    ],
    type: Components\CreateInvoiceRequestType::F2,
    clientId: 'f58fbf7f-333f-499b-affc-c0d396cbd83f',
    pricesIncludeTax: true,
    operationOn: LocalDate::parse('2026-06-01'),
    payment: new Components\CreateInvoiceRequestPayment(
        method: Components\CreateInvoiceRequestMethod::CreditCard,
        reference: 'TPV-8841-000188',
    ),
    issuedOn: LocalDate::parse('2026-06-02'),
    dueOn: LocalDate::parse('2026-06-02'),
    externalId: 'KIOSK-0042-20260601-000188',
    options: new Components\CreateInvoiceRequestOptions(
        registerVerifactu: true,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesCreate(
    company: 'Hudson - Murray',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: cajero_ticket_anonimo

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.create" method="post" path="/companies/{company}/invoices" example="cajero_ticket_anonimo" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Utils;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$body = new Components\CreateInvoiceRequest(
    lines: [
        new Components\CreateInvoiceRequestLine(
            description: 'Lavado exprés',
            quantity: 1,
            unitPrice: 5,
            taxRate: 21,
        ),
    ],
    type: Components\CreateInvoiceRequestType::F2,
    pricesIncludeTax: true,
    payment: new Components\CreateInvoiceRequestPayment(
        method: Components\CreateInvoiceRequestMethod::CreditCard,
        paidAt: Utils\Utils::parseDateTime('2026-06-01T10:42:11+02:00'),
        reference: 'TPV-8841-000187',
    ),
    issuedOn: LocalDate::parse('2026-06-01'),
    dueOn: LocalDate::parse('2026-06-01'),
    externalId: 'KIOSK-0042-20260601-000187',
    options: new Components\CreateInvoiceRequestOptions(
        waitForPdf: true,
        registerVerifactu: true,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesCreate(
    company: 'Stanton LLC',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: con_irpf

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.create" method="post" path="/companies/{company}/invoices" example="con_irpf" -->
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

$body = new Components\CreateInvoiceRequest(
    lines: [
        new Components\CreateInvoiceRequestLine(
            description: 'Servicios profesionales de abogacía',
            quantity: 1,
            unitPrice: 1000,
            taxRate: 21,
            retentionRate: 15,
            regimeKey: Components\CreateInvoiceRequestRegimeKey::One,
        ),
    ],
    clientId: 'f58fbf7f-333f-499b-affc-c0d396cbd83f',
    seriesId: '019e5584-7a72-7038-a8f6-561ed180b699',
    issuedOn: LocalDate::parse('2026-06-01'),
    dueOn: LocalDate::parse('2026-07-01'),
    notes: 'Factura de profesional con retención IRPF del 15%',
);

$response = $sdk->invoices->publicApiV1InvoicesCreate(
    company: 'McLaughlin - Altenwerth',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.create" method="post" path="/companies/{company}/invoices" example="factuarea_version_invalid_format" -->
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

$body = new Components\CreateInvoiceRequest(
    businessReferences: new Components\InvoiceHeaderReferences(
        receiverContractReference: 'CON-2026-1',
        receiverTransactionReference: 'PED-2026-1',
        fileReference: 'EXP-2026-1',
    ),
    invoicingPeriod: new Components\InvoiceInvoicingPeriod(
        startDate: LocalDate::parse('2026-01-01'),
        endDate: LocalDate::parse('2026-01-31'),
    ),
    legalLiterals: [
        'Pago sujeto al contrato indicado.',
        'Conservar el albarán.',
        'Conservar el albarán.',
    ],
    administrativeCentres: [
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::One,
            code: 'L01000001',
            name: 'Oficina contable',
        ),
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::Two,
            code: 'L01000002',
            name: 'Órgano gestor',
        ),
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::Three,
            code: 'L01000003',
            name: 'Unidad tramitadora',
        ),
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::Four,
            code: 'L01000004',
            name: 'Órgano proponente',
        ),
    ],
    lines: [
        new Components\CreateInvoiceRequestLine(
            businessReferences: new Components\InvoiceLineReferences(
                issuerContractReference: 'REF-2026-01',
                issuerContractDate: LocalDate::parse('2026-01-10'),
                issuerTransactionReference: 'REF-2026-01',
                issuerTransactionDate: LocalDate::parse('2026-01-10'),
                receiverContractReference: 'REF-2026-01',
                receiverContractDate: LocalDate::parse('2026-01-10'),
                receiverTransactionReference: 'REF-2026-01',
                receiverTransactionDate: LocalDate::parse('2026-01-10'),
                fileReference: 'REF-2026-01',
                fileDate: LocalDate::parse('2026-01-10'),
            ),
            deliveryNotesReferences: [
                new Components\InvoiceDeliveryNoteReference(
                    number: 'ALB-2026-1',
                    date: LocalDate::parse('2026-01-10'),
                ),
                new Components\InvoiceDeliveryNoteReference(
                    number: 'ALB-2026-1',
                    date: null,
                ),
            ],
            sequenceNumber: 0,
            description: 'Servicio contratado',
            quantity: 1,
            unitPrice: 100,
            taxRate: 21,
        ),
    ],
    clientId: '019e0000-0000-7000-8000-0000000000f1',
    seriesId: '019e0000-0000-7000-8000-0000000000f2',
    issuedOn: LocalDate::parse('2026-01-15'),
    dueOn: LocalDate::parse('2026-02-15'),
);

$response = $sdk->invoices->publicApiV1InvoicesCreate(
    company: 'e0f86a7d-e413-4b40-806c-1645403fdb8b',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: idempotent_replay

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.create" method="post" path="/companies/{company}/invoices" example="idempotent_replay" -->
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

$body = new Components\CreateInvoiceRequest(
    businessReferences: new Components\InvoiceHeaderReferences(
        receiverContractReference: 'CON-2026-1',
        receiverTransactionReference: 'PED-2026-1',
        fileReference: 'EXP-2026-1',
    ),
    invoicingPeriod: new Components\InvoiceInvoicingPeriod(
        startDate: LocalDate::parse('2026-01-01'),
        endDate: LocalDate::parse('2026-01-31'),
    ),
    legalLiterals: [
        'Pago sujeto al contrato indicado.',
        'Conservar el albarán.',
        'Conservar el albarán.',
    ],
    administrativeCentres: [
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::One,
            code: 'L01000001',
            name: 'Oficina contable',
        ),
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::Two,
            code: 'L01000002',
            name: 'Órgano gestor',
        ),
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::Three,
            code: 'L01000003',
            name: 'Unidad tramitadora',
        ),
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::Four,
            code: 'L01000004',
            name: 'Órgano proponente',
        ),
    ],
    lines: [
        new Components\CreateInvoiceRequestLine(
            businessReferences: new Components\InvoiceLineReferences(
                issuerContractReference: 'REF-2026-01',
                issuerContractDate: LocalDate::parse('2026-01-10'),
                issuerTransactionReference: 'REF-2026-01',
                issuerTransactionDate: LocalDate::parse('2026-01-10'),
                receiverContractReference: 'REF-2026-01',
                receiverContractDate: LocalDate::parse('2026-01-10'),
                receiverTransactionReference: 'REF-2026-01',
                receiverTransactionDate: LocalDate::parse('2026-01-10'),
                fileReference: 'REF-2026-01',
                fileDate: LocalDate::parse('2026-01-10'),
            ),
            deliveryNotesReferences: [
                new Components\InvoiceDeliveryNoteReference(
                    number: 'ALB-2026-1',
                    date: LocalDate::parse('2026-01-10'),
                ),
                new Components\InvoiceDeliveryNoteReference(
                    number: 'ALB-2026-1',
                    date: null,
                ),
            ],
            sequenceNumber: 0,
            description: 'Servicio contratado',
            quantity: 1,
            unitPrice: 100,
            taxRate: 21,
        ),
    ],
    clientId: '019e0000-0000-7000-8000-0000000000f1',
    seriesId: '019e0000-0000-7000-8000-0000000000f2',
    issuedOn: LocalDate::parse('2026-01-15'),
    dueOn: LocalDate::parse('2026-02-15'),
);

$response = $sdk->invoices->publicApiV1InvoicesCreate(
    company: 'O\'Reilly, O\'Connell and Ernser',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: intracomunitario_bienes

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.create" method="post" path="/companies/{company}/invoices" example="intracomunitario_bienes" -->
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

$body = new Components\CreateInvoiceRequest(
    lines: [
        new Components\CreateInvoiceRequestLine(
            description: 'Suministro de maquinaria a cliente UE (DE)',
            quantity: 1,
            unitPrice: 5000,
            taxRate: 0,
            regimeKey: Components\CreateInvoiceRequestRegimeKey::One,
            exemptionReason: Components\CreateInvoiceRequestExemptionReason::E5,
        ),
    ],
    clientId: 'f58fbf7f-333f-499b-affc-c0d396cbd83f',
    seriesId: '019e5584-7a72-7038-a8f6-561ed180b699',
    issuedOn: LocalDate::parse('2026-06-01'),
    dueOn: LocalDate::parse('2026-07-01'),
    notes: 'Entrega intracomunitaria de bienes exenta (art. 25 LIVA)',
);

$response = $sdk->invoices->publicApiV1InvoicesCreate(
    company: 'Dietrich, Schoen and Goldner',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.create" method="post" path="/companies/{company}/invoices" example="invalid_api_key" -->
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

$body = new Components\CreateInvoiceRequest(
    lines: [
        new Components\CreateInvoiceRequestLine(
            description: 'cautiously than fooey briefly dial as robust',
            quantity: 5830.08,
            unitPrice: 482.34,
        ),
    ],
    clientId: '8b4931f3-c449-40ad-ab16-a033daa399fd',
    seriesId: 'fdab8e05-a5ba-4149-b81c-1cbdbd6e4e58',
    issuedOn: LocalDate::parse('2026-09-28'),
    dueOn: LocalDate::parse('2024-01-31'),
    metadata: [
        'erp_code' => 'IVA-GEN',
        'ledger_account' => '477000',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesCreate(
    company: 'fdab8e05-a5ba-4149-b81c-1cbdbd6e4e58',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.create" method="post" path="/companies/{company}/invoices" example="missing_api_key" -->
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

$body = new Components\CreateInvoiceRequest(
    lines: [],
    issuedOn: LocalDate::parse('2025-07-18'),
    dueOn: LocalDate::parse('2026-03-24'),
    metadata: [
        'erp_code' => 'IVA-GEN',
        'ledger_account' => '477000',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesCreate(
    company: 'Ritchie, Lueilwitz and Vandervort',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.create" method="post" path="/companies/{company}/invoices" example="retired_active_profile_header" -->
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

$body = new Components\CreateInvoiceRequest(
    businessReferences: new Components\InvoiceHeaderReferences(
        receiverContractReference: 'CON-2026-1',
        receiverTransactionReference: 'PED-2026-1',
        fileReference: 'EXP-2026-1',
    ),
    invoicingPeriod: new Components\InvoiceInvoicingPeriod(
        startDate: LocalDate::parse('2026-01-01'),
        endDate: LocalDate::parse('2026-01-31'),
    ),
    legalLiterals: [
        'Pago sujeto al contrato indicado.',
        'Conservar el albarán.',
        'Conservar el albarán.',
    ],
    administrativeCentres: [
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::One,
            code: 'L01000001',
            name: 'Oficina contable',
        ),
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::Two,
            code: 'L01000002',
            name: 'Órgano gestor',
        ),
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::Three,
            code: 'L01000003',
            name: 'Unidad tramitadora',
        ),
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::Four,
            code: 'L01000004',
            name: 'Órgano proponente',
        ),
    ],
    lines: [
        new Components\CreateInvoiceRequestLine(
            businessReferences: new Components\InvoiceLineReferences(
                issuerContractReference: 'REF-2026-01',
                issuerContractDate: LocalDate::parse('2026-01-10'),
                issuerTransactionReference: 'REF-2026-01',
                issuerTransactionDate: LocalDate::parse('2026-01-10'),
                receiverContractReference: 'REF-2026-01',
                receiverContractDate: LocalDate::parse('2026-01-10'),
                receiverTransactionReference: 'REF-2026-01',
                receiverTransactionDate: LocalDate::parse('2026-01-10'),
                fileReference: 'REF-2026-01',
                fileDate: LocalDate::parse('2026-01-10'),
            ),
            deliveryNotesReferences: [
                new Components\InvoiceDeliveryNoteReference(
                    number: 'ALB-2026-1',
                    date: LocalDate::parse('2026-01-10'),
                ),
                new Components\InvoiceDeliveryNoteReference(
                    number: 'ALB-2026-1',
                    date: null,
                ),
            ],
            sequenceNumber: 0,
            description: 'Servicio contratado',
            quantity: 1,
            unitPrice: 100,
            taxRate: 21,
        ),
    ],
    clientId: '019e0000-0000-7000-8000-0000000000f1',
    seriesId: '019e0000-0000-7000-8000-0000000000f2',
    issuedOn: LocalDate::parse('2026-01-15'),
    dueOn: LocalDate::parse('2026-02-15'),
);

$response = $sdk->invoices->publicApiV1InvoicesCreate(
    company: 'fdab8e05-a5ba-4149-b81c-1cbdbd6e4e58',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.create" method="post" path="/companies/{company}/invoices" example="success" -->
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

$body = new Components\CreateInvoiceRequest(
    businessReferences: new Components\InvoiceHeaderReferences(
        receiverContractReference: 'CON-2026-1',
        receiverTransactionReference: 'PED-2026-1',
        fileReference: 'EXP-2026-1',
    ),
    invoicingPeriod: new Components\InvoiceInvoicingPeriod(
        startDate: LocalDate::parse('2026-01-01'),
        endDate: LocalDate::parse('2026-01-31'),
    ),
    legalLiterals: [
        'Pago sujeto al contrato indicado.',
        'Conservar el albarán.',
        'Conservar el albarán.',
    ],
    administrativeCentres: [
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::One,
            code: 'L01000001',
            name: 'Oficina contable',
        ),
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::Two,
            code: 'L01000002',
            name: 'Órgano gestor',
        ),
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::Three,
            code: 'L01000003',
            name: 'Unidad tramitadora',
        ),
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::Four,
            code: 'L01000004',
            name: 'Órgano proponente',
        ),
    ],
    lines: [
        new Components\CreateInvoiceRequestLine(
            businessReferences: new Components\InvoiceLineReferences(
                issuerContractReference: 'REF-2026-01',
                issuerContractDate: LocalDate::parse('2026-01-10'),
                issuerTransactionReference: 'REF-2026-01',
                issuerTransactionDate: LocalDate::parse('2026-01-10'),
                receiverContractReference: 'REF-2026-01',
                receiverContractDate: LocalDate::parse('2026-01-10'),
                receiverTransactionReference: 'REF-2026-01',
                receiverTransactionDate: LocalDate::parse('2026-01-10'),
                fileReference: 'REF-2026-01',
                fileDate: LocalDate::parse('2026-01-10'),
            ),
            deliveryNotesReferences: [
                new Components\InvoiceDeliveryNoteReference(
                    number: 'ALB-2026-1',
                    date: LocalDate::parse('2026-01-10'),
                ),
                new Components\InvoiceDeliveryNoteReference(
                    number: 'ALB-2026-1',
                    date: null,
                ),
            ],
            sequenceNumber: 0,
            description: 'Servicio contratado',
            quantity: 1,
            unitPrice: 100,
            taxRate: 21,
        ),
    ],
    clientId: '019e0000-0000-7000-8000-0000000000f1',
    seriesId: '019e0000-0000-7000-8000-0000000000f2',
    issuedOn: LocalDate::parse('2026-01-15'),
    dueOn: LocalDate::parse('2026-02-15'),
);

$response = $sdk->invoices->publicApiV1InvoicesCreate(
    company: 'Morissette and Sons',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: three_instalments

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.create" method="post" path="/companies/{company}/invoices" example="three_instalments" -->
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

$body = new Components\CreateInvoiceRequest(
    businessReferences: new Components\InvoiceHeaderReferences(
        receiverContractReference: 'CON-2026-1',
        receiverTransactionReference: 'PED-2026-1',
        fileReference: 'EXP-2026-1',
    ),
    invoicingPeriod: new Components\InvoiceInvoicingPeriod(
        startDate: LocalDate::parse('2026-01-01'),
        endDate: LocalDate::parse('2026-01-31'),
    ),
    legalLiterals: [
        'Pago sujeto al contrato indicado.',
        'Conservar el albarán.',
        'Conservar el albarán.',
    ],
    administrativeCentres: [
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::One,
            code: 'L01000001',
            name: 'Oficina contable',
        ),
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::Two,
            code: 'L01000002',
            name: 'Órgano gestor',
        ),
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::Three,
            code: 'L01000003',
            name: 'Unidad tramitadora',
        ),
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::Four,
            code: 'L01000004',
            name: 'Órgano proponente',
        ),
    ],
    lines: [
        new Components\CreateInvoiceRequestLine(
            businessReferences: new Components\InvoiceLineReferences(
                issuerContractReference: 'REF-2026-01',
                issuerContractDate: LocalDate::parse('2026-01-10'),
                issuerTransactionReference: 'REF-2026-01',
                issuerTransactionDate: LocalDate::parse('2026-01-10'),
                receiverContractReference: 'REF-2026-01',
                receiverContractDate: LocalDate::parse('2026-01-10'),
                receiverTransactionReference: 'REF-2026-01',
                receiverTransactionDate: LocalDate::parse('2026-01-10'),
                fileReference: 'REF-2026-01',
                fileDate: LocalDate::parse('2026-01-10'),
            ),
            deliveryNotesReferences: [
                new Components\InvoiceDeliveryNoteReference(
                    number: 'ALB-2026-1',
                    date: LocalDate::parse('2026-01-10'),
                ),
                new Components\InvoiceDeliveryNoteReference(
                    number: 'ALB-2026-1',
                    date: null,
                ),
            ],
            sequenceNumber: 0,
            description: 'Servicio contratado',
            quantity: 1,
            unitPrice: 100,
            taxRate: 21,
        ),
    ],
    paymentTerms: [
        new Components\InvoiceInstalmentInput(
            dueDate: LocalDate::parse('2026-02-15'),
            amount: '40.00',
            paymentMeans: Components\InvoiceInstalmentInputPaymentMeans::One,
            account: null,
        ),
        new Components\InvoiceInstalmentInput(
            dueDate: LocalDate::parse('2026-03-15'),
            amount: '30.00',
            paymentMeans: Components\InvoiceInstalmentInputPaymentMeans::Two,
            account: new Components\InvoicePaymentAccountInput(
                iban: 'GB82WEST12345698765432',
                bic: 'NWBKGB2L',
            ),
        ),
        new Components\InvoiceInstalmentInput(
            dueDate: LocalDate::parse('2026-04-15'),
            amount: '51.00',
            paymentMeans: Components\InvoiceInstalmentInputPaymentMeans::Four,
            account: new Components\InvoicePaymentAccountInput(
                iban: 'ES9121000418450200051332',
                bic: 'CAIXESBBXXX',
            ),
        ),
    ],
    clientId: '019e0000-0000-7000-8000-0000000000f1',
    seriesId: '019e0000-0000-7000-8000-0000000000f2',
    issuedOn: LocalDate::parse('2026-01-15'),
    dueOn: LocalDate::parse('2026-04-15'),
);

$response = $sdk->invoices->publicApiV1InvoicesCreate(
    company: '8b4931f3-c449-40ad-ab16-a033daa399fd',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: unattended_checkout

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.create" method="post" path="/companies/{company}/invoices" example="unattended_checkout" -->
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

$body = new Components\CreateInvoiceRequest(
    businessReferences: new Components\InvoiceHeaderReferences(
        receiverContractReference: 'CON-2026-1',
        receiverTransactionReference: 'PED-2026-1',
        fileReference: 'EXP-2026-1',
    ),
    invoicingPeriod: new Components\InvoiceInvoicingPeriod(
        startDate: LocalDate::parse('2026-01-01'),
        endDate: LocalDate::parse('2026-01-31'),
    ),
    legalLiterals: [
        'Pago sujeto al contrato indicado.',
        'Conservar el albarán.',
        'Conservar el albarán.',
    ],
    administrativeCentres: [
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::One,
            code: 'L01000001',
            name: 'Oficina contable',
        ),
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::Two,
            code: 'L01000002',
            name: 'Órgano gestor',
        ),
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::Three,
            code: 'L01000003',
            name: 'Unidad tramitadora',
        ),
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::Four,
            code: 'L01000004',
            name: 'Órgano proponente',
        ),
    ],
    lines: [
        new Components\CreateInvoiceRequestLine(
            businessReferences: new Components\InvoiceLineReferences(
                issuerContractReference: 'REF-2026-01',
                issuerContractDate: LocalDate::parse('2026-01-10'),
                issuerTransactionReference: 'REF-2026-01',
                issuerTransactionDate: LocalDate::parse('2026-01-10'),
                receiverContractReference: 'REF-2026-01',
                receiverContractDate: LocalDate::parse('2026-01-10'),
                receiverTransactionReference: 'REF-2026-01',
                receiverTransactionDate: LocalDate::parse('2026-01-10'),
                fileReference: 'REF-2026-01',
                fileDate: LocalDate::parse('2026-01-10'),
            ),
            deliveryNotesReferences: [
                new Components\InvoiceDeliveryNoteReference(
                    number: 'ALB-2026-1',
                    date: LocalDate::parse('2026-01-10'),
                ),
                new Components\InvoiceDeliveryNoteReference(
                    number: 'ALB-2026-1',
                    date: null,
                ),
            ],
            sequenceNumber: 0,
            description: 'Servicio contratado',
            quantity: 1,
            unitPrice: 100,
            taxRate: 21,
        ),
    ],
    clientId: '019e0000-0000-7000-8000-0000000000f1',
    seriesId: '019e0000-0000-7000-8000-0000000000f2',
    issuedOn: LocalDate::parse('2026-01-15'),
    dueOn: LocalDate::parse('2026-02-15'),
);

$response = $sdk->invoices->publicApiV1InvoicesCreate(
    company: 'McGlynn, Ziemann and Shanahan',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.create" method="post" path="/companies/{company}/invoices" example="unsupported_api_version" -->
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

$body = new Components\CreateInvoiceRequest(
    businessReferences: new Components\InvoiceHeaderReferences(
        receiverContractReference: 'CON-2026-1',
        receiverTransactionReference: 'PED-2026-1',
        fileReference: 'EXP-2026-1',
    ),
    invoicingPeriod: new Components\InvoiceInvoicingPeriod(
        startDate: LocalDate::parse('2026-01-01'),
        endDate: LocalDate::parse('2026-01-31'),
    ),
    legalLiterals: [
        'Pago sujeto al contrato indicado.',
        'Conservar el albarán.',
        'Conservar el albarán.',
    ],
    administrativeCentres: [
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::One,
            code: 'L01000001',
            name: 'Oficina contable',
        ),
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::Two,
            code: 'L01000002',
            name: 'Órgano gestor',
        ),
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::Three,
            code: 'L01000003',
            name: 'Unidad tramitadora',
        ),
        new Components\InvoiceAdministrativeCentre(
            roleCode: Components\RoleCode::Four,
            code: 'L01000004',
            name: 'Órgano proponente',
        ),
    ],
    lines: [
        new Components\CreateInvoiceRequestLine(
            businessReferences: new Components\InvoiceLineReferences(
                issuerContractReference: 'REF-2026-01',
                issuerContractDate: LocalDate::parse('2026-01-10'),
                issuerTransactionReference: 'REF-2026-01',
                issuerTransactionDate: LocalDate::parse('2026-01-10'),
                receiverContractReference: 'REF-2026-01',
                receiverContractDate: LocalDate::parse('2026-01-10'),
                receiverTransactionReference: 'REF-2026-01',
                receiverTransactionDate: LocalDate::parse('2026-01-10'),
                fileReference: 'REF-2026-01',
                fileDate: LocalDate::parse('2026-01-10'),
            ),
            deliveryNotesReferences: [
                new Components\InvoiceDeliveryNoteReference(
                    number: 'ALB-2026-1',
                    date: LocalDate::parse('2026-01-10'),
                ),
                new Components\InvoiceDeliveryNoteReference(
                    number: 'ALB-2026-1',
                    date: null,
                ),
            ],
            sequenceNumber: 0,
            description: 'Servicio contratado',
            quantity: 1,
            unitPrice: 100,
            taxRate: 21,
        ),
    ],
    clientId: '019e0000-0000-7000-8000-0000000000f1',
    seriesId: '019e0000-0000-7000-8000-0000000000f2',
    issuedOn: LocalDate::parse('2026-01-15'),
    dueOn: LocalDate::parse('2026-02-15'),
);

$response = $sdk->invoices->publicApiV1InvoicesCreate(
    company: '1d16cdc3-9fad-40e1-8a36-6efb90125d7c',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | [Components\CreateInvoiceRequest](../../Models/Components/CreateInvoiceRequest.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | {<br/>"client_id": "019e0000-0000-7000-8000-0000000000f1",<br/>"series_id": "019e0000-0000-7000-8000-0000000000f2",<br/>"issued_on": "2026-01-15",<br/>"due_on": "2026-02-15",<br/>"business_references": {<br/>"receiver_contract_reference": "CON-2026-1",<br/>"receiver_transaction_reference": "PED-2026-1",<br/>"file_reference": "EXP-2026-1"<br/>},<br/>"invoicing_period": {<br/>"start_date": "2026-01-01",<br/>"end_date": "2026-01-31"<br/>},<br/>"legal_literals": [<br/>"Pago sujeto al contrato indicado.",<br/>"Conservar el albarán.",<br/>"Conservar el albarán."<br/>],<br/>"administrative_centres": [<br/>{<br/>"role_code": "01",<br/>"code": "L01000001",<br/>"name": "Oficina contable"<br/>},<br/>{<br/>"role_code": "02",<br/>"code": "L01000002",<br/>"name": "Órgano gestor"<br/>},<br/>{<br/>"role_code": "03",<br/>"code": "L01000003",<br/>"name": "Unidad tramitadora"<br/>},<br/>{<br/>"role_code": "04",<br/>"code": "L01000004",<br/>"name": "Órgano proponente"<br/>}<br/>],<br/>"lines": [<br/>{<br/>"description": "Servicio contratado",<br/>"quantity": 1,<br/>"unit_price": 100,<br/>"tax_rate": 21,<br/>"business_references": {<br/>"issuer_contract_reference": "REF-2026-01",<br/>"issuer_contract_date": "2026-01-10",<br/>"issuer_transaction_reference": "REF-2026-01",<br/>"issuer_transaction_date": "2026-01-10",<br/>"receiver_contract_reference": "REF-2026-01",<br/>"receiver_contract_date": "2026-01-10",<br/>"receiver_transaction_reference": "REF-2026-01",<br/>"receiver_transaction_date": "2026-01-10",<br/>"file_reference": "REF-2026-01",<br/>"file_date": "2026-01-10"<br/>},<br/>"delivery_notes_references": [<br/>{<br/>"number": "ALB-2026-1",<br/>"date": "2026-01-10"<br/>},<br/>{<br/>"number": "ALB-2026-1",<br/>"date": null<br/>}<br/>],<br/>"sequence_number": 0<br/>}<br/>]<br/>} |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      |

### Response

**[?Operations\PublicApiV1InvoicesCreateResponse](../../Models/Operations/PublicApiV1InvoicesCreateResponse.md)**

### Errors

| Error Type                                  | Status Code                                 | Content Type                                |
| ------------------------------------------- | ------------------------------------------- | ------------------------------------------- |
| Errors\Error                                | 400, 401, 402, 403, 404, 409, 422, 428, 429 | application/json                            |
| Errors\Error                                | 500                                         | application/json                            |
| Errors\APIException                         | 4XX, 5XX                                    | \*/\*                                       |

## publicApiV1InvoicesList

List your invoices with cursor-based pagination. Supports filtering by `status[in]`, `client_id`, `series_id`, `issued_on[gte|lte]`, and `total[gte|lte]`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.list" method="get" path="/companies/{company}/invoices" example="success" -->
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

$request = new Operations\PublicApiV1InvoicesListRequest(
    company: 'Hackett and Sons',
    sort: Operations\PublicApiV1InvoicesListSort::MinusCreated,
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->invoices->publicApiV1InvoicesList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                              | Type                                                                                                   | Required                                                                                               | Description                                                                                            |
| ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                             | [Operations\PublicApiV1InvoicesListRequest](../../Models/Operations/PublicApiV1InvoicesListRequest.md) | :heavy_check_mark:                                                                                     | The request object to use for the request.                                                             |

### Response

**[?Operations\PublicApiV1InvoicesListResponse](../../Models/Operations/PublicApiV1InvoicesListResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1InvoicesVerifactuCreate

Creates the VeriFactu alta record for an already-issued invoice and enqueues AEAT transmission. Use when automatic creation on send was skipped.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.verifactu_create" method="post" path="/companies/{company}/invoices/{invoice}/verifactu" example="success" -->
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



$response = $sdk->invoices->publicApiV1InvoicesVerifactuCreate(
    company: 'DuBuque, Parker and Stamm',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `invoice`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the invoice.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1InvoicesVerifactuCreateResponse](../../Models/Operations/PublicApiV1InvoicesVerifactuCreateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1InvoicesVerifactuGet

Returns the VeriFactu (Spanish AEAT SIF) record associated with the invoice if one exists. Responds with `data: null` when the invoice has no record yet.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.verifactu_get" method="get" path="/companies/{company}/invoices/{invoice}/verifactu" example="success" -->
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



$response = $sdk->invoices->publicApiV1InvoicesVerifactuGet(
    company: 'Heidenreich, Altenwerth and Beer',
    invoice: '<value>',
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
| `invoice`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the invoice.                                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1InvoicesVerifactuGetResponse](../../Models/Operations/PublicApiV1InvoicesVerifactuGetResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1InvoicesCreateRecurring

Create a recurring invoice template that reuses the lines, client, and series of an existing invoice, applying the cadence (frequency, start date, optional end date and limits) supplied in the body. Returns the new recurring invoice.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.create_recurring" method="post" path="/companies/{company}/invoices/{invoice}/create-recurring" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1InvoicesCreateRecurringRequest(
    company: 'cf900940-b160-46bd-8c4b-e327abfb835a',
    invoice: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateRecurringFromInvoiceRequest(
        frequency: Components\CreateRecurringFromInvoiceRequestFrequency::Semiannual,
        startOn: LocalDate::parse('2026-11-08'),
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesCreateRecurring(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.create_recurring" method="post" path="/companies/{company}/invoices/{invoice}/create-recurring" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1InvoicesCreateRecurringRequest(
    company: 'aa6b4619-1560-402c-869b-04b7f1bc1c1b',
    invoice: '0259135e-dcdb-4433-bb35-8a6f4c8760f0',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateRecurringFromInvoiceRequest(
        frequency: Components\CreateRecurringFromInvoiceRequestFrequency::Semiannual,
        startOn: LocalDate::parse('2026-11-08'),
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesCreateRecurring(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.create_recurring" method="post" path="/companies/{company}/invoices/{invoice}/create-recurring" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1InvoicesCreateRecurringRequest(
    company: 'db2ad436-2230-4fcc-9221-fef40c4d3a2d',
    invoice: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateRecurringFromInvoiceRequest(
        frequency: Components\CreateRecurringFromInvoiceRequestFrequency::Semiannual,
        startOn: LocalDate::parse('2026-11-08'),
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesCreateRecurring(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.create_recurring" method="post" path="/companies/{company}/invoices/{invoice}/create-recurring" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1InvoicesCreateRecurringRequest(
    company: 'Moore - Green',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateRecurringFromInvoiceRequest(
        frequency: Components\CreateRecurringFromInvoiceRequestFrequency::Semiannual,
        startOn: LocalDate::parse('2026-11-08'),
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesCreateRecurring(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.create_recurring" method="post" path="/companies/{company}/invoices/{invoice}/create-recurring" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1InvoicesCreateRecurringRequest(
    company: 'cf900940-b160-46bd-8c4b-e327abfb835a',
    invoice: 'db2ad436-2230-4fcc-9221-fef40c4d3a2d',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateRecurringFromInvoiceRequest(
        frequency: Components\CreateRecurringFromInvoiceRequestFrequency::Semiannual,
        startOn: LocalDate::parse('2026-11-08'),
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesCreateRecurring(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.create_recurring" method="post" path="/companies/{company}/invoices/{invoice}/create-recurring" example="success" -->
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

$request = new Operations\PublicApiV1InvoicesCreateRecurringRequest(
    company: 'Wiegand, Moore and Bashirian',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateRecurringFromInvoiceRequest(
        frequency: Components\CreateRecurringFromInvoiceRequestFrequency::Semiannual,
        startOn: LocalDate::parse('2026-11-08'),
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesCreateRecurring(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.create_recurring" method="post" path="/companies/{company}/invoices/{invoice}/create-recurring" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1InvoicesCreateRecurringRequest(
    company: '6bfd8f1d-2f08-44af-a762-a0ac0b41d35b',
    invoice: 'ba483393-c3e1-4bb5-9e00-06675959b84f',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CreateRecurringFromInvoiceRequest(
        frequency: Components\CreateRecurringFromInvoiceRequestFrequency::Semiannual,
        startOn: LocalDate::parse('2026-11-08'),
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesCreateRecurring(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                    | Type                                                                                                                         | Required                                                                                                                     | Description                                                                                                                  |
| ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                   | [Operations\PublicApiV1InvoicesCreateRecurringRequest](../../Models/Operations/PublicApiV1InvoicesCreateRecurringRequest.md) | :heavy_check_mark:                                                                                                           | The request object to use for the request.                                                                                   |

### Response

**[?Operations\PublicApiV1InvoicesCreateRecurringResponse](../../Models/Operations/PublicApiV1InvoicesCreateRecurringResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1InvoicesDelete

Delete a draft invoice. Issued invoices cannot be deleted (use `void` instead).

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.delete" method="delete" path="/companies/{company}/invoices/{invoice}" -->
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



$response = $sdk->invoices->publicApiV1InvoicesDelete(
    company: 'Powlowski - Huel',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->statusCode === 200) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `invoice`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the invoice.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1InvoicesDeleteResponse](../../Models/Operations/PublicApiV1InvoicesDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1InvoicesShow

Retrieve a sales invoice by its `id`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.show" method="get" path="/companies/{company}/invoices/{invoice}" example="success" -->
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



$response = $sdk->invoices->publicApiV1InvoicesShow(
    company: 'Gorczany - Hane',
    invoice: '<value>',
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
| `invoice`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the invoice.                                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1InvoicesShowResponse](../../Models/Operations/PublicApiV1InvoicesShowResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1InvoicesUpdate

Update a draft invoice; once issued, most fields are immutable per AEAT.
- Sending `lines` replaces the whole set; a line without tax takes the default tax or returns 422.
- `operation_on` cannot be after `issued_on` (422 `operation_date_after_issue_date`); `type` switches a draft between `F1` and `F2`.
- `payment_terms`: omitted keeps it, `null`, `[]` or a list replace it; any calendar write needs `expected_payment_terms_version` (428 missing, 409 stale).

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.update" method="patch" path="/companies/{company}/invoices/{invoice}" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1InvoicesUpdateRequest(
    company: 'fdf0e156-e141-46af-b0eb-fa6cdac22409',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateInvoiceRequest(
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: clear_to_unknown

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.update" method="patch" path="/companies/{company}/invoices/{invoice}" example="clear_to_unknown" -->
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

$request = new Operations\PublicApiV1InvoicesUpdateRequest(
    company: 'd943ec9f-1d82-47bb-b6ea-03c8c699eb6a',
    invoice: '24507dcf-f555-4010-bf09-f552759000b9',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateInvoiceRequest(
        paymentTerms: null,
        expectedPaymentTermsVersion: 1,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: commercial_1

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.update" method="patch" path="/companies/{company}/invoices/{invoice}" example="commercial_1" -->
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

$request = new Operations\PublicApiV1InvoicesUpdateRequest(
    company: 'fdf0e156-e141-46af-b0eb-fa6cdac22409',
    invoice: '3dc94cec-4b9c-4188-9d56-b4e6edbe9e48',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateInvoiceRequest(
        businessReferences: new Components\InvoiceHeaderReferences(
            receiverContractReference: 'CON-2026-1',
            receiverTransactionReference: 'PED-2026-1',
            fileReference: 'EXP-2026-1',
        ),
        invoicingPeriod: new Components\InvoiceInvoicingPeriod(
            startDate: LocalDate::parse('2026-01-01'),
            endDate: LocalDate::parse('2026-01-31'),
        ),
        legalLiterals: [
            'Pago sujeto al contrato indicado.',
            'Conservar el albarán.',
            'Conservar el albarán.',
        ],
        administrativeCentres: [
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::One,
                code: 'L01000001',
                name: 'Oficina contable',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Two,
                code: 'L01000002',
                name: 'Órgano gestor',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Three,
                code: 'L01000003',
                name: 'Unidad tramitadora',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Four,
                code: 'L01000004',
                name: 'Órgano proponente',
            ),
        ],
        lines: [
            new Components\UpdateInvoiceRequestLine(
                id: '019e0000-0000-7000-8000-0000000000b1',
                businessReferences: new Components\InvoiceLineReferences(
                    issuerContractReference: 'REF-2026-01',
                    issuerContractDate: LocalDate::parse('2026-01-10'),
                    issuerTransactionReference: 'REF-2026-01',
                    issuerTransactionDate: LocalDate::parse('2026-01-10'),
                    receiverContractReference: 'REF-2026-01',
                    receiverContractDate: LocalDate::parse('2026-01-10'),
                    receiverTransactionReference: 'REF-2026-01',
                    receiverTransactionDate: LocalDate::parse('2026-01-10'),
                    fileReference: 'REF-2026-01',
                    fileDate: LocalDate::parse('2026-01-10'),
                ),
                deliveryNotesReferences: [
                    new Components\InvoiceDeliveryNoteReference(
                        number: 'ALB-2026-1',
                        date: LocalDate::parse('2026-01-10'),
                    ),
                    new Components\InvoiceDeliveryNoteReference(
                        number: 'ALB-2026-1',
                        date: null,
                    ),
                ],
                sequenceNumber: 0,
                description: 'Servicio contratado',
                quantity: 1,
                unitPrice: 100,
                taxRate: 21,
            ),
        ],
        clientId: '019e0000-0000-7000-8000-0000000000f1',
        seriesId: '019e0000-0000-7000-8000-0000000000f2',
        issuedOn: LocalDate::parse('2026-01-15'),
        dueOn: LocalDate::parse('2026-02-15'),
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: commercial_2

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.update" method="patch" path="/companies/{company}/invoices/{invoice}" example="commercial_2" -->
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

$request = new Operations\PublicApiV1InvoicesUpdateRequest(
    company: '7e2e8533-bc97-4960-9a2c-31dc8d18a171',
    invoice: '218a46a6-498d-4422-8aec-451815325a98',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateInvoiceRequest(
        businessReferences: new Components\InvoiceHeaderReferences(
            fileReference: null,
        ),
        invoicingPeriod: null,
        legalLiterals: [],
        administrativeCentres: [],
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: default_example

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.update" method="patch" path="/companies/{company}/invoices/{invoice}" example="default_example" -->
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

$request = new Operations\PublicApiV1InvoicesUpdateRequest(
    company: 'ccb6323e-7a3a-4ab7-bb10-ad2b3c38b0f6',
    invoice: '1dd77a51-75e7-4a4c-a16c-4b54bf8f8f10',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateInvoiceRequest(
        lines: [
            new Components\UpdateInvoiceRequestLine(
                description: 'Servicio de consultoria (revisado)',
                quantity: 3,
                unitPrice: 150,
                taxRateId: '019e5584-7a7f-70fd-b9cb-9466c3ee12a8',
                discountPercent: 10,
            ),
        ],
        notes: 'Notas actualizadas via API v1',
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: explicit_empty

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.update" method="patch" path="/companies/{company}/invoices/{invoice}" example="explicit_empty" -->
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

$request = new Operations\PublicApiV1InvoicesUpdateRequest(
    company: '0c647963-a004-46e2-bf63-f7272719c27f',
    invoice: '34fc357a-9270-429c-9975-cb1a62d6577e',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateInvoiceRequest(
        businessReferences: new Components\InvoiceHeaderReferences(
            receiverContractReference: 'CON-2026-1',
            receiverTransactionReference: 'PED-2026-1',
            fileReference: 'EXP-2026-1',
        ),
        invoicingPeriod: new Components\InvoiceInvoicingPeriod(
            startDate: LocalDate::parse('2026-01-01'),
            endDate: LocalDate::parse('2026-01-31'),
        ),
        legalLiterals: [
            'Pago sujeto al contrato indicado.',
            'Conservar el albarán.',
            'Conservar el albarán.',
        ],
        administrativeCentres: [
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::One,
                code: 'L01000001',
                name: 'Oficina contable',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Two,
                code: 'L01000002',
                name: 'Órgano gestor',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Three,
                code: 'L01000003',
                name: 'Unidad tramitadora',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Four,
                code: 'L01000004',
                name: 'Órgano proponente',
            ),
        ],
        lines: [
            new Components\UpdateInvoiceRequestLine(
                id: '019e0000-0000-7000-8000-0000000000b1',
                businessReferences: new Components\InvoiceLineReferences(
                    issuerContractReference: 'REF-2026-01',
                    issuerContractDate: LocalDate::parse('2026-01-10'),
                    issuerTransactionReference: 'REF-2026-01',
                    issuerTransactionDate: LocalDate::parse('2026-01-10'),
                    receiverContractReference: 'REF-2026-01',
                    receiverContractDate: LocalDate::parse('2026-01-10'),
                    receiverTransactionReference: 'REF-2026-01',
                    receiverTransactionDate: LocalDate::parse('2026-01-10'),
                    fileReference: 'REF-2026-01',
                    fileDate: LocalDate::parse('2026-01-10'),
                ),
                deliveryNotesReferences: [
                    new Components\InvoiceDeliveryNoteReference(
                        number: 'ALB-2026-1',
                        date: LocalDate::parse('2026-01-10'),
                    ),
                    new Components\InvoiceDeliveryNoteReference(
                        number: 'ALB-2026-1',
                        date: null,
                    ),
                ],
                sequenceNumber: 0,
                description: 'Servicio contratado',
                quantity: 1,
                unitPrice: 100,
                taxRate: 21,
            ),
        ],
        paymentTerms: [],
        expectedPaymentTermsVersion: 1,
        clientId: '019e0000-0000-7000-8000-0000000000f1',
        seriesId: '019e0000-0000-7000-8000-0000000000f2',
        issuedOn: LocalDate::parse('2026-01-15'),
        dueOn: LocalDate::parse('2026-02-15'),
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.update" method="patch" path="/companies/{company}/invoices/{invoice}" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1InvoicesUpdateRequest(
    company: '340195ff-0fb6-4582-8bf5-69c767fda6f6',
    invoice: 'a3921da9-3d3b-4802-9917-ae49fa079ebe',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateInvoiceRequest(
        businessReferences: new Components\InvoiceHeaderReferences(
            receiverContractReference: 'CON-2026-1',
            receiverTransactionReference: 'PED-2026-1',
            fileReference: 'EXP-2026-1',
        ),
        invoicingPeriod: new Components\InvoiceInvoicingPeriod(
            startDate: LocalDate::parse('2026-01-01'),
            endDate: LocalDate::parse('2026-01-31'),
        ),
        legalLiterals: [
            'Pago sujeto al contrato indicado.',
            'Conservar el albarán.',
            'Conservar el albarán.',
        ],
        administrativeCentres: [
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::One,
                code: 'L01000001',
                name: 'Oficina contable',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Two,
                code: 'L01000002',
                name: 'Órgano gestor',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Three,
                code: 'L01000003',
                name: 'Unidad tramitadora',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Four,
                code: 'L01000004',
                name: 'Órgano proponente',
            ),
        ],
        lines: [
            new Components\UpdateInvoiceRequestLine(
                id: '019e0000-0000-7000-8000-0000000000b1',
                businessReferences: new Components\InvoiceLineReferences(
                    issuerContractReference: 'REF-2026-01',
                    issuerContractDate: LocalDate::parse('2026-01-10'),
                    issuerTransactionReference: 'REF-2026-01',
                    issuerTransactionDate: LocalDate::parse('2026-01-10'),
                    receiverContractReference: 'REF-2026-01',
                    receiverContractDate: LocalDate::parse('2026-01-10'),
                    receiverTransactionReference: 'REF-2026-01',
                    receiverTransactionDate: LocalDate::parse('2026-01-10'),
                    fileReference: 'REF-2026-01',
                    fileDate: LocalDate::parse('2026-01-10'),
                ),
                deliveryNotesReferences: [
                    new Components\InvoiceDeliveryNoteReference(
                        number: 'ALB-2026-1',
                        date: LocalDate::parse('2026-01-10'),
                    ),
                    new Components\InvoiceDeliveryNoteReference(
                        number: 'ALB-2026-1',
                        date: null,
                    ),
                ],
                sequenceNumber: 0,
                description: 'Servicio contratado',
                quantity: 1,
                unitPrice: 100,
                taxRate: 21,
            ),
        ],
        clientId: '019e0000-0000-7000-8000-0000000000f1',
        seriesId: '019e0000-0000-7000-8000-0000000000f2',
        issuedOn: LocalDate::parse('2026-01-15'),
        dueOn: LocalDate::parse('2026-02-15'),
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.update" method="patch" path="/companies/{company}/invoices/{invoice}" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1InvoicesUpdateRequest(
    company: '3dc94cec-4b9c-4188-9d56-b4e6edbe9e48',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateInvoiceRequest(
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: legacy_unknown

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.update" method="patch" path="/companies/{company}/invoices/{invoice}" example="legacy_unknown" -->
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

$request = new Operations\PublicApiV1InvoicesUpdateRequest(
    company: 'f6b61b35-883f-4a1b-996d-da14158bf40d',
    invoice: 'ae964210-d365-45d3-9558-c4c5b853e64a',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateInvoiceRequest(
        businessReferences: new Components\InvoiceHeaderReferences(
            receiverContractReference: 'CON-2026-1',
            receiverTransactionReference: 'PED-2026-1',
            fileReference: 'EXP-2026-1',
        ),
        invoicingPeriod: new Components\InvoiceInvoicingPeriod(
            startDate: LocalDate::parse('2026-01-01'),
            endDate: LocalDate::parse('2026-01-31'),
        ),
        legalLiterals: [
            'Pago sujeto al contrato indicado.',
            'Conservar el albarán.',
            'Conservar el albarán.',
        ],
        administrativeCentres: [
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::One,
                code: 'L01000001',
                name: 'Oficina contable',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Two,
                code: 'L01000002',
                name: 'Órgano gestor',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Three,
                code: 'L01000003',
                name: 'Unidad tramitadora',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Four,
                code: 'L01000004',
                name: 'Órgano proponente',
            ),
        ],
        lines: [
            new Components\UpdateInvoiceRequestLine(
                id: '019e0000-0000-7000-8000-0000000000b1',
                businessReferences: new Components\InvoiceLineReferences(
                    issuerContractReference: 'REF-2026-01',
                    issuerContractDate: LocalDate::parse('2026-01-10'),
                    issuerTransactionReference: 'REF-2026-01',
                    issuerTransactionDate: LocalDate::parse('2026-01-10'),
                    receiverContractReference: 'REF-2026-01',
                    receiverContractDate: LocalDate::parse('2026-01-10'),
                    receiverTransactionReference: 'REF-2026-01',
                    receiverTransactionDate: LocalDate::parse('2026-01-10'),
                    fileReference: 'REF-2026-01',
                    fileDate: LocalDate::parse('2026-01-10'),
                ),
                deliveryNotesReferences: [
                    new Components\InvoiceDeliveryNoteReference(
                        number: 'ALB-2026-1',
                        date: LocalDate::parse('2026-01-10'),
                    ),
                    new Components\InvoiceDeliveryNoteReference(
                        number: 'ALB-2026-1',
                        date: null,
                    ),
                ],
                sequenceNumber: 0,
                description: 'Servicio contratado',
                quantity: 1,
                unitPrice: 100,
                taxRate: 21,
            ),
        ],
        clientId: '019e0000-0000-7000-8000-0000000000f1',
        seriesId: '019e0000-0000-7000-8000-0000000000f2',
        issuedOn: LocalDate::parse('2026-01-15'),
        dueOn: LocalDate::parse('2026-02-15'),
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.update" method="patch" path="/companies/{company}/invoices/{invoice}" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1InvoicesUpdateRequest(
    company: 'Bode, Hahn and Bradtke',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateInvoiceRequest(
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.update" method="patch" path="/companies/{company}/invoices/{invoice}" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1InvoicesUpdateRequest(
    company: '001f1222-77d3-4b64-af50-a7774f0405b8',
    invoice: 'b6690254-ebe4-4609-9c63-2003caf9cf69',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateInvoiceRequest(
        businessReferences: new Components\InvoiceHeaderReferences(
            receiverContractReference: 'CON-2026-1',
            receiverTransactionReference: 'PED-2026-1',
            fileReference: 'EXP-2026-1',
        ),
        invoicingPeriod: new Components\InvoiceInvoicingPeriod(
            startDate: LocalDate::parse('2026-01-01'),
            endDate: LocalDate::parse('2026-01-31'),
        ),
        legalLiterals: [
            'Pago sujeto al contrato indicado.',
            'Conservar el albarán.',
            'Conservar el albarán.',
        ],
        administrativeCentres: [
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::One,
                code: 'L01000001',
                name: 'Oficina contable',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Two,
                code: 'L01000002',
                name: 'Órgano gestor',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Three,
                code: 'L01000003',
                name: 'Unidad tramitadora',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Four,
                code: 'L01000004',
                name: 'Órgano proponente',
            ),
        ],
        lines: [
            new Components\UpdateInvoiceRequestLine(
                id: '019e0000-0000-7000-8000-0000000000b1',
                businessReferences: new Components\InvoiceLineReferences(
                    issuerContractReference: 'REF-2026-01',
                    issuerContractDate: LocalDate::parse('2026-01-10'),
                    issuerTransactionReference: 'REF-2026-01',
                    issuerTransactionDate: LocalDate::parse('2026-01-10'),
                    receiverContractReference: 'REF-2026-01',
                    receiverContractDate: LocalDate::parse('2026-01-10'),
                    receiverTransactionReference: 'REF-2026-01',
                    receiverTransactionDate: LocalDate::parse('2026-01-10'),
                    fileReference: 'REF-2026-01',
                    fileDate: LocalDate::parse('2026-01-10'),
                ),
                deliveryNotesReferences: [
                    new Components\InvoiceDeliveryNoteReference(
                        number: 'ALB-2026-1',
                        date: LocalDate::parse('2026-01-10'),
                    ),
                    new Components\InvoiceDeliveryNoteReference(
                        number: 'ALB-2026-1',
                        date: null,
                    ),
                ],
                sequenceNumber: 0,
                description: 'Servicio contratado',
                quantity: 1,
                unitPrice: 100,
                taxRate: 21,
            ),
        ],
        clientId: '019e0000-0000-7000-8000-0000000000f1',
        seriesId: '019e0000-0000-7000-8000-0000000000f2',
        issuedOn: LocalDate::parse('2026-01-15'),
        dueOn: LocalDate::parse('2026-02-15'),
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.update" method="patch" path="/companies/{company}/invoices/{invoice}" example="success" -->
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

$request = new Operations\PublicApiV1InvoicesUpdateRequest(
    company: 'Brakus, Hessel and Keebler',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateInvoiceRequest(
        businessReferences: new Components\InvoiceHeaderReferences(
            receiverContractReference: 'CON-2026-1',
            receiverTransactionReference: 'PED-2026-1',
            fileReference: 'EXP-2026-1',
        ),
        invoicingPeriod: new Components\InvoiceInvoicingPeriod(
            startDate: LocalDate::parse('2026-01-01'),
            endDate: LocalDate::parse('2026-01-31'),
        ),
        legalLiterals: [
            'Pago sujeto al contrato indicado.',
            'Conservar el albarán.',
            'Conservar el albarán.',
        ],
        administrativeCentres: [
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::One,
                code: 'L01000001',
                name: 'Oficina contable',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Two,
                code: 'L01000002',
                name: 'Órgano gestor',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Three,
                code: 'L01000003',
                name: 'Unidad tramitadora',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Four,
                code: 'L01000004',
                name: 'Órgano proponente',
            ),
        ],
        lines: [
            new Components\UpdateInvoiceRequestLine(
                id: '019e0000-0000-7000-8000-0000000000b1',
                businessReferences: new Components\InvoiceLineReferences(
                    issuerContractReference: 'REF-2026-01',
                    issuerContractDate: LocalDate::parse('2026-01-10'),
                    issuerTransactionReference: 'REF-2026-01',
                    issuerTransactionDate: LocalDate::parse('2026-01-10'),
                    receiverContractReference: 'REF-2026-01',
                    receiverContractDate: LocalDate::parse('2026-01-10'),
                    receiverTransactionReference: 'REF-2026-01',
                    receiverTransactionDate: LocalDate::parse('2026-01-10'),
                    fileReference: 'REF-2026-01',
                    fileDate: LocalDate::parse('2026-01-10'),
                ),
                deliveryNotesReferences: [
                    new Components\InvoiceDeliveryNoteReference(
                        number: 'ALB-2026-1',
                        date: LocalDate::parse('2026-01-10'),
                    ),
                    new Components\InvoiceDeliveryNoteReference(
                        number: 'ALB-2026-1',
                        date: null,
                    ),
                ],
                sequenceNumber: 0,
                description: 'Servicio contratado',
                quantity: 1,
                unitPrice: 100,
                taxRate: 21,
            ),
        ],
        clientId: '019e0000-0000-7000-8000-0000000000f1',
        seriesId: '019e0000-0000-7000-8000-0000000000f2',
        issuedOn: LocalDate::parse('2026-01-15'),
        dueOn: LocalDate::parse('2026-02-15'),
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: three_instalments

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.update" method="patch" path="/companies/{company}/invoices/{invoice}" example="three_instalments" -->
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

$request = new Operations\PublicApiV1InvoicesUpdateRequest(
    company: '60971531-1f14-460e-9d3a-dc2ecc1952f7',
    invoice: '64d1675a-b560-4162-b1e8-26e4868d1636',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateInvoiceRequest(
        businessReferences: new Components\InvoiceHeaderReferences(
            receiverContractReference: 'CON-2026-1',
            receiverTransactionReference: 'PED-2026-1',
            fileReference: 'EXP-2026-1',
        ),
        invoicingPeriod: new Components\InvoiceInvoicingPeriod(
            startDate: LocalDate::parse('2026-01-01'),
            endDate: LocalDate::parse('2026-01-31'),
        ),
        legalLiterals: [
            'Pago sujeto al contrato indicado.',
            'Conservar el albarán.',
            'Conservar el albarán.',
        ],
        administrativeCentres: [
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::One,
                code: 'L01000001',
                name: 'Oficina contable',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Two,
                code: 'L01000002',
                name: 'Órgano gestor',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Three,
                code: 'L01000003',
                name: 'Unidad tramitadora',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Four,
                code: 'L01000004',
                name: 'Órgano proponente',
            ),
        ],
        lines: [
            new Components\UpdateInvoiceRequestLine(
                id: '019e0000-0000-7000-8000-0000000000b1',
                businessReferences: new Components\InvoiceLineReferences(
                    issuerContractReference: 'REF-2026-01',
                    issuerContractDate: LocalDate::parse('2026-01-10'),
                    issuerTransactionReference: 'REF-2026-01',
                    issuerTransactionDate: LocalDate::parse('2026-01-10'),
                    receiverContractReference: 'REF-2026-01',
                    receiverContractDate: LocalDate::parse('2026-01-10'),
                    receiverTransactionReference: 'REF-2026-01',
                    receiverTransactionDate: LocalDate::parse('2026-01-10'),
                    fileReference: 'REF-2026-01',
                    fileDate: LocalDate::parse('2026-01-10'),
                ),
                deliveryNotesReferences: [
                    new Components\InvoiceDeliveryNoteReference(
                        number: 'ALB-2026-1',
                        date: LocalDate::parse('2026-01-10'),
                    ),
                    new Components\InvoiceDeliveryNoteReference(
                        number: 'ALB-2026-1',
                        date: null,
                    ),
                ],
                sequenceNumber: 0,
                description: 'Servicio contratado',
                quantity: 1,
                unitPrice: 100,
                taxRate: 21,
            ),
        ],
        paymentTerms: [
            new Components\InvoiceInstalmentInput(
                id: '019e0000-0000-7000-8000-000000000701',
                dueDate: LocalDate::parse('2026-02-15'),
                amount: '40.00',
                paymentMeans: Components\InvoiceInstalmentInputPaymentMeans::One,
                account: null,
            ),
            new Components\InvoiceInstalmentInput(
                id: '019e0000-0000-7000-8000-000000000702',
                dueDate: LocalDate::parse('2026-03-15'),
                amount: '30.00',
                paymentMeans: Components\InvoiceInstalmentInputPaymentMeans::Two,
                account: new Components\InvoicePaymentAccountInput(
                    iban: 'GB82WEST12345698765432',
                    bic: 'NWBKGB2L',
                ),
            ),
            new Components\InvoiceInstalmentInput(
                id: '019e0000-0000-7000-8000-000000000703',
                dueDate: LocalDate::parse('2026-04-15'),
                amount: '51.00',
                paymentMeans: Components\InvoiceInstalmentInputPaymentMeans::Four,
                account: new Components\InvoicePaymentAccountInput(
                    iban: 'ES9121000418450200051332',
                    bic: 'CAIXESBBXXX',
                ),
            ),
        ],
        expectedPaymentTermsVersion: 1,
        clientId: '019e0000-0000-7000-8000-0000000000f1',
        seriesId: '019e0000-0000-7000-8000-0000000000f2',
        issuedOn: LocalDate::parse('2026-01-15'),
        dueOn: LocalDate::parse('2026-04-15'),
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.update" method="patch" path="/companies/{company}/invoices/{invoice}" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1InvoicesUpdateRequest(
    company: '34987bac-70f5-4d35-b64e-a7f58a8a98b9',
    invoice: 'fcb1c247-8940-4a58-9e3f-e2b771923f5b',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateInvoiceRequest(
        businessReferences: new Components\InvoiceHeaderReferences(
            receiverContractReference: 'CON-2026-1',
            receiverTransactionReference: 'PED-2026-1',
            fileReference: 'EXP-2026-1',
        ),
        invoicingPeriod: new Components\InvoiceInvoicingPeriod(
            startDate: LocalDate::parse('2026-01-01'),
            endDate: LocalDate::parse('2026-01-31'),
        ),
        legalLiterals: [
            'Pago sujeto al contrato indicado.',
            'Conservar el albarán.',
            'Conservar el albarán.',
        ],
        administrativeCentres: [
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::One,
                code: 'L01000001',
                name: 'Oficina contable',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Two,
                code: 'L01000002',
                name: 'Órgano gestor',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Three,
                code: 'L01000003',
                name: 'Unidad tramitadora',
            ),
            new Components\InvoiceAdministrativeCentre(
                roleCode: Components\RoleCode::Four,
                code: 'L01000004',
                name: 'Órgano proponente',
            ),
        ],
        lines: [
            new Components\UpdateInvoiceRequestLine(
                id: '019e0000-0000-7000-8000-0000000000b1',
                businessReferences: new Components\InvoiceLineReferences(
                    issuerContractReference: 'REF-2026-01',
                    issuerContractDate: LocalDate::parse('2026-01-10'),
                    issuerTransactionReference: 'REF-2026-01',
                    issuerTransactionDate: LocalDate::parse('2026-01-10'),
                    receiverContractReference: 'REF-2026-01',
                    receiverContractDate: LocalDate::parse('2026-01-10'),
                    receiverTransactionReference: 'REF-2026-01',
                    receiverTransactionDate: LocalDate::parse('2026-01-10'),
                    fileReference: 'REF-2026-01',
                    fileDate: LocalDate::parse('2026-01-10'),
                ),
                deliveryNotesReferences: [
                    new Components\InvoiceDeliveryNoteReference(
                        number: 'ALB-2026-1',
                        date: LocalDate::parse('2026-01-10'),
                    ),
                    new Components\InvoiceDeliveryNoteReference(
                        number: 'ALB-2026-1',
                        date: null,
                    ),
                ],
                sequenceNumber: 0,
                description: 'Servicio contratado',
                quantity: 1,
                unitPrice: 100,
                taxRate: 21,
            ),
        ],
        clientId: '019e0000-0000-7000-8000-0000000000f1',
        seriesId: '019e0000-0000-7000-8000-0000000000f2',
        issuedOn: LocalDate::parse('2026-01-15'),
        dueOn: LocalDate::parse('2026-02-15'),
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                  | Type                                                                                                       | Required                                                                                                   | Description                                                                                                |
| ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                 | [Operations\PublicApiV1InvoicesUpdateRequest](../../Models/Operations/PublicApiV1InvoicesUpdateRequest.md) | :heavy_check_mark:                                                                                         | The request object to use for the request.                                                                 |

### Response

**[?Operations\PublicApiV1InvoicesUpdateResponse](../../Models/Operations/PublicApiV1InvoicesUpdateResponse.md)**

### Errors

| Error Type                             | Status Code                            | Content Type                           |
| -------------------------------------- | -------------------------------------- | -------------------------------------- |
| Errors\Error                           | 400, 401, 403, 404, 409, 422, 428, 429 | application/json                       |
| Errors\Error                           | 500                                    | application/json                       |
| Errors\APIException                    | 4XX, 5XX                               | \*/\*                                  |

## publicApiV1InvoicesFacturae

Stream the Facturae 3.2.2 XML of an issued invoice: signed `.xsig` with a signing certificate, otherwise `.xml` (see `X-Facturae-Signed`).
- Without `submission_id` it exports the current legal source; draft and scheduled invoices return 422 `invoice_not_emittable_for_facturae`.
- With an own `submission_id` it returns that attempt's exact retained bytes, never regenerated or completed with live data.
- Hash and `ETag` only accompany a known retained artifact.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.facturae" method="get" path="/companies/{company}/invoices/{invoice}/facturae" example="unsigned_facturae" -->
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



$response = $sdk->invoices->publicApiV1InvoicesFacturae(
    company: 'Padberg, MacGyver and Okuneva',
    invoice: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->res !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `invoice`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the invoice.                                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `submissionId`                                                                                                                                                                                                                                                                                                                                                               | *?string*                                                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Own FACe attempt to export. Omitted or null exports the current legal source; a UUID returns the exact retained bytes of that attempt, never regenerated or re-signed.                                                                                                                                                                                                       |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1InvoicesFacturaeResponse](../../Models/Operations/PublicApiV1InvoicesFacturaeResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1InvoicesPdf

Download the invoice PDF as a binary stream (`application/pdf`).
- `format` is `a4` (default), `ticket_80` or `ticket_58`; others return 422 `parameter_invalid_enum`.
- The `ETag` changes with the invoice, VeriFactu alta and format; `If-None-Match` returns 304.
- With revisioned presentation, a draft returns its `BORRADOR` PDF (or `202` with `Retry-After` while it renders) and is never issued.

### Example Usage: failed_render_claimed_again

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.pdf" method="get" path="/companies/{company}/invoices/{invoice}/pdf" example="failed_render_claimed_again" -->
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

$request = new Operations\PublicApiV1InvoicesPdfRequest(
    company: 'c885933a-0683-4362-9307-e7dac770f236',
    invoice: '86d3e1d1-46df-4cef-adcd-1d1bf5f45a7b',
    ifNoneMatch: '"5d41402abc4b2a76b9719d911017c592"',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->invoices->publicApiV1InvoicesPdf(
    request: $request
);

if ($response->bytes !== null) {
    // handle response
}
```
### Example Usage: pending

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.pdf" method="get" path="/companies/{company}/invoices/{invoice}/pdf" example="pending" -->
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

$request = new Operations\PublicApiV1InvoicesPdfRequest(
    company: 'e85c4ca1-efe2-427d-9267-ad60a8edb606',
    invoice: '97bfecaf-3cb2-413f-8164-06aae12a5855',
    ifNoneMatch: '"5d41402abc4b2a76b9719d911017c592"',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->invoices->publicApiV1InvoicesPdf(
    request: $request
);

if ($response->bytes !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                            | Type                                                                                                 | Required                                                                                             | Description                                                                                          |
| ---------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------- |
| `$request`                                                                                           | [Operations\PublicApiV1InvoicesPdfRequest](../../Models/Operations/PublicApiV1InvoicesPdfRequest.md) | :heavy_check_mark:                                                                                   | The request object to use for the request.                                                           |

### Response

**[?Operations\PublicApiV1InvoicesPdfResponse](../../Models/Operations/PublicApiV1InvoicesPdfResponse.md)**

### Errors

| Error Type                             | Status Code                            | Content Type                           |
| -------------------------------------- | -------------------------------------- | -------------------------------------- |
| Errors\Error                           | 400, 401, 403, 404, 409, 410, 422, 429 | application/json                       |
| Errors\Error                           | 500, 503                               | application/json                       |
| Errors\APIException                    | 4XX, 5XX                               | \*/\*                                  |

## publicApiV1InvoicesDuplicate

Copy the lines, client and metadata of an invoice into a new draft with a fresh `id`.
- The draft is issued today; each `payment_terms` instalment keeps its amount and moves by the days between both issue dates, and `due_on` is the last moved instalment.
- A simplified invoice is copied without a series and must still be eligible: 422 `simplified_invoice_not_allowed`.
- Corrective, annulled and cancelled invoices return 422 `invoice_not_duplicatable_in_current_state`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.duplicate" method="post" path="/companies/{company}/invoices/{invoice}/duplicate" example="success" -->
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

$request = new Operations\PublicApiV1InvoicesDuplicateRequest(
    company: 'Jacobson Group',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->invoices->publicApiV1InvoicesDuplicate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                        | Type                                                                                                             | Required                                                                                                         | Description                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                       | [Operations\PublicApiV1InvoicesDuplicateRequest](../../Models/Operations/PublicApiV1InvoicesDuplicateRequest.md) | :heavy_check_mark:                                                                                               | The request object to use for the request.                                                                       |

### Response

**[?Operations\PublicApiV1InvoicesDuplicateResponse](../../Models/Operations/PublicApiV1InvoicesDuplicateResponse.md)**

### Errors

| Error Type                             | Status Code                            | Content Type                           |
| -------------------------------------- | -------------------------------------- | -------------------------------------- |
| Errors\Error                           | 400, 401, 402, 403, 404, 409, 422, 429 | application/json                       |
| Errors\Error                           | 500                                    | application/json                       |
| Errors\APIException                    | 4XX, 5XX                               | \*/\*                                  |

## publicApiV1InvoicesExportExcel

Export a selection of invoices to `xlsx` or `csv` and stream it back as an attachment.
- Filter by `invoice_ids`, `client_id`, `series_id`, `status`, dates or `search`; `format` is `SUMMARY` or `ITEMS`.
- Capped at 5,000 invoices: 422 `export_limit_exceeded`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.export_excel" method="post" path="/companies/{company}/invoices/export/excel" -->
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



$response = $sdk->invoices->publicApiV1InvoicesExportExcel(
    company: 'O\'Hara - Nienow',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: $body

);

if ($response->twoHundredApplicationVndOpenxmlformatsOfficedocumentSpreadsheetmlSheetBytes !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [?Components\ExportInvoicesExcelV1Request](../../Models/Components/ExportInvoicesExcelV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |

### Response

**[?Operations\PublicApiV1InvoicesExportExcelResponse](../../Models/Operations/PublicApiV1InvoicesExportExcelResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 413, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1InvoicesFindByExternalId

Look up a single invoice by its `external_id` (sent in the JSON body), the integration key that maps it to a record in a third-party system (ERP/CRM/e-commerce). Distinct from the fiscal number and the `id`. Returns the matching invoice or 404 `invoice_not_found` if no invoice uses that external_id within your company.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.find_by_external_id" method="post" path="/companies/{company}/invoices/find-by-external-id" example="api_key_revoked" -->
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

$body = new Components\FindInvoiceByExternalIdRequest(
    externalId: '<id>',
);

$response = $sdk->invoices->publicApiV1InvoicesFindByExternalId(
    company: 'ef1311d4-dff2-45e5-9f65-8355c8d5fc82',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.find_by_external_id" method="post" path="/companies/{company}/invoices/find-by-external-id" example="factuarea_version_invalid_format" -->
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

$body = new Components\FindInvoiceByExternalIdRequest(
    externalId: '<id>',
);

$response = $sdk->invoices->publicApiV1InvoicesFindByExternalId(
    company: '2a6e022a-36ed-40f9-a8a5-3a451468b739',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.find_by_external_id" method="post" path="/companies/{company}/invoices/find-by-external-id" example="invalid_api_key" -->
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

$body = new Components\FindInvoiceByExternalIdRequest(
    externalId: '<id>',
);

$response = $sdk->invoices->publicApiV1InvoicesFindByExternalId(
    company: '2a6e022a-36ed-40f9-a8a5-3a451468b739',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.find_by_external_id" method="post" path="/companies/{company}/invoices/find-by-external-id" example="missing_api_key" -->
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

$body = new Components\FindInvoiceByExternalIdRequest(
    externalId: '<id>',
);

$response = $sdk->invoices->publicApiV1InvoicesFindByExternalId(
    company: 'Breitenberg - Spinka',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.find_by_external_id" method="post" path="/companies/{company}/invoices/find-by-external-id" example="retired_active_profile_header" -->
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

$body = new Components\FindInvoiceByExternalIdRequest(
    externalId: '<id>',
);

$response = $sdk->invoices->publicApiV1InvoicesFindByExternalId(
    company: 'ef1311d4-dff2-45e5-9f65-8355c8d5fc82',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.find_by_external_id" method="post" path="/companies/{company}/invoices/find-by-external-id" example="success" -->
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

$body = new Components\FindInvoiceByExternalIdRequest(
    externalId: '<id>',
);

$response = $sdk->invoices->publicApiV1InvoicesFindByExternalId(
    company: 'Wilderman, Boyer and Emard',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.find_by_external_id" method="post" path="/companies/{company}/invoices/find-by-external-id" example="unsupported_api_version" -->
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

$body = new Components\FindInvoiceByExternalIdRequest(
    externalId: '<id>',
);

$response = $sdk->invoices->publicApiV1InvoicesFindByExternalId(
    company: 'f666143d-4ef3-456f-9f5a-adf7b4a2568a',
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
| `body`                                                                                                                                                                                                                                                                                                                                                                       | [Components\FindInvoiceByExternalIdRequest](../../Models/Components/FindInvoiceByExternalIdRequest.md)                                                                                                                                                                                                                                                                       | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | N/A                                                                                                                                                                                                                                                                                                                                                                          |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1InvoicesFindByExternalIdResponse](../../Models/Operations/PublicApiV1InvoicesFindByExternalIdResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1InvoicesFindByNumber

Look up one invoice by number, optionally narrowed with `year` and `series_id`.
- Returns 404 if not found.
- Returns 422 if the number still matches several invoices; the message lists the series and fiscal years.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.find_by_number" method="post" path="/companies/{company}/invoices/find-by-number" example="api_key_revoked" -->
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

$body = new Components\FindInvoiceByNumberRequest(
    number: '<value>',
);

$response = $sdk->invoices->publicApiV1InvoicesFindByNumber(
    company: '202405e6-8f26-4294-b2c2-20c8a34d3b47',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.find_by_number" method="post" path="/companies/{company}/invoices/find-by-number" example="factuarea_version_invalid_format" -->
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

$body = new Components\FindInvoiceByNumberRequest(
    number: '<value>',
);

$response = $sdk->invoices->publicApiV1InvoicesFindByNumber(
    company: '59d25f92-2b11-416e-b821-c04e16fc68aa',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.find_by_number" method="post" path="/companies/{company}/invoices/find-by-number" example="invalid_api_key" -->
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

$body = new Components\FindInvoiceByNumberRequest(
    number: '<value>',
);

$response = $sdk->invoices->publicApiV1InvoicesFindByNumber(
    company: '59d25f92-2b11-416e-b821-c04e16fc68aa',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.find_by_number" method="post" path="/companies/{company}/invoices/find-by-number" example="missing_api_key" -->
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

$body = new Components\FindInvoiceByNumberRequest(
    number: '<value>',
);

$response = $sdk->invoices->publicApiV1InvoicesFindByNumber(
    company: 'Kautzer, Ledner and Williamson',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.find_by_number" method="post" path="/companies/{company}/invoices/find-by-number" example="retired_active_profile_header" -->
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

$body = new Components\FindInvoiceByNumberRequest(
    number: '<value>',
);

$response = $sdk->invoices->publicApiV1InvoicesFindByNumber(
    company: '202405e6-8f26-4294-b2c2-20c8a34d3b47',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.find_by_number" method="post" path="/companies/{company}/invoices/find-by-number" example="success" -->
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

$body = new Components\FindInvoiceByNumberRequest(
    number: '<value>',
);

$response = $sdk->invoices->publicApiV1InvoicesFindByNumber(
    company: 'Beier - Hilll',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.find_by_number" method="post" path="/companies/{company}/invoices/find-by-number" example="unsupported_api_version" -->
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

$body = new Components\FindInvoiceByNumberRequest(
    number: '<value>',
);

$response = $sdk->invoices->publicApiV1InvoicesFindByNumber(
    company: '28be5fed-5eb6-481f-9564-c934285226be',
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
| `body`                                                                                                                                                                                                                                                                                                                                                                       | [Components\FindInvoiceByNumberRequest](../../Models/Components/FindInvoiceByNumberRequest.md)                                                                                                                                                                                                                                                                               | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | N/A                                                                                                                                                                                                                                                                                                                                                                          |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1InvoicesFindByNumberResponse](../../Models/Operations/PublicApiV1InvoicesFindByNumberResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1InvoicesPdfLink

Return a temporary signed URL to the invoice PDF instead of the bytes; `format` is `a4` (default), `ticket_80` or `ticket_58`.
- The URL needs no API key and expires at `expires_at` (24 h) with 403.
- 200 when the PDF is ready; 202 `pendiente` when queued, so retry until 200.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.pdf_link" method="get" path="/companies/{company}/invoices/{invoice}/pdf-link" example="success" -->
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



$response = $sdk->invoices->publicApiV1InvoicesPdfLink(
    company: 'Cartwright Inc',
    invoice: '<value>',
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
| `invoice`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the invoice.                                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `format`                                                                                                                                                                                                                                                                                                                                                                     | [?Operations\PublicApiV1InvoicesPdfLinkFormat](../../Models/Operations/PublicApiV1InvoicesPdfLinkFormat.md)                                                                                                                                                                                                                                                                  | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Paper of the PDF the signed link serves: `a4` (default), `ticket_80` (80 mm roll) or `ticket_58` (58 mm roll).                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1InvoicesPdfLinkResponse](../../Models/Operations/PublicApiV1InvoicesPdfLinkResponse.md)**

### Errors

| Error Type                             | Status Code                            | Content Type                           |
| -------------------------------------- | -------------------------------------- | -------------------------------------- |
| Errors\Error                           | 400, 401, 403, 404, 409, 410, 422, 429 | application/json                       |
| Errors\Error                           | 500, 503                               | application/json                       |
| Errors\APIException                    | 4XX, 5XX                               | \*/\*                                  |

## publicApiV1InvoicesPublicLinkGet

Returns the shareable public URL of the invoice along with its status, expiration, and the plan-allowed maximum extension days.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.public_link_get" method="get" path="/companies/{company}/invoices/{invoice}/public-link" example="success" -->
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



$response = $sdk->invoices->publicApiV1InvoicesPublicLinkGet(
    company: 'Frami Inc',
    invoice: '<value>',
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
| `invoice`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the invoice.                                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1InvoicesPublicLinkGetResponse](../../Models/Operations/PublicApiV1InvoicesPublicLinkGetResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1InvoicesPublicLinkUpdate

Applies an action to the public link: `revoke`, `activate`, `extend` (with `extend_days`), or `reset` to the plan default.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.public_link_update" method="patch" path="/companies/{company}/invoices/{invoice}/public-link" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1InvoicesPublicLinkUpdateRequest(
    company: '098a5b06-f731-42d8-a7cd-dc0ebc34ba79',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateInvoicePublicLinkRequest(
        action: Components\UpdateInvoicePublicLinkRequestAction::Revoke,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesPublicLinkUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: document_public_link_update

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.public_link_update" method="patch" path="/companies/{company}/invoices/{invoice}/public-link" example="document_public_link_update" -->
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

$request = new Operations\PublicApiV1InvoicesPublicLinkUpdateRequest(
    company: 'Mohr - Lehner',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateInvoicePublicLinkRequest(
        action: Components\UpdateInvoicePublicLinkRequestAction::Extend,
        extendDays: 30,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesPublicLinkUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.public_link_update" method="patch" path="/companies/{company}/invoices/{invoice}/public-link" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1InvoicesPublicLinkUpdateRequest(
    company: '4edf687a-4a11-4d5b-9566-32e0a1d3dd59',
    invoice: '5a938a36-b49d-4a15-be81-f23d97614394',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateInvoicePublicLinkRequest(
        action: Components\UpdateInvoicePublicLinkRequestAction::Revoke,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesPublicLinkUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.public_link_update" method="patch" path="/companies/{company}/invoices/{invoice}/public-link" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1InvoicesPublicLinkUpdateRequest(
    company: '7e959af7-c91d-4bf8-b3a5-6486664e579c',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateInvoicePublicLinkRequest(
        action: Components\UpdateInvoicePublicLinkRequestAction::Revoke,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesPublicLinkUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.public_link_update" method="patch" path="/companies/{company}/invoices/{invoice}/public-link" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1InvoicesPublicLinkUpdateRequest(
    company: 'Kautzer - Wehner',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateInvoicePublicLinkRequest(
        action: Components\UpdateInvoicePublicLinkRequestAction::Revoke,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesPublicLinkUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.public_link_update" method="patch" path="/companies/{company}/invoices/{invoice}/public-link" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1InvoicesPublicLinkUpdateRequest(
    company: '098a5b06-f731-42d8-a7cd-dc0ebc34ba79',
    invoice: '7e959af7-c91d-4bf8-b3a5-6486664e579c',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateInvoicePublicLinkRequest(
        action: Components\UpdateInvoicePublicLinkRequestAction::Revoke,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesPublicLinkUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.public_link_update" method="patch" path="/companies/{company}/invoices/{invoice}/public-link" example="success" -->
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

$request = new Operations\PublicApiV1InvoicesPublicLinkUpdateRequest(
    company: 'Hermann LLC',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateInvoicePublicLinkRequest(
        action: Components\UpdateInvoicePublicLinkRequestAction::Revoke,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesPublicLinkUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.public_link_update" method="patch" path="/companies/{company}/invoices/{invoice}/public-link" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1InvoicesPublicLinkUpdateRequest(
    company: '4cfbdfb8-69bf-4eb6-a338-38360836cf03',
    invoice: 'cabe497a-7451-41f5-ad11-8a932cbc196d',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateInvoicePublicLinkRequest(
        action: Components\UpdateInvoicePublicLinkRequestAction::Revoke,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesPublicLinkUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                      | Type                                                                                                                           | Required                                                                                                                       | Description                                                                                                                    |
| ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                     | [Operations\PublicApiV1InvoicesPublicLinkUpdateRequest](../../Models/Operations/PublicApiV1InvoicesPublicLinkUpdateRequest.md) | :heavy_check_mark:                                                                                                             | The request object to use for the request.                                                                                     |

### Response

**[?Operations\PublicApiV1InvoicesPublicLinkUpdateResponse](../../Models/Operations/PublicApiV1InvoicesPublicLinkUpdateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1InvoicesStats

Returns aggregate KPIs for the company: counts by status, revenue, pending and overdue totals, average days to payment, and corrective counts. Filterable by period (defaults to the current year).

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.stats" method="get" path="/companies/{company}/invoices/stats" example="success" -->
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



$response = $sdk->invoices->publicApiV1InvoicesStats(
    company: 'Murphy Inc',
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
| `dateFrom`                                                                                                                                                                                                                                                                                                                                                                   | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Start of the period, by issue date (`YYYY-MM-DD`, inclusive).                                                                                                                                                                                                                                                                                                                |                                                                                                                                                                                                                                                                                                                                                                              |
| `dateTo`                                                                                                                                                                                                                                                                                                                                                                     | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | End of the period, by issue date (`YYYY-MM-DD`, inclusive).                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1InvoicesStatsResponse](../../Models/Operations/PublicApiV1InvoicesStatsResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1InvoicesIssue

Issue a draft without sending it, assigning its series number.
- Irreversible; send an `Idempotency-Key`. Only a `draft`, else 422 `invalid_status_transition`.
- Dated the day of issue (Europe/Madrid); `issued_on` moves to an empty `operation_on`. A later issued invoice in the series: 422 `issue_date_before_series_last_issue_date`.
- Freezes `payment_terms`; `due_on` must be the last instalment (422 `invoice_due_date_not_last_instalment`).
- NO VERI*FACTU: without a valid company certificate, 422 `verifactu_not_eligible` (`signing_certificate_unavailable`); nothing is consumed.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.issue" method="post" path="/companies/{company}/invoices/{invoice}/issue" example="success" -->
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

$request = new Operations\PublicApiV1InvoicesIssueRequest(
    company: 'Hickle and Sons',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->invoices->publicApiV1InvoicesIssue(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                | Type                                                                                                     | Required                                                                                                 | Description                                                                                              |
| -------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                               | [Operations\PublicApiV1InvoicesIssueRequest](../../Models/Operations/PublicApiV1InvoicesIssueRequest.md) | :heavy_check_mark:                                                                                       | The request object to use for the request.                                                               |

### Response

**[?Operations\PublicApiV1InvoicesIssueResponse](../../Models/Operations/PublicApiV1InvoicesIssueResponse.md)**

### Errors

| Error Type                             | Status Code                            | Content Type                           |
| -------------------------------------- | -------------------------------------- | -------------------------------------- |
| Errors\Error                           | 400, 401, 403, 404, 409, 422, 428, 429 | application/json                       |
| Errors\Error                           | 500, 503                               | application/json                       |
| Errors\APIException                    | 4XX, 5XX                               | \*/\*                                  |

## publicApiV1InvoicesListPresentations

Return page-based capture history in descending number. Each summary retains its own purpose, format and availability; capture metadata does not prove prepared PDF bytes. Privacy-erased rows remain metadata tombstones.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.list_presentations" method="get" path="/companies/{company}/invoices/{invoice}/presentation-history" example="retained_history" -->
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

$request = new Operations\PublicApiV1InvoicesListPresentationsRequest(
    company: '6a87b925-57ec-4ed4-ac4b-cbaee3cb74d4',
    invoice: '12091eae-25fd-4411-a9ae-5fb9a6e8a885',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->invoices->publicApiV1InvoicesListPresentations(
    request: $request
);

if ($response->documentPresentationHistoryEnvelope !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                        | Type                                                                                                                             | Required                                                                                                                         | Description                                                                                                                      |
| -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                       | [Operations\PublicApiV1InvoicesListPresentationsRequest](../../Models/Operations/PublicApiV1InvoicesListPresentationsRequest.md) | :heavy_check_mark:                                                                                                               | The request object to use for the request.                                                                                       |

### Response

**[?Operations\PublicApiV1InvoicesListPresentationsResponse](../../Models/Operations/PublicApiV1InvoicesListPresentationsResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1InvoicesActivities

Return the cursor-paginated activity timeline (audit log) of an invoice.
- Covers status transitions, emails, reminders, VeriFactu submission results to AEAT, public link visits and payments.
- The `metadata` of each type follows the `InvoiceActivity` schema.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.activities" method="get" path="/companies/{company}/invoices/{invoice}/activities" example="success" -->
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



$response = $sdk->invoices->publicApiV1InvoicesActivities(
    company: 'Parker, Fahey and Kozey',
    invoice: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `invoice`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the invoice.                                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1InvoicesActivitiesResponse](../../Models/Operations/PublicApiV1InvoicesActivitiesResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1InvoicesCorrectives

Returns all corrective invoices associated with the original invoice. Used to reconstruct the original → rectificativa tree.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.correctives" method="get" path="/companies/{company}/invoices/{invoice}/correctives" example="success" -->
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



$response = $sdk->invoices->publicApiV1InvoicesCorrectives(
    company: 'Hoeger, Heaney and Kuhlman',
    invoice: '<value>',
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
| `invoice`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the invoice.                                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1InvoicesCorrectivesResponse](../../Models/Operations/PublicApiV1InvoicesCorrectivesResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1InvoicesPaymentsList

List the payments registered against an invoice, ordered by payment date. Reverted payments stay in the ledger and are reported with `is_reversed: true` — read the flag, do not infer it from absence.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.payments_list" method="get" path="/companies/{company}/invoices/{invoice}/payments" example="success" -->
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



$response = $sdk->invoices->publicApiV1InvoicesPaymentsList(
    company: 'Fisher LLC',
    invoice: '<value>',
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
| `invoice`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the invoice.                                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1InvoicesPaymentsListResponse](../../Models/Operations/PublicApiV1InvoicesPaymentsListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1InvoicesPaymentsCreate

Register a partial or full payment against an invoice.
- The invoice becomes `partially_paid` until the total is reached, then `paid`.
- 422 for a status that rejects payments or `paid_on` later than today; an earlier date records an advance payment.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.payments_create" method="post" path="/companies/{company}/invoices/{invoice}/payments" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1InvoicesPaymentsCreateRequest(
    company: '8b0d3bf3-0da8-42bf-87c1-2a6e5e09893d',
    invoice: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RegisterInvoicePaymentRequest(
        amount: 5286.57,
        paidOn: LocalDate::parse('2026-03-30'),
        paymentMethod: Components\RegisterInvoicePaymentRequestPaymentMethod::BankTransfer,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesPaymentsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.payments_create" method="post" path="/companies/{company}/invoices/{invoice}/payments" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1InvoicesPaymentsCreateRequest(
    company: '8eb00428-beac-48a8-9bf8-a35fe04d2896',
    invoice: '9c2b62cf-5dcd-4001-8df5-fe39c0a7fbdd',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RegisterInvoicePaymentRequest(
        amount: 5286.57,
        paidOn: LocalDate::parse('2026-03-30'),
        paymentMethod: Components\RegisterInvoicePaymentRequestPaymentMethod::BankTransfer,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesPaymentsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.payments_create" method="post" path="/companies/{company}/invoices/{invoice}/payments" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1InvoicesPaymentsCreateRequest(
    company: '1e90097d-05d0-48fc-8fd2-4678017c9cae',
    invoice: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RegisterInvoicePaymentRequest(
        amount: 5286.57,
        paidOn: LocalDate::parse('2026-03-30'),
        paymentMethod: Components\RegisterInvoicePaymentRequestPaymentMethod::BankTransfer,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesPaymentsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.payments_create" method="post" path="/companies/{company}/invoices/{invoice}/payments" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1InvoicesPaymentsCreateRequest(
    company: 'Doyle, Predovic and Willms',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RegisterInvoicePaymentRequest(
        amount: 5286.57,
        paidOn: LocalDate::parse('2026-03-30'),
        paymentMethod: Components\RegisterInvoicePaymentRequestPaymentMethod::BankTransfer,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesPaymentsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.payments_create" method="post" path="/companies/{company}/invoices/{invoice}/payments" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1InvoicesPaymentsCreateRequest(
    company: '8b0d3bf3-0da8-42bf-87c1-2a6e5e09893d',
    invoice: '1e90097d-05d0-48fc-8fd2-4678017c9cae',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RegisterInvoicePaymentRequest(
        amount: 5286.57,
        paidOn: LocalDate::parse('2026-03-30'),
        paymentMethod: Components\RegisterInvoicePaymentRequestPaymentMethod::BankTransfer,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesPaymentsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.payments_create" method="post" path="/companies/{company}/invoices/{invoice}/payments" example="success" -->
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

$request = new Operations\PublicApiV1InvoicesPaymentsCreateRequest(
    company: 'Rogahn Group',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RegisterInvoicePaymentRequest(
        amount: 5286.57,
        paidOn: LocalDate::parse('2026-03-30'),
        paymentMethod: Components\RegisterInvoicePaymentRequestPaymentMethod::BankTransfer,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesPaymentsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.payments_create" method="post" path="/companies/{company}/invoices/{invoice}/payments" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1InvoicesPaymentsCreateRequest(
    company: '8132aeb9-0faa-4570-a4d3-082182169778',
    invoice: '413705f6-2832-49b1-8bed-85f79370ada8',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RegisterInvoicePaymentRequest(
        amount: 5286.57,
        paidOn: LocalDate::parse('2026-03-30'),
        paymentMethod: Components\RegisterInvoicePaymentRequestPaymentMethod::BankTransfer,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesPaymentsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1InvoicesPaymentsCreateRequest](../../Models/Operations/PublicApiV1InvoicesPaymentsCreateRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1InvoicesPaymentsCreateResponse](../../Models/Operations/PublicApiV1InvoicesPaymentsCreateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1InvoicesStatuses

Lists the closed catalog of invoice statuses with their public `value`, localized `label`, and UI `color`. Use it to populate filters or status pickers instead of hard-coding values.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.statuses" method="get" path="/companies/{company}/invoices/statuses" example="success" -->
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



$response = $sdk->invoices->publicApiV1InvoicesStatuses(
    company: 'Schaden Group',
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
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1InvoicesStatusesResponse](../../Models/Operations/PublicApiV1InvoicesStatusesResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1InvoicesMarkPaid

Mark an invoice as fully paid. Idempotent: if already paid, returns the invoice unchanged. Returns 422 if the invoice is in a status that cannot transition to `paid`, or if `paid_on` is later than today. A `paid_on` earlier than the issue date is accepted: it records an advance payment.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.mark_paid" method="post" path="/companies/{company}/invoices/{invoice}/mark-paid" example="success" -->
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

$request = new Operations\PublicApiV1InvoicesMarkPaidRequest(
    company: 'Schiller - O\'Keefe',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->invoices->publicApiV1InvoicesMarkPaid(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                      | Type                                                                                                           | Required                                                                                                       | Description                                                                                                    |
| -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                     | [Operations\PublicApiV1InvoicesMarkPaidRequest](../../Models/Operations/PublicApiV1InvoicesMarkPaidRequest.md) | :heavy_check_mark:                                                                                             | The request object to use for the request.                                                                     |

### Response

**[?Operations\PublicApiV1InvoicesMarkPaidResponse](../../Models/Operations/PublicApiV1InvoicesMarkPaidResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1InvoicesMarkSent

Record delivery of an invoice; the meaning depends on the API version.
- Before `2026-10-01` it issues a draft, dated the day of issue.
- From `2026-10-01` it only sets `is_sent`, on `issued`/`overdue`; else 422 `invoice_cannot_be_marked_as_sent`.

**Signing certificate (NO VERI*FACTU).** Only in that mode, a missing or invalid company certificate is rejected before the invoice is issued: 422 `verifactu_not_eligible`, `error.subcode: signing_certificate_unavailable`; nothing is consumed, so fix it and repeat the call.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.mark_sent" method="post" path="/companies/{company}/invoices/{invoice}/mark-sent" example="success" -->
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



$response = $sdk->invoices->publicApiV1InvoicesMarkSent(
    company: 'Graham, Walker and Mann',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `invoice`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the invoice.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1InvoicesMarkSentResponse](../../Models/Operations/PublicApiV1InvoicesMarkSentResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1InvoicesPdfPreview

Stream a non-fiscal draft PDF (`application/pdf`) of an invoice marked BORRADOR, with a placeholder number and no VeriFactu QR. Nothing is persisted: the series counter and fingerprint are untouched. Returns 422 for an already issued invoice — use the standard `pdf` endpoint instead.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.pdf_preview" method="get" path="/companies/{company}/invoices/{invoice}/pdf/preview" -->
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



$response = $sdk->invoices->publicApiV1InvoicesPdfPreview(
    company: 'Powlowski Inc',
    invoice: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->bytes !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `invoice`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the invoice.                                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1InvoicesPdfPreviewResponse](../../Models/Operations/PublicApiV1InvoicesPdfPreviewResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 409, 429 | application/json             |
| Errors\Error                 | 500, 503                     | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1InvoicesReminderPreview

Renders the HTML, subject, and resolved recipients of the reminder email without sending it. Same override fields as send-reminder.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.reminder_preview" method="post" path="/companies/{company}/invoices/{invoice}/reminder-preview" example="success" -->
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



$response = $sdk->invoices->publicApiV1InvoicesReminderPreview(
    company: 'Kovacek - Kreiger',
    invoice: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: $body

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `invoice`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the invoice.                                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |
| `body`                                                                                                                                                                                                                                                                                                                                                                       | [?Components\SendInvoiceReminderV1Request](../../Models/Components/SendInvoiceReminderV1Request.md)                                                                                                                                                                                                                                                                          | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | N/A                                                                                                                                                                                                                                                                                                                                                                          |                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1InvoicesReminderPreviewResponse](../../Models/Operations/PublicApiV1InvoicesReminderPreviewResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1InvoicesPaymentReceipt

Stream the PDF receipt of one invoice payment, partial payments included; it is not an invoice and has no VERI*FACTU data.
- Without `payment_id`, returns the most recent active payment.
- 422 with no active payments or a reversed payment; 404 if `payment_id` is not of this invoice.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.payment_receipt" method="get" path="/companies/{company}/invoices/{invoice}/payment-receipt" -->
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



$response = $sdk->invoices->publicApiV1InvoicesPaymentReceipt(
    company: 'Schoen, Swaniawski and Mraz',
    invoice: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->bytes !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `invoice`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the invoice.                                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `paymentId`                                                                                                                                                                                                                                                                                                                                                                  | *?string*                                                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | ID (UUID v7) of the payment whose receipt you want. Without it, the most recent active payment.                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1InvoicesPaymentReceiptResponse](../../Models/Operations/PublicApiV1InvoicesPaymentReceiptResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1InvoicesReschedule

Move the issuance date of a scheduled invoice; it stays `scheduled` throughout and never returns to `draft`.
- Only `scheduled_for` changes (strictly in the future); `scheduled_action` is kept.
- With instalments, `due_on` must be the last one, else 422 `invoice_due_date_not_last_instalment`.
- Any other status returns 422.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.reschedule" method="patch" path="/companies/{company}/invoices/{invoice}/reschedule" example="api_key_revoked" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;
use Factuarea\Sdk\Utils;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1InvoicesRescheduleRequest(
    company: 'eaf952c1-e7b5-4b33-be90-3b667caa806d',
    invoice: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RescheduleInvoiceRequest(
        scheduledFor: Utils\Utils::parseDateTime('2026-08-26T11:02:45.567Z'),
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesReschedule(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.reschedule" method="patch" path="/companies/{company}/invoices/{invoice}/reschedule" example="factuarea_version_invalid_format" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;
use Factuarea\Sdk\Utils;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1InvoicesRescheduleRequest(
    company: '78b145aa-31a9-4d55-991b-c2f7dec82c72',
    invoice: 'cbd0fba9-5f61-4472-849a-c458e9a0303f',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RescheduleInvoiceRequest(
        scheduledFor: Utils\Utils::parseDateTime('2026-08-26T11:02:45.567Z'),
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesReschedule(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.reschedule" method="patch" path="/companies/{company}/invoices/{invoice}/reschedule" example="invalid_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;
use Factuarea\Sdk\Utils;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1InvoicesRescheduleRequest(
    company: 'de86c582-3304-40c6-b187-9b48f6a5f6af',
    invoice: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RescheduleInvoiceRequest(
        scheduledFor: Utils\Utils::parseDateTime('2026-08-26T11:02:45.567Z'),
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesReschedule(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.reschedule" method="patch" path="/companies/{company}/invoices/{invoice}/reschedule" example="missing_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;
use Factuarea\Sdk\Utils;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1InvoicesRescheduleRequest(
    company: 'Corwin and Sons',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RescheduleInvoiceRequest(
        scheduledFor: Utils\Utils::parseDateTime('2026-08-26T11:02:45.567Z'),
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesReschedule(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.reschedule" method="patch" path="/companies/{company}/invoices/{invoice}/reschedule" example="retired_active_profile_header" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;
use Factuarea\Sdk\Utils;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1InvoicesRescheduleRequest(
    company: 'eaf952c1-e7b5-4b33-be90-3b667caa806d',
    invoice: 'de86c582-3304-40c6-b187-9b48f6a5f6af',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RescheduleInvoiceRequest(
        scheduledFor: Utils\Utils::parseDateTime('2026-08-26T11:02:45.567Z'),
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesReschedule(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.reschedule" method="patch" path="/companies/{company}/invoices/{invoice}/reschedule" example="success" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;
use Factuarea\Sdk\Utils;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1InvoicesRescheduleRequest(
    company: 'Orn, Wehner and Medhurst',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RescheduleInvoiceRequest(
        scheduledFor: Utils\Utils::parseDateTime('2026-08-26T11:02:45.567Z'),
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesReschedule(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.reschedule" method="patch" path="/companies/{company}/invoices/{invoice}/reschedule" example="unsupported_api_version" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;
use Factuarea\Sdk\Utils;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1InvoicesRescheduleRequest(
    company: '5106f334-affd-494e-91ee-57c5b6f025f1',
    invoice: '7f1ad7b3-5147-45fe-80f4-caf7e31db5b4',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RescheduleInvoiceRequest(
        scheduledFor: Utils\Utils::parseDateTime('2026-08-26T11:02:45.567Z'),
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesReschedule(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                          | Type                                                                                                               | Required                                                                                                           | Description                                                                                                        |
| ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                         | [Operations\PublicApiV1InvoicesRescheduleRequest](../../Models/Operations/PublicApiV1InvoicesRescheduleRequest.md) | :heavy_check_mark:                                                                                                 | The request object to use for the request.                                                                         |

### Response

**[?Operations\PublicApiV1InvoicesRescheduleResponse](../../Models/Operations/PublicApiV1InvoicesRescheduleResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1InvoicesPaymentsRevert

Void a payment with a required `reason`; the payment stays flagged `is_reversed: true` and is never deleted.
- Irreversible: to restate the collection, register a new payment.
- 422 `payment_already_reversed`, `payment_reversal_reason_invalid` or `reversal_reason_reserved` (`issued_in_error`).
- The invoice returns to `overdue` or `sent`.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.payments_revert" method="post" path="/companies/{company}/invoices/{invoice}/payments/{payment}/reversal" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1InvoicesPaymentsRevertRequest(
    company: 'dab90c85-ebf9-4cd0-af82-baf00280f2a8',
    invoice: '0f8d557d-8da0-428e-b929-099ce572b1eb',
    payment: '594f4a59-1bc1-455f-8947-00f821afb0d2',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RevertInvoicePaymentRequest(
        reason: '<value>',
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesPaymentsRevert(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.payments_revert" method="post" path="/companies/{company}/invoices/{invoice}/payments/{payment}/reversal" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1InvoicesPaymentsRevertRequest(
    company: 'Hahn - Beier',
    invoice: '<value>',
    payment: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RevertInvoicePaymentRequest(
        reason: '<value>',
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesPaymentsRevert(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.payments_revert" method="post" path="/companies/{company}/invoices/{invoice}/payments/{payment}/reversal" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1InvoicesPaymentsRevertRequest(
    company: 'abf10722-50df-49a4-8119-253dbc24a289',
    invoice: 'da6a72c7-f329-485c-bcc1-04a1696d0ad5',
    payment: 'b50451ef-4a9c-443a-a55d-a737b1c3d94d',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RevertInvoicePaymentRequest(
        reason: '<value>',
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesPaymentsRevert(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.payments_revert" method="post" path="/companies/{company}/invoices/{invoice}/payments/{payment}/reversal" example="success" -->
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

$request = new Operations\PublicApiV1InvoicesPaymentsRevertRequest(
    company: 'Kuphal - Champlin',
    invoice: '<value>',
    payment: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RevertInvoicePaymentRequest(
        reason: '<value>',
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesPaymentsRevert(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.payments_revert" method="post" path="/companies/{company}/invoices/{invoice}/payments/{payment}/reversal" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1InvoicesPaymentsRevertRequest(
    company: 'bbfd5eed-4e49-46d1-beb9-589aed98d01c',
    invoice: '47290d8a-4241-4012-a4c0-938693c5b4bb',
    payment: 'c4cad8d7-72b6-4142-8f8c-7f883ea03e5e',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RevertInvoicePaymentRequest(
        reason: '<value>',
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesPaymentsRevert(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1InvoicesPaymentsRevertRequest](../../Models/Operations/PublicApiV1InvoicesPaymentsRevertRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1InvoicesPaymentsRevertResponse](../../Models/Operations/PublicApiV1InvoicesPaymentsRevertResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1InvoicesRevisePresentation

Append a derived capture using the authentic presentation_revision preview of the exact parent, target, policy and current source. Send the source token and preview fingerprint; a delivery or illustrative preview cannot authorize this action. Existing document state, fiscal numbering, references, instalments and totals remain intact. This action does not render, publish or send a PDF. Personal-data erasure is terminal.

### Example Usage: captured_visual_revision

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.revise_presentation" method="post" path="/companies/{company}/invoices/{invoice}/presentation-revisions" example="captured_visual_revision" -->
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

$request = new Operations\PublicApiV1InvoicesRevisePresentationRequest(
    company: '8d7e3104-0113-4db1-b322-46e22a283b4d',
    invoice: '01389fdc-7f3f-4703-887c-4b52f9920509',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReviseDocumentPresentationRequest(
        parentId: '019e0000-0000-7000-8000-000000000201',
        templateId: null,
        expectedVersion: 1,
        reason: 'Actualizar la presentación conservando el contenido.',
        expectedSourceToken: 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
        expectedPreviewContextFingerprint: 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb',
        expectedTemplateRevisionId: null,
        expectedCommercialIdentityRevisionId: null,
        purpose: Components\ReviseDocumentPresentationRequestPurpose::Complete,
        format: Components\ReviseDocumentPresentationRequestFormat::A4,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesRevisePresentation(
    request: $request
);

if ($response->documentPresentationRevisionEnvelope !== null) {
    // handle response
}
```
### Example Usage: current_legal_revision

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.revise_presentation" method="post" path="/companies/{company}/invoices/{invoice}/presentation-revisions" example="current_legal_revision" -->
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

$request = new Operations\PublicApiV1InvoicesRevisePresentationRequest(
    company: '557d6bb6-feb7-47c0-8121-c43225354bd5',
    invoice: 'bc658362-f25f-41e7-9831-54a180cf38ea',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReviseDocumentPresentationRequest(
        parentId: '019e0000-0000-7000-8000-000000000201',
        templateId: null,
        expectedVersion: 1,
        reason: 'Actualizar la presentación conservando el contenido.',
        expectedSourceToken: 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
        expectedPreviewContextFingerprint: 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb',
        expectedTemplateRevisionId: null,
        expectedCommercialIdentityRevisionId: null,
        purpose: Components\ReviseDocumentPresentationRequestPurpose::Complete,
        format: Components\ReviseDocumentPresentationRequestFormat::A4,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesRevisePresentation(
    request: $request
);

if ($response->documentPresentationRevisionEnvelope !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.revise_presentation" method="post" path="/companies/{company}/invoices/{invoice}/presentation-revisions" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1InvoicesRevisePresentationRequest(
    company: 'b6399c05-62fd-4d0d-aa19-3952c934e5e3',
    invoice: 'd7dfb3c9-130f-42d4-97e2-b34a4ae14094',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReviseDocumentPresentationRequest(
        parentId: '019e0000-0000-7000-8000-000000000201',
        templateId: null,
        expectedVersion: 1,
        reason: 'Actualizar la presentación conservando el contenido.',
        expectedSourceToken: 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
        expectedPreviewContextFingerprint: 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb',
        expectedTemplateRevisionId: null,
        expectedCommercialIdentityRevisionId: null,
        purpose: Components\ReviseDocumentPresentationRequestPurpose::Complete,
        format: Components\ReviseDocumentPresentationRequestFormat::A4,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesRevisePresentation(
    request: $request
);

if ($response->documentPresentationRevisionEnvelope !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.revise_presentation" method="post" path="/companies/{company}/invoices/{invoice}/presentation-revisions" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1InvoicesRevisePresentationRequest(
    company: '3729cd1b-1239-43d3-a337-79cc9dc99f2b',
    invoice: 'ed643947-66d7-4f03-851a-737cbcce79d7',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReviseDocumentPresentationRequest(
        parentId: '019e0000-0000-7000-8000-000000000201',
        templateId: null,
        expectedVersion: 1,
        reason: 'Actualizar la presentación conservando el contenido.',
        expectedSourceToken: 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
        expectedPreviewContextFingerprint: 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb',
        expectedTemplateRevisionId: null,
        expectedCommercialIdentityRevisionId: null,
        purpose: Components\ReviseDocumentPresentationRequestPurpose::Complete,
        format: Components\ReviseDocumentPresentationRequestFormat::A4,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesRevisePresentation(
    request: $request
);

if ($response->documentPresentationRevisionEnvelope !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.revise_presentation" method="post" path="/companies/{company}/invoices/{invoice}/presentation-revisions" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1InvoicesRevisePresentationRequest(
    company: '1b3b7ee4-4a86-4040-8893-8932fe76368a',
    invoice: '4ed47aac-7041-4243-80de-3d56f16955dc',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReviseDocumentPresentationRequest(
        parentId: '019e0000-0000-7000-8000-000000000201',
        templateId: null,
        expectedVersion: 1,
        reason: 'Actualizar la presentación conservando el contenido.',
        expectedSourceToken: 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
        expectedPreviewContextFingerprint: 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb',
        expectedTemplateRevisionId: null,
        expectedCommercialIdentityRevisionId: null,
        purpose: Components\ReviseDocumentPresentationRequestPurpose::Complete,
        format: Components\ReviseDocumentPresentationRequestFormat::A4,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesRevisePresentation(
    request: $request
);

if ($response->documentPresentationRevisionEnvelope !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                          | Type                                                                                                                               | Required                                                                                                                           | Description                                                                                                                        |
| ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                         | [Operations\PublicApiV1InvoicesRevisePresentationRequest](../../Models/Operations/PublicApiV1InvoicesRevisePresentationRequest.md) | :heavy_check_mark:                                                                                                                 | The request object to use for the request.                                                                                         |

### Response

**[?Operations\PublicApiV1InvoicesRevisePresentationResponse](../../Models/Operations/PublicApiV1InvoicesRevisePresentationResponse.md)**

### Errors

| Error Type                                  | Status Code                                 | Content Type                                |
| ------------------------------------------- | ------------------------------------------- | ------------------------------------------- |
| Errors\Error                                | 400, 401, 403, 404, 409, 410, 422, 428, 429 | application/json                            |
| Errors\Error                                | 500, 503                                    | application/json                            |
| Errors\APIException                         | 4XX, 5XX                                    | \*/\*                                       |

## publicApiV1InvoicesSchedule

Reserve the issuance of a draft invoice for a future instant (`scheduled_for`).
- The invoice moves to `scheduled` and keeps its `BORRADOR` number; no series number is consumed yet.
- Only a `draft` can be scheduled and the date must be in the future (422).
- With instalments, `due_on` must be the last one, else 422 `invoice_due_date_not_last_instalment`.
- It is issued when it runs, dated that day (not the draft date); if the series then has a later issued invoice it stays `scheduled` and is retried. `issue_and_send` also emails the client.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.schedule" method="post" path="/companies/{company}/invoices/{invoice}/schedule" example="api_key_revoked" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;
use Factuarea\Sdk\Utils;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1InvoicesScheduleRequest(
    company: 'e46e8c23-1c75-4c3b-820a-3dd85f1eab4d',
    invoice: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ScheduleInvoiceRequest(
        scheduledFor: Utils\Utils::parseDateTime('2026-10-13T03:00:05.244Z'),
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesSchedule(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.schedule" method="post" path="/companies/{company}/invoices/{invoice}/schedule" example="factuarea_version_invalid_format" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;
use Factuarea\Sdk\Utils;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1InvoicesScheduleRequest(
    company: '753db9d4-3610-439e-82f5-130939eb5107',
    invoice: 'd26e5458-e541-42c8-8f1d-ac906df4db09',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ScheduleInvoiceRequest(
        scheduledFor: Utils\Utils::parseDateTime('2026-10-13T03:00:05.244Z'),
        scheduledAction: Components\ScheduleInvoiceRequestScheduledAction::IssueAndSend,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesSchedule(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.schedule" method="post" path="/companies/{company}/invoices/{invoice}/schedule" example="invalid_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;
use Factuarea\Sdk\Utils;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1InvoicesScheduleRequest(
    company: '114cf64f-7b52-433a-bead-723b52cf3430',
    invoice: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ScheduleInvoiceRequest(
        scheduledFor: Utils\Utils::parseDateTime('2026-10-13T03:00:05.244Z'),
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesSchedule(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.schedule" method="post" path="/companies/{company}/invoices/{invoice}/schedule" example="missing_api_key" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;
use Factuarea\Sdk\Utils;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1InvoicesScheduleRequest(
    company: 'Runte Group',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ScheduleInvoiceRequest(
        scheduledFor: Utils\Utils::parseDateTime('2026-10-13T03:00:05.244Z'),
        scheduledAction: Components\ScheduleInvoiceRequestScheduledAction::IssueAndSend,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesSchedule(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.schedule" method="post" path="/companies/{company}/invoices/{invoice}/schedule" example="retired_active_profile_header" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;
use Factuarea\Sdk\Utils;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1InvoicesScheduleRequest(
    company: 'e46e8c23-1c75-4c3b-820a-3dd85f1eab4d',
    invoice: '114cf64f-7b52-433a-bead-723b52cf3430',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ScheduleInvoiceRequest(
        scheduledFor: Utils\Utils::parseDateTime('2026-10-13T03:00:05.244Z'),
        scheduledAction: Components\ScheduleInvoiceRequestScheduledAction::IssueAndSend,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesSchedule(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.schedule" method="post" path="/companies/{company}/invoices/{invoice}/schedule" example="success" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;
use Factuarea\Sdk\Utils;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1InvoicesScheduleRequest(
    company: 'Gorczany, Kemmer and Tillman',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ScheduleInvoiceRequest(
        scheduledFor: Utils\Utils::parseDateTime('2026-10-13T03:00:05.244Z'),
        scheduledAction: Components\ScheduleInvoiceRequestScheduledAction::IssueAndSend,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesSchedule(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.schedule" method="post" path="/companies/{company}/invoices/{invoice}/schedule" example="unsupported_api_version" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;
use Factuarea\Sdk\Utils;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            bearerAuth: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1InvoicesScheduleRequest(
    company: '0aa1cc89-870e-45bf-b112-58aeeda1fe2d',
    invoice: '2e5d5d7d-7398-4924-b25d-8fdef25a0843',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ScheduleInvoiceRequest(
        scheduledFor: Utils\Utils::parseDateTime('2026-10-13T03:00:05.244Z'),
        scheduledAction: Components\ScheduleInvoiceRequestScheduledAction::IssueAndSend,
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesSchedule(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                      | Type                                                                                                           | Required                                                                                                       | Description                                                                                                    |
| -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                     | [Operations\PublicApiV1InvoicesScheduleRequest](../../Models/Operations/PublicApiV1InvoicesScheduleRequest.md) | :heavy_check_mark:                                                                                             | The request object to use for the request.                                                                     |

### Response

**[?Operations\PublicApiV1InvoicesScheduleResponse](../../Models/Operations/PublicApiV1InvoicesScheduleResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1InvoicesSend

Send an invoice to the client by email, to the email on file unless the payload overrides it.
- `attach_facturae: true` attaches the Facturae XML, `submission_id` the exact XML retained for that FACe submission; nothing is submitted to FACe.
- A draft is issued first, dated the day of issue.

**Signing certificate (NO VERI*FACTU).** Only in that mode, a missing or invalid company certificate is rejected before the invoice is issued: 422 `verifactu_not_eligible`, `error.subcode: signing_certificate_unavailable`; nothing is consumed, so fix it and repeat the call.

### Example Usage: document_send_email

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.send" method="post" path="/companies/{company}/invoices/{invoice}/send" example="document_send_email" -->
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

$request = new Operations\PublicApiV1InvoicesSendRequest(
    company: 'Crona - Daniel',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SendInvoiceRequest(
        to: 'cliente@example.com',
        cc: [
            'copia@example.com',
        ],
        subject: 'Su documento de Factuarea',
        body: 'Adjuntamos el documento solicitado.',
    ),
);

$response = $sdk->invoices->publicApiV1InvoicesSend(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.send" method="post" path="/companies/{company}/invoices/{invoice}/send" example="success" -->
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

$request = new Operations\PublicApiV1InvoicesSendRequest(
    company: 'O\'Conner, Kulas and Swaniawski',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->invoices->publicApiV1InvoicesSend(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                              | Type                                                                                                   | Required                                                                                               | Description                                                                                            |
| ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                             | [Operations\PublicApiV1InvoicesSendRequest](../../Models/Operations/PublicApiV1InvoicesSendRequest.md) | :heavy_check_mark:                                                                                     | The request object to use for the request.                                                             |

### Response

**[?Operations\PublicApiV1InvoicesSendResponse](../../Models/Operations/PublicApiV1InvoicesSendResponse.md)**

### Errors

| Error Type                             | Status Code                            | Content Type                           |
| -------------------------------------- | -------------------------------------- | -------------------------------------- |
| Errors\Error                           | 400, 401, 403, 404, 409, 422, 428, 429 | application/json                       |
| Errors\Error                           | 500, 503                               | application/json                       |
| Errors\APIException                    | 4XX, 5XX                               | \*/\*                                  |

## publicApiV1InvoicesSendReminder

Emails a payment reminder to the customer for this invoice. Accepts optional `email`, `subject`, `message`, `cc`, `bcc` overrides.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.send_reminder" method="post" path="/companies/{company}/invoices/{invoice}/send-reminder" example="success" -->
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

$request = new Operations\PublicApiV1InvoicesSendReminderRequest(
    company: 'Skiles, Murray and Cremin',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->invoices->publicApiV1InvoicesSendReminder(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                              | Type                                                                                                                   | Required                                                                                                               | Description                                                                                                            |
| ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                             | [Operations\PublicApiV1InvoicesSendReminderRequest](../../Models/Operations/PublicApiV1InvoicesSendReminderRequest.md) | :heavy_check_mark:                                                                                                     | The request object to use for the request.                                                                             |

### Response

**[?Operations\PublicApiV1InvoicesSendReminderResponse](../../Models/Operations/PublicApiV1InvoicesSendReminderResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1InvoicesSubstituteSimplified

Groups N simplified invoices (F2) under a single substitutive full invoice (F3) with complete recipient data. Marks the originals as substituted.

**Signing certificate (NO VERI*FACTU).** Only in that mode, a missing or invalid company certificate is rejected before the invoice is issued: 422 `verifactu_not_eligible`, `error.subcode: signing_certificate_unavailable`; nothing is consumed, so fix it and repeat the call.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.substitute_simplified" method="post" path="/companies/{company}/invoices/substitute-simplified" example="api_key_revoked" -->
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

$body = new Components\SubstituteSimplifiedV1Request(
    clientId: '029b489a-3f6a-4b22-b759-f00a5949b6bc',
    simplifiedInvoiceIds: [
        'fb1627b9-f8b4-4445-9b21-e6f860e3b5e6',
        '4d5de73b-01f6-438b-9870-1c513434f97d',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesSubstituteSimplified(
    company: '029b489a-3f6a-4b22-b759-f00a5949b6bc',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.substitute_simplified" method="post" path="/companies/{company}/invoices/substitute-simplified" example="factuarea_version_invalid_format" -->
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

$body = new Components\SubstituteSimplifiedV1Request(
    clientId: '029b489a-3f6a-4b22-b759-f00a5949b6bc',
    simplifiedInvoiceIds: [
        'fb1627b9-f8b4-4445-9b21-e6f860e3b5e6',
        '4d5de73b-01f6-438b-9870-1c513434f97d',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesSubstituteSimplified(
    company: '9fb1627b-9f8b-4444-95b2-1e6f860e3b5e',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.substitute_simplified" method="post" path="/companies/{company}/invoices/substitute-simplified" example="invalid_api_key" -->
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

$body = new Components\SubstituteSimplifiedV1Request(
    clientId: '029b489a-3f6a-4b22-b759-f00a5949b6bc',
    simplifiedInvoiceIds: [
        'fb1627b9-f8b4-4445-9b21-e6f860e3b5e6',
        '4d5de73b-01f6-438b-9870-1c513434f97d',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesSubstituteSimplified(
    company: '9fb1627b-9f8b-4444-95b2-1e6f860e3b5e',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.substitute_simplified" method="post" path="/companies/{company}/invoices/substitute-simplified" example="missing_api_key" -->
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

$body = new Components\SubstituteSimplifiedV1Request(
    clientId: '029b489a-3f6a-4b22-b759-f00a5949b6bc',
    simplifiedInvoiceIds: [
        'fb1627b9-f8b4-4445-9b21-e6f860e3b5e6',
        '4d5de73b-01f6-438b-9870-1c513434f97d',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesSubstituteSimplified(
    company: 'Farrell, Willms and Hudson',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.substitute_simplified" method="post" path="/companies/{company}/invoices/substitute-simplified" example="retired_active_profile_header" -->
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

$body = new Components\SubstituteSimplifiedV1Request(
    clientId: '029b489a-3f6a-4b22-b759-f00a5949b6bc',
    simplifiedInvoiceIds: [
        'fb1627b9-f8b4-4445-9b21-e6f860e3b5e6',
        '4d5de73b-01f6-438b-9870-1c513434f97d',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesSubstituteSimplified(
    company: '029b489a-3f6a-4b22-b759-f00a5949b6bc',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.substitute_simplified" method="post" path="/companies/{company}/invoices/substitute-simplified" example="success" -->
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

$body = new Components\SubstituteSimplifiedV1Request(
    clientId: '029b489a-3f6a-4b22-b759-f00a5949b6bc',
    simplifiedInvoiceIds: [
        'fb1627b9-f8b4-4445-9b21-e6f860e3b5e6',
        '4d5de73b-01f6-438b-9870-1c513434f97d',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesSubstituteSimplified(
    company: 'Hackett, Labadie and Metz',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.substitute_simplified" method="post" path="/companies/{company}/invoices/substitute-simplified" example="unsupported_api_version" -->
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

$body = new Components\SubstituteSimplifiedV1Request(
    clientId: '029b489a-3f6a-4b22-b759-f00a5949b6bc',
    simplifiedInvoiceIds: [
        'fb1627b9-f8b4-4445-9b21-e6f860e3b5e6',
        '4d5de73b-01f6-438b-9870-1c513434f97d',
    ],
);

$response = $sdk->invoices->publicApiV1InvoicesSubstituteSimplified(
    company: '54d5de73-b01f-4638-bb87-01c513434f97',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Components\SubstituteSimplifiedV1Request](../../Models/Components/SubstituteSimplifiedV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1InvoicesSubstituteSimplifiedResponse](../../Models/Operations/PublicApiV1InvoicesSubstituteSimplifiedResponse.md)**

### Errors

| Error Type                             | Status Code                            | Content Type                           |
| -------------------------------------- | -------------------------------------- | -------------------------------------- |
| Errors\Error                           | 400, 401, 402, 403, 404, 409, 422, 429 | application/json                       |
| Errors\Error                           | 500, 503                               | application/json                       |
| Errors\APIException                    | 4XX, 5XX                               | \*/\*                                  |

## publicApiV1InvoicesUnschedule

Cancel a scheduled issuance and return the invoice to `draft`.
- Leaves no fiscal trace: no number consumed, nothing registered with VeriFactu, no email sent.
- Only while the invoice is `scheduled`; a draft or an already issued invoice returns 422.
- To only move the date, use `PATCH /v1/companies/{company}/invoices/{invoice}/reschedule`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.unschedule" method="post" path="/companies/{company}/invoices/{invoice}/unschedule" example="success" -->
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



$response = $sdk->invoices->publicApiV1InvoicesUnschedule(
    company: 'Emmerich, Jones and Schinner',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                   |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `invoice`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Public identifier (UUID v7) of the invoice.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                               | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |

### Response

**[?Operations\PublicApiV1InvoicesUnscheduleResponse](../../Models/Operations/PublicApiV1InvoicesUnscheduleResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1InvoicesUnsend

Clear the delivery mark of an issued invoice (`is_sent` back to `false`) without changing its fiscal status.
- Only `issued` and `overdue` invoices qualify; otherwise 422 `invalid_status_transition`.
- Number, VeriFactu record and stock are untouched; idempotent when the invoice is not marked as sent.
- To undo the sale itself, void the invoice or issue a corrective.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.unsend" method="post" path="/companies/{company}/invoices/{invoice}/unsend" example="success" -->
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



$response = $sdk->invoices->publicApiV1InvoicesUnsend(
    company: 'Ondricka, Mosciski and Murray',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                   |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `invoice`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Public identifier (UUID v7) of the invoice.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                               | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |

### Response

**[?Operations\PublicApiV1InvoicesUnsendResponse](../../Models/Operations/PublicApiV1InvoicesUnsendResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1InvoicesVoid

Withdraw an issued invoice; it moves to `annulled`.
- Irreversible; only `sent` or `overdue` invoices qualify, a paid one needs a corrective.
- The VeriFactu cancellation is queued asynchronously: a 200 is not AEAT acceptance.

**Signing certificate (NO VERI*FACTU).** Only in that mode, a missing or invalid company certificate is rejected before the invoice is annulled: 422 `verifactu_not_eligible`, `error.subcode: signing_certificate_unavailable`; nothing is consumed, so fix it and repeat the call.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.invoices.void" method="post" path="/companies/{company}/invoices/{invoice}/void" example="success" -->
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

$request = new Operations\PublicApiV1InvoicesVoidRequest(
    company: 'Predovic, Mayer and Ullrich',
    invoice: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->invoices->publicApiV1InvoicesVoid(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                              | Type                                                                                                   | Required                                                                                               | Description                                                                                            |
| ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                             | [Operations\PublicApiV1InvoicesVoidRequest](../../Models/Operations/PublicApiV1InvoicesVoidRequest.md) | :heavy_check_mark:                                                                                     | The request object to use for the request.                                                             |

### Response

**[?Operations\PublicApiV1InvoicesVoidResponse](../../Models/Operations/PublicApiV1InvoicesVoidResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |