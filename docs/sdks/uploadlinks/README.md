# Tasks.UploadLinks

## Overview

### Available Operations

* [publicApiV1TasksUploadLinksCreate](#publicapiv1tasksuploadlinkscreate) - Create a task upload link

## publicApiV1TasksUploadLinksCreate

Create a single-use link to upload files to a task from a phone or by someone without an account. The link expires after 30 minutes and works once; the response includes the `url` (shown only now). `target` and `note` behave as in the attachment upload.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.upload_links.create" method="post" path="/companies/{company}/tasks/{task}/upload-links" example="success" -->
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

$request = new Operations\PublicApiV1TasksUploadLinksCreateRequest(
    company: 'Goyette - Smitham',
    task: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->tasks->uploadLinks->publicApiV1TasksUploadLinksCreate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                  | Type                                                                                                                       | Required                                                                                                                   | Description                                                                                                                |
| -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                 | [Operations\PublicApiV1TasksUploadLinksCreateRequest](../../Models/Operations/PublicApiV1TasksUploadLinksCreateRequest.md) | :heavy_check_mark:                                                                                                         | The request object to use for the request.                                                                                 |

### Response

**[?Operations\PublicApiV1TasksUploadLinksCreateResponse](../../Models/Operations/PublicApiV1TasksUploadLinksCreateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |