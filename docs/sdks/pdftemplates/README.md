# PdfTemplates

## Overview

### Available Operations

* [publicApiV1PdfTemplatesArchive](#publicapiv1pdftemplatesarchive) - Archive a PDF template
* [publicApiV1PdfTemplatesCreate](#publicapiv1pdftemplatescreate) - Create a PDF template
* [publicApiV1PdfTemplatesList](#publicapiv1pdftemplateslist) - List PDF templates
* [publicApiV1PdfTemplatesDuplicate](#publicapiv1pdftemplatesduplicate) - Duplicate a PDF template
* [publicApiV1PdfTemplatesFind](#publicapiv1pdftemplatesfind) - Retrieve a PDF template
* [publicApiV1PdfTemplatesUpdate](#publicapiv1pdftemplatesupdate) - Update PDF template metadata
* [publicApiV1PdfTemplatesContactDefaultGet](#publicapiv1pdftemplatescontactdefaultget) - Retrieve a contact PDF template preference
* [publicApiV1PdfTemplatesContactDefaultSet](#publicapiv1pdftemplatescontactdefaultset) - Set a contact PDF template preference
* [publicApiV1PdfTemplatesDefaultsGet](#publicapiv1pdftemplatesdefaultsget) - Retrieve the PDF template defaults
* [publicApiV1PdfTemplatesDefaultsSet](#publicapiv1pdftemplatesdefaultsset) - Update the PDF template defaults
* [publicApiV1PdfTemplatesImpact](#publicapiv1pdftemplatesimpact) - Inspect the impact of a PDF template change
* [publicApiV1PdfTemplatesAssetGet](#publicapiv1pdftemplatesassetget) - Retrieve a PDF template image
* [publicApiV1PdfTemplatesRevision](#publicapiv1pdftemplatesrevision) - Retrieve a PDF template revision
* [publicApiV1PdfTemplatesPublishRevision](#publicapiv1pdftemplatespublishrevision) - Publish a PDF template revision
* [publicApiV1PdfTemplatesRestore](#publicapiv1pdftemplatesrestore) - Restore a PDF template
* [publicApiV1PdfTemplatesAssetUpload](#publicapiv1pdftemplatesassetupload) - Upload a PDF template image

## publicApiV1PdfTemplatesArchive

Archive a template and resolve every association that depends on it in one atomic step. First read the impact of the template with `action=archive`, then send its `expected_impact_fingerprint`, the `expected_version` of the template and one decision per listed association in `replacements` (`replace`, `clear` or `pause`). A changed impact returns 409 `pdf_library_impact_conflict` and nothing is modified. Archived templates keep their revisions and the documents that already used them. Requires `pdf_templates:write` and supports `Idempotency-Key`.

### Example Usage: archived

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.archive" method="post" path="/companies/{company}/pdf-templates/{template}/archive" example="archived" -->
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

$request = new Operations\PublicApiV1PdfTemplatesArchiveRequest(
    company: '58af7be3-a200-4fcd-a856-b091f938d157',
    template: 'f1d75813-d184-44e9-b48e-eec1358b7197',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesArchiveRequestBody(
        expectedVersion: 432019,
        expectedImpactFingerprint: '<value>',
        replacements: [],
    ),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesArchive(
    request: $request
);

if ($response->tenantPdfTemplate !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.archive" method="post" path="/companies/{company}/pdf-templates/{template}/archive" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1PdfTemplatesArchiveRequest(
    company: 'aa50135b-090f-4732-b692-904df9554cb4',
    template: '2e209c93-d7b5-4f4c-a3fd-ab25bd09001b',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesArchiveRequestBody(
        expectedVersion: 432019,
        expectedImpactFingerprint: '<value>',
        replacements: [],
    ),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesArchive(
    request: $request
);

if ($response->tenantPdfTemplate !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.archive" method="post" path="/companies/{company}/pdf-templates/{template}/archive" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1PdfTemplatesArchiveRequest(
    company: 'ab72dda1-c662-4293-99ab-a7e5d69119a0',
    template: 'a119004c-7afd-429f-8a97-e898d254b1c2',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesArchiveRequestBody(
        expectedVersion: 432019,
        expectedImpactFingerprint: '<value>',
        replacements: [],
    ),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesArchive(
    request: $request
);

if ($response->tenantPdfTemplate !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.archive" method="post" path="/companies/{company}/pdf-templates/{template}/archive" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1PdfTemplatesArchiveRequest(
    company: '004319b9-8309-4f91-a7c3-ac791ce98f8a',
    template: '5d332022-25e1-42f4-8862-01c0485384d2',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesArchiveRequestBody(
        expectedVersion: 432019,
        expectedImpactFingerprint: '<value>',
        replacements: [],
    ),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesArchive(
    request: $request
);

if ($response->tenantPdfTemplate !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                            | Type                                                                                                                 | Required                                                                                                             | Description                                                                                                          |
| -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                           | [Operations\PublicApiV1PdfTemplatesArchiveRequest](../../Models/Operations/PublicApiV1PdfTemplatesArchiveRequest.md) | :heavy_check_mark:                                                                                                   | The request object to use for the request.                                                                           |

### Response

**[?Operations\PublicApiV1PdfTemplatesArchiveResponse](../../Models/Operations/PublicApiV1PdfTemplatesArchiveResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1PdfTemplatesCreate

Create a template with its first revision; only `name` is required (optional `id` and `revision_id`, UUID v7). Returns 201.
- `document_kind` is `commercial` by default or `transport-document` for a transport control document (DeCA) template; it never changes, and a DeCA template accepts only `branding`, `typography` and `colors`.
- A logo or brand image needs an effective brand; HTML, CSS, scripts, URLs and legal or bank data are rejected.
- Company administrators only, with `pdf_templates:write`; supports `Idempotency-Key`.

### Example Usage: created

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.create" method="post" path="/companies/{company}/pdf-templates" example="created" -->
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

$body = new Operations\PublicApiV1PdfTemplatesCreateRequestBody(
    name: '<value>',
    documentKind: Operations\PublicApiV1PdfTemplatesCreateDocumentKind::Commercial,
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesCreate(
    company: '09cf2e87-bf0e-4b0e-a5b0-b6b75a60b415',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->tenantPdfTemplate !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.create" method="post" path="/companies/{company}/pdf-templates" example="factuarea_version_invalid_format" -->
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

$body = new Operations\PublicApiV1PdfTemplatesCreateRequestBody(
    name: '<value>',
    documentKind: Operations\PublicApiV1PdfTemplatesCreateDocumentKind::Commercial,
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesCreate(
    company: '4eb5c424-edd1-4177-99bf-fd89f3a0d8e5',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->tenantPdfTemplate !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.create" method="post" path="/companies/{company}/pdf-templates" example="retired_active_profile_header" -->
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

$body = new Operations\PublicApiV1PdfTemplatesCreateRequestBody(
    name: '<value>',
    documentKind: Operations\PublicApiV1PdfTemplatesCreateDocumentKind::Commercial,
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesCreate(
    company: '02fd5882-d319-41fc-b173-44276e344e92',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->tenantPdfTemplate !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.create" method="post" path="/companies/{company}/pdf-templates" example="unsupported_api_version" -->
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

$body = new Operations\PublicApiV1PdfTemplatesCreateRequestBody(
    name: '<value>',
    documentKind: Operations\PublicApiV1PdfTemplatesCreateDocumentKind::Commercial,
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesCreate(
    company: 'd7770ccc-0cc9-4d96-a9cb-7cb5825bab68',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->tenantPdfTemplate !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                   |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [Operations\PublicApiV1PdfTemplatesCreateRequestBody](../../Models/Operations/PublicApiV1PdfTemplatesCreateRequestBody.md)                                                                                                                                                                                                                                                                                                                                                                                                  | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Internal reversible write: idempotency follows the current policy; a supplied key is replayed before CAS. Unknown fields are rejected. Until the company is prepared for revisioned templates (tenant initialization pending or revisioned rendering off) every library write returns 422 `pdf_presentation_not_ready` before any precondition or effect; reads stay available and no `Retry-After` applies.                                                                                                                |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                               | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |

### Response

**[?Operations\PublicApiV1PdfTemplatesCreateResponse](../../Models/Operations/PublicApiV1PdfTemplatesCreateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1PdfTemplatesList

List the PDF templates of your company, paginated by `page` and `per_page` (up to 100) and ordered by name, then id.
- Filters: `q` (name), `state` (`active` by default, `archived` or `all`), `document_kind` (`commercial` or `transport-document`), `document_type`, `purpose` and `format`.
- A filter or page value out of range returns 422 instead of being trimmed silently.
- Requires `pdf_templates:read`; employees are not allowed.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.list" method="get" path="/companies/{company}/pdf-templates" example="list" -->
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

$request = new Operations\PublicApiV1PdfTemplatesListRequest(
    company: '856e4652-96ab-4930-9f61-781cfc565feb',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesList(
    request: $request
);

if ($response->tenantPdfTemplateList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                      | Type                                                                                                           | Required                                                                                                       | Description                                                                                                    |
| -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                     | [Operations\PublicApiV1PdfTemplatesListRequest](../../Models/Operations/PublicApiV1PdfTemplatesListRequest.md) | :heavy_check_mark:                                                                                             | The request object to use for the request.                                                                     |

### Response

**[?Operations\PublicApiV1PdfTemplatesListResponse](../../Models/Operations/PublicApiV1PdfTemplatesListResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1PdfTemplatesDuplicate

Create a new active template from the current configuration of a source template, which may be archived. Returns 201.
- Send `name` and the `expected_version` of the source in the body; `If-Match` is not accepted.
- `document_kind` keeps the kind of the source; `transport-document` on a commercial source creates a DeCA template with only its preset, layout, branding, typography (8-11 pt), colors and brand.
- The copy starts at version 1 without defaults, series or contact preferences. Requires `pdf_templates:write`; supports `Idempotency-Key`.

### Example Usage: duplicated

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.duplicate" method="post" path="/companies/{company}/pdf-templates/{template}/duplicate" example="duplicated" -->
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

$request = new Operations\PublicApiV1PdfTemplatesDuplicateRequest(
    company: '7dd11c14-2a9a-4efc-a447-a4fca08a0f0b',
    template: 'c338b626-a011-442e-aff2-db18efac7b97',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesDuplicateRequestBody(
        expectedVersion: 646146,
        name: '<value>',
        documentKind: Operations\PublicApiV1PdfTemplatesDuplicateDocumentKind::Commercial,
    ),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesDuplicate(
    request: $request
);

if ($response->tenantPdfTemplate !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.duplicate" method="post" path="/companies/{company}/pdf-templates/{template}/duplicate" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1PdfTemplatesDuplicateRequest(
    company: 'a44e138a-369f-4f2b-8b2b-0dfad0320ef4',
    template: 'e67fc600-cbbc-481a-9a29-4fc320daac56',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesDuplicateRequestBody(
        expectedVersion: 646146,
        name: '<value>',
        documentKind: Operations\PublicApiV1PdfTemplatesDuplicateDocumentKind::Commercial,
    ),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesDuplicate(
    request: $request
);

if ($response->tenantPdfTemplate !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.duplicate" method="post" path="/companies/{company}/pdf-templates/{template}/duplicate" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1PdfTemplatesDuplicateRequest(
    company: '883ef0b3-fc94-4a09-a701-34bbb941a137',
    template: 'c3607191-27c7-48c1-a96a-e26e541d75cd',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesDuplicateRequestBody(
        expectedVersion: 646146,
        name: '<value>',
        documentKind: Operations\PublicApiV1PdfTemplatesDuplicateDocumentKind::Commercial,
    ),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesDuplicate(
    request: $request
);

if ($response->tenantPdfTemplate !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.duplicate" method="post" path="/companies/{company}/pdf-templates/{template}/duplicate" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1PdfTemplatesDuplicateRequest(
    company: 'f51be1fe-8007-4163-a143-f4c0ab70b282',
    template: 'e43801aa-2f23-4ef7-b745-725e32e07c9f',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesDuplicateRequestBody(
        expectedVersion: 646146,
        name: '<value>',
        documentKind: Operations\PublicApiV1PdfTemplatesDuplicateDocumentKind::Commercial,
    ),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesDuplicate(
    request: $request
);

if ($response->tenantPdfTemplate !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                | Type                                                                                                                     | Required                                                                                                                 | Description                                                                                                              |
| ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                               | [Operations\PublicApiV1PdfTemplatesDuplicateRequest](../../Models/Operations/PublicApiV1PdfTemplatesDuplicateRequest.md) | :heavy_check_mark:                                                                                                       | The request object to use for the request.                                                                               |

### Response

**[?Operations\PublicApiV1PdfTemplatesDuplicateResponse](../../Models/Operations/PublicApiV1PdfTemplatesDuplicateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1PdfTemplatesFind

Retrieve one template of your company with its current revision, its availability for the plan of your company and its version. An unknown identifier and one that belongs to another company are indistinguishable (404). Requires `pdf_templates:read`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.find" method="get" path="/companies/{company}/pdf-templates/{template}" example="found" -->
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



$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesFind(
    company: 'ab805f0c-82a4-48e1-9f21-7f78c6594564',
    template: '6d21c551-a993-4577-b5b3-0928c35bebdb',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->tenantPdfTemplate !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `template`                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the PDF template.                                                                                                                                                                                                                                                                                                                             |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1PdfTemplatesFindResponse](../../Models/Operations/PublicApiV1PdfTemplatesFindResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1PdfTemplatesUpdate

Change only the name and the description of a template; the visual content is published with a new revision instead. Send the current `expected_version` in the body or the strong `ETag` of this same template in `If-Match`. A wrong tag returns 412, an outdated version returns 409 with `current_version`, and sending neither returns 428. Omitting the description keeps it; `null` clears it. A request that changes nothing does not create a new version. Requires `pdf_templates:write` and supports `Idempotency-Key`.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.update" method="patch" path="/companies/{company}/pdf-templates/{template}" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1PdfTemplatesUpdateRequest(
    company: 'bd065695-1bd6-4189-8e3e-24537ef65a41',
    template: '1d728f98-ad8e-44e9-acdc-9d980876d048',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesUpdateRequestBody(),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesUpdate(
    request: $request
);

if ($response->tenantPdfTemplate !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.update" method="patch" path="/companies/{company}/pdf-templates/{template}" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1PdfTemplatesUpdateRequest(
    company: '22f715f7-3a06-490d-827d-b987918479e7',
    template: '8cb26823-2a08-4c9e-9841-6979c2eff06b',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesUpdateRequestBody(),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesUpdate(
    request: $request
);

if ($response->tenantPdfTemplate !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.update" method="patch" path="/companies/{company}/pdf-templates/{template}" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1PdfTemplatesUpdateRequest(
    company: '995047fc-a93e-4d58-8023-b32736feac6e',
    template: '86ffd75a-2a1b-433e-9878-c9277508e1e6',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesUpdateRequestBody(),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesUpdate(
    request: $request
);

if ($response->tenantPdfTemplate !== null) {
    // handle response
}
```
### Example Usage: updated

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.update" method="patch" path="/companies/{company}/pdf-templates/{template}" example="updated" -->
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

$request = new Operations\PublicApiV1PdfTemplatesUpdateRequest(
    company: 'd265c36d-9709-4f1d-a912-12c853c15589',
    template: '4a77dd48-3a39-4625-a96e-4865b4fb3098',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesUpdateRequestBody(),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesUpdate(
    request: $request
);

if ($response->tenantPdfTemplate !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                          | Type                                                                                                               | Required                                                                                                           | Description                                                                                                        |
| ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                         | [Operations\PublicApiV1PdfTemplatesUpdateRequest](../../Models/Operations/PublicApiV1PdfTemplatesUpdateRequest.md) | :heavy_check_mark:                                                                                                 | The request object to use for the request.                                                                         |

### Response

**[?Operations\PublicApiV1PdfTemplatesUpdateResponse](../../Models/Operations/PublicApiV1PdfTemplatesUpdateResponse.md)**

### Errors

| Error Type                                  | Status Code                                 | Content Type                                |
| ------------------------------------------- | ------------------------------------------- | ------------------------------------------- |
| Errors\Error                                | 400, 401, 403, 404, 409, 412, 422, 428, 429 | application/json                            |
| Errors\Error                                | 500                                         | application/json                            |
| Errors\APIException                         | 4XX, 5XX                                    | \*/\*                                       |

## publicApiV1PdfTemplatesContactDefaultGet

Retrieve the template that your company prefers for one contact, document type (`invoice`, `quote`, `proforma`, `delivery-note`) and purpose. A merged contact is resolved to its surviving contact. When nothing is stored the response is virtual (version 1, `template_id` null) and nothing is written. An unknown or foreign contact returns 404. Requires `pdf_templates:read`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.contact_default_get" method="get" path="/companies/{company}/pdf-templates/contact-defaults/{contact}/{document_type}/{purpose}" example="not_persisted" -->
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

$request = new Operations\PublicApiV1PdfTemplatesContactDefaultGetRequest(
    company: '4eaffaa3-7fbe-4e66-8121-7d2c7ce92387',
    contact: '7b7dcb2a-8027-49fb-8ffa-cbf36842da76',
    documentType: '<value>',
    purpose: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesContactDefaultGet(
    request: $request
);

if ($response->pdfTemplateContactPreference !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                | Type                                                                                                                                     | Required                                                                                                                                 | Description                                                                                                                              |
| ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                               | [Operations\PublicApiV1PdfTemplatesContactDefaultGetRequest](../../Models/Operations/PublicApiV1PdfTemplatesContactDefaultGetRequest.md) | :heavy_check_mark:                                                                                                                       | The request object to use for the request.                                                                                               |

### Response

**[?Operations\PublicApiV1PdfTemplatesContactDefaultGetResponse](../../Models/Operations/PublicApiV1PdfTemplatesContactDefaultGetResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1PdfTemplatesContactDefaultSet

Set or clear (`template_id: null`) the template used by default for one contact, document type and purpose. The template must be active and compatible with that type and purpose, and the contact must be a customer. Send the current `expected_version` in the body or the strong `ETag` of this preference in `If-Match`. Omitting `template_id` keeps the stored value. Requires `pdf_templates:write` and supports `Idempotency-Key`.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.contact_default_set" method="patch" path="/companies/{company}/pdf-templates/contact-defaults/{contact}/{document_type}/{purpose}" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1PdfTemplatesContactDefaultSetRequest(
    company: '9cc63743-7708-4875-b8a4-8ce8412b4de2',
    contact: '4aef1949-2b92-4bad-bae8-54e335535ce3',
    documentType: '<value>',
    purpose: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesContactDefaultSetRequestBody(),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesContactDefaultSet(
    request: $request
);

if ($response->pdfTemplateContactPreference !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.contact_default_set" method="patch" path="/companies/{company}/pdf-templates/contact-defaults/{contact}/{document_type}/{purpose}" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1PdfTemplatesContactDefaultSetRequest(
    company: '49dc2a09-d952-4271-b435-0d4074f2b9a3',
    contact: '3debe5c7-58c5-4b4c-ac1d-485210312c31',
    documentType: '<value>',
    purpose: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesContactDefaultSetRequestBody(),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesContactDefaultSet(
    request: $request
);

if ($response->pdfTemplateContactPreference !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.contact_default_set" method="patch" path="/companies/{company}/pdf-templates/contact-defaults/{contact}/{document_type}/{purpose}" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1PdfTemplatesContactDefaultSetRequest(
    company: '596934cd-369d-4f12-9aca-29e55b48af07',
    contact: 'b8a593c0-a551-47fe-905f-ce8fdb1a115c',
    documentType: '<value>',
    purpose: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesContactDefaultSetRequestBody(),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesContactDefaultSet(
    request: $request
);

if ($response->pdfTemplateContactPreference !== null) {
    // handle response
}
```
### Example Usage: updated

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.contact_default_set" method="patch" path="/companies/{company}/pdf-templates/contact-defaults/{contact}/{document_type}/{purpose}" example="updated" -->
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

$request = new Operations\PublicApiV1PdfTemplatesContactDefaultSetRequest(
    company: 'b4ade44f-0cdb-4c36-945f-7e8bfffa1246',
    contact: 'aa68b5a2-c7fb-4a56-91f8-7b84092bef9a',
    documentType: '<value>',
    purpose: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesContactDefaultSetRequestBody(),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesContactDefaultSet(
    request: $request
);

if ($response->pdfTemplateContactPreference !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                | Type                                                                                                                                     | Required                                                                                                                                 | Description                                                                                                                              |
| ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                               | [Operations\PublicApiV1PdfTemplatesContactDefaultSetRequest](../../Models/Operations/PublicApiV1PdfTemplatesContactDefaultSetRequest.md) | :heavy_check_mark:                                                                                                                       | The request object to use for the request.                                                                                               |

### Response

**[?Operations\PublicApiV1PdfTemplatesContactDefaultSetResponse](../../Models/Operations/PublicApiV1PdfTemplatesContactDefaultSetResponse.md)**

### Errors

| Error Type                                  | Status Code                                 | Content Type                                |
| ------------------------------------------- | ------------------------------------------- | ------------------------------------------- |
| Errors\Error                                | 400, 401, 403, 404, 409, 412, 422, 428, 429 | application/json                            |
| Errors\Error                                | 500                                         | application/json                            |
| Errors\APIException                         | 4XX, 5XX                                    | \*/\*                                       |

## publicApiV1PdfTemplatesDefaultsGet

Retrieve the company default template and brand, the per-type defaults and `render_activation` (`legacy` or `revisioned`), which every document creator needs. A company that has never configured defaults gets a virtual response at version 1 that is not stored. Any non-employee member of the company can read it with the `pdf_templates:read` scope, even when the templates section is restricted for that member; employees receive 403.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.defaults_get" method="get" path="/companies/{company}/pdf-templates/defaults" example="defaults" -->
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



$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesDefaultsGet(
    company: '9081ba1e-0545-48ca-9e50-3b23728e3ea2',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->pdfTemplateDefaults !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1PdfTemplatesDefaultsGetResponse](../../Models/Operations/PublicApiV1PdfTemplatesDefaultsGetResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1PdfTemplatesDefaultsSet

Set the company default template and brand and the per-type defaults. Omitted fields are kept, `null` clears a value and `type_defaults`, when sent, replaces the whole list.
- `type_defaults` admits `transport-document` with `complete` and an active A4 template of kind `transport-document`.
- Send the current `expected_version` in the body or the strong `ETag` of the defaults in `If-Match`.
- Company administrators only, with `pdf_templates:write`; supports `Idempotency-Key`.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.defaults_set" method="patch" path="/companies/{company}/pdf-templates/defaults" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1PdfTemplatesDefaultsSetRequest(
    company: 'df13f20c-e760-40d7-9060-8ea3c796210a',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesDefaultsSetRequestBody(),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesDefaultsSet(
    request: $request
);

if ($response->pdfTemplateDefaults !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.defaults_set" method="patch" path="/companies/{company}/pdf-templates/defaults" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1PdfTemplatesDefaultsSetRequest(
    company: 'c71324e9-437e-434f-8aff-7fdeec7d85e6',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesDefaultsSetRequestBody(),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesDefaultsSet(
    request: $request
);

if ($response->pdfTemplateDefaults !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.defaults_set" method="patch" path="/companies/{company}/pdf-templates/defaults" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1PdfTemplatesDefaultsSetRequest(
    company: '88682c06-77db-4126-a6a1-b73f408c1322',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesDefaultsSetRequestBody(),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesDefaultsSet(
    request: $request
);

if ($response->pdfTemplateDefaults !== null) {
    // handle response
}
```
### Example Usage: updated

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.defaults_set" method="patch" path="/companies/{company}/pdf-templates/defaults" example="updated" -->
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

$request = new Operations\PublicApiV1PdfTemplatesDefaultsSetRequest(
    company: '5ee00bc6-42ae-4cb9-9506-03be00e92a56',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesDefaultsSetRequestBody(),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesDefaultsSet(
    request: $request
);

if ($response->pdfTemplateDefaults !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                    | Type                                                                                                                         | Required                                                                                                                     | Description                                                                                                                  |
| ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                   | [Operations\PublicApiV1PdfTemplatesDefaultsSetRequest](../../Models/Operations/PublicApiV1PdfTemplatesDefaultsSetRequest.md) | :heavy_check_mark:                                                                                                           | The request object to use for the request.                                                                                   |

### Response

**[?Operations\PublicApiV1PdfTemplatesDefaultsSetResponse](../../Models/Operations/PublicApiV1PdfTemplatesDefaultsSetResponse.md)**

### Errors

| Error Type                                  | Status Code                                 | Content Type                                |
| ------------------------------------------- | ------------------------------------------- | ------------------------------------------- |
| Errors\Error                                | 400, 401, 403, 404, 409, 412, 422, 428, 429 | application/json                            |
| Errors\Error                                | 500                                         | application/json                            |
| Errors\APIException                         | 4XX, 5XX                                    | \*/\*                                       |

## publicApiV1PdfTemplatesImpact

Read-only preview of what publishing a new revision (`action=publish`) or archiving (`action=archive`) would affect: company and per-type defaults, series, recurring invoices and contact preferences. `action` is required and any other value returns 422. The response carries the `impact_fingerprint` that the publish and archive operations must echo as `expected_impact_fingerprint`. It does not modify anything. Requires `pdf_templates:read`, not write.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.impact" method="get" path="/companies/{company}/pdf-templates/{template}/impact" example="archive_impact" -->
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



$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesImpact(
    company: 'd5d47be8-88d4-4922-9c78-605f5af4b2a3',
    template: '9e1fb17b-b4bf-4770-a615-8f579180a9b2',
    action: Operations\PublicApiV1PdfTemplatesImpactAction::Publish,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->pdfLibraryImpact !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `template`                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the PDF template.                                                                                                                                                                                                                                                                                                                             |                                                                                                                                                                                                                                                                                                                                                                              |
| `action`                                                                                                                                                                                                                                                                                                                                                                     | [Operations\PublicApiV1PdfTemplatesImpactAction](../../Models/Operations/PublicApiV1PdfTemplatesImpactAction.md)                                                                                                                                                                                                                                                             | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Change to preview: `publish` (a new revision) or `archive`. Any other value, or none, returns 422.                                                                                                                                                                                                                                                                           |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1PdfTemplatesImpactResponse](../../Models/Operations/PublicApiV1PdfTemplatesImpactResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1PdfTemplatesAssetGet

Retrieve a private image of your company. With `Accept: application/json` (the default) it returns the metadata of the asset; with an `image/*` Accept header it returns the stored bytes inline with `Cache-Control: private, no-store`. Requires `pdf_templates:read`. Accept application/json returns immutable metadata; an image MIME returns authorized private bytes with integrity verified. No storage path or binary content is exposed in JSON.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.asset_get" method="get" path="/companies/{company}/pdf-templates/assets/{asset}" example="metadata" -->
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



$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesAssetGet(
    company: '1330246d-a232-4055-8a71-836bf22108eb',
    asset: '9dc21157-800b-40e9-b384-15df678f9ca1',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->pdfTemplateAsset !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `asset`                                                                                                                                                                                                                                                                                                                                                                      | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the PDF template image.                                                                                                                                                                                                                                                                                                                       |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1PdfTemplatesAssetGetResponse](../../Models/Operations/PublicApiV1PdfTemplatesAssetGetResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1PdfTemplatesRevision

Retrieve one immutable revision of a template, with the compatibility and configuration that were published. Revisions never change after publication. Requires `pdf_templates:read`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.revision" method="get" path="/companies/{company}/pdf-templates/{template}/revisions/{revision}" example="found" -->
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



$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesRevision(
    company: '303d0beb-20ea-4e44-8b37-6afef2b25068',
    template: '6926fbe4-6ebb-442c-a8f5-4f940ddeec6d',
    revision: '8c16aa16-c8e0-4706-8cb2-23b3db1718f8',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->pdfTemplateRevision !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `template`                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the PDF template.                                                                                                                                                                                                                                                                                                                             |                                                                                                                                                                                                                                                                                                                                                                              |
| `revision`                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the PDF template revision.                                                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1PdfTemplatesRevisionResponse](../../Models/Operations/PublicApiV1PdfTemplatesRevisionResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1PdfTemplatesPublishRevision

Append an immutable revision with the new compatibility, base preset, brand or configuration. Returns 201 with the new revision.
- First read the impact with `action=publish`, then send its `expected_impact_fingerprint` and the `expected_version` of the template; a changed impact returns 409.
- Objects in `configuration` are merged deeply and lists replaced; the kind is fixed, so `document_kind` is rejected.
- Earlier revisions and the documents issued with them are not modified. Requires `pdf_templates:write`; supports `Idempotency-Key`.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.publish_revision" method="post" path="/companies/{company}/pdf-templates/{template}/revisions" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1PdfTemplatesPublishRevisionRequest(
    company: '597eff32-717c-45be-903f-53bc12bff6bb',
    template: '06018fae-579d-46f7-9fb0-e949b2ce1b35',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesPublishRevisionRequestBody(
        expectedVersion: 723746,
        expectedImpactFingerprint: '<value>',
    ),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesPublishRevision(
    request: $request
);

if ($response->pdfTemplateRevision !== null) {
    // handle response
}
```
### Example Usage: published

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.publish_revision" method="post" path="/companies/{company}/pdf-templates/{template}/revisions" example="published" -->
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

$request = new Operations\PublicApiV1PdfTemplatesPublishRevisionRequest(
    company: '8fe38c76-eeab-4801-af9f-ba6f040adbe2',
    template: 'a29f023f-e346-4562-9222-d2d686e8e67e',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesPublishRevisionRequestBody(
        expectedVersion: 723746,
        expectedImpactFingerprint: '<value>',
    ),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesPublishRevision(
    request: $request
);

if ($response->pdfTemplateRevision !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.publish_revision" method="post" path="/companies/{company}/pdf-templates/{template}/revisions" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1PdfTemplatesPublishRevisionRequest(
    company: 'fac8a5b7-2470-41c1-9109-771327e623b6',
    template: 'a0aa6e61-1702-4745-9f52-7c0ac2187c17',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesPublishRevisionRequestBody(
        expectedVersion: 723746,
        expectedImpactFingerprint: '<value>',
    ),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesPublishRevision(
    request: $request
);

if ($response->pdfTemplateRevision !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.publish_revision" method="post" path="/companies/{company}/pdf-templates/{template}/revisions" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1PdfTemplatesPublishRevisionRequest(
    company: '4d005aba-185b-40da-b6ba-39fec0ae0d19',
    template: 'e8b1d423-aa56-447d-8a38-44b501bf2650',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesPublishRevisionRequestBody(
        expectedVersion: 723746,
        expectedImpactFingerprint: '<value>',
    ),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesPublishRevision(
    request: $request
);

if ($response->pdfTemplateRevision !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                            | Type                                                                                                                                 | Required                                                                                                                             | Description                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                           | [Operations\PublicApiV1PdfTemplatesPublishRevisionRequest](../../Models/Operations/PublicApiV1PdfTemplatesPublishRevisionRequest.md) | :heavy_check_mark:                                                                                                                   | The request object to use for the request.                                                                                           |

### Response

**[?Operations\PublicApiV1PdfTemplatesPublishRevisionResponse](../../Models/Operations/PublicApiV1PdfTemplatesPublishRevisionResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1PdfTemplatesRestore

Make an archived template active again. Send its current `expected_version` in the body; `If-Match` is not accepted on this action. Restoring only changes the state: it does not create a revision and does not re-establish the associations that were replaced when it was archived. Requires `pdf_templates:write` and supports `Idempotency-Key`.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.restore" method="post" path="/companies/{company}/pdf-templates/{template}/restore" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1PdfTemplatesRestoreRequest(
    company: '224f0084-a60b-41bb-8bae-daa63869bb33',
    template: '6f58e44a-a31e-4f9a-967e-b270f7d5765c',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesRestoreRequestBody(
        expectedVersion: 367394,
    ),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesRestore(
    request: $request
);

if ($response->tenantPdfTemplate !== null) {
    // handle response
}
```
### Example Usage: restored

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.restore" method="post" path="/companies/{company}/pdf-templates/{template}/restore" example="restored" -->
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

$request = new Operations\PublicApiV1PdfTemplatesRestoreRequest(
    company: '993a00a2-e7d0-4900-bd8b-ce41749d1e85',
    template: 'e3137aac-e7d7-499f-b762-0a94ab5871c1',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesRestoreRequestBody(
        expectedVersion: 367394,
    ),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesRestore(
    request: $request
);

if ($response->tenantPdfTemplate !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.restore" method="post" path="/companies/{company}/pdf-templates/{template}/restore" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1PdfTemplatesRestoreRequest(
    company: 'd8a7fad1-f400-4244-8f54-ccca7d1eac8b',
    template: 'e0ffd339-42e2-46e8-883d-fa2ade917b9b',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesRestoreRequestBody(
        expectedVersion: 367394,
    ),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesRestore(
    request: $request
);

if ($response->tenantPdfTemplate !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.restore" method="post" path="/companies/{company}/pdf-templates/{template}/restore" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1PdfTemplatesRestoreRequest(
    company: 'ace39149-2e00-4b32-b864-303a4c7dd3e0',
    template: '5e7e07ac-ac3a-4e5d-bd05-813e7ed90aa3',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfTemplatesRestoreRequestBody(
        expectedVersion: 367394,
    ),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesRestore(
    request: $request
);

if ($response->tenantPdfTemplate !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                            | Type                                                                                                                 | Required                                                                                                             | Description                                                                                                          |
| -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                           | [Operations\PublicApiV1PdfTemplatesRestoreRequest](../../Models/Operations/PublicApiV1PdfTemplatesRestoreRequest.md) | :heavy_check_mark:                                                                                                   | The request object to use for the request.                                                                           |

### Response

**[?Operations\PublicApiV1PdfTemplatesRestoreResponse](../../Models/Operations/PublicApiV1PdfTemplatesRestoreResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1PdfTemplatesAssetUpload

Upload a logo or brand image (`kind`: `logo` or `imago`) as `multipart/form-data` in the `file` field. Accepted formats are JPEG, PNG and WebP up to 2048 KiB, at most 6000 px per side and 24 megapixels. Metadata such as EXIF and GPS is stripped. The asset is private and immutable, and a repeated `id` returns 409 `pdf_template_asset_conflict`. Returns 201 with the asset identifier to use in a brand. Requires `pdf_templates:write` and `Idempotency-Key`.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.asset_upload" method="post" path="/companies/{company}/pdf-templates/assets" example="factuarea_version_invalid_format" -->
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

$body = new Operations\PublicApiV1PdfTemplatesAssetUploadRequestBody(
    kind: Operations\PublicApiV1PdfTemplatesAssetUploadKind::Imago,
    file: new Operations\File(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesAssetUpload(
    company: '2a365f39-6f9d-40a7-902d-aeb08cd0096b',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->pdfTemplateAsset !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.asset_upload" method="post" path="/companies/{company}/pdf-templates/assets" example="retired_active_profile_header" -->
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

$body = new Operations\PublicApiV1PdfTemplatesAssetUploadRequestBody(
    kind: Operations\PublicApiV1PdfTemplatesAssetUploadKind::Imago,
    file: new Operations\File(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesAssetUpload(
    company: 'fcac183f-fa5c-4121-87c2-f9f2155676f5',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->pdfTemplateAsset !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.asset_upload" method="post" path="/companies/{company}/pdf-templates/assets" example="unsupported_api_version" -->
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

$body = new Operations\PublicApiV1PdfTemplatesAssetUploadRequestBody(
    kind: Operations\PublicApiV1PdfTemplatesAssetUploadKind::Imago,
    file: new Operations\File(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesAssetUpload(
    company: '0bb22c65-16da-4580-8027-b4207901b0e1',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->pdfTemplateAsset !== null) {
    // handle response
}
```
### Example Usage: uploaded

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_templates.asset_upload" method="post" path="/companies/{company}/pdf-templates/assets" example="uploaded" -->
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

$body = new Operations\PublicApiV1PdfTemplatesAssetUploadRequestBody(
    kind: Operations\PublicApiV1PdfTemplatesAssetUploadKind::Imago,
    file: new Operations\File(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
);

$response = $sdk->pdfTemplates->publicApiV1PdfTemplatesAssetUpload(
    company: 'a5dbc69a-9fc4-4ed4-9a8d-b016d2475f40',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->pdfTemplateAsset !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Operations\PublicApiV1PdfTemplatesAssetUploadRequestBody](../../Models/Operations/PublicApiV1PdfTemplatesAssetUploadRequestBody.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Internal reversible write: idempotency follows the current policy; a supplied key is replayed before CAS. Unknown fields are rejected. Until the company is prepared for revisioned templates (tenant initialization pending or revisioned rendering off) every library write returns 422 `pdf_presentation_not_ready` before any precondition or effect; reads stay available and no `Retry-After` applies.                                                                                                                                                                                                                                                                                                                                                                                            |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1PdfTemplatesAssetUploadResponse](../../Models/Operations/PublicApiV1PdfTemplatesAssetUploadResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |