# Verifactu

## Overview

### Available Operations

* [publicApiV1VerifactuConfig](#publicapiv1verifactuconfig) - Retrieve VeriFactu config
* [publicApiV1VerifactuStats](#publicapiv1verifactustats) - Get VeriFactu stats

## publicApiV1VerifactuConfig

Return the VeriFactu configuration of your company (mode, environment, enrollment status) and how it remits to AEAT: `remission_mode` (`own_certificate`, `social_collaborator` or `power_of_attorney`), whether it has an active representation and its kind, and the state of the certificate that the remission would present (`presenter_certificate_status`: `valid`, `invalid` or `not_configured`). `has_active_representation` is true only for a current representation that has NOT expired; `active_representation_valid_until` and `active_representation_is_expired` describe the current one (even an expired one keeps its id and kind), and `social_collaborator_available` says whether this instance offers the `social_collaborator` mode. The certificate password is never exposed, and neither is anything about the certificate of Factuarea. Returned as `{ "data": VeriFactuConfig }`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.verifactu.config" method="get" path="/companies/{company}/verifactu/config" example="success" -->
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



$response = $sdk->verifactu->publicApiV1VerifactuConfig(
    company: 'Lehner - Towne',
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

**[?Operations\PublicApiV1VerifactuConfigResponse](../../Models/Operations/PublicApiV1VerifactuConfigResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1VerifactuStats

Aggregated KPIs of your VeriFactu records: total count, counts per status (pending, submitted, accepted, rejected, error), breakdown by record and invoice type, and last transmission timestamp. Accepts optional `date_from`, `date_to`, and `environment` filters. It also reports the pending-remission alert: `pending_incident_count` — the records still waiting to be remitted to AEAT: pending (or without a state yet), in error of ANY kind, submitted and rejected records that were already subsanados; accepted records and rejected ones still waiting for your correction are not counted, nor are sandbox companies —, `blocked_incident_count` — of those, the blocked ones: errors with no automatic retry that need your action (see `POST /v1/companies/{company}/verifactu/records/retry-blocked`) — and `oldest_pending_at`, the generation time of the oldest pending record. Those three fields are never affected by the filters: they are the figure art. 16 of Order HAC/1177/2024 asks you to show the taxpayer. Returned as `{ "data": VeriFactuStats }`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.verifactu.stats" method="get" path="/companies/{company}/verifactu/stats" example="success" -->
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

$request = new Operations\PublicApiV1VerifactuStatsRequest(
    company: 'Fadel - Kassulke',
    status: 'accepted',
    recordType: 'alta',
    invoiceType: 'F1',
    dateFrom: '2026-01-01',
    dateTo: '2026-03-31',
    environment: 'production',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->verifactu->publicApiV1VerifactuStats(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                  | Type                                                                                                       | Required                                                                                                   | Description                                                                                                |
| ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                 | [Operations\PublicApiV1VerifactuStatsRequest](../../Models/Operations/PublicApiV1VerifactuStatsRequest.md) | :heavy_check_mark:                                                                                         | The request object to use for the request.                                                                 |

### Response

**[?Operations\PublicApiV1VerifactuStatsResponse](../../Models/Operations/PublicApiV1VerifactuStatsResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |