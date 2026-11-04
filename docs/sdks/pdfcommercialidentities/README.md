# PdfCommercialIdentities

## Overview

### Available Operations

* [publicApiV1PdfCommercialIdentitiesArchive](#publicapiv1pdfcommercialidentitiesarchive) - Archive a PDF commercial identity
* [publicApiV1PdfCommercialIdentitiesCreate](#publicapiv1pdfcommercialidentitiescreate) - Create a PDF commercial identity
* [publicApiV1PdfCommercialIdentitiesList](#publicapiv1pdfcommercialidentitieslist) - List PDF commercial identities
* [publicApiV1PdfCommercialIdentitiesFind](#publicapiv1pdfcommercialidentitiesfind) - Retrieve a PDF commercial identity
* [publicApiV1PdfCommercialIdentitiesUpdate](#publicapiv1pdfcommercialidentitiesupdate) - Update PDF commercial identity metadata
* [publicApiV1PdfCommercialIdentitiesImpact](#publicapiv1pdfcommercialidentitiesimpact) - Inspect the impact of a PDF commercial identity change
* [publicApiV1PdfCommercialIdentitiesRevision](#publicapiv1pdfcommercialidentitiesrevision) - Retrieve a PDF commercial identity revision
* [publicApiV1PdfCommercialIdentitiesPublishRevision](#publicapiv1pdfcommercialidentitiespublishrevision) - Publish a PDF commercial identity revision
* [publicApiV1PdfCommercialIdentitiesRestore](#publicapiv1pdfcommercialidentitiesrestore) - Restore a PDF commercial identity

## publicApiV1PdfCommercialIdentitiesArchive

Archive a brand identity and resolve in the same atomic step every template or default that points to it. Send the `expected_impact_fingerprint` read from the impact endpoint, the `expected_version` and one decision per association in `replacements` (`replace`, `clear` or `pause`; a template link also accepts `revision_id`). A changed impact returns 409 and nothing is modified. Retained images are not deleted. Requires `pdf_templates:write` and supports `Idempotency-Key`.

### Example Usage: archived

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_commercial_identities.archive" method="post" path="/companies/{company}/pdf-commercial-identities/{identity}/archive" example="archived" -->
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

$request = new Operations\PublicApiV1PdfCommercialIdentitiesArchiveRequest(
    company: 'd6225ca6-45e1-470e-a36c-7258878f1c44',
    identity: 'e31bc2e9-b99e-44fa-b31f-3c1dc609aa4f',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfCommercialIdentitiesArchiveRequestBody(
        expectedVersion: 281963,
        expectedImpactFingerprint: '<value>',
        replacements: [],
    ),
);

$response = $sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesArchive(
    request: $request
);

if ($response->pdfCommercialIdentity !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_commercial_identities.archive" method="post" path="/companies/{company}/pdf-commercial-identities/{identity}/archive" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1PdfCommercialIdentitiesArchiveRequest(
    company: 'fa10855c-554c-4bde-bfba-b135e546c05d',
    identity: '6a1040d8-575f-451d-9911-3649d3d85bd8',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfCommercialIdentitiesArchiveRequestBody(
        expectedVersion: 281963,
        expectedImpactFingerprint: '<value>',
        replacements: [],
    ),
);

$response = $sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesArchive(
    request: $request
);

if ($response->pdfCommercialIdentity !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_commercial_identities.archive" method="post" path="/companies/{company}/pdf-commercial-identities/{identity}/archive" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1PdfCommercialIdentitiesArchiveRequest(
    company: 'ccbf49ca-207b-4e91-a1f0-b622151666c3',
    identity: '232e12d6-553b-44bd-9e21-520eeb867c57',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfCommercialIdentitiesArchiveRequestBody(
        expectedVersion: 281963,
        expectedImpactFingerprint: '<value>',
        replacements: [],
    ),
);

$response = $sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesArchive(
    request: $request
);

if ($response->pdfCommercialIdentity !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_commercial_identities.archive" method="post" path="/companies/{company}/pdf-commercial-identities/{identity}/archive" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1PdfCommercialIdentitiesArchiveRequest(
    company: '73a82019-d412-487d-89f7-3728bab326ee',
    identity: 'c2083bdf-69e5-46d5-83be-a88db58841d7',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfCommercialIdentitiesArchiveRequestBody(
        expectedVersion: 281963,
        expectedImpactFingerprint: '<value>',
        replacements: [],
    ),
);

$response = $sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesArchive(
    request: $request
);

if ($response->pdfCommercialIdentity !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                  | Type                                                                                                                                       | Required                                                                                                                                   | Description                                                                                                                                |
| ------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                                 | [Operations\PublicApiV1PdfCommercialIdentitiesArchiveRequest](../../Models/Operations/PublicApiV1PdfCommercialIdentitiesArchiveRequest.md) | :heavy_check_mark:                                                                                                                         | The request object to use for the request.                                                                                                 |

### Response

**[?Operations\PublicApiV1PdfCommercialIdentitiesArchiveResponse](../../Models/Operations/PublicApiV1PdfCommercialIdentitiesArchiveResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1PdfCommercialIdentitiesCreate

Create a brand identity with its first revision: `name` plus a `configuration` with the commercial name, logo and brand image asset identifiers, brand mode and contact email, phone and address. Assets must belong to your company and be of the right kind. Supply `id` and `revision_id` (UUID v7) to choose the identifiers yourself. Returns 201. Only company administrators can create it. Requires `pdf_templates:write` and supports `Idempotency-Key`.

### Example Usage: created

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_commercial_identities.create" method="post" path="/companies/{company}/pdf-commercial-identities" example="created" -->
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

$body = new Operations\PublicApiV1PdfCommercialIdentitiesCreateRequestBody(
    name: '<value>',
);

$response = $sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesCreate(
    company: '416f72e8-c876-4aed-a4f9-95b75fb31fa1',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->pdfCommercialIdentity !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_commercial_identities.create" method="post" path="/companies/{company}/pdf-commercial-identities" example="factuarea_version_invalid_format" -->
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

$body = new Operations\PublicApiV1PdfCommercialIdentitiesCreateRequestBody(
    name: '<value>',
);

$response = $sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesCreate(
    company: 'b9ab61ed-86f1-4cd8-ae2d-939f536049e4',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->pdfCommercialIdentity !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_commercial_identities.create" method="post" path="/companies/{company}/pdf-commercial-identities" example="retired_active_profile_header" -->
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

$body = new Operations\PublicApiV1PdfCommercialIdentitiesCreateRequestBody(
    name: '<value>',
);

$response = $sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesCreate(
    company: 'f0f42487-03f4-483c-ae0d-bd5420e702a2',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->pdfCommercialIdentity !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_commercial_identities.create" method="post" path="/companies/{company}/pdf-commercial-identities" example="unsupported_api_version" -->
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

$body = new Operations\PublicApiV1PdfCommercialIdentitiesCreateRequestBody(
    name: '<value>',
);

$response = $sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesCreate(
    company: '556c658d-4afc-4ec3-b33a-d06e9fe91b5d',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->pdfCommercialIdentity !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                   |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [Operations\PublicApiV1PdfCommercialIdentitiesCreateRequestBody](../../Models/Operations/PublicApiV1PdfCommercialIdentitiesCreateRequestBody.md)                                                                                                                                                                                                                                                                                                                                                                            | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Internal reversible write: idempotency follows the current policy; a supplied key is replayed before CAS. Unknown fields are rejected. Until the company is prepared for revisioned templates (tenant initialization pending or revisioned rendering off) every library write returns 422 `pdf_presentation_not_ready` before any precondition or effect; reads stay available and no `Retry-After` applies.                                                                                                                |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                               | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |

### Response

**[?Operations\PublicApiV1PdfCommercialIdentitiesCreateResponse](../../Models/Operations/PublicApiV1PdfCommercialIdentitiesCreateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1PdfCommercialIdentitiesList

List the brand identities (commercial name, logo, contact details) that your templates can use, with page-based pagination (`page`, `per_page` up to 100). Filters: `q` (name) and `state` (`active` by default, `archived` or `all`). A commercial identity never replaces the fiscal identity or the bank data printed on a document. Requires `pdf_templates:read`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_commercial_identities.list" method="get" path="/companies/{company}/pdf-commercial-identities" example="list" -->
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

$request = new Operations\PublicApiV1PdfCommercialIdentitiesListRequest(
    company: '9773a435-55b9-407f-866e-2af4125190a5',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesList(
    request: $request
);

if ($response->pdfCommercialIdentityList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                            | Type                                                                                                                                 | Required                                                                                                                             | Description                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                           | [Operations\PublicApiV1PdfCommercialIdentitiesListRequest](../../Models/Operations/PublicApiV1PdfCommercialIdentitiesListRequest.md) | :heavy_check_mark:                                                                                                                   | The request object to use for the request.                                                                                           |

### Response

**[?Operations\PublicApiV1PdfCommercialIdentitiesListResponse](../../Models/Operations/PublicApiV1PdfCommercialIdentitiesListResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1PdfCommercialIdentitiesFind

Retrieve one brand identity of your company with its current revision, availability and version. An unknown identifier and one that belongs to another company are indistinguishable (404). Requires `pdf_templates:read`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_commercial_identities.find" method="get" path="/companies/{company}/pdf-commercial-identities/{identity}" example="found" -->
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



$response = $sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesFind(
    company: '7a8dfdc8-39e0-4d83-ad61-529cf1b8f74b',
    identity: 'b2a6caf5-d34b-4094-9a44-0ba2f1cc9efb',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->pdfCommercialIdentity !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `identity`                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the PDF commercial identity.                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1PdfCommercialIdentitiesFindResponse](../../Models/Operations/PublicApiV1PdfCommercialIdentitiesFindResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1PdfCommercialIdentitiesUpdate

Change only the name and the description; the commercial content is published with a new revision. Send the current `expected_version` in the body or the strong `ETag` of this identity in `If-Match`: a wrong tag returns 412, an outdated version 409 and neither 428. Requires `pdf_templates:write` and supports `Idempotency-Key`.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_commercial_identities.update" method="patch" path="/companies/{company}/pdf-commercial-identities/{identity}" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1PdfCommercialIdentitiesUpdateRequest(
    company: 'd107b116-8f3f-44d6-adbf-8b564941c39b',
    identity: '8f5f0c8d-c198-45e0-a441-821619b539c2',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfCommercialIdentitiesUpdateRequestBody(),
);

$response = $sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesUpdate(
    request: $request
);

if ($response->pdfCommercialIdentity !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_commercial_identities.update" method="patch" path="/companies/{company}/pdf-commercial-identities/{identity}" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1PdfCommercialIdentitiesUpdateRequest(
    company: '12ec8287-715f-4d42-b8d2-5eecf6910797',
    identity: '1be041e8-3e8d-44de-aa14-0875011e7528',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfCommercialIdentitiesUpdateRequestBody(),
);

$response = $sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesUpdate(
    request: $request
);

if ($response->pdfCommercialIdentity !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_commercial_identities.update" method="patch" path="/companies/{company}/pdf-commercial-identities/{identity}" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1PdfCommercialIdentitiesUpdateRequest(
    company: '47aa4e8e-ccb6-4018-8914-8a1a13a7007d',
    identity: '0098f326-ccf0-4037-b6a1-188daedb6be5',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfCommercialIdentitiesUpdateRequestBody(),
);

$response = $sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesUpdate(
    request: $request
);

if ($response->pdfCommercialIdentity !== null) {
    // handle response
}
```
### Example Usage: updated

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_commercial_identities.update" method="patch" path="/companies/{company}/pdf-commercial-identities/{identity}" example="updated" -->
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

$request = new Operations\PublicApiV1PdfCommercialIdentitiesUpdateRequest(
    company: 'c6fb87dd-4426-498e-bd13-c9a9da8845ff',
    identity: '4571497f-9cb3-47e3-82dd-cb73643d8cac',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfCommercialIdentitiesUpdateRequestBody(),
);

$response = $sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesUpdate(
    request: $request
);

if ($response->pdfCommercialIdentity !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                | Type                                                                                                                                     | Required                                                                                                                                 | Description                                                                                                                              |
| ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                               | [Operations\PublicApiV1PdfCommercialIdentitiesUpdateRequest](../../Models/Operations/PublicApiV1PdfCommercialIdentitiesUpdateRequest.md) | :heavy_check_mark:                                                                                                                       | The request object to use for the request.                                                                                               |

### Response

**[?Operations\PublicApiV1PdfCommercialIdentitiesUpdateResponse](../../Models/Operations/PublicApiV1PdfCommercialIdentitiesUpdateResponse.md)**

### Errors

| Error Type                                  | Status Code                                 | Content Type                                |
| ------------------------------------------- | ------------------------------------------- | ------------------------------------------- |
| Errors\Error                                | 400, 401, 403, 404, 409, 412, 422, 428, 429 | application/json                            |
| Errors\Error                                | 500                                         | application/json                            |
| Errors\APIException                         | 4XX, 5XX                                    | \*/\*                                       |

## publicApiV1PdfCommercialIdentitiesImpact

Read-only preview of the templates and company defaults affected by publishing a revision (`action=publish`) or archiving the identity (`action=archive`). `action` is required. The response carries the `impact_fingerprint` that publish and archive must echo as `expected_impact_fingerprint`. It does not modify anything. Requires `pdf_templates:read`, not write.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_commercial_identities.impact" method="get" path="/companies/{company}/pdf-commercial-identities/{identity}/impact" example="archive_impact" -->
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



$response = $sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesImpact(
    company: 'e866c68a-22c5-4d25-a893-be66e46eda4e',
    identity: '67703959-8f03-403d-85a3-c55123d2e0e4',
    action: Operations\PublicApiV1PdfCommercialIdentitiesImpactAction::Archive,
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
| `identity`                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the PDF commercial identity.                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `action`                                                                                                                                                                                                                                                                                                                                                                     | [Operations\PublicApiV1PdfCommercialIdentitiesImpactAction](../../Models/Operations/PublicApiV1PdfCommercialIdentitiesImpactAction.md)                                                                                                                                                                                                                                       | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Change to preview: `publish` (a new revision) or `archive`. Any other value, or none, returns 422.                                                                                                                                                                                                                                                                           |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1PdfCommercialIdentitiesImpactResponse](../../Models/Operations/PublicApiV1PdfCommercialIdentitiesImpactResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1PdfCommercialIdentitiesRevision

Retrieve one immutable revision of a brand identity as it was published. Requires `pdf_templates:read`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_commercial_identities.revision" method="get" path="/companies/{company}/pdf-commercial-identities/{identity}/revisions/{revision}" example="revision" -->
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



$response = $sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesRevision(
    company: 'd5984a74-a9b3-4bb8-94d1-a0a3590f07c4',
    identity: '289b2bab-5cf3-4875-85da-ab6680e0cd6f',
    revision: '5cf27ad5-4fd2-4069-b4ed-780ef694017c',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->pdfCommercialIdentityRevision !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `identity`                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the PDF commercial identity.                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `revision`                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the PDF commercial identity revision.                                                                                                                                                                                                                                                                                                         |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1PdfCommercialIdentitiesRevisionResponse](../../Models/Operations/PublicApiV1PdfCommercialIdentitiesRevisionResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1PdfCommercialIdentitiesPublishRevision

Append an immutable revision with a partial `configuration` (at least one commercial key). Read the impact of the identity with `action=publish` first and send its `expected_impact_fingerprint` with the `expected_version` of the identity; `If-Match` is not accepted on this action. Documents already issued keep the revision they used. Returns 201. Requires `pdf_templates:write` and supports `Idempotency-Key`.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_commercial_identities.publish_revision" method="post" path="/companies/{company}/pdf-commercial-identities/{identity}/revisions" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1PdfCommercialIdentitiesPublishRevisionRequest(
    company: '3b3ee244-aa38-46b5-b287-a88ceff76306',
    identity: 'd2e418ca-8707-4157-a12c-51e65182593b',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfCommercialIdentitiesPublishRevisionRequestBody(
        expectedVersion: 930280,
        expectedImpactFingerprint: '<value>',
        configuration: new Operations\PublicApiV1PdfCommercialIdentitiesPublishRevisionConfiguration(),
    ),
);

$response = $sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesPublishRevision(
    request: $request
);

if ($response->pdfCommercialIdentityRevision !== null) {
    // handle response
}
```
### Example Usage: published

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_commercial_identities.publish_revision" method="post" path="/companies/{company}/pdf-commercial-identities/{identity}/revisions" example="published" -->
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

$request = new Operations\PublicApiV1PdfCommercialIdentitiesPublishRevisionRequest(
    company: 'bcca7fa0-13af-4246-b5e5-1fb3ebb3a597',
    identity: '7f52742d-1c3f-4060-9554-f1137d9bc4a9',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfCommercialIdentitiesPublishRevisionRequestBody(
        expectedVersion: 930280,
        expectedImpactFingerprint: '<value>',
        configuration: new Operations\PublicApiV1PdfCommercialIdentitiesPublishRevisionConfiguration(),
    ),
);

$response = $sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesPublishRevision(
    request: $request
);

if ($response->pdfCommercialIdentityRevision !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_commercial_identities.publish_revision" method="post" path="/companies/{company}/pdf-commercial-identities/{identity}/revisions" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1PdfCommercialIdentitiesPublishRevisionRequest(
    company: 'f059425b-f9eb-4699-9b63-c0ce3fc41014',
    identity: '21c3d2a3-bad9-44ea-89c6-d4aba6ead9f4',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfCommercialIdentitiesPublishRevisionRequestBody(
        expectedVersion: 930280,
        expectedImpactFingerprint: '<value>',
        configuration: new Operations\PublicApiV1PdfCommercialIdentitiesPublishRevisionConfiguration(),
    ),
);

$response = $sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesPublishRevision(
    request: $request
);

if ($response->pdfCommercialIdentityRevision !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_commercial_identities.publish_revision" method="post" path="/companies/{company}/pdf-commercial-identities/{identity}/revisions" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1PdfCommercialIdentitiesPublishRevisionRequest(
    company: '86152788-c55a-4ee7-a6a4-d32aab285fb7',
    identity: 'ad75bf5f-e201-480a-948e-8e1aa83537db',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfCommercialIdentitiesPublishRevisionRequestBody(
        expectedVersion: 930280,
        expectedImpactFingerprint: '<value>',
        configuration: new Operations\PublicApiV1PdfCommercialIdentitiesPublishRevisionConfiguration(),
    ),
);

$response = $sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesPublishRevision(
    request: $request
);

if ($response->pdfCommercialIdentityRevision !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                  | Type                                                                                                                                                       | Required                                                                                                                                                   | Description                                                                                                                                                |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                                 | [Operations\PublicApiV1PdfCommercialIdentitiesPublishRevisionRequest](../../Models/Operations/PublicApiV1PdfCommercialIdentitiesPublishRevisionRequest.md) | :heavy_check_mark:                                                                                                                                         | The request object to use for the request.                                                                                                                 |

### Response

**[?Operations\PublicApiV1PdfCommercialIdentitiesPublishRevisionResponse](../../Models/Operations/PublicApiV1PdfCommercialIdentitiesPublishRevisionResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1PdfCommercialIdentitiesRestore

Make an archived brand identity active again. Send its current `expected_version` in the body; `If-Match` is not accepted on this action. It does not re-establish the associations that were replaced on archive. Requires `pdf_templates:write` and supports `Idempotency-Key`.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_commercial_identities.restore" method="post" path="/companies/{company}/pdf-commercial-identities/{identity}/restore" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1PdfCommercialIdentitiesRestoreRequest(
    company: '73005ad4-9184-4a3c-8bff-a9d03a900471',
    identity: '6467891a-9ab5-469d-9922-9d22ed1f1387',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfCommercialIdentitiesRestoreRequestBody(
        expectedVersion: 296804,
    ),
);

$response = $sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesRestore(
    request: $request
);

if ($response->pdfCommercialIdentity !== null) {
    // handle response
}
```
### Example Usage: restored

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_commercial_identities.restore" method="post" path="/companies/{company}/pdf-commercial-identities/{identity}/restore" example="restored" -->
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

$request = new Operations\PublicApiV1PdfCommercialIdentitiesRestoreRequest(
    company: 'b5ecaed4-c7c3-45e9-80ec-404fdb4035cf',
    identity: '67d4aa01-5103-45dd-9c61-d88256a4a37c',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfCommercialIdentitiesRestoreRequestBody(
        expectedVersion: 296804,
    ),
);

$response = $sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesRestore(
    request: $request
);

if ($response->pdfCommercialIdentity !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_commercial_identities.restore" method="post" path="/companies/{company}/pdf-commercial-identities/{identity}/restore" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1PdfCommercialIdentitiesRestoreRequest(
    company: '18cf8d53-b6cc-4c05-8251-966e3d85acfc',
    identity: '3fd9764d-452a-4baf-ae2c-0f94e10d5b5b',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfCommercialIdentitiesRestoreRequestBody(
        expectedVersion: 296804,
    ),
);

$response = $sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesRestore(
    request: $request
);

if ($response->pdfCommercialIdentity !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_commercial_identities.restore" method="post" path="/companies/{company}/pdf-commercial-identities/{identity}/restore" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1PdfCommercialIdentitiesRestoreRequest(
    company: '245754df-553f-4ca5-9358-68a871d30edd',
    identity: '7d43ed3a-9c2b-4e5b-89af-4510820eb7b3',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Operations\PublicApiV1PdfCommercialIdentitiesRestoreRequestBody(
        expectedVersion: 296804,
    ),
);

$response = $sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesRestore(
    request: $request
);

if ($response->pdfCommercialIdentity !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                  | Type                                                                                                                                       | Required                                                                                                                                   | Description                                                                                                                                |
| ------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                                 | [Operations\PublicApiV1PdfCommercialIdentitiesRestoreRequest](../../Models/Operations/PublicApiV1PdfCommercialIdentitiesRestoreRequest.md) | :heavy_check_mark:                                                                                                                         | The request object to use for the request.                                                                                                 |

### Response

**[?Operations\PublicApiV1PdfCommercialIdentitiesRestoreResponse](../../Models/Operations/PublicApiV1PdfCommercialIdentitiesRestoreResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |