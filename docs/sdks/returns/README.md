# Returns

## Overview

### Available Operations

* [publicApiV1ReturnsApprove](#publicapiv1returnsapprove) - Approve a return
* [publicApiV1ReturnsCreate](#publicapiv1returnscreate) - Request a return
* [publicApiV1ReturnsList](#publicapiv1returnslist) - List all returns
* [publicApiV1ReturnsDelete](#publicapiv1returnsdelete) - Delete a return
* [publicApiV1ReturnsShow](#publicapiv1returnsshow) - Retrieve a return
* [publicApiV1ReturnsUpdate](#publicapiv1returnsupdate) - Update a return
* [publicApiV1ReturnsStatuses](#publicapiv1returnsstatuses) - List return statuses
* [publicApiV1ReturnsReturnableLines](#publicapiv1returnsreturnablelines) - List the returnable lines of a document
* [publicApiV1ReturnsReceive](#publicapiv1returnsreceive) - Receive a return
* [publicApiV1ReturnsRefund](#publicapiv1returnsrefund) - Refund a return
* [publicApiV1ReturnsReject](#publicapiv1returnsreject) - Reject a return

## publicApiV1ReturnsApprove

Approve a return that is still requested. It takes no body. Approving STAMPS the definitive number of the return series, which is what makes this step matter: the number is consumed at this moment and there is no operation that gives it back, so a return that is approved and later deleted would leave a permanent gap — and that is why an approved return can no longer be deleted. From here the return can be received or rejected. A return that is not in the requested status is rejected with 422 naming the transition that is not allowed, and one of another company answers as not found. Irreversible, because of the number it consumes: it requires an `Idempotency-Key` when the policy of your credential demands it, and repeating the call with the same key produces the effect once.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.returns.approve" method="post" path="/companies/{company}/returns/{return}/approve" -->
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

$request = new Operations\PublicApiV1ReturnsApproveRequest(
    company: 'Pouros - Gleason',
    return: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->returns->publicApiV1ReturnsApprove(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                  | Type                                                                                                       | Required                                                                                                   | Description                                                                                                |
| ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                 | [Operations\PublicApiV1ReturnsApproveRequest](../../Models/Operations/PublicApiV1ReturnsApproveRequest.md) | :heavy_check_mark:                                                                                         | The request object to use for the request.                                                                 |

### Response

**[?Operations\PublicApiV1ReturnsApproveResponse](../../Models/Operations/PublicApiV1ReturnsApproveResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ReturnsCreate

Request a return of goods against a document you already issued: the kind of the origin and its public id, and at least one line, each one naming the line of the origin with the reference the returnable-lines read gives you, the quantity with four decimal places and a reason from the closed catalog of reasons. Optionally a header reason — derived from the lines when you omit it —, the destination warehouse and a note. The unit price and the tax rate are NOT accepted from you: both are INHERITED from the line of the origin, because the amount that will eventually be corrected is not decided by whoever asks for the return, and a corrective invoice hangs from it. The article and the variant come from the origin too, and so does the moment of the request. Two rules of admission are checked before anything is written and both answer 422: the return window of the origin has to still be open, and a line cannot return more than what was delivered and is not already covered by another live return — a return that was rejected frees its share again. The return is created as REQUESTED and with no number yet, and it can be edited and deleted while it stays in that status. Ask the returnable lines of the origin first if you want to know the ceiling instead of discovering it through successive rejections. Returns the return that was created, with its public id and its lines, and a location header pointing at it.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.returns.create" method="post" path="/companies/{company}/returns" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1ReturnsCreateRequest(
    company: 'Johnston, Schmitt and Bednar',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateReturnRequest(
        originType: Components\CreateReturnRequestOriginType::DeliveryNote,
        originId: '98da322c-6ab2-4d75-9059-ea21b535eede',
        lines: [
            new Components\CreateReturnRequestLine(
                originLineId: 617641,
                quantity: 6397.61,
                reason: Components\LineReason::MerchandiseReturned,
            ),
        ],
    ),
);

$response = $sdk->returns->publicApiV1ReturnsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.returns.create" method="post" path="/companies/{company}/returns" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1ReturnsCreateRequest(
    company: 'Stanton - Beer',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateReturnRequest(
        originType: Components\CreateReturnRequestOriginType::DeliveryNote,
        originId: '98da322c-6ab2-4d75-9059-ea21b535eede',
        lines: [
            new Components\CreateReturnRequestLine(
                originLineId: 617641,
                quantity: 6397.61,
                reason: Components\LineReason::MerchandiseReturned,
            ),
        ],
    ),
);

$response = $sdk->returns->publicApiV1ReturnsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.returns.create" method="post" path="/companies/{company}/returns" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1ReturnsCreateRequest(
    company: 'Murazik, Roob and Effertz',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\CreateReturnRequest(
        originType: Components\CreateReturnRequestOriginType::DeliveryNote,
        originId: '98da322c-6ab2-4d75-9059-ea21b535eede',
        lines: [
            new Components\CreateReturnRequestLine(
                originLineId: 617641,
                quantity: 6397.61,
                reason: Components\LineReason::MerchandiseReturned,
            ),
        ],
    ),
);

$response = $sdk->returns->publicApiV1ReturnsCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                | Type                                                                                                     | Required                                                                                                 | Description                                                                                              |
| -------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                               | [Operations\PublicApiV1ReturnsCreateRequest](../../Models/Operations/PublicApiV1ReturnsCreateRequest.md) | :heavy_check_mark:                                                                                       | The request object to use for the request.                                                               |

### Response

**[?Operations\PublicApiV1ReturnsCreateResponse](../../Models/Operations/PublicApiV1ReturnsCreateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ReturnsList

List the returns of your company with cursor-based pagination. Filter by status, by the kind of document the goods came from, by reason, by destination warehouse, by business contact, by number and by the dates of the cycle; search free text; and sort by creation, by number or by the date the return was requested. The filters that take a public id — the warehouse, the contact, the origin document — are resolved inside your company, so an id that belongs to another company returns an empty page and never a row. A return that is still requested has NO number: the number is stamped when it is approved, so do not key your own records on it before then. The `id` is stable from the moment the return is created.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.returns.list" method="get" path="/companies/{company}/returns" -->
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

$request = new Operations\PublicApiV1ReturnsListRequest(
    company: 'Konopelski, Rau and Walker',
    sort: Operations\PublicApiV1ReturnsListSort::MinusCreated,
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->returns->publicApiV1ReturnsList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                            | Type                                                                                                 | Required                                                                                             | Description                                                                                          |
| ---------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------- |
| `$request`                                                                                           | [Operations\PublicApiV1ReturnsListRequest](../../Models/Operations/PublicApiV1ReturnsListRequest.md) | :heavy_check_mark:                                                                                   | The request object to use for the request.                                                           |

### Response

**[?Operations\PublicApiV1ReturnsListResponse](../../Models/Operations/PublicApiV1ReturnsListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1ReturnsDelete

Delete a return. It returns no content. Only a return that is still REQUESTED or that was REJECTED can be deleted: one that was approved has consumed a number of the return series, one that was received has a stock movement behind it, and one that was refunded has a fiscal document that cannot be removed — all three are rejected as a business rule violation with 422. A return of another company answers exactly like one that does not exist. Irreversible: this API publishes no operation that brings a deleted return back, so it requires an `Idempotency-Key` when the policy of your credential demands it.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.returns.delete" method="delete" path="/companies/{company}/returns/{return}" -->
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

$request = new Operations\PublicApiV1ReturnsDeleteRequest(
    company: 'Rowe, Botsford and Bradtke',
    return: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->returns->publicApiV1ReturnsDelete(
    request: $request
);

if ($response->statusCode === 200) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                | Type                                                                                                     | Required                                                                                                 | Description                                                                                              |
| -------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                               | [Operations\PublicApiV1ReturnsDeleteRequest](../../Models/Operations/PublicApiV1ReturnsDeleteRequest.md) | :heavy_check_mark:                                                                                       | The request object to use for the request.                                                               |

### Response

**[?Operations\PublicApiV1ReturnsDeleteResponse](../../Models/Operations/PublicApiV1ReturnsDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ReturnsShow

Retrieve a return by its `id`, with its status and its localized label, its number once it has been approved, the document it came from by kind, public id and number, its reason, the destination warehouse, the business contact, the amount that can be refunded and the amount that was refunded, the moments of every step of its cycle, and its lines with the quantity, the reason, the inherited unit price and tax rate and the line amount. When it has been rejected it also carries the rejection reason and its note, and when it has been refunded, the corrective invoice by its public id and its number. The detail publishes no internal identifier of any kind, and it does not expose the reference of the origin line either: that reference belongs to the pre-flight read and to the body of the request. A return of another company answers exactly like one that does not exist.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.returns.show" method="get" path="/companies/{company}/returns/{return}" -->
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



$response = $sdk->returns->publicApiV1ReturnsShow(
    company: 'Braun LLC',
    return: '<value>',
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
| `company`                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `return`                                                                                                                                                                                                                                                                                                                                                                                         | *string*                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1ReturnsShowResponse](../../Models/Operations/PublicApiV1ReturnsShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1ReturnsUpdate

Partially update a return: the header reason, the destination warehouse, the invoice to be corrected and the notes. Only the fields present in the body change, the ones you omit keep their value, and a field sent explicitly as null unsets it — that is how a destination warehouse or a chosen invoice is cleared. An empty body is accepted and does nothing, which is what a partial update means. The body does NOT accept lines: the lines of a return are fixed at the moment it is requested, and correcting them means deleting the return and requesting it again while it is still in that status. Only a return that is still REQUESTED can be edited; on any other status the call is rejected as a business rule violation with 422 naming the status, never with 403. The warehouse and the invoice are resolved inside your company, so a reference of another company is rejected without revealing that it exists.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.returns.update" method="patch" path="/companies/{company}/returns/{return}" -->
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

$request = new Operations\PublicApiV1ReturnsUpdateRequest(
    company: 'Cremin - Spencer',
    return: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\UpdateReturnRequest(
        notes: 'El cliente confirma que trae también el cargador.',
    ),
);

$response = $sdk->returns->publicApiV1ReturnsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                | Type                                                                                                     | Required                                                                                                 | Description                                                                                              |
| -------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                               | [Operations\PublicApiV1ReturnsUpdateRequest](../../Models/Operations/PublicApiV1ReturnsUpdateRequest.md) | :heavy_check_mark:                                                                                       | The request object to use for the request.                                                               |

### Response

**[?Operations\PublicApiV1ReturnsUpdateResponse](../../Models/Operations/PublicApiV1ReturnsUpdateResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ReturnsStatuses

Returns the closed catalog of return statuses with their public `value` and their localized `label`: requested, approved, received, refunded and rejected. Use it to populate filters and status pickers instead of hard-coding values. The cycle runs requested, approved, received and refunded; a return can be rejected from requested and from approved; and refunded and rejected are both TERMINAL. Only a return that is still requested can be edited. The catalog enumerates STATUSES and not transition targets: a return is born requested and nothing transitions back to it.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.returns.statuses" method="get" path="/companies/{company}/returns/statuses" -->
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



$response = $sdk->returns->publicApiV1ReturnsStatuses(
    company: 'Schowalter Inc',
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
| `company`                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1ReturnsStatusesResponse](../../Models/Operations/PublicApiV1ReturnsStatusesResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1ReturnsReturnableLines

Ask what is still returnable of a document BEFORE requesting anything. The kind of the origin and its public id travel as query parameters, and the answer carries, line by line, the reference of the origin line — the one a request quotes —, the article and its variant, the description, how much was delivered, how much is already returned and how much is STILL returnable, plus the unit price and the tax rate that a request would inherit. Quantities carry four decimal places. Returns that were rejected do not count as returned, so their share is available again. This is the read that keeps you from discovering the ceiling through successive rejections. A document of another company answers as not found, and a document with nothing left to return answers with an empty list, which is the correct answer to a legitimate question and never an error.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.returns.returnable_lines" method="get" path="/companies/{company}/returns/returnable-lines" -->
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

$request = new Operations\PublicApiV1ReturnsReturnableLinesRequest(
    company: 'Cremin, Gulgowski and Hyatt',
    originType: Operations\PublicApiV1ReturnsReturnableLinesOriginType::Invoice,
    originId: '4c4ac8ee-39d3-4539-b036-8316ab55245b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->returns->publicApiV1ReturnsReturnableLines(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1ReturnsReturnableLinesRequest](../../Models/Operations/PublicApiV1ReturnsReturnableLinesRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1ReturnsReturnableLinesResponse](../../Models/Operations/PublicApiV1ReturnsReturnableLinesResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1ReturnsReceive

Record the physical reception of an approved return. The body is optional and accepts only two things: the destination WAREHOUSE, by its public id and resolved inside your company, and the MOMENT of the reception. There is no quantity anywhere in this body, and that is deliberate: what comes back is what was approved, and changing the quantity at reception would break the amount that the refund will correct. This call DOES move stock, and it is the part to read before building your own reconciliation: receiving writes the INCOMING stock movement of every line into the destination warehouse, so the balance of those articles goes up by what the return carries. Irreversible: this API publishes no operation that takes that movement back — an adjustment is another movement with another reason, not a reversal — so a received return can no longer be deleted and the call requires an `Idempotency-Key` when the policy of your credential demands it. Repeating it with the same key produces the effect once; without it, a repeated reception would be a second entry of the same goods.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.returns.receive" method="post" path="/companies/{company}/returns/{return}/receive" -->
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

$request = new Operations\PublicApiV1ReturnsReceiveRequest(
    company: 'Goyette, Becker and Gulgowski',
    return: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\ReceiveReturnRequest(
        warehouseId: '01934c8d-2e5f-7a1b-8c9d-4e5f6a7b8e10',
        receivedAt: LocalDate::parse('2026-01-22'),
    ),
);

$response = $sdk->returns->publicApiV1ReturnsReceive(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                  | Type                                                                                                       | Required                                                                                                   | Description                                                                                                |
| ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                 | [Operations\PublicApiV1ReturnsReceiveRequest](../../Models/Operations/PublicApiV1ReturnsReceiveRequest.md) | :heavy_check_mark:                                                                                         | The request object to use for the request.                                                                 |

### Response

**[?Operations\PublicApiV1ReturnsReceiveResponse](../../Models/Operations/PublicApiV1ReturnsReceiveResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ReturnsRefund

Refund a received return by issuing the corrective invoice that returns the money. The body is optional and carries only the invoice to correct, by its public id: when the origin of the return resolves to exactly ONE invoice you can omit it and it is taken; when it resolves to NONE the call is rejected because there is nothing to correct; and when it resolves to SEVERAL and you name none, the call is rejected asking you to choose, and the candidates are the ones the corrective-candidates read of this return returns. This operation is idempotent TWICE OVER, and the two guarantees are different: by the return itself, so two refunds of the same return produce ONE corrective invoice even when they arrive through different channels or days apart; and by the `Idempotency-Key` header, which protects the immediate retry of the same request, including one whose effect you never got to see. It requires that header when the policy of your credential demands it. Irreversible: the corrective invoice is a fiscal document that under VeriFactu is not deleted — it is only voided with another fiscal document — so a refunded return can no longer be deleted. Refunded is terminal. Returns the return with the corrective invoice it produced.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.returns.refund" method="post" path="/companies/{company}/returns/{return}/refund" -->
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

$request = new Operations\PublicApiV1ReturnsRefundRequest(
    company: 'Jerde LLC',
    return: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\RefundReturnRequest(
        targetInvoiceId: '01934c8d-2e5f-7a1b-8c9d-4e5f6a7b8e20',
    ),
);

$response = $sdk->returns->publicApiV1ReturnsRefund(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                | Type                                                                                                     | Required                                                                                                 | Description                                                                                              |
| -------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                               | [Operations\PublicApiV1ReturnsRefundRequest](../../Models/Operations/PublicApiV1ReturnsRefundRequest.md) | :heavy_check_mark:                                                                                       | The request object to use for the request.                                                               |

### Response

**[?Operations\PublicApiV1ReturnsRefundResponse](../../Models/Operations/PublicApiV1ReturnsRefundResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ReturnsReject

Reject a return, from requested or from approved. The body carries the REASON, taken from the closed catalog of rejection reasons — window expired, quantity above what was delivered, or a manual decision — and, only when the reason is the manual decision, an optional note explaining it: a note on any other reason is rejected with 422 naming the parameter, because the other two reasons are facts and not opinions. Rejected is TERMINAL: no transition leads out of it, and the goods that were being returned go back to being returnable, so their quantity can be requested again in another return. A rejected return can still be deleted. A return already in a terminal status is rejected with 422 naming the transition that is not allowed. Irreversible, because the status is terminal: it requires an `Idempotency-Key` when the policy of your credential demands it.

### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.returns.reject" method="post" path="/companies/{company}/returns/{return}/reject" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1ReturnsRejectRequest(
    company: 'Stokes, Feil and O\'Hara',
    return: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\RejectReturnRequest(
        rejectionReason: Components\RejectReturnRequestRejectionReason::WindowExpired,
    ),
);

$response = $sdk->returns->publicApiV1ReturnsReject(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.returns.reject" method="post" path="/companies/{company}/returns/{return}/reject" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1ReturnsRejectRequest(
    company: 'Kozey LLC',
    return: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\RejectReturnRequest(
        rejectionReason: Components\RejectReturnRequestRejectionReason::WindowExpired,
    ),
);

$response = $sdk->returns->publicApiV1ReturnsReject(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.returns.reject" method="post" path="/companies/{company}/returns/{return}/reject" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1ReturnsRejectRequest(
    company: 'Rowe Inc',
    return: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\RejectReturnRequest(
        rejectionReason: Components\RejectReturnRequestRejectionReason::WindowExpired,
    ),
);

$response = $sdk->returns->publicApiV1ReturnsReject(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                | Type                                                                                                     | Required                                                                                                 | Description                                                                                              |
| -------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                               | [Operations\PublicApiV1ReturnsRejectRequest](../../Models/Operations/PublicApiV1ReturnsRejectRequest.md) | :heavy_check_mark:                                                                                       | The request object to use for the request.                                                               |

### Response

**[?Operations\PublicApiV1ReturnsRejectResponse](../../Models/Operations/PublicApiV1ReturnsRejectResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |