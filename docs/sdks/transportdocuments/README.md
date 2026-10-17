# TransportDocuments

## Overview

### Available Operations

* [publicApiV1TransportDocumentsCancel](#publicapiv1transportdocumentscancel) - Cancel a transport document
* [publicApiV1TransportDocumentsClose](#publicapiv1transportdocumentsclose) - Close a transport document
* [publicApiV1TransportDocumentsCreate](#publicapiv1transportdocumentscreate) - Create a transport document
* [publicApiV1TransportDocumentsIndex](#publicapiv1transportdocumentsindex) - List all transport documents
* [publicApiV1TransportDocumentsDelete](#publicapiv1transportdocumentsdelete) - Delete a draft transport document
* [publicApiV1TransportDocumentsShow](#publicapiv1transportdocumentsshow) - Retrieve a transport document
* [publicApiV1TransportDocumentsUpdate](#publicapiv1transportdocumentsupdate) - Update a draft transport document
* [publicApiV1TransportDocumentsIssuanceProof](#publicapiv1transportdocumentsissuanceproof) - Download transport document issuance proof
* [publicApiV1TransportDocumentsPdf](#publicapiv1transportdocumentspdf) - Download transport document PDF
* [publicApiV1TransportDocumentsEvidence](#publicapiv1transportdocumentsevidence) - List transport document issuance evidence
* [publicApiV1TransportDocumentsQr](#publicapiv1transportdocumentsqr) - Download transport document QR code
* [publicApiV1TransportDocumentsStatuses](#publicapiv1transportdocumentsstatuses) - List transport document statuses
* [publicApiV1TransportDocumentsIssue](#publicapiv1transportdocumentsissue) - Issue a transport document
* [publicApiV1TransportDocumentsModify](#publicapiv1transportdocumentsmodify) - Modify an issued transport document
* [publicApiV1TransportDocumentsReopen](#publicapiv1transportdocumentsreopen) - Reopen a transport document
* [publicApiV1TransportDocumentsSend](#publicapiv1transportdocumentssend) - Send a transport document by email
* [publicApiV1TransportDocumentsSupersede](#publicapiv1transportdocumentssupersede) - Mark a transport document as superseded

## publicApiV1TransportDocumentsCancel

Cancel an `issued` transport document with a `reason` (3–500 characters). It stores a final PDF version marked «ANULADO» that the public URL keeps serving, and the document becomes terminal (`cancelled`). A PDF over 5 MB returns 422 `transport_document_pdf_too_large` and nothing changes. Irreversible: requires `Idempotency-Key`.

### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.transport_documents.cancel" method="post" path="/companies/{company}/transport-documents/{transport_document}/cancel" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1TransportDocumentsCancelRequest(
    company: '798e7b21-df30-4496-a81a-7333099737e1',
    transportDocument: '08181c03-7310-4a74-863d-5a0b9aa0bcbd',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CancelTransportDocumentRequest(
        reason: '<value>',
    ),
);

$response = $sdk->transportDocuments->publicApiV1TransportDocumentsCancel(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.transport_documents.cancel" method="post" path="/companies/{company}/transport-documents/{transport_document}/cancel" example="success" -->
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

$request = new Operations\PublicApiV1TransportDocumentsCancelRequest(
    company: '30c66773-d963-4f92-97da-25813844dc5d',
    transportDocument: '33ab7928-cb47-49a2-a164-ffe444a0ddf8',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\CancelTransportDocumentRequest(
        reason: '<value>',
    ),
);

$response = $sdk->transportDocuments->publicApiV1TransportDocumentsCancel(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                      | Type                                                                                                                           | Required                                                                                                                       | Description                                                                                                                    |
| ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                     | [Operations\PublicApiV1TransportDocumentsCancelRequest](../../Models/Operations/PublicApiV1TransportDocumentsCancelRequest.md) | :heavy_check_mark:                                                                                                             | The request object to use for the request.                                                                                     |

### Response

**[?Operations\PublicApiV1TransportDocumentsCancelResponse](../../Models/Operations/PublicApiV1TransportDocumentsCancelResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1TransportDocumentsClose

Mark an `issued` transport document as finished (`closed`, `close_mode: manual`). Documents are also closed automatically 7 days after their last transport date. A manually closed document can be reopened until that automatic-close deadline (`reopen_deadline`).

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.transport_documents.close" method="post" path="/companies/{company}/transport-documents/{transport_document}/close" example="success" -->
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



$response = $sdk->transportDocuments->publicApiV1TransportDocumentsClose(
    company: 'cb90f435-8f83-48cb-99b6-08adfefe482e',
    transportDocument: '82e06a7e-510b-48f4-bee4-fc8889589bd4',
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
| `transportDocument`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Public identifier (UUID v7) of the transport control document (DeCA).                                                                                                                                                                                                                                                                                                                                                                                                                                                       |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                               | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |

### Response

**[?Operations\PublicApiV1TransportDocumentsCloseResponse](../../Models/Operations/PublicApiV1TransportDocumentsCloseResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1TransportDocumentsCreate

Create a transport document (DeCA) in `draft`. `company_role` says whether your company acts as contractual shipper (`shipper`) or effective carrier (`carrier`); your party is filled with your company data and the other one from `contact_id` or from the fields you send. Pass `delivery_note_ids` (1 to 50 of your delivery notes) to pre-fill one shipment per delivery note with its destination, date and goods (services excluded, frozen weights); a cancelled delivery note is rejected with 422 `delivery_note_cancelled`. A shipment that omits `origin` gets your default loading place or, if you have none, your registered address (an explicit `origin: null` leaves it without origin). The `id` is generated by the server; `shipments[].id` is optional and an identifier already in use returns 422 on `shipments.N.id`. A goods line whose `quantity × gross_weight_kg` exceeds 9,999,999,999.9999 kg or rounds to 0 returns 422 `invalid_gross_weight` on `shipments.N.goods.M.gross_weight_kg`. A draft admits incomplete data; the minimum data is only required to issue. A concurrent write that collides on an identifier returns 409 `resource_conflict`: nothing was saved and the request can be retried. Returns 201 with the resource and a `Location` header.

### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.transport_documents.create" method="post" path="/companies/{company}/transport-documents" example="missing_api_key" -->
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

$body = new Components\CreateTransportDocumentRequest(
    companyRole: Components\CreateTransportDocumentRequestCompanyRole::Shipper,
);

$response = $sdk->transportDocuments->publicApiV1TransportDocumentsCreate(
    company: 'cc2b66de-2f2f-4726-af76-c0e08f8449bf',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.transport_documents.create" method="post" path="/companies/{company}/transport-documents" example="success" -->
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

$body = new Components\CreateTransportDocumentRequest(
    companyRole: Components\CreateTransportDocumentRequestCompanyRole::Shipper,
);

$response = $sdk->transportDocuments->publicApiV1TransportDocumentsCreate(
    company: 'e8384ad4-a4a0-4fa6-a4fb-1841af24dfa2',
    body: $body,
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
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [Components\CreateTransportDocumentRequest](../../Models/Components/CreateTransportDocumentRequest.md)                                                                                                                                                                                                                                                                                                                                                                                                                      | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                               | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |

### Response

**[?Operations\PublicApiV1TransportDocumentsCreateResponse](../../Models/Operations/PublicApiV1TransportDocumentsCreateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1TransportDocumentsIndex

List the transport control documents (DeCA, Spanish electronic administrative control document for road freight) of your company with cursor-based pagination. Filters: `status` (also `status[in]`), `transport_date[gte]`/`transport_date[lte]` (a document matches when the date range of its shipments overlaps the period), `company_role` (`shipper` or `carrier`), `carrier_tax_id`, `delivery_note_id` (documents with a shipment from that delivery note) and `q` (number, parties or plates). Sort with `sort` (`transport_date`, `number`, `created`, `updated`; prefix `-` for descending; default `-transport_date`). `q` takes 1 to 255 characters. Any other query parameter returns 400. The MCP tool `search_transport_documents` accepts the same `sort` and `q`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.transport_documents.index" method="get" path="/companies/{company}/transport-documents" example="success" -->
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

$request = new Operations\PublicApiV1TransportDocumentsIndexRequest(
    company: 'f5e7cc08-541c-48b7-829d-5f92b0a0ef33',
    sort: Operations\PublicApiV1TransportDocumentsIndexSort::MinusTransportDate,
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->transportDocuments->publicApiV1TransportDocumentsIndex(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                    | Type                                                                                                                         | Required                                                                                                                     | Description                                                                                                                  |
| ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                   | [Operations\PublicApiV1TransportDocumentsIndexRequest](../../Models/Operations/PublicApiV1TransportDocumentsIndexRequest.md) | :heavy_check_mark:                                                                                                           | The request object to use for the request.                                                                                   |

### Response

**[?Operations\PublicApiV1TransportDocumentsIndexResponse](../../Models/Operations/PublicApiV1TransportDocumentsIndexResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1TransportDocumentsDelete

Delete a transport document that is still in `draft`, together with its shipments. It consumes no number and leaves no file. An issued document is always kept: deleting it returns 422 `transport_document_cannot_be_deleted`. Returns 204.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.transport_documents.delete" method="delete" path="/companies/{company}/transport-documents/{transport_document}" -->
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



$response = $sdk->transportDocuments->publicApiV1TransportDocumentsDelete(
    company: 'b9a829ff-598e-4aee-803b-366c362a2c16',
    transportDocument: '07a869a2-3ee6-4df6-8ef1-585439e7862b',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->statusCode === 200) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                   |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `transportDocument`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Public identifier (UUID v7) of the transport control document (DeCA).                                                                                                                                                                                                                                                                                                                                                                                                                                                       |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                               | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |

### Response

**[?Operations\PublicApiV1TransportDocumentsDeleteResponse](../../Models/Operations/PublicApiV1TransportDocumentsDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1TransportDocumentsShow

Retrieve a transport document (DeCA) by its `id`. The resource carries the parties, vehicle, shipments with their goods and declared measure, every version with its author, reason, changes and sha256, the public URL printed on the QR, and the lifecycle dates (including `reopen_deadline` and `retention_until`). Each shipment built from a delivery note reports `source_changed`/`source_changes` when the delivery note changed after the current version, and `source_missing` when it was cancelled or deleted. A document of another company returns 404 `transport_document_not_found`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.transport_documents.show" method="get" path="/companies/{company}/transport-documents/{transport_document}" example="success" -->
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



$response = $sdk->transportDocuments->publicApiV1TransportDocumentsShow(
    company: 'f33e0104-7ba8-4855-adc6-eec9257d017d',
    transportDocument: '77705b99-7a2f-4bc8-914c-6b1b792a7daf',
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
| `transportDocument`                                                                                                                                                                                                                                                                                                                                                          | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the transport control document (DeCA).                                                                                                                                                                                                                                                                                                        |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1TransportDocumentsShowResponse](../../Models/Operations/PublicApiV1TransportDocumentsShowResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1TransportDocumentsUpdate

Partially update a transport document in `draft`: an absent field is kept, an explicit `null` clears it and `shipments` replaces the whole list. Any other status returns 422 `transport_document_cannot_be_edited`; an issued document is changed with `POST /v1/companies/{company}/transport-documents/{transport_document}/modify`. `shipments[].id` only identifies a shipment of this document (any other returns 422 `parameter_invalid_value` on `shipments.N.id`); a goods line with an impossible weight returns 422 `invalid_gross_weight`. A concurrent write that collides on an identifier returns 409 `resource_conflict`, retryable: nothing was saved.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.transport_documents.update" method="patch" path="/companies/{company}/transport-documents/{transport_document}" example="success" -->
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

$request = new Operations\PublicApiV1TransportDocumentsUpdateRequest(
    company: '5c3e0b9d-5730-46ee-8a5e-2870a3912d60',
    transportDocument: '5c451ad9-1364-4b13-8047-cef58595dbb6',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->transportDocuments->publicApiV1TransportDocumentsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                      | Type                                                                                                                           | Required                                                                                                                       | Description                                                                                                                    |
| ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                     | [Operations\PublicApiV1TransportDocumentsUpdateRequest](../../Models/Operations/PublicApiV1TransportDocumentsUpdateRequest.md) | :heavy_check_mark:                                                                                                             | The request object to use for the request.                                                                                     |

### Response

**[?Operations\PublicApiV1TransportDocumentsUpdateResponse](../../Models/Operations/PublicApiV1TransportDocumentsUpdateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1TransportDocumentsIssuanceProof

Requires the scope `transport_documents:read` (it contains the issuance evidence; `pdfs:read` alone returns 403 `insufficient_scope`). Generate on demand a PDF proof of issuance with the number, public URL and QR, parties, every version with its date, author, reason, data origin and sha256, the status changes, the email deliveries and the public downloads. It is not stored: each version is verified by recomputing the sha256 of its PDF. Pass `?download=1` for `Content-Disposition: attachment`. A draft returns 422.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.transport_documents.issuance_proof" method="get" path="/companies/{company}/transport-documents/{transport_document}/issuance-proof" -->
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



$response = $sdk->transportDocuments->publicApiV1TransportDocumentsIssuanceProof(
    company: '9336d00b-9397-43c6-b538-75a1661bf5f4',
    transportDocument: 'ebffbe34-9fcd-4c9c-a720-ed3597d3735a',
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
| `transportDocument`                                                                                                                                                                                                                                                                                                                                                          | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the transport control document (DeCA).                                                                                                                                                                                                                                                                                                        |                                                                                                                                                                                                                                                                                                                                                                              |
| `download`                                                                                                                                                                                                                                                                                                                                                                   | *?string*                                                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pass `1` (or `true`) to receive the PDF with `Content-Disposition: attachment`; otherwise it is served `inline`.                                                                                                                                                                                                                                                             |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1TransportDocumentsIssuanceProofResponse](../../Models/Operations/PublicApiV1TransportDocumentsIssuanceProofResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1TransportDocumentsPdf

Download the stored PDF of the current version or of `?version=N`, byte for byte as it was issued (never re-rendered): its sha256 matches `versions[].sha256`. Returns the binary stream (`application/pdf`); pass `?download=1` for `Content-Disposition: attachment`. A draft returns 422, a purged document 410 `transport_document_file_unavailable` and a `version` the document does not have 404 `transport_document_version_not_found` (`error.param` = `version`).

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.transport_documents.pdf" method="get" path="/companies/{company}/transport-documents/{transport_document}/pdf" -->
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

$request = new Operations\PublicApiV1TransportDocumentsPdfRequest(
    company: '488fe94f-b2f5-4a5b-ab67-c16b50825434',
    transportDocument: 'c4471e84-f1a8-4680-9a81-9066b64158da',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->transportDocuments->publicApiV1TransportDocumentsPdf(
    request: $request
);

if ($response->bytes !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                | Type                                                                                                                     | Required                                                                                                                 | Description                                                                                                              |
| ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                               | [Operations\PublicApiV1TransportDocumentsPdfRequest](../../Models/Operations/PublicApiV1TransportDocumentsPdfRequest.md) | :heavy_check_mark:                                                                                                       | The request object to use for the request.                                                                               |

### Response

**[?Operations\PublicApiV1TransportDocumentsPdfResponse](../../Models/Operations/PublicApiV1TransportDocumentsPdfResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 410, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1TransportDocumentsEvidence

List the issuance evidence of a transport document in chronological order: versions issued or modified, manual closes (`closed`) and automatic closes (`auto_closed`), reopenings, cancellation, supersession, email deliveries with their recipients and public downloads with truncated IP (/24 IPv4, /48 IPv6) and user agent. Public downloads are recorded once per hour for the same network and version (the user agent is stored but does not count as a different download), and only the first 100 are listed: `meta.downloads_total` is the number recorded and `meta.downloads_listed` how many are in `data`. Each entry names its actor in `actor_name` (user, API key or MCP client, with the name it had then; `null` for automatic closes and public downloads) and `occurred_at` carries its UTC offset. It is the proof of issuance of art. 7.1 of Orden FOM/2861/2012. A draft returns 422.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.transport_documents.evidence" method="get" path="/companies/{company}/transport-documents/{transport_document}/evidence" example="success" -->
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



$response = $sdk->transportDocuments->publicApiV1TransportDocumentsEvidence(
    company: '16912c8d-e862-47c4-b73b-6a440ed3bc7b',
    transportDocument: 'e90b2336-e74b-44b3-b43a-01829853b49f',
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
| `transportDocument`                                                                                                                                                                                                                                                                                                                                                          | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the transport control document (DeCA).                                                                                                                                                                                                                                                                                                        |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1TransportDocumentsEvidenceResponse](../../Models/Operations/PublicApiV1TransportDocumentsEvidenceResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1TransportDocumentsQr

Download the QR code that encodes the permanent public URL of the document, generated on the server as `?format=svg` (default) or `?format=png`. The URL is the same in every version. Pass `?download=1` for `Content-Disposition: attachment`. A draft has no URL and returns 422.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.transport_documents.qr" method="get" path="/companies/{company}/transport-documents/{transport_document}/qr" -->
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

$request = new Operations\PublicApiV1TransportDocumentsQrRequest(
    company: 'adffce9d-4ca0-4c11-be46-db6687a11bb8',
    transportDocument: 'f1431be2-118a-430f-8fa4-45a6f3969d8b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->transportDocuments->publicApiV1TransportDocumentsQr(
    request: $request
);

if ($response->twoHundredImageSvgPlusXmlBytes !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                              | Type                                                                                                                   | Required                                                                                                               | Description                                                                                                            |
| ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                             | [Operations\PublicApiV1TransportDocumentsQrRequest](../../Models/Operations/PublicApiV1TransportDocumentsQrRequest.md) | :heavy_check_mark:                                                                                                     | The request object to use for the request.                                                                             |

### Response

**[?Operations\PublicApiV1TransportDocumentsQrResponse](../../Models/Operations/PublicApiV1TransportDocumentsQrResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1TransportDocumentsStatuses

List the closed catalog of transport document statuses (`draft`, `issued`, `closed`, `cancelled`, `superseded`) with their Spanish label and UI color. Use it to populate filters or status pickers instead of hard-coding values. The response `data` is an array of `{ value, label, color }` items.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.transport_documents.statuses" method="get" path="/companies/{company}/transport-documents/statuses" example="success" -->
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



$response = $sdk->transportDocuments->publicApiV1TransportDocumentsStatuses(
    company: '4cf581a8-378d-4e30-af9c-96733f45f8f1',
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

**[?Operations\PublicApiV1TransportDocumentsStatusesResponse](../../Models/Operations/PublicApiV1TransportDocumentsStatusesResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1TransportDocumentsIssue

Issue a draft transport document. It checks the minimum data (shipper name, tax ID and full address; carrier name and tax ID; at least one shipment with origin, destination, goods and declared measure; tractor plate; transport date), that every origin and destination is in Spain and that every transport date is today or later (Europe/Madrid). In a single atomic operation it assigns the next number of the `DC` series and the permanent public URL, renders and stores version 1 of the PDF with its sha256, and moves the document to `issued`. Missing data returns 422 `transport_document_not_issuable` with the list in `error.details.missing`, using this closed vocabulary: `shipper.name`, `shipper.tax_id`, `shipper.address`, `carrier.name`, `carrier.tax_id`, `vehicle.tractor_plate`, `transport_date`, `shipments` (no shipment) and `shipments.N.origin|destination|goods|measure` with `N` counting from 1. A PDF over 5 MB returns 422 `transport_document_pdf_too_large` and nothing is issued. Irreversible: requires `Idempotency-Key`. Hand the PDF, link or QR to the driver before the transport starts.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.transport_documents.issue" method="post" path="/companies/{company}/transport-documents/{transport_document}/issue" example="success" -->
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



$response = $sdk->transportDocuments->publicApiV1TransportDocumentsIssue(
    company: 'f2945cea-b9cc-4666-830d-03c0f95430c1',
    transportDocument: 'ea082fff-b51e-468c-9c06-29f771ff3b27',
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
| `transportDocument`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the transport control document (DeCA).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1TransportDocumentsIssueResponse](../../Models/Operations/PublicApiV1TransportDocumentsIssueResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1TransportDocumentsModify

Modify an `issued` transport document during the service (method 1 of the Resolution of 5 June 2026): same number, same URL and same QR. Send the changed data plus `reason` (3–500 characters) and `data_origin` (`company` or `carrier_reported`). The parties cannot change (422 `transport_document_parties_immutable`: cancel and issue a new one) and a request that changes nothing returns 422 `transport_document_no_changes`. A document has at most 50 versions and the last one is reserved for cancelling or superseding, so the 49th modification returns 422 `transport_document_version_limit_reached` (issue a new document and mark this one as superseded); a PDF over 5 MB returns 422 `transport_document_pdf_too_large`; a goods line with an impossible weight, 422 `invalid_gross_weight`; a `shipments[].id` of another document, 422 on `shipments.N.id`; and a concurrent write that collides on an identifier, 409 `resource_conflict` (retryable, nothing saved). If the result lacks minimum data it returns 422 `transport_document_cannot_be_modified` with `error.details.missing` (same vocabulary as issuing). It stores a new full PDF version that keeps the original creation date and lists the data that is no longer valid. Irreversible: requires `Idempotency-Key`. Send the modified PDF to the driver.

### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.transport_documents.modify" method="post" path="/companies/{company}/transport-documents/{transport_document}/modify" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1TransportDocumentsModifyRequest(
    company: '2dc749ad-8e43-4762-bb9d-3f856395cfce',
    transportDocument: '61bf4e2d-cd1d-49bc-b237-a6c51c9f6785',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ModifyTransportDocumentRequest(
        reason: '<value>',
        dataOrigin: Components\ModifyTransportDocumentRequestDataOrigin::Company,
    ),
);

$response = $sdk->transportDocuments->publicApiV1TransportDocumentsModify(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.transport_documents.modify" method="post" path="/companies/{company}/transport-documents/{transport_document}/modify" example="success" -->
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

$request = new Operations\PublicApiV1TransportDocumentsModifyRequest(
    company: '077aec2b-39ea-4b4c-ae12-2219d410f90b',
    transportDocument: 'adc77d7b-e624-4a37-8364-cda2a146b3fd',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ModifyTransportDocumentRequest(
        reason: '<value>',
        dataOrigin: Components\ModifyTransportDocumentRequestDataOrigin::Company,
    ),
);

$response = $sdk->transportDocuments->publicApiV1TransportDocumentsModify(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                      | Type                                                                                                                           | Required                                                                                                                       | Description                                                                                                                    |
| ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                     | [Operations\PublicApiV1TransportDocumentsModifyRequest](../../Models/Operations/PublicApiV1TransportDocumentsModifyRequest.md) | :heavy_check_mark:                                                                                                             | The request object to use for the request.                                                                                     |

### Response

**[?Operations\PublicApiV1TransportDocumentsModifyResponse](../../Models/Operations/PublicApiV1TransportDocumentsModifyResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1TransportDocumentsReopen

Reopen a manually `closed` transport document with a `reason` (3–500 characters). It returns to `issued` without creating a version. Allowed only before the automatic-close deadline and never after an automatic close; otherwise 422 `transport_document_cannot_be_reopened`.

### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.transport_documents.reopen" method="post" path="/companies/{company}/transport-documents/{transport_document}/reopen" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1TransportDocumentsReopenRequest(
    company: 'd4481342-56e6-4736-960c-62086f1ddd61',
    transportDocument: '62f14943-9c96-4908-a746-a7217ae9dc54',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReopenTransportDocumentRequest(
        reason: '<value>',
    ),
);

$response = $sdk->transportDocuments->publicApiV1TransportDocumentsReopen(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.transport_documents.reopen" method="post" path="/companies/{company}/transport-documents/{transport_document}/reopen" example="success" -->
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

$request = new Operations\PublicApiV1TransportDocumentsReopenRequest(
    company: '50c658c9-6c1a-4362-94a8-a477bb1ca7f8',
    transportDocument: '64086af0-73f7-42c7-bc57-f5cf4e61139a',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ReopenTransportDocumentRequest(
        reason: '<value>',
    ),
);

$response = $sdk->transportDocuments->publicApiV1TransportDocumentsReopen(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                      | Type                                                                                                                           | Required                                                                                                                       | Description                                                                                                                    |
| ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                     | [Operations\PublicApiV1TransportDocumentsReopenRequest](../../Models/Operations/PublicApiV1TransportDocumentsReopenRequest.md) | :heavy_check_mark:                                                                                                             | The request object to use for the request.                                                                                     |

### Response

**[?Operations\PublicApiV1TransportDocumentsReopenResponse](../../Models/Operations/PublicApiV1TransportDocumentsReopenResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1TransportDocumentsSend

Email the stored PDF of the current version, with the public link, to up to 5 recipients in total across `to`, `cc` and `bcc`, with optional `subject` and `message`. Without `to`, the email of the carrier contact is used; if there is none, 422 `transport_document_not_sendable`. Only `issued` or `closed` documents can be sent; the delivery is recorded in the issuance evidence with its recipients. Irreversible: requires `Idempotency-Key`. Returns 410 `transport_document_file_unavailable` once the files were purged, and 500 `send_failed` if the mail provider rejects or cannot take the message (nothing is recorded in the evidence; retry with a new `Idempotency-Key`).

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.transport_documents.send" method="post" path="/companies/{company}/transport-documents/{transport_document}/send" example="success" -->
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

$request = new Operations\PublicApiV1TransportDocumentsSendRequest(
    company: 'fc18ab07-133c-436b-b28b-067a6964661e',
    transportDocument: 'b18dca40-9598-4a8b-b5c9-ef9884d1be16',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->transportDocuments->publicApiV1TransportDocumentsSend(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1TransportDocumentsSendRequest](../../Models/Operations/PublicApiV1TransportDocumentsSendRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1TransportDocumentsSendResponse](../../Models/Operations/PublicApiV1TransportDocumentsSendResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 410, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1TransportDocumentsSupersede

Mark an `issued` transport document as superseded by the carrier's own document (method 2 of the Resolution): send `reference` (1–300 characters, e.g. its number or URL) and `reason`. It stores a final PDF version marked «SUSTITUIDO» and the document becomes terminal (`superseded`). A PDF over 5 MB returns 422 `transport_document_pdf_too_large` and nothing changes. Irreversible: requires `Idempotency-Key`.

### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.transport_documents.supersede" method="post" path="/companies/{company}/transport-documents/{transport_document}/supersede" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1TransportDocumentsSupersedeRequest(
    company: 'd6addd84-4bac-455e-904d-c6a237b1234d',
    transportDocument: '40dca93f-b949-4b5c-81f0-28a5f14cb4ca',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SupersedeTransportDocumentRequest(
        reference: '<value>',
        reason: '<value>',
    ),
);

$response = $sdk->transportDocuments->publicApiV1TransportDocumentsSupersede(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.transport_documents.supersede" method="post" path="/companies/{company}/transport-documents/{transport_document}/supersede" example="success" -->
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

$request = new Operations\PublicApiV1TransportDocumentsSupersedeRequest(
    company: 'e97bc226-ed69-4642-b0ae-bb9131d59c35',
    transportDocument: 'e9f467cc-d937-43e4-91f6-977c79b0d44d',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\SupersedeTransportDocumentRequest(
        reference: '<value>',
        reason: '<value>',
    ),
);

$response = $sdk->transportDocuments->publicApiV1TransportDocumentsSupersede(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                            | Type                                                                                                                                 | Required                                                                                                                             | Description                                                                                                                          |
| ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                                           | [Operations\PublicApiV1TransportDocumentsSupersedeRequest](../../Models/Operations/PublicApiV1TransportDocumentsSupersedeRequest.md) | :heavy_check_mark:                                                                                                                   | The request object to use for the request.                                                                                           |

### Response

**[?Operations\PublicApiV1TransportDocumentsSupersedeResponse](../../Models/Operations/PublicApiV1TransportDocumentsSupersedeResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |