# Automations.Rules.Versions

## Overview

### Available Operations

* [publicApiV1AutomationsRulesVersionsList](#publicapiv1automationsrulesversionslist) - List the versions of an automation rule
* [publicApiV1AutomationsRulesVersionsShow](#publicapiv1automationsrulesversionsshow) - Retrieve a version of an automation rule

## publicApiV1AutomationsRulesVersionsList

Browse the sealed versions of the definition of a rule, from the most recent to the oldest. Every edit seals a version and every run keeps a pointer to the one it executed, so this is how you reconstruct what a rule looked like when it ran.

IMPORTANT — the cursor of THIS listing is not a UUID. It is the only listing of the automation surface whose read path still paginates by offset, so `next_cursor` carries an opaque token of a different shape (a page number as a string). Treat it as strictly opaque: pass back verbatim the value you received and never build, parse or validate one yourself. The envelope is the canonical one, `limit` behaves as everywhere else, and `ending_before` steps one page back.

A rule of another company returns 200 with an empty list instead of 404 — an empty page tells an attacker nothing about whether the rule exists.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.automations.rules.versions.list" method="get" path="/companies/{company}/automations/rules/{rule}/versions" -->
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

$request = new Operations\PublicApiV1AutomationsRulesVersionsListRequest(
    company: 'Rau - Kris',
    rule: '<value>',
    startingAfter: '2',
    endingBefore: '3',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->automations->rules->versions->publicApiV1AutomationsRulesVersionsList(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                              | Type                                                                                                                                   | Required                                                                                                                               | Description                                                                                                                            |
| -------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                             | [Operations\PublicApiV1AutomationsRulesVersionsListRequest](../../Models/Operations/PublicApiV1AutomationsRulesVersionsListRequest.md) | :heavy_check_mark:                                                                                                                     | The request object to use for the request.                                                                                             |

### Response

**[?Operations\PublicApiV1AutomationsRulesVersionsListResponse](../../Models/Operations/PublicApiV1AutomationsRulesVersionsListResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 401, 403, 404, 422, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1AutomationsRulesVersionsShow

Retrieve one frozen version of the definition of a rule by its version number — an ordinal, not a UUID. Unlike the listing, an unknown pair of rule and version returns 404. A version keeps resolving after its rule has been deleted, which is what lets the detail of an old run explain the definition it started from.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.automations.rules.versions.show" method="get" path="/companies/{company}/automations/rules/{rule}/versions/{version}" -->
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



$response = $sdk->automations->rules->versions->publicApiV1AutomationsRulesVersionsShow(
    company: 'King - Osinski',
    rule: '<value>',
    version: '<value>',
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
| `rule`                                                                                                                                                                                                                                                                                                                                                                       | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the automation rule, as returned in `id` by its list and detail responses.                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `version`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Version number of the rule (an integer, not a UUID), as returned in `version` by `GET /v1/companies/{company}/automations/rules/{rule}/versions`.                                                                                                                                                                                                                            |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1AutomationsRulesVersionsShowResponse](../../Models/Operations/PublicApiV1AutomationsRulesVersionsShowResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\Error        | 401, 403, 404, 429  | application/json    |
| Errors\Error        | 500                 | application/json    |
| Errors\APIException | 4XX, 5XX            | \*/\*               |