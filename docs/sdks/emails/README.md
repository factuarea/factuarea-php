# Emails

## Overview

### Available Operations

* [publicApiV1EmailsIndicators](#publicapiv1emailsindicators) - Summarize email delivery per document
* [publicApiV1EmailsList](#publicapiv1emailslist) - List sent emails
* [publicApiV1EmailsShow](#publicapiv1emailsshow) - Retrieve a sent email

## publicApiV1EmailsIndicators

Answer "did the email for these documents go out?" for a whole batch at once, instead of paging through the deliveries of each one: per document, how many emails were sent, the last status, the last hand-off and how many failed. Ideal to paint a "sent / not sent" column over a page of invoices in a single call.

IMPORTANT — `last_status` and `last_sent_at` describe the hand-off to the OUTGOING SMTP SERVER, not real delivery: a `sent` email may still bounce afterwards without the platform observing it.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.emails.indicators" method="get" path="/emails/indicators" example="success" -->
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



$response = $sdk->emails->publicApiV1EmailsIndicators(
    relatedEntityIds: [
        '6a504d80-c25d-4547-a3f0-509c478974f0',
        'a491c4bc-8f0c-4294-81ee-4880f8c28273',
    ],
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c'

);

if ($response->emailDeliveryIndicators !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `relatedEntityIds`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | array<*string*>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Documents to summarize: UUID v7 of each one, either comma-separated (`related_entity_ids=a,b,c`) or repeated. Required, maximum 100 per call — more returns 422. The type is not needed: a UUID v7 is globally unique, so the batch MAY mix invoices, quotes, pro formas, delivery notes, purchase invoices and recurring invoices. Ids without any email, and ids that do not belong to a document of your company, are OMITTED from the response instead of being reported as zero, so match the results back by `related_entity_id`. |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `relatedEntityType`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     | [?Operations\PublicApiV1EmailsIndicatorsRelatedEntityType](../../Models/Operations/PublicApiV1EmailsIndicatorsRelatedEntityType.md)                                                                                                                                                                                                                                                                                                                                                                                                     | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Optional document type that narrows the resolution of every id in the batch.                                                                                                                                                                                                                                                                                                                                                                                                                                                            |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf).                                                                                                                                        | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |

### Response

**[?Operations\PublicApiV1EmailsIndicatorsResponse](../../Models/Operations/PublicApiV1EmailsIndicatorsResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1EmailsList

Browse the emails your company has sent through the platform — invoices, quotes, pro formas, delivery notes, payment reminders — newest first, so you can answer "did the email for this invoice actually go out?" without asking your customer. Scoped to the authenticated company.

IMPORTANT — `status` describes the hand-off to the OUTGOING SMTP SERVER, not real delivery: `sent` means the outgoing mail server accepted the message, not that the recipient received it.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.emails.list" method="get" path="/emails" example="success" -->
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

$request = new Operations\PublicApiV1EmailsListRequest(
    startingAfter: '48210',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
);

$response = $sdk->emails->publicApiV1EmailsList(
    request: $request
);

if ($response->emailDeliveryList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                          | Type                                                                                               | Required                                                                                           | Description                                                                                        |
| -------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------- |
| `$request`                                                                                         | [Operations\PublicApiV1EmailsListRequest](../../Models/Operations/PublicApiV1EmailsListRequest.md) | :heavy_check_mark:                                                                                 | The request object to use for the request.                                                         |

### Response

**[?Operations\PublicApiV1EmailsListResponse](../../Models/Operations/PublicApiV1EmailsListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1EmailsShow

Retrieve one email by its id, typically after finding it in the listing, to investigate what happened to it: recipient, subject, status, attempts, the error message when it failed, and the document it was sent for. Scoped to the authenticated company.

IMPORTANT — `status` describes the hand-off to the OUTGOING SMTP SERVER, not real delivery: `sent` means the outgoing mail server accepted the message, not that the recipient received it.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.emails.show" method="get" path="/emails/{email}" example="success" -->
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



$response = $sdk->emails->publicApiV1EmailsShow(
    email: 'Jennyfer8@yahoo.com',
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
| `email`                                                                                                                                                                                                                                                                                                                                                                                          | *string*                                                                                                                                                                                                                                                                                                                                                                                         | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                               | N/A                                                                                                                                                                                                                                                                                                                                                                                              |                                                                                                                                                                                                                                                                                                                                                                                                  |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                     | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                       |
| `xActiveProfile`                                                                                                                                                                                                                                                                                                                                                                                 | *?string*                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                               | Operate on behalf of a child company (gestoría master key): pass its public `id` (UUID v7) and the request runs against that child's data without changing the key's scope, tier or environment (omit to use the key's own company). Invalid UUID → `400 parameter_invalid_uuid`; unknown or non-owned id → `404 profile_not_found`. See the [Acting on behalf guide](/guides/acting-on-behalf). | 01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c                                                                                                                                                                                                                                                                                                                                                             |

### Response

**[?Operations\PublicApiV1EmailsShowResponse](../../Models/Operations/PublicApiV1EmailsShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |