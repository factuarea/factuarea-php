# Verifactu.AeatAccess

## Overview

### Available Operations

* [publicApiV1VerifactuAeatAccessList](#publicapiv1verifactuaeataccesslist) - List AEAT access records
* [publicApiV1VerifactuAeatAccessShow](#publicapiv1verifactuaeataccessshow) - Retrieve an AEAT access record

## publicApiV1VerifactuAeatAccessList

Return the dissociated (anonymized) AEAT access ledger with cursor-based pagination. Third-party tax identifiers (NIF) are never exposed; the cursor uses the underlying record UUID v7 only for ordering.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.verifactu.aeat_access.list" method="get" path="/companies/{company}/verifactu/aeat-access/records" example="success" -->
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

$request = new Operations\PublicApiV1VerifactuAeatAccessListRequest(
    company: 'Anderson, Koelpin and Mayert',
    startingAfter: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a42',
    endingBefore: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a42',
    dateFrom: '2026-01-01',
    dateTo: '2026-03-31',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->verifactu->aeatAccess->publicApiV1VerifactuAeatAccessList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                    | Type                                                                                                                         | Required                                                                                                                     | Description                                                                                                                  |
| ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                   | [Operations\PublicApiV1VerifactuAeatAccessListRequest](../../Models/Operations/PublicApiV1VerifactuAeatAccessListRequest.md) | :heavy_check_mark:                                                                                                           | The request object to use for the request.                                                                                   |

### Response

**[?Operations\PublicApiV1VerifactuAeatAccessListResponse](../../Models/Operations/PublicApiV1VerifactuAeatAccessListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1VerifactuAeatAccessShow

Retrieve a single dissociated AEAT access record by its `id` (UUID v7). Returns 404 if the record does not exist or belongs to another company.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.verifactu.aeat_access.show" method="get" path="/companies/{company}/verifactu/aeat-access/records/{record}" example="success" -->
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



$response = $sdk->verifactu->aeatAccess->publicApiV1VerifactuAeatAccessShow(
    company: 'Feil and Sons',
    record: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID) of the company the request acts on. It must be in your credential's scope; read it from `GET /v1/me` (`data.scope[].id`). Never the tax ID.                                                                                                                                                                                                         |                                                                                                                                                                                                                                                                                                                                                                              |
| `record`                                                                                                                                                                                                                                                                                                                                                                     | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the AEAT access record, as returned in `id` by its list and detail responses.                                                                                                                                                                                                                                                                 |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1VerifactuAeatAccessShowResponse](../../Models/Operations/PublicApiV1VerifactuAeatAccessShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |