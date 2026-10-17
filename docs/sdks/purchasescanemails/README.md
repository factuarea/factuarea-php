# PurchaseScanEmails

## Overview

### Available Operations

* [publicApiV1PurchaseScanEmailsList](#publicapiv1purchasescanemailslist) - List purchase scanner emails

## publicApiV1PurchaseScanEmailsList

List the emails received in the purchase scanner mailbox with sender, subject, accepted and rejected attachments and ingestion result. Search by sender or subject and filter by ingestion result or date range; cursor-based pagination.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.purchase_scan_emails.list" method="get" path="/companies/{company}/purchase-scan-emails" example="success" -->
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

$request = new Operations\PublicApiV1PurchaseScanEmailsListRequest(
    company: 'Barrows - Kuhic',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->purchaseScanEmails->publicApiV1PurchaseScanEmailsList(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1PurchaseScanEmailsListRequest](../../Models/Operations/PublicApiV1PurchaseScanEmailsListRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1PurchaseScanEmailsListResponse](../../Models/Operations/PublicApiV1PurchaseScanEmailsListResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |