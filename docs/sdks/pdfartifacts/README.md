# PdfArtifacts

## Overview

### Available Operations

* [publicApiV1PdfArtifactsDownload](#publicapiv1pdfartifactsdownload) - Download the exact retained PDF
* [publicApiV1PdfArtifactsShow](#publicapiv1pdfartifactsshow) - Read PDF artifact metadata
* [publicApiV1PdfArtifactsPrepare](#publicapiv1pdfartifactsprepare) - Prepare an exact presentation PDF
* [publicApiV1PdfArtifactsRender](#publicapiv1pdfartifactsrender) - Render a prepared PDF artifact

## publicApiV1PdfArtifactsDownload

Download the retained bytes of a ready artifact as `application/pdf` (`Content-Disposition: attachment`). The `ETag` is the SHA-256 of the file; send it in `If-None-Match` to receive `304 Not Modified`. Authorization and integrity are verified before any 304. Requires `pdfs:read`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_artifacts.download" method="get" path="/companies/{company}/pdf-artifacts/{artifact}/download" -->
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



$response = $sdk->pdfArtifacts->publicApiV1PdfArtifactsDownload(
    company: '25fe902a-fa24-4cb9-8ea6-4f99fb82083f',
    artifact: 'b86c4b42-f20c-4d06-bec3-95834fea1846',
    ifNoneMatch: '"5d41402abc4b2a76b9719d911017c592"',
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
| `artifact`                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the PDF artifact.                                                                                                                                                                                                                                                                                                                             |                                                                                                                                                                                                                                                                                                                                                                              |
| `ifNoneMatch`                                                                                                                                                                                                                                                                                                                                                                | *?string*                                                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Conditional request: send the `ETag` you stored from a previous response of this operation (quoted, as received) to get `304 Not Modified` without a body when the content has not changed. Omit it to always receive the full response.                                                                                                                                     | "5d41402abc4b2a76b9719d911017c592"                                                                                                                                                                                                                                                                                                                                           |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1PdfArtifactsDownloadResponse](../../Models/Operations/PublicApiV1PdfArtifactsDownloadResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 410, 422, 429 | application/json                  |
| Errors\Error                      | 500, 503                          | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1PdfArtifactsShow

Read the state of an artifact (`prepared`, `queued`, `processing`, `ready`, `failed`, `unavailable` or `corrupt`), its hash, size and `download_url` once it is ready. It never prepares or renders anything. Requires `pdfs:read`.

### Example Usage: corrupt

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_artifacts.show" method="get" path="/companies/{company}/pdf-artifacts/{artifact}" example="corrupt" -->
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



$response = $sdk->pdfArtifacts->publicApiV1PdfArtifactsShow(
    company: '1e097733-781b-4ffd-8adf-5c179e67d611',
    artifact: 'a63afcce-8b23-4d39-a689-26710c970baf',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: failed

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_artifacts.show" method="get" path="/companies/{company}/pdf-artifacts/{artifact}" example="failed" -->
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



$response = $sdk->pdfArtifacts->publicApiV1PdfArtifactsShow(
    company: '6c29950d-c9c8-41a1-833b-d0fe8be50eb7',
    artifact: 'b097d408-5e76-4798-b0a7-5f5dc9b50610',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: privacy_erased

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_artifacts.show" method="get" path="/companies/{company}/pdf-artifacts/{artifact}" example="privacy_erased" -->
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



$response = $sdk->pdfArtifacts->publicApiV1PdfArtifactsShow(
    company: 'c55fd4bd-bf76-4fbd-b7eb-5195fd8b5f66',
    artifact: 'd2bbaa7f-ce7e-4c59-99b9-2a4818ecb961',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: processing

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_artifacts.show" method="get" path="/companies/{company}/pdf-artifacts/{artifact}" example="processing" -->
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



$response = $sdk->pdfArtifacts->publicApiV1PdfArtifactsShow(
    company: '7879989b-6076-41de-92cb-e9fc28a2e96a',
    artifact: '5e93d191-ea38-4680-9d50-ccb2542c02e3',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ready

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_artifacts.show" method="get" path="/companies/{company}/pdf-artifacts/{artifact}" example="ready" -->
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



$response = $sdk->pdfArtifacts->publicApiV1PdfArtifactsShow(
    company: 'de0dc3ae-03fe-4fbb-b549-0a9542138ed7',
    artifact: '32c05f9b-f760-4fab-8deb-a00f59ba26ee',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unavailable

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_artifacts.show" method="get" path="/companies/{company}/pdf-artifacts/{artifact}" example="unavailable" -->
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



$response = $sdk->pdfArtifacts->publicApiV1PdfArtifactsShow(
    company: '183d5f8e-94f8-4004-a77c-7c74b59571bc',
    artifact: 'bbb84ecb-6cb7-446d-b171-099880ad8731',
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
| `artifact`                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the PDF artifact.                                                                                                                                                                                                                                                                                                                             |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1PdfArtifactsShowResponse](../../Models/Operations/PublicApiV1PdfArtifactsShowResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1PdfArtifactsPrepare

Reserve the artifact of one retained presentation of a document without rendering it. Send `document_type`, `document_id`, `presentation_id`, `purpose`, `format` and `variant` (`official` or `alternative`); every field is required and unknown fields are rejected. Repeating the same selection returns the same artifact. Render it afterwards with the render operation. Requires `pdfs:read` and `Idempotency-Key`.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_artifacts.prepare" method="post" path="/companies/{company}/pdf-artifacts" example="factuarea_version_invalid_format" -->
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

$body = new Components\PreparePdfArtifactRequest(
    documentType: Components\PreparePdfArtifactRequestDocumentType::Quote,
    documentId: '1082db28-d449-4a11-a017-4cdad5a2ce7d',
    presentationId: '6b14dc97-bfb4-4a2f-82c6-d588d6d33608',
    purpose: Components\PreparePdfArtifactRequestPurpose::Corrective,
    format: Components\PreparePdfArtifactRequestFormat::A4,
    variant: Components\PreparePdfArtifactRequestVariant::Alternative,
);

$response = $sdk->pdfArtifacts->publicApiV1PdfArtifactsPrepare(
    company: '840c16f2-42f3-494b-8d10-43c6a26e373d',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: prepared

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_artifacts.prepare" method="post" path="/companies/{company}/pdf-artifacts" example="prepared" -->
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

$body = new Components\PreparePdfArtifactRequest(
    documentType: Components\PreparePdfArtifactRequestDocumentType::Quote,
    documentId: '1082db28-d449-4a11-a017-4cdad5a2ce7d',
    presentationId: '6b14dc97-bfb4-4a2f-82c6-d588d6d33608',
    purpose: Components\PreparePdfArtifactRequestPurpose::Corrective,
    format: Components\PreparePdfArtifactRequestFormat::A4,
    variant: Components\PreparePdfArtifactRequestVariant::Alternative,
);

$response = $sdk->pdfArtifacts->publicApiV1PdfArtifactsPrepare(
    company: 'b9dd5dad-0209-47bb-ada1-b80dfa39b5e7',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: ready

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_artifacts.prepare" method="post" path="/companies/{company}/pdf-artifacts" example="ready" -->
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

$body = new Components\PreparePdfArtifactRequest(
    documentType: Components\PreparePdfArtifactRequestDocumentType::Quote,
    documentId: '1082db28-d449-4a11-a017-4cdad5a2ce7d',
    presentationId: '6b14dc97-bfb4-4a2f-82c6-d588d6d33608',
    purpose: Components\PreparePdfArtifactRequestPurpose::Corrective,
    format: Components\PreparePdfArtifactRequestFormat::A4,
    variant: Components\PreparePdfArtifactRequestVariant::Alternative,
);

$response = $sdk->pdfArtifacts->publicApiV1PdfArtifactsPrepare(
    company: 'bfc23f64-4227-42ab-8df8-6f86777e5e6f',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_artifacts.prepare" method="post" path="/companies/{company}/pdf-artifacts" example="retired_active_profile_header" -->
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

$body = new Components\PreparePdfArtifactRequest(
    documentType: Components\PreparePdfArtifactRequestDocumentType::Quote,
    documentId: '1082db28-d449-4a11-a017-4cdad5a2ce7d',
    presentationId: '6b14dc97-bfb4-4a2f-82c6-d588d6d33608',
    purpose: Components\PreparePdfArtifactRequestPurpose::Corrective,
    format: Components\PreparePdfArtifactRequestFormat::A4,
    variant: Components\PreparePdfArtifactRequestVariant::Alternative,
);

$response = $sdk->pdfArtifacts->publicApiV1PdfArtifactsPrepare(
    company: '7ddfaf49-1989-4f2b-990a-89fe49de4cc3',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_artifacts.prepare" method="post" path="/companies/{company}/pdf-artifacts" example="unsupported_api_version" -->
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

$body = new Components\PreparePdfArtifactRequest(
    documentType: Components\PreparePdfArtifactRequestDocumentType::Quote,
    documentId: '1082db28-d449-4a11-a017-4cdad5a2ce7d',
    presentationId: '6b14dc97-bfb4-4a2f-82c6-d588d6d33608',
    purpose: Components\PreparePdfArtifactRequestPurpose::Corrective,
    format: Components\PreparePdfArtifactRequestFormat::A4,
    variant: Components\PreparePdfArtifactRequestVariant::Alternative,
);

$response = $sdk->pdfArtifacts->publicApiV1PdfArtifactsPrepare(
    company: '8eb68f38-3eeb-4ba7-9861-bbbb66688d77',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Components\PreparePdfArtifactRequest](../../Models/Components/PreparePdfArtifactRequest.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Internal reversible preparation/rendering uses the existing idempotency policy; the same key and raw body replay, another body with that key is rejected with `422 idempotency_key_reused` before admission.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1PdfArtifactsPrepareResponse](../../Models/Operations/PublicApiV1PdfArtifactsPrepareResponse.md)**

### Errors

| Error Type                             | Status Code                            | Content Type                           |
| -------------------------------------- | -------------------------------------- | -------------------------------------- |
| Errors\Error                           | 400, 401, 403, 404, 409, 410, 422, 429 | application/json                       |
| Errors\Error                           | 500, 503                               | application/json                       |
| Errors\APIException                    | 4XX, 5XX                               | \*/\*                                  |

## publicApiV1PdfArtifactsRender

Render a prepared artifact. `mode` is required: `sync` waits for the result and `async` queues it. The artifact is returned in state `ready` (200) or still pending (202); poll the show operation while it is `queued` or `processing`. Rendering an artifact that is already ready changes nothing. It counts against the PDF generation budget. Requires `pdfs:read` and `Idempotency-Key`.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_artifacts.render" method="post" path="/companies/{company}/pdf-artifacts/{artifact}/render" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1PdfArtifactsRenderRequest(
    company: 'fddf1ade-6a80-4816-84be-4a1e267ea3cc',
    artifact: '4f0aa2e8-2798-4c11-86b4-67bd26a0a661',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RenderPdfArtifactRequest(
        mode: Components\RenderPdfArtifactRequestMode::Async,
    ),
);

$response = $sdk->pdfArtifacts->publicApiV1PdfArtifactsRender(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: processing

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_artifacts.render" method="post" path="/companies/{company}/pdf-artifacts/{artifact}/render" example="processing" -->
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

$request = new Operations\PublicApiV1PdfArtifactsRenderRequest(
    company: '43b98850-281f-4c03-a71c-abb5c799ef18',
    artifact: '40bf1071-aa02-4f47-a6fc-faa0da37be04',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RenderPdfArtifactRequest(
        mode: Components\RenderPdfArtifactRequestMode::Async,
    ),
);

$response = $sdk->pdfArtifacts->publicApiV1PdfArtifactsRender(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: queued

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_artifacts.render" method="post" path="/companies/{company}/pdf-artifacts/{artifact}/render" example="queued" -->
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

$request = new Operations\PublicApiV1PdfArtifactsRenderRequest(
    company: 'd1f448a3-339d-43ba-a142-f996ae7c9fd4',
    artifact: '8e191186-2368-4b41-8b82-110c81ea3561',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RenderPdfArtifactRequest(
        mode: Components\RenderPdfArtifactRequestMode::Async,
    ),
);

$response = $sdk->pdfArtifacts->publicApiV1PdfArtifactsRender(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: ready

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_artifacts.render" method="post" path="/companies/{company}/pdf-artifacts/{artifact}/render" example="ready" -->
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

$request = new Operations\PublicApiV1PdfArtifactsRenderRequest(
    company: '1a2031c9-f666-4d7d-b3ae-f02df1285bc2',
    artifact: '44378e87-2c5e-4067-9805-a3a588013aae',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RenderPdfArtifactRequest(
        mode: Components\RenderPdfArtifactRequestMode::Async,
    ),
);

$response = $sdk->pdfArtifacts->publicApiV1PdfArtifactsRender(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_artifacts.render" method="post" path="/companies/{company}/pdf-artifacts/{artifact}/render" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1PdfArtifactsRenderRequest(
    company: 'f633dcf2-4a2d-43e6-b8c5-315f6488f76b',
    artifact: 'ef01316f-4022-4625-9d47-0e9fd5982ec4',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RenderPdfArtifactRequest(
        mode: Components\RenderPdfArtifactRequestMode::Async,
    ),
);

$response = $sdk->pdfArtifacts->publicApiV1PdfArtifactsRender(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.pdf_artifacts.render" method="post" path="/companies/{company}/pdf-artifacts/{artifact}/render" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1PdfArtifactsRenderRequest(
    company: 'b0a4ccae-776d-4ad5-8f26-755fc50c1f33',
    artifact: '2662f108-2363-4c51-ba25-332e74c62c92',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\RenderPdfArtifactRequest(
        mode: Components\RenderPdfArtifactRequestMode::Async,
    ),
);

$response = $sdk->pdfArtifacts->publicApiV1PdfArtifactsRender(
    request: $request
);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                          | Type                                                                                                               | Required                                                                                                           | Description                                                                                                        |
| ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                         | [Operations\PublicApiV1PdfArtifactsRenderRequest](../../Models/Operations/PublicApiV1PdfArtifactsRenderRequest.md) | :heavy_check_mark:                                                                                                 | The request object to use for the request.                                                                         |

### Response

**[?Operations\PublicApiV1PdfArtifactsRenderResponse](../../Models/Operations/PublicApiV1PdfArtifactsRenderResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 410, 422, 429 | application/json                  |
| Errors\Error                      | 500, 503                          | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |