# Tasks.CustomFields

## Overview

### Available Operations

* [publicApiV1TasksCustomFieldsSet](#publicapiv1taskscustomfieldsset) - Set a task custom field value

## publicApiV1TasksCustomFieldsSet

Set the value of one custom field of the task's project. The value must fit the field type (and its options for `dropdown`/`multiselect`); `null` clears an optional field. An invalid value returns 422 `invalid_custom_field_value`.

### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.custom_fields.set" method="put" path="/tasks/{task}/custom-fields/{field}" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1TasksCustomFieldsSetRequest(
    task: '<value>',
    field: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\SetTaskCustomFieldValueV1Request(
        value: [],
    ),
);

$response = $sdk->tasks->customFields->publicApiV1TasksCustomFieldsSet(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.custom_fields.set" method="put" path="/tasks/{task}/custom-fields/{field}" example="success" -->
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

$request = new Operations\PublicApiV1TasksCustomFieldsSetRequest(
    task: '<value>',
    field: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\SetTaskCustomFieldValueV1Request(
        value: [],
    ),
);

$response = $sdk->tasks->customFields->publicApiV1TasksCustomFieldsSet(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                              | Type                                                                                                                   | Required                                                                                                               | Description                                                                                                            |
| ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                             | [Operations\PublicApiV1TasksCustomFieldsSetRequest](../../Models/Operations/PublicApiV1TasksCustomFieldsSetRequest.md) | :heavy_check_mark:                                                                                                     | The request object to use for the request.                                                                             |

### Response

**[?Operations\PublicApiV1TasksCustomFieldsSetResponse](../../Models/Operations/PublicApiV1TasksCustomFieldsSetResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 401, 403, 404, 409, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |