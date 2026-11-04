# Tasks.CustomFields

## Overview

### Available Operations

* [publicApiV1TasksCustomFieldsSet](#publicapiv1taskscustomfieldsset) - Set a task custom field value

## publicApiV1TasksCustomFieldsSet

Set the value of one custom field of the task's project. The value must fit the field type (and its options for `dropdown`/`multiselect`); `null` clears an optional field. An invalid value returns 422 `invalid_custom_field_value`.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.custom_fields.set" method="put" path="/companies/{company}/tasks/{task}/custom-fields/{field}" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1TasksCustomFieldsSetRequest(
    company: 'b4f824c6-6ed1-40d7-a232-dd886c9a9800',
    task: 'b0235e1e-c07b-41b9-935d-e4c6ff193f08',
    field: '4da4de22-8cd3-44fc-94e6-5de393d3eea8',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.custom_fields.set" method="put" path="/companies/{company}/tasks/{task}/custom-fields/{field}" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1TasksCustomFieldsSetRequest(
    company: 'Spencer - White',
    task: '<value>',
    field: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.custom_fields.set" method="put" path="/companies/{company}/tasks/{task}/custom-fields/{field}" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1TasksCustomFieldsSetRequest(
    company: '7c05dfcf-ffc4-401a-9f97-8069dd843bab',
    task: '6afbadf7-3f62-4727-b588-c34f6029143f',
    field: '6865cf05-f7f1-4320-ac3d-fc91621e13b7',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.custom_fields.set" method="put" path="/companies/{company}/tasks/{task}/custom-fields/{field}" example="success" -->
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

$request = new Operations\PublicApiV1TasksCustomFieldsSetRequest(
    company: 'Runolfsson Group',
    task: '<value>',
    field: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.tasks.custom_fields.set" method="put" path="/companies/{company}/tasks/{task}/custom-fields/{field}" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1TasksCustomFieldsSetRequest(
    company: '4350d41d-0443-46e7-ad83-d47efbe3949e',
    task: 'f0e5de0a-a7ef-4ece-b91f-6014bb79bf87',
    field: '8315cd5b-1bd7-4083-8345-1189b0c088af',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
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
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |