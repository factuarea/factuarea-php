# Automations.Catalog

## Overview

### Available Operations

* [publicApiV1AutomationsCatalogShow](#publicapiv1automationscatalogshow) - Retrieve the automation catalog
* [publicApiV1AutomationsCatalogTriggerFields](#publicapiv1automationscatalogtriggerfields) - Retrieve the evaluable fields of a trigger

## publicApiV1AutomationsCatalogShow

Everything you need to build a valid automation rule without guessing: the triggers visible to your company, the closed set of condition operators and combinators, and the actions that have a registered adapter together with the JSON schema of their parameters. Visibility follows the plan and the modules of the company, so two accounts can legitimately see different catalogs. The response is deterministic — two consecutive calls with nothing changed return exactly the same body — and its labels are translated according to the `Accept-Language` header, defaulting to Spanish. The evaluable fields of each trigger are NOT included here: they are served per trigger by `GET /v1/companies/{company}/automations/catalog/triggers/{trigger}/fields`, which is what keeps this call from weighing hundreds of kilobytes.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.automations.catalog.show" method="get" path="/companies/{company}/automations/catalog" -->
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



$response = $sdk->automations->catalog->publicApiV1AutomationsCatalogShow(
    company: 'Casper Group',
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

**[?Operations\PublicApiV1AutomationsCatalogShowResponse](../../Models/Operations/PublicApiV1AutomationsCatalogShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## publicApiV1AutomationsCatalogTriggerFields

List the fields you can write conditions against for a single trigger: where to read each one inside the triggering event, its type, the translated label of that type and the operators it accepts. It is the companion of the catalog, split into its own operation because serving the fields of every trigger at once would weigh hundreds of kilobytes per call. A trigger outside the catalog visible to your company returns 404 — whether the name does not exist, its module is not in your plan, or it is admin-only — so this endpoint cannot be used to probe what an account has contracted. A visible trigger whose event type publishes no evaluable fields returns 422.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.automations.catalog.trigger_fields" method="get" path="/companies/{company}/automations/catalog/triggers/{trigger}/fields" -->
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



$response = $sdk->automations->catalog->publicApiV1AutomationsCatalogTriggerFields(
    company: 'Schowalter, Kulas and Bechtelar',
    trigger: '<value>',
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
| `trigger`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Trigger key, a dotted event name such as `invoice.created`.                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1AutomationsCatalogTriggerFieldsResponse](../../Models/Operations/PublicApiV1AutomationsCatalogTriggerFieldsResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |