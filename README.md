# Factuarea PHP SDK

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![PHP 8.2+](https://img.shields.io/badge/php-8.2%2B-777bb4.svg)](https://www.php.net/)
[![Spec sync](https://github.com/factuarea/factuarea-php/actions/workflows/spec-sync.yml/badge.svg)](https://github.com/factuarea/factuarea-php/actions/workflows/spec-sync.yml)

Official PHP SDK for the [Factuarea](https://factuarea.com) public API — invoicing,
quotes, proformas, delivery notes, products, clients, suppliers, taxes, VeriFactu
and webhooks for Spanish businesses.

Type-safe, PSR-4, built on Guzzle, with automatic retries, automatic idempotency,
cursor auto-pagination, typed errors and a webhook signature verifier.

> **Status:** pre-GA (`0.x`). The method surface follows the stable
> [SDK method naming contract](docs/sdks/) and is protected by SemVer, but `0.x`
> signals the API may still evolve before GA.

---

## Installation

> **Not yet on Packagist — coming soon.** Until the package is published you can
> install it directly from GitHub.

Once published:

```bash
composer require factuarea/factuarea-php
```

Until then, install from the repository by adding it to your `composer.json`:

```json
{
    "repositories": [
        { "type": "vcs", "url": "https://github.com/factuarea/factuarea-php" }
    ],
    "require": {
        "factuarea/factuarea-php": "dev-main"
    }
}
```

then run `composer update`.

**Requirements:** PHP 8.2 or higher, with the `json` and `mbstring` extensions
(both bundled with standard PHP builds).

---

## Quickstart

```php
<?php

require 'vendor/autoload.php';

use Factuarea\Sdk\Custom\FactuareaClient;

// The key prefix selects the environment:
//   fact_test_… → sandbox    fact_live_… → production
$factuarea = FactuareaClient::create(getenv('FACTUAREA_API_KEY'));

// List the first page of invoices.
$response = $factuarea->invoices->publicApiV1InvoicesList();
foreach ($response->paginatedList?->data ?? [] as $invoice) {
    echo $invoice->id, PHP_EOL;
}
```

`FactuareaClient::create()` is the recommended entry point: it wires Bearer
authentication and registers the automatic `Idempotency-Key` behaviour for you.
For advanced configuration (custom Guzzle client, custom retry policy, staging
base URL) the generated builder is still available:

```php
use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\Models\Components\Security;

$factuarea = Factuarea::builder()
    ->setSecurity(new Security(bearerAuth: getenv('FACTUAREA_API_KEY')))
    ->build();
```

> **Server-side only.** A secret API key must never ship in a browser or mobile
> app. Load it from an environment variable or secret manager.

---

## Sandbox (test mode)

Every API key belongs to one of two environments, decided by its **prefix** —
there is no environment flag in the SDK:

| Prefix        | Environment | External effects                                              |
| ------------- | ----------- | ------------------------------------------------------------ |
| `fact_test_`  | sandbox     | **Neutralized** — VeriFactu/AEAT, emails and webhooks are off |
| `fact_live_`  | production  | Real fiscal numbering, VeriFactu→AEAT, emails, webhooks       |

A `fact_test_` key operates on an isolated sandbox company with the same
functional surface as production. Always integrate against a `fact_test_` key
first, then switch the prefix to `fact_live_` to go live.

```php
$sandbox = FactuareaClient::create('fact_test_…');
$prod    = FactuareaClient::create('fact_live_…');
```

---

## Runtime features

### Automatic retries

Transient failures (`429` and `5xx`) are retried with exponential backoff,
honouring the server's `Retry-After` header. Client errors (`4xx` other than
`429`) are never retried. Retries reuse the same request — including its
`Idempotency-Key` — so a retried mutation is idempotent end to end.

### Automatic idempotency

Every mutating request (`POST`, `PUT`, `PATCH`, `DELETE`) gets a generated
`Idempotency-Key` (UUID v4) unless you supply one explicitly:

```php
// Auto-generated key — safe to retry.
$factuarea->invoices->publicApiV1InvoicesCreate($body);

// Or pin your own key for app-level deduplication.
$factuarea->invoices->publicApiV1InvoicesCreate($body, idempotencyKey: 'order-4711');
```

Replaying a request with the same key returns the original result and the
response carries `Idempotent-Replayed: true`.

### Cursor auto-pagination

List endpoints return the cursor envelope `{ data, has_more, next_cursor }`. The
`PageIterator` helper streams every item across all pages without manual cursor
handling:

```php
use Factuarea\Sdk\Custom\Pagination\PageIterator;
use Factuarea\Sdk\Models\Operations\PublicApiV1InvoicesListRequest;

$pages = new PageIterator(
    fn (?string $cursor) => $factuarea->invoices->publicApiV1InvoicesList(
        new PublicApiV1InvoicesListRequest(startingAfter: $cursor),
    )->rawResponse,
);

foreach ($pages->items() as $invoice) {
    // one invoice at a time, across every page
}
```

### Typed errors

Documented API errors (`401`, `403`, `409`, `422`, `429`, `5xx`) are thrown as a
typed `ErrorThrowable` exposing the full error envelope — including the
`request_id` you can quote to support. The API key is never included in any
exception message.

```php
use Factuarea\Sdk\Models\Errors\ErrorThrowable;

try {
    $factuarea->invoices->publicApiV1InvoicesCreate($body);
} catch (ErrorThrowable $e) {
    $error = $e->container->error;
    echo $error->type->value;   // e.g. "invalid_request_error"
    echo $error->code;          // e.g. "parameter_invalid"
    echo $error->param;         // e.g. "client_id"
    echo $error->requestId;     // e.g. "req_abc123" — quote this to support
}
```

### Webhook signature verification

Factuarea signs every webhook delivery with HMAC-SHA256 in the
`Factuarea-Signature` header (`t=<unix>,v1=<hex>`). Verify deliveries with a
constant-time check and replay protection:

```php
use Factuarea\Sdk\Custom\Webhooks\WebhookVerifier;
use Factuarea\Sdk\Custom\Webhooks\WebhookSignatureException;

$verifier = new WebhookVerifier();
$rawBody = file_get_contents('php://input');
$signature = $_SERVER['HTTP_FACTUAREA_SIGNATURE'] ?? '';

try {
    $event = $verifier->verify($rawBody, $signature, getenv('FACTUAREA_WEBHOOK_SECRET'));
    // $event is the decoded, authenticated payload
} catch (WebhookSignatureException $e) {
    http_response_code(400);
}
```

The verifier accepts deliveries during a secret-rotation grace window (the header
may carry both the new and previous `v1` signatures) and rejects payloads whose
timestamp is outside the tolerance (default 5 minutes, configurable).

---

## API reference

The full, generated reference for all resources and operations lives in
[`docs/sdks/`](docs/sdks/). Method names follow the deterministic
`operationId → method` mapping (e.g. `public-api.v1.invoices.mark_paid` →
`$factuarea->invoices->publicApiV1InvoicesMarkPaid(...)`).

### ERP and omnichannel families

The 2026-09-18 regeneration added **184 operations** (470 → 654 over 532 paths)
covering the order-to-cash and stock side of the platform, plus the buyer-facing
storefront. They are reached from the client like any other family:

| Family | Entry point | Operations |
| --- | --- | --- |
| Sales orders | `$factuarea->salesOrders` (`->lines`, `->buyer`, `->shippingAddress`) | 24 |
| Purchase orders | `$factuarea->purchaseOrders` (`->lines`, `->receipts`) | 24 |
| Reorder suggestions | `$factuarea->purchaseReorderSuggestions` | 2 |
| Goods receipts | `$factuarea->goodsReceipts` (`->lines`) | 9 |
| Warehouses | `$factuarea->warehouses` (`->locations`) | 14 |
| Stock transfers | `$factuarea->stockTransfers` (`->lines`) | 12 |
| Stock reservations | `$factuarea->stockReservations` | 7 |
| Stock availability | `$factuarea->stockAvailability` (`->commitments`) | 4 |
| Carriers | `$factuarea->carriers` | 6 |
| Returns | `$factuarea->returns` (`->correctiveCandidates`) | 12 |
| Storefront | `$factuarea->storefront` (`->products`, `->prices`, `->availability`, `->orders`, `->sessions`, `->categories`, `->catalogSelections`) | 23 |
| Storefront credentials | `$factuarea->storefrontKeys` | 7 |
| Contacts | `$factuarea->contacts` | 18 |

Fulfilment extends the existing delivery-note family in place
(`$factuarea->deliveryNotes->pickingList`, `->pickingQueue`, `->pickingLines`,
`->packages`, `->shipment`, `->fulfilmentStatus`), so no new entry point is
needed for it.

Everything the rest of the SDK gives you applies unchanged to these families:
listings page with the same `{data, has_more, next_cursor}` envelope and are
iterated by [`PageIterator`](src/Custom/Pagination/PageIterator.php); mutating
calls get an automatic `Idempotency-Key`; PDF downloads (sales order, purchase
order, stock transfer, picking list, packing list, shipping label) return the
body on `$response->bytes` with the untouched stream on
`$response->rawResponse->getBody()`.

---

## Versioning

The SDK pins the `Factuarea-Version` it was generated against (currently
`2026-06-04`) and sends it on every request, so the API behaves consistently
until you upgrade. SemVer applies to the SDK's public surface: new operations are
a minor bump, renames/removals or a behaviour-changing `Factuarea-Version` bump
are breaking, fixes are a patch. The SDK stays on `0.x` until the API's GA, which
ships `1.0.0` — so while it is pre-GA a breaking change lands in a **minor**, and
the changelog names the replacement of every operation withdrawn.

See [`docs/VERSIONING.md`](docs/VERSIONING.md) for the full `Factuarea-Version` ↔
SDK-version mapping and [`CHANGELOG.md`](CHANGELOG.md) for release history.

The pinned spec is kept in sync with the published one automatically — see
[`docs/SPEC_SYNC.md`](docs/SPEC_SYNC.md). The **Spec sync** badge above is when
that check last ran, not when the repo was last committed to: a check that finds
nothing to sync leaves no commit. Click it for the date of the latest run.

---

## Support

| Aspect              | Supported                                                  |
| ------------------- | ---------------------------------------------------------- |
| PHP                 | 8.2, 8.3, 8.4 (tested in CI)                               |
| Stability           | Pre-GA `0.x` — the public surface may change before `1.0.0` |
| `Factuarea-Version` | `2026-06-04` (pinned, sent on every request)              |

Full runtime-support matrix and the deprecation / breaking-change policy live in
[`SUPPORT.md`](SUPPORT.md).

---

## Development

This SDK is generated with [Speakeasy](https://www.speakeasy.com/) from the pinned
OpenAPI document in [`spec/openapi.json`](spec/openapi.json). Generated code lives
in `src/` (managed by Speakeasy); hand-written runtime helpers live in
`src/Custom/` and are never overwritten by regeneration. See
[`docs/REGENERATION.md`](docs/REGENERATION.md) for how to regenerate (and the
documented fallback to `openapi-generator`).

```bash
composer install
composer test   # PHPUnit, HTTP fully mocked — no network
composer stan   # PHPStan static analysis
```

---

## License

[MIT](LICENSE) © Factuarea. Contact: info@factuarea.com

<!-- Start Summary [summary] -->
## Summary

Factuarea Public API: Public REST API for invoicing in Spain — manage invoices, quotes, proformas, delivery notes, products, clients and webhooks.

## Authentication

The API authenticates with a secret **API key**, sent on every request as:

```
Authorization: Bearer fact_live_xxxxxxxxxxxxxxxxxxxxxxxx
```

or, alternatively, in the `X-API-Key` header. The key is issued from the Factuarea portal and must NEVER be exposed in public clients (browser, mobile apps).

## Environments: `live` and `test`

Each API key belongs to one of two environments, unambiguously identifiable by its **prefix**:

| Prefix | Environment | Data it operates on | External effects |
|---------|---------|---------------------------|------------------|
| `fact_live_` | **live** (production) | Your real company | Real: legal fiscal numbering, VeriFactu→AEAT, emails to clients, outbound webhooks |
| `fact_test_` | **test** (sandbox) | An isolated *sandbox* company | **Neutralized** (see below) |

The prefix is the **source of truth** for the environment: a `fact_test_` key always operates in test mode and a `fact_live_` key always in live. There is no request parameter that changes the environment — it is determined by the key.

### Data isolation (sandbox)

`fact_test_` keys operate on a dedicated **sandbox company** (a technical "twin company" of the account holder, automatically provisioned the first time test mode is used, which inherits the plan of your real company for faithful *feature-gating*). Thanks to per-company data isolation:

- Resources created with a `fact_test_` key are **not visible** from a `fact_live_` key, and vice versa.
- Test fiscal numbering uses the sandbox's own series and **never** consumes or alters the sequential numbering of your production series.

The functional surface is **identical** in both environments: the same endpoints and operations are available in test as in live.

### External effects neutralized in test

When you operate with a `fact_test_` key (sandbox environment), outbound effects are **disabled** so you can test your integration without real consequences:

- **VeriFactu**: the Alta record is created locally, but **not transmitted to AEAT**.
- **Email**: document emails are **not delivered** to real recipients.
- **Webhooks**: events are **not delivered** to your external HTTP endpoints.

In `live`, all these effects fire normally.

> **Recommendation**: always integrate and test first with a `fact_test_` key. Once your flow works, switch to the `fact_live_` prefix to operate in production.
<!-- End Summary [summary] -->

<!-- Start Table of Contents [toc] -->
## Table of Contents
<!-- $toc-max-depth=2 -->
* [Factuarea PHP SDK](#factuarea-php-sdk)
  * [Installation](#installation)
  * [Quickstart](#quickstart)
  * [Sandbox (test mode)](#sandbox-test-mode)
  * [Runtime features](#runtime-features)
  * [API reference](#api-reference)
  * [Versioning](#versioning)
  * [Support](#support)
  * [Development](#development)
  * [License](#license)
  * [Authentication](#authentication)
  * [Environments: `live` and `test`](#environments-live-and-test)
  * [SDK Installation](#sdk-installation)
  * [SDK Example Usage](#sdk-example-usage)
  * [Authentication](#authentication-1)
  * [Available Resources and Operations](#available-resources-and-operations)
  * [Retries](#retries)
  * [Error Handling](#error-handling)
  * [Server Selection](#server-selection)

<!-- End Table of Contents [toc] -->

<!-- Start SDK Installation [installation] -->
## SDK Installation

> [!TIP]
> To finish publishing your SDK you must [run your first generation action](https://www.speakeasy.com/docs/github-setup#step-by-step-guide).


The SDK relies on [Composer](https://getcomposer.org/) to manage its dependencies.

To install the SDK first add the below to your `composer.json` file:

```json
{
    "repositories": [
        {
            "type": "github",
            "url": "<UNSET>.git"
        }
    ],
    "require": {
        "factuarea/factuarea-php": "*"
    }
}
```

Then run the following command:

```bash
composer update
```
<!-- End SDK Installation [installation] -->

<!-- Start SDK Example Usage [usage] -->
## SDK Example Usage

### Example

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

$request = new Operations\PublicApiV1ProformasAcceptRequest(
    proforma: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\AcceptProformaRequest(
        reason: 'Cliente confirma pedido por telefono',
    ),
);

$response = $sdk->proformas->publicApiV1ProformasAccept(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
<!-- End SDK Example Usage [usage] -->

<!-- Start Authentication [security] -->
## Authentication

### Per-Client Security Schemes

This SDK supports the following security schemes globally:

| Name         | Type   | Scheme       |
| ------------ | ------ | ------------ |
| `http`       | http   | HTTP Bearer  |
| `bearerAuth` | http   | HTTP Bearer  |
| `apiKeyAuth` | apiKey | API key      |
| `oAuth2`     | oauth2 | OAuth2 token |

You can set the security parameters through the `setSecurity` function on the `SDKBuilder` when initializing the SDK. The selected scheme will be used by default to authenticate with the API for all operations that support it. For example:
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

$request = new Operations\PublicApiV1ProformasAcceptRequest(
    proforma: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\AcceptProformaRequest(
        reason: 'Cliente confirma pedido por telefono',
    ),
);

$response = $sdk->proformas->publicApiV1ProformasAccept(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
<!-- End Authentication [security] -->

<!-- Start Available Resources and Operations [operations] -->
## Available Resources and Operations

<details open>
<summary>Available methods</summary>

### [AbsenceBalances](docs/sdks/absencebalances/README.md)

* [publicApiV1AbsenceBalancesList](docs/sdks/absencebalances/README.md#publicapiv1absencebalanceslist) - List all absence balances
* [publicApiV1AbsenceBalancesShow](docs/sdks/absencebalances/README.md#publicapiv1absencebalancesshow) - Retrieve an absence balance

### [AbsenceCalendar](docs/sdks/absencecalendar/README.md)

* [publicApiV1AbsenceCalendarShow](docs/sdks/absencecalendar/README.md#publicapiv1absencecalendarshow) - Get the team absence calendar

### [AbsencePolicies](docs/sdks/absencepolicies/README.md)

* [publicApiV1AbsencePoliciesArchive](docs/sdks/absencepolicies/README.md#publicapiv1absencepoliciesarchive) - Archive an absence policy
* [publicApiV1AbsencePoliciesAssign](docs/sdks/absencepolicies/README.md#publicapiv1absencepoliciesassign) - Assign a policy to employees
* [publicApiV1AbsencePoliciesCarryover](docs/sdks/absencepolicies/README.md#publicapiv1absencepoliciescarryover) - Configure a policy’s year-end carryover
* [publicApiV1AbsencePoliciesCreate](docs/sdks/absencepolicies/README.md#publicapiv1absencepoliciescreate) - Create an absence policy
* [publicApiV1AbsencePoliciesList](docs/sdks/absencepolicies/README.md#publicapiv1absencepolicieslist) - List all absence policies
* [publicApiV1AbsencePoliciesAssignments](docs/sdks/absencepolicies/README.md#publicapiv1absencepoliciesassignments) - List a policy’s assigned employees
* [publicApiV1AbsencePoliciesShow](docs/sdks/absencepolicies/README.md#publicapiv1absencepoliciesshow) - Retrieve an absence policy
* [publicApiV1AbsencePoliciesUpdate](docs/sdks/absencepolicies/README.md#publicapiv1absencepoliciesupdate) - Update an absence policy
* [publicApiV1AbsencePoliciesUnarchive](docs/sdks/absencepolicies/README.md#publicapiv1absencepoliciesunarchive) - Unarchive an absence policy
* [publicApiV1AbsencePoliciesUnassign](docs/sdks/absencepolicies/README.md#publicapiv1absencepoliciesunassign) - Unassign a policy from employees

### [AbsenceRequests](docs/sdks/absencerequests/README.md)

* [publicApiV1AbsenceRequestsApprove](docs/sdks/absencerequests/README.md#publicapiv1absencerequestsapprove) - Approve an absence request
* [publicApiV1AbsenceRequestsCancel](docs/sdks/absencerequests/README.md#publicapiv1absencerequestscancel) - Cancel an absence request
* [publicApiV1AbsenceRequestsCreate](docs/sdks/absencerequests/README.md#publicapiv1absencerequestscreate) - Create an absence request
* [publicApiV1AbsenceRequestsList](docs/sdks/absencerequests/README.md#publicapiv1absencerequestslist) - List all absence requests
* [publicApiV1AbsenceRequestsReject](docs/sdks/absencerequests/README.md#publicapiv1absencerequestsreject) - Reject an absence request
* [publicApiV1AbsenceRequestsShow](docs/sdks/absencerequests/README.md#publicapiv1absencerequestsshow) - Retrieve an absence request

### [AbsenceTypes](docs/sdks/absencetypes/README.md)

* [publicApiV1AbsenceTypesArchive](docs/sdks/absencetypes/README.md#publicapiv1absencetypesarchive) - Archive an absence type
* [publicApiV1AbsenceTypesCreate](docs/sdks/absencetypes/README.md#publicapiv1absencetypescreate) - Create an absence type
* [publicApiV1AbsenceTypesList](docs/sdks/absencetypes/README.md#publicapiv1absencetypeslist) - List all absence types
* [publicApiV1AbsenceTypesShow](docs/sdks/absencetypes/README.md#publicapiv1absencetypesshow) - Retrieve an absence type
* [publicApiV1AbsenceTypesUpdate](docs/sdks/absencetypes/README.md#publicapiv1absencetypesupdate) - Update an absence type
* [publicApiV1AbsenceTypesUnarchive](docs/sdks/absencetypes/README.md#publicapiv1absencetypesunarchive) - Unarchive an absence type

### [Account](docs/sdks/account/README.md)

* [publicApiV1AccountShow](docs/sdks/account/README.md#publicapiv1accountshow) - Retrieve account details
* [publicApiV1AccountBilling](docs/sdks/account/README.md#publicapiv1accountbilling) - Retrieve account billing details
* [publicApiV1AccountVerifyCensus](docs/sdks/account/README.md#publicapiv1accountverifycensus) - Verify account against the AEAT census

#### [Account.ApiKeys](docs/sdks/accountapikeys/README.md)

* [publicApiV1AccountApiKeysCreate](docs/sdks/accountapikeys/README.md#publicapiv1accountapikeyscreate) - Create an API key
* [publicApiV1AccountApiKeysList](docs/sdks/accountapikeys/README.md#publicapiv1accountapikeyslist) - List your API keys
* [publicApiV1AccountApiKeysRevoke](docs/sdks/accountapikeys/README.md#publicapiv1accountapikeysrevoke) - Revoke an API key
* [publicApiV1AccountApiKeysRotateSecret](docs/sdks/accountapikeys/README.md#publicapiv1accountapikeysrotatesecret) - Rotate an API key secret
* [publicApiV1AccountApiKeysShow](docs/sdks/accountapikeys/README.md#publicapiv1accountapikeysshow) - Retrieve an API key

#### [Account.Personalization](docs/sdks/personalization/README.md)

* [publicApiV1AccountPersonalizationTemplates](docs/sdks/personalization/README.md#publicapiv1accountpersonalizationtemplates) - List available personalization templates
* [publicApiV1AccountPersonalizationUpdate](docs/sdks/personalization/README.md#publicapiv1accountpersonalizationupdate) - Update account personalization

### [Automations.Catalog](docs/sdks/catalog/README.md)

* [publicApiV1AutomationsCatalogShow](docs/sdks/catalog/README.md#publicapiv1automationscatalogshow) - Retrieve the automation catalog
* [publicApiV1AutomationsCatalogTriggerFields](docs/sdks/catalog/README.md#publicapiv1automationscatalogtriggerfields) - Retrieve the evaluable fields of a trigger

### [Automations.Rules](docs/sdks/rules/README.md)

* [publicApiV1AutomationsRulesActivate](docs/sdks/rules/README.md#publicapiv1automationsrulesactivate) - Activate an automation rule
* [publicApiV1AutomationsRulesCreate](docs/sdks/rules/README.md#publicapiv1automationsrulescreate) - Create an automation rule
* [publicApiV1AutomationsRulesList](docs/sdks/rules/README.md#publicapiv1automationsruleslist) - List your automation rules
* [publicApiV1AutomationsRulesDelete](docs/sdks/rules/README.md#publicapiv1automationsrulesdelete) - Delete an automation rule
* [publicApiV1AutomationsRulesShow](docs/sdks/rules/README.md#publicapiv1automationsrulesshow) - Retrieve an automation rule
* [publicApiV1AutomationsRulesUpdate](docs/sdks/rules/README.md#publicapiv1automationsrulesupdate) - Update an automation rule
* [publicApiV1AutomationsRulesDryRun](docs/sdks/rules/README.md#publicapiv1automationsrulesdryrun) - Dry-run an automation rule
* [publicApiV1AutomationsRulesPause](docs/sdks/rules/README.md#publicapiv1automationsrulespause) - Pause an automation rule

#### [Automations.Rules.Versions](docs/sdks/versions/README.md)

* [publicApiV1AutomationsRulesVersionsList](docs/sdks/versions/README.md#publicapiv1automationsrulesversionslist) - List the versions of an automation rule
* [publicApiV1AutomationsRulesVersionsShow](docs/sdks/versions/README.md#publicapiv1automationsrulesversionsshow) - Retrieve a version of an automation rule

### [Automations.Runs](docs/sdks/runs/README.md)

* [publicApiV1AutomationsRunsList](docs/sdks/runs/README.md#publicapiv1automationsrunslist) - List automation runs
* [publicApiV1AutomationsRunsReplay](docs/sdks/runs/README.md#publicapiv1automationsrunsreplay) - Replay the parked steps of an automation run
* [publicApiV1AutomationsRunsShow](docs/sdks/runs/README.md#publicapiv1automationsrunsshow) - Retrieve an automation run

#### [Automations.Runs.Steps](docs/sdks/steps/README.md)

* [publicApiV1AutomationsRunsStepsList](docs/sdks/steps/README.md#publicapiv1automationsrunsstepslist) - List the steps of an automation run
* [publicApiV1AutomationsRunsStepsReplay](docs/sdks/steps/README.md#publicapiv1automationsrunsstepsreplay) - Replay one step of an automation run

### [Automations.Usage](docs/sdks/usage/README.md)

* [publicApiV1AutomationsUsageShow](docs/sdks/usage/README.md#publicapiv1automationsusageshow) - Retrieve automation usage

### [Carriers](docs/sdks/carriers/README.md)

* [publicApiV1CarriersCreate](docs/sdks/carriers/README.md#publicapiv1carrierscreate) - Create a carrier
* [publicApiV1CarriersList](docs/sdks/carriers/README.md#publicapiv1carrierslist) - List all carriers
* [publicApiV1CarriersDelete](docs/sdks/carriers/README.md#publicapiv1carriersdelete) - Delete a carrier
* [publicApiV1CarriersShow](docs/sdks/carriers/README.md#publicapiv1carriersshow) - Retrieve a carrier
* [publicApiV1CarriersUpdate](docs/sdks/carriers/README.md#publicapiv1carriersupdate) - Update a carrier
* [publicApiV1CarriersFindByCode](docs/sdks/carriers/README.md#publicapiv1carriersfindbycode) - Find a carrier by code

### [Clients](docs/sdks/clients/README.md)

* [publicApiV1ClientsBulkCreate](docs/sdks/clients/README.md#publicapiv1clientsbulkcreate) - Bulk create clients
* [publicApiV1ClientsBulkDelete](docs/sdks/clients/README.md#publicapiv1clientsbulkdelete) - Delete multiple clients in bulk
* [publicApiV1ClientsCreate](docs/sdks/clients/README.md#publicapiv1clientscreate) - Create a client
* [publicApiV1ClientsList](docs/sdks/clients/README.md#publicapiv1clientslist) - List all clients
* [publicApiV1ClientsDelete](docs/sdks/clients/README.md#publicapiv1clientsdelete) - Delete a client
* [publicApiV1ClientsShow](docs/sdks/clients/README.md#publicapiv1clientsshow) - Retrieve a client
* [publicApiV1ClientsUpdate](docs/sdks/clients/README.md#publicapiv1clientsupdate) - Update a client
* [publicApiV1ClientsImportTemplate](docs/sdks/clients/README.md#publicapiv1clientsimporttemplate) - Download the client import template
* [publicApiV1ClientsFindByExternalId](docs/sdks/clients/README.md#publicapiv1clientsfindbyexternalid) - Find a client by external ID
* [publicApiV1ClientsFindByTaxId](docs/sdks/clients/README.md#publicapiv1clientsfindbytaxid) - Find a client by tax ID
* [publicApiV1ClientsActivities](docs/sdks/clients/README.md#publicapiv1clientsactivities) - List client activity timeline
* [publicApiV1ClientsStats](docs/sdks/clients/README.md#publicapiv1clientsstats) - Get client stats
* [publicApiV1ClientsImport](docs/sdks/clients/README.md#publicapiv1clientsimport) - Import clients from a file
* [publicApiV1ClientsSearch](docs/sdks/clients/README.md#publicapiv1clientssearch) - Search clients
* [publicApiV1ClientsVerifyCensus](docs/sdks/clients/README.md#publicapiv1clientsverifycensus) - Verify a client against the AEAT census

### [Companies](docs/sdks/companies/README.md)

* [publicApiV1CompaniesActivateBatch](docs/sdks/companies/README.md#publicapiv1companiesactivatebatch) - Activate several managed companies
* [publicApiV1CompaniesActivate](docs/sdks/companies/README.md#publicapiv1companiesactivate) - Activate a managed company
* [publicApiV1CompaniesCreate](docs/sdks/companies/README.md#publicapiv1companiescreate) - Create a managed company
* [publicApiV1CompaniesList](docs/sdks/companies/README.md#publicapiv1companieslist) - List your managed companies
* [publicApiV1CompaniesDeactivate](docs/sdks/companies/README.md#publicapiv1companiesdeactivate) - Deactivate a managed company
* [publicApiV1CompaniesDelete](docs/sdks/companies/README.md#publicapiv1companiesdelete) - Archive a managed company
* [publicApiV1CompaniesShow](docs/sdks/companies/README.md#publicapiv1companiesshow) - Retrieve a managed company
* [publicApiV1CompaniesUpdate](docs/sdks/companies/README.md#publicapiv1companiesupdate) - Update a managed company
* [publicApiV1CompaniesCreationStatus](docs/sdks/companies/README.md#publicapiv1companiescreationstatus) - Retrieve the creation status of a managed company
* [publicApiV1CompaniesSeatChargePreview](docs/sdks/companies/README.md#publicapiv1companiesseatchargepreview) - Preview the seat charge of adding a company
* [publicApiV1CompaniesVerifyCreation](docs/sdks/companies/README.md#publicapiv1companiesverifycreation) - Verify the creation of a managed company

#### [Companies.ApiKeys](docs/sdks/companiesapikeys/README.md)

* [publicApiV1CompaniesApiKeysCreate](docs/sdks/companiesapikeys/README.md#publicapiv1companiesapikeyscreate) - Create a child API key
* [publicApiV1CompaniesApiKeysList](docs/sdks/companiesapikeys/README.md#publicapiv1companiesapikeyslist) - List child API keys
* [publicApiV1CompaniesApiKeysRevoke](docs/sdks/companiesapikeys/README.md#publicapiv1companiesapikeysrevoke) - Revoke a child API key
* [publicApiV1CompaniesApiKeysShow](docs/sdks/companiesapikeys/README.md#publicapiv1companiesapikeysshow) - Retrieve a child API key
* [publicApiV1CompaniesApiKeysRotateSecret](docs/sdks/companiesapikeys/README.md#publicapiv1companiesapikeysrotatesecret) - Rotate a child API key secret

### [Contacts](docs/sdks/contacts/README.md)

* [publicApiV1ContactsAssignContactRole](docs/sdks/contacts/README.md#publicapiv1contactsassigncontactrole) - Assign a contact role
* [publicApiV1ContactsRemoveContactRole](docs/sdks/contacts/README.md#publicapiv1contactsremovecontactrole) - Remove a contact role
* [publicApiV1ContactsBulkArchive](docs/sdks/contacts/README.md#publicapiv1contactsbulkarchive) - Archive contacts in bulk
* [publicApiV1ContactsBulkChangeContactRoleStatus](docs/sdks/contacts/README.md#publicapiv1contactsbulkchangecontactrolestatus) - Change contact role status in bulk
* [publicApiV1ContactsChangeContactRoleStatus](docs/sdks/contacts/README.md#publicapiv1contactschangecontactrolestatus) - Change a contact role status
* [publicApiV1ContactsCreate](docs/sdks/contacts/README.md#publicapiv1contactscreate) - Create a contact
* [publicApiV1ContactsList](docs/sdks/contacts/README.md#publicapiv1contactslist) - List contacts
* [publicApiV1ContactsDelete](docs/sdks/contacts/README.md#publicapiv1contactsdelete) - Archive a contact
* [publicApiV1ContactsShow](docs/sdks/contacts/README.md#publicapiv1contactsshow) - Retrieve a contact
* [publicApiV1ContactsUpdate](docs/sdks/contacts/README.md#publicapiv1contactsupdate) - Update a contact
* [publicApiV1ContactsOptions](docs/sdks/contacts/README.md#publicapiv1contactsoptions) - List contact filter options
* [publicApiV1ContactsImport](docs/sdks/contacts/README.md#publicapiv1contactsimport) - Import contacts
* [publicApiV1ContactsPreviewImport](docs/sdks/contacts/README.md#publicapiv1contactspreviewimport) - Preview a contact import
* [publicApiV1ContactsRestore](docs/sdks/contacts/README.md#publicapiv1contactsrestore) - Restore an archived contact
* [publicApiV1ContactsSearch](docs/sdks/contacts/README.md#publicapiv1contactssearch) - Search contacts
* [publicApiV1ContactsUpdateBankAccounts](docs/sdks/contacts/README.md#publicapiv1contactsupdatebankaccounts) - Replace the bank accounts of a contact
* [publicApiV1ContactsUpdateCustomerProfile](docs/sdks/contacts/README.md#publicapiv1contactsupdatecustomerprofile) - Update a customer profile
* [publicApiV1ContactsUpdateSupplierProfile](docs/sdks/contacts/README.md#publicapiv1contactsupdatesupplierprofile) - Update a supplier profile

### [DeliveryNotes](docs/sdks/deliverynotes/README.md)

* [publicApiV1DeliveryNotesBulkDelete](docs/sdks/deliverynotes/README.md#publicapiv1deliverynotesbulkdelete) - Bulk delete delivery notes
* [publicApiV1DeliveryNotesBulkPdf](docs/sdks/deliverynotes/README.md#publicapiv1deliverynotesbulkpdf) - Bulk download delivery note PDFs
* [publicApiV1DeliveryNotesBulkSend](docs/sdks/deliverynotes/README.md#publicapiv1deliverynotesbulksend) - Bulk send delivery notes
* [publicApiV1DeliveryNotesBulkStatus](docs/sdks/deliverynotes/README.md#publicapiv1deliverynotesbulkstatus) - Bulk change delivery note status
* [publicApiV1DeliveryNotesCancel](docs/sdks/deliverynotes/README.md#publicapiv1deliverynotescancel) - Cancel a delivery note
* [publicApiV1DeliveryNotesConvert](docs/sdks/deliverynotes/README.md#publicapiv1deliverynotesconvert) - Convert delivery note to invoice
* [publicApiV1DeliveryNotesCreate](docs/sdks/deliverynotes/README.md#publicapiv1deliverynotescreate) - Create a delivery note
* [publicApiV1DeliveryNotesList](docs/sdks/deliverynotes/README.md#publicapiv1deliverynoteslist) - List all delivery notes
* [publicApiV1DeliveryNotesDelete](docs/sdks/deliverynotes/README.md#publicapiv1deliverynotesdelete) - Delete a delivery note
* [publicApiV1DeliveryNotesShow](docs/sdks/deliverynotes/README.md#publicapiv1deliverynotesshow) - Retrieve a delivery note
* [publicApiV1DeliveryNotesUpdate](docs/sdks/deliverynotes/README.md#publicapiv1deliverynotesupdate) - Update a delivery note
* [publicApiV1DeliveryNotesPdf](docs/sdks/deliverynotes/README.md#publicapiv1deliverynotespdf) - Download delivery note PDF
* [publicApiV1DeliveryNotesPackingList](docs/sdks/deliverynotes/README.md#publicapiv1deliverynotespackinglist) - Download the packing list PDF
* [publicApiV1DeliveryNotesShippingLabel](docs/sdks/deliverynotes/README.md#publicapiv1deliverynotesshippinglabel) - Download the shipping label PDF
* [publicApiV1DeliveryNotesDuplicate](docs/sdks/deliverynotes/README.md#publicapiv1deliverynotesduplicate) - Duplicate a delivery note
* [publicApiV1DeliveryNotesFindByExternalId](docs/sdks/deliverynotes/README.md#publicapiv1deliverynotesfindbyexternalid) - Find a delivery note by external ID
* [publicApiV1DeliveryNotesStats](docs/sdks/deliverynotes/README.md#publicapiv1deliverynotesstats) - Retrieve delivery note stats
* [publicApiV1DeliveryNotesStatuses](docs/sdks/deliverynotes/README.md#publicapiv1deliverynotesstatuses) - List delivery note statuses
* [publicApiV1DeliveryNotesFulfilmentStatuses](docs/sdks/deliverynotes/README.md#publicapiv1deliverynotesfulfilmentstatuses) - List delivery note fulfilment statuses
* [publicApiV1DeliveryNotesMarkDelivered](docs/sdks/deliverynotes/README.md#publicapiv1deliverynotesmarkdelivered) - Mark delivery note as delivered
* [publicApiV1DeliveryNotesSend](docs/sdks/deliverynotes/README.md#publicapiv1deliverynotessend) - Send a delivery note
* [publicApiV1DeliveryNotesSign](docs/sdks/deliverynotes/README.md#publicapiv1deliverynotessign) - Sign a delivery note

#### [DeliveryNotes.FulfilmentStatus](docs/sdks/fulfilmentstatus/README.md)

* [publicApiV1DeliveryNotesFulfilmentStatusTransition](docs/sdks/fulfilmentstatus/README.md#publicapiv1deliverynotesfulfilmentstatustransition) - Transition the fulfilment status of a delivery note

#### [DeliveryNotes.Packages](docs/sdks/packages/README.md)

* [publicApiV1DeliveryNotesPackagesCreate](docs/sdks/packages/README.md#publicapiv1deliverynotespackagescreate) - Add a package to a delivery note
* [publicApiV1DeliveryNotesPackagesList](docs/sdks/packages/README.md#publicapiv1deliverynotespackageslist) - List the packages of a delivery note
* [publicApiV1DeliveryNotesPackagesDelete](docs/sdks/packages/README.md#publicapiv1deliverynotespackagesdelete) - Delete a package of a delivery note
* [publicApiV1DeliveryNotesPackagesUpdate](docs/sdks/packages/README.md#publicapiv1deliverynotespackagesupdate) - Update a package of a delivery note

#### [DeliveryNotes.PickingLines](docs/sdks/pickinglines/README.md)

* [publicApiV1DeliveryNotesPickingLinesPick](docs/sdks/pickinglines/README.md#publicapiv1deliverynotespickinglinespick) - Mark a picking line

#### [DeliveryNotes.PickingList](docs/sdks/pickinglist/README.md)

* [publicApiV1DeliveryNotesPickingListPdf](docs/sdks/pickinglist/README.md#publicapiv1deliverynotespickinglistpdf) - Download the picking list PDF
* [publicApiV1DeliveryNotesPickingListOpen](docs/sdks/pickinglist/README.md#publicapiv1deliverynotespickinglistopen) - Open the picking list of a delivery note
* [publicApiV1DeliveryNotesPickingListShow](docs/sdks/pickinglist/README.md#publicapiv1deliverynotespickinglistshow) - Retrieve the picking list of a delivery note

#### [DeliveryNotes.PickingQueue](docs/sdks/pickingqueue/README.md)

* [publicApiV1DeliveryNotesPickingQueueList](docs/sdks/pickingqueue/README.md#publicapiv1deliverynotespickingqueuelist) - List the delivery note picking queue

#### [DeliveryNotes.PublicLink](docs/sdks/publiclink/README.md)

* [publicApiV1DeliveryNotesPublicLinkGet](docs/sdks/publiclink/README.md#publicapiv1deliverynotespubliclinkget) - Retrieve a delivery note public link
* [publicApiV1DeliveryNotesPublicLinkUpdate](docs/sdks/publiclink/README.md#publicapiv1deliverynotespubliclinkupdate) - Update a delivery note public link

#### [DeliveryNotes.Shipment](docs/sdks/shipment/README.md)

* [publicApiV1DeliveryNotesShipmentShow](docs/sdks/shipment/README.md#publicapiv1deliverynotesshipmentshow) - Retrieve the shipment of a delivery note
* [publicApiV1DeliveryNotesShipmentUpdate](docs/sdks/shipment/README.md#publicapiv1deliverynotesshipmentupdate) - Update the shipment of a delivery note

#### [DeliveryNotes.SignatureAudits](docs/sdks/signatureaudits/README.md)

* [publicApiV1DeliveryNotesSignatureAuditsForget](docs/sdks/signatureaudits/README.md#publicapiv1deliverynotessignatureauditsforget) - Forget delivery note signature PII

### [Developers.RequestLogs](docs/sdks/requestlogs/README.md)

* [publicApiV1DevelopersRequestLogsList](docs/sdks/requestlogs/README.md#publicapiv1developersrequestlogslist) - List your API request logs
* [publicApiV1DevelopersRequestLogsShow](docs/sdks/requestlogs/README.md#publicapiv1developersrequestlogsshow) - Retrieve an API request log

### [Emails](docs/sdks/emails/README.md)

* [publicApiV1EmailsIndicators](docs/sdks/emails/README.md#publicapiv1emailsindicators) - Summarize email delivery per document
* [publicApiV1EmailsList](docs/sdks/emails/README.md#publicapiv1emailslist) - List sent emails
* [publicApiV1EmailsShow](docs/sdks/emails/README.md#publicapiv1emailsshow) - Retrieve a sent email

### [EmployeeInvitations](docs/sdks/employeeinvitations/README.md)

* [publicApiV1EmployeeInvitationsCancel](docs/sdks/employeeinvitations/README.md#publicapiv1employeeinvitationscancel) - Cancel an employee invitation
* [publicApiV1EmployeeInvitationsList](docs/sdks/employeeinvitations/README.md#publicapiv1employeeinvitationslist) - List employee invitations
* [publicApiV1EmployeeInvitationsSend](docs/sdks/employeeinvitations/README.md#publicapiv1employeeinvitationssend) - Send an employee invitation
* [publicApiV1EmployeeInvitationsResend](docs/sdks/employeeinvitations/README.md#publicapiv1employeeinvitationsresend) - Resend an employee invitation

### [Employees](docs/sdks/employees/README.md)

* [publicApiV1EmployeesCreate](docs/sdks/employees/README.md#publicapiv1employeescreate) - Create an employee
* [publicApiV1EmployeesList](docs/sdks/employees/README.md#publicapiv1employeeslist) - List all employees
* [publicApiV1EmployeesDeactivate](docs/sdks/employees/README.md#publicapiv1employeesdeactivate) - Deactivate an employee
* [publicApiV1EmployeesFindByExternalId](docs/sdks/employees/README.md#publicapiv1employeesfindbyexternalid) - Find an employee by external ID
* [publicApiV1EmployeesStats](docs/sdks/employees/README.md#publicapiv1employeesstats) - Get employee stats
* [publicApiV1EmployeesReactivate](docs/sdks/employees/README.md#publicapiv1employeesreactivate) - Reactivate an employee
* [publicApiV1EmployeesShow](docs/sdks/employees/README.md#publicapiv1employeesshow) - Retrieve an employee
* [publicApiV1EmployeesUpdate](docs/sdks/employees/README.md#publicapiv1employeesupdate) - Update an employee

### [EmployeeSeats](docs/sdks/employeeseats/README.md)

* [publicApiV1EmployeeSeatsCancel](docs/sdks/employeeseats/README.md#publicapiv1employeeseatscancel) - Cancel the employee seat add-on
* [publicApiV1EmployeeSeatsChangeQuantity](docs/sdks/employeeseats/README.md#publicapiv1employeeseatschangequantity) - Sync the employee seat quantity
* [publicApiV1EmployeeSeatsStatus](docs/sdks/employeeseats/README.md#publicapiv1employeeseatsstatus) - Retrieve employee seat billing status
* [publicApiV1EmployeeSeatsPreview](docs/sdks/employeeseats/README.md#publicapiv1employeeseatspreview) - Preview the employee seat charge
* [publicApiV1EmployeeSeatsSubscribe](docs/sdks/employeeseats/README.md#publicapiv1employeeseatssubscribe) - Subscribe to the employee seat add-on

### [EventCatalog](docs/sdks/eventcatalog/README.md)

* [publicApiV1EventCatalogList](docs/sdks/eventcatalog/README.md#publicapiv1eventcataloglist) - List event types

### [Events](docs/sdks/events/README.md)

* [publicApiV1EventsList](docs/sdks/events/README.md#publicapiv1eventslist) - List all events
* [publicApiV1EventsShow](docs/sdks/events/README.md#publicapiv1eventsshow) - Retrieve an event

### [FaceSubmissions](docs/sdks/facesubmissions/README.md)

* [publicApiV1FaceSubmissionsCancel](docs/sdks/facesubmissions/README.md#publicapiv1facesubmissionscancel) - Request FACe submission cancellation
* [publicApiV1FaceSubmissionsShow](docs/sdks/facesubmissions/README.md#publicapiv1facesubmissionsshow) - Retrieve a FACe submission

### [Gestoria](docs/sdks/gestoria/README.md)

* [publicApiV1GestoriaWorkforceSummary](docs/sdks/gestoria/README.md#publicapiv1gestoriaworkforcesummary) - Retrieve the consolidated workforce compliance overview

### [GoodsReceipts](docs/sdks/goodsreceipts/README.md)

* [publicApiV1GoodsReceiptsBulkStatus](docs/sdks/goodsreceipts/README.md#publicapiv1goodsreceiptsbulkstatus) - Bulk change goods receipt status
* [publicApiV1GoodsReceiptsCancel](docs/sdks/goodsreceipts/README.md#publicapiv1goodsreceiptscancel) - Cancel a goods receipt
* [publicApiV1GoodsReceiptsStats](docs/sdks/goodsreceipts/README.md#publicapiv1goodsreceiptsstats) - Retrieve goods receipt stats
* [publicApiV1GoodsReceiptsStatuses](docs/sdks/goodsreceipts/README.md#publicapiv1goodsreceiptsstatuses) - List goods receipt statuses
* [publicApiV1GoodsReceiptsList](docs/sdks/goodsreceipts/README.md#publicapiv1goodsreceiptslist) - List all goods receipts
* [publicApiV1GoodsReceiptsCreate](docs/sdks/goodsreceipts/README.md#publicapiv1goodsreceiptscreate) - Register a goods receipt
* [publicApiV1GoodsReceiptsPost](docs/sdks/goodsreceipts/README.md#publicapiv1goodsreceiptspost) - Post a goods receipt
* [publicApiV1GoodsReceiptsShow](docs/sdks/goodsreceipts/README.md#publicapiv1goodsreceiptsshow) - Retrieve a goods receipt

#### [GoodsReceipts.Lines](docs/sdks/goodsreceiptslines/README.md)

* [publicApiV1GoodsReceiptsLinesList](docs/sdks/goodsreceiptslines/README.md#publicapiv1goodsreceiptslineslist) - List the lines of a goods receipt

### [Holidays](docs/sdks/holidays/README.md)

* [publicApiV1HolidaysList](docs/sdks/holidays/README.md#publicapiv1holidayslist) - List all holidays
* [publicApiV1HolidaysResolve](docs/sdks/holidays/README.md#publicapiv1holidaysresolve) - Resolve applicable holidays
* [publicApiV1HolidaysShow](docs/sdks/holidays/README.md#publicapiv1holidaysshow) - Retrieve a holiday

### [Integrations.Events](docs/sdks/integrationsevents/README.md)

* [publicApiV1IntegrationsEventsList](docs/sdks/integrationsevents/README.md#publicapiv1integrationseventslist) - List integration events
* [publicApiV1IntegrationsEventsReplay](docs/sdks/integrationsevents/README.md#publicapiv1integrationseventsreplay) - Replay a parked integration event
* [publicApiV1IntegrationsEventsShow](docs/sdks/integrationsevents/README.md#publicapiv1integrationseventsshow) - Retrieve an integration event

### [Invoices](docs/sdks/invoices/README.md)

* [publicApiV1InvoicesAnnul](docs/sdks/invoices/README.md#publicapiv1invoicesannul) - Annul an invoice
* [publicApiV1InvoicesAssignRealNumber](docs/sdks/invoices/README.md#publicapiv1invoicesassignrealnumber) - Assign a real invoice number
* [publicApiV1InvoicesBulkCreate](docs/sdks/invoices/README.md#publicapiv1invoicesbulkcreate) - Bulk create invoices
* [publicApiV1InvoicesBulkDelete](docs/sdks/invoices/README.md#publicapiv1invoicesbulkdelete) - Bulk delete invoices
* [publicApiV1InvoicesBulkPdf](docs/sdks/invoices/README.md#publicapiv1invoicesbulkpdf) - Bulk download invoice PDFs
* [publicApiV1InvoicesBulkSend](docs/sdks/invoices/README.md#publicapiv1invoicesbulksend) - Bulk send invoices
* [publicApiV1InvoicesBulkStatus](docs/sdks/invoices/README.md#publicapiv1invoicesbulkstatus) - Bulk change invoice status
* [publicApiV1InvoicesCanAnnul](docs/sdks/invoices/README.md#publicapiv1invoicescanannul) - Check annulment eligibility
* [publicApiV1InvoicesSimplifiedEligibility](docs/sdks/invoices/README.md#publicapiv1invoicessimplifiedeligibility) - Check simplified invoice eligibility
* [publicApiV1InvoicesCorrective](docs/sdks/invoices/README.md#publicapiv1invoicescorrective) - Generate corrective invoice
* [publicApiV1InvoicesCreate](docs/sdks/invoices/README.md#publicapiv1invoicescreate) - Create an invoice
* [publicApiV1InvoicesList](docs/sdks/invoices/README.md#publicapiv1invoiceslist) - List all invoices
* [publicApiV1InvoicesVerifactuCreate](docs/sdks/invoices/README.md#publicapiv1invoicesverifactucreate) - Force-create VeriFactu record for invoice
* [publicApiV1InvoicesVerifactuGet](docs/sdks/invoices/README.md#publicapiv1invoicesverifactuget) - Retrieve invoice VeriFactu record
* [publicApiV1InvoicesCreateRecurring](docs/sdks/invoices/README.md#publicapiv1invoicescreaterecurring) - Create a recurring invoice from an invoice
* [publicApiV1InvoicesDelete](docs/sdks/invoices/README.md#publicapiv1invoicesdelete) - Delete an invoice
* [publicApiV1InvoicesShow](docs/sdks/invoices/README.md#publicapiv1invoicesshow) - Retrieve an invoice
* [publicApiV1InvoicesUpdate](docs/sdks/invoices/README.md#publicapiv1invoicesupdate) - Update an invoice
* [publicApiV1InvoicesFacturae](docs/sdks/invoices/README.md#publicapiv1invoicesfacturae) - Download FacturaE XML
* [publicApiV1InvoicesPdf](docs/sdks/invoices/README.md#publicapiv1invoicespdf) - Download invoice PDF
* [publicApiV1InvoicesDuplicate](docs/sdks/invoices/README.md#publicapiv1invoicesduplicate) - Duplicate an invoice
* [publicApiV1InvoicesExportExcel](docs/sdks/invoices/README.md#publicapiv1invoicesexportexcel) - Export invoices to a spreadsheet
* [publicApiV1InvoicesFindByExternalId](docs/sdks/invoices/README.md#publicapiv1invoicesfindbyexternalid) - Find an invoice by external ID
* [publicApiV1InvoicesFindByNumber](docs/sdks/invoices/README.md#publicapiv1invoicesfindbynumber) - Find an invoice by number
* [publicApiV1InvoicesPdfLink](docs/sdks/invoices/README.md#publicapiv1invoicespdflink) - Generate temporary PDF link
* [publicApiV1InvoicesPublicLinkGet](docs/sdks/invoices/README.md#publicapiv1invoicespubliclinkget) - Retrieve invoice public link
* [publicApiV1InvoicesPublicLinkUpdate](docs/sdks/invoices/README.md#publicapiv1invoicespubliclinkupdate) - Update invoice public link
* [publicApiV1InvoicesStats](docs/sdks/invoices/README.md#publicapiv1invoicesstats) - Get invoice statistics
* [publicApiV1InvoicesActivities](docs/sdks/invoices/README.md#publicapiv1invoicesactivities) - List invoice activity
* [publicApiV1InvoicesCorrectives](docs/sdks/invoices/README.md#publicapiv1invoicescorrectives) - List corrective invoices
* [publicApiV1InvoicesPaymentsList](docs/sdks/invoices/README.md#publicapiv1invoicespaymentslist) - List invoice payments
* [publicApiV1InvoicesPaymentsCreate](docs/sdks/invoices/README.md#publicapiv1invoicespaymentscreate) - Register a payment
* [publicApiV1InvoicesStatuses](docs/sdks/invoices/README.md#publicapiv1invoicesstatuses) - List invoice statuses
* [publicApiV1InvoicesMarkPaid](docs/sdks/invoices/README.md#publicapiv1invoicesmarkpaid) - Mark invoice as paid
* [publicApiV1InvoicesMarkSent](docs/sdks/invoices/README.md#publicapiv1invoicesmarksent) - Mark an invoice as sent
* [publicApiV1InvoicesPdfPreview](docs/sdks/invoices/README.md#publicapiv1invoicespdfpreview) - Preview an invoice draft PDF
* [publicApiV1InvoicesReminderPreview](docs/sdks/invoices/README.md#publicapiv1invoicesreminderpreview) - Preview a payment reminder email
* [publicApiV1InvoicesPaymentReceipt](docs/sdks/invoices/README.md#publicapiv1invoicespaymentreceipt) - Download payment receipt PDF
* [publicApiV1InvoicesReschedule](docs/sdks/invoices/README.md#publicapiv1invoicesreschedule) - Reschedule an invoice
* [publicApiV1InvoicesPaymentsRevert](docs/sdks/invoices/README.md#publicapiv1invoicespaymentsrevert) - Revert an invoice payment
* [publicApiV1InvoicesSchedule](docs/sdks/invoices/README.md#publicapiv1invoicesschedule) - Schedule an invoice
* [publicApiV1InvoicesSend](docs/sdks/invoices/README.md#publicapiv1invoicessend) - Send invoice by email
* [publicApiV1InvoicesSendReminder](docs/sdks/invoices/README.md#publicapiv1invoicessendreminder) - Send a payment reminder
* [publicApiV1InvoicesSubstituteSimplified](docs/sdks/invoices/README.md#publicapiv1invoicessubstitutesimplified) - Substitute simplified invoices with full invoice
* [publicApiV1InvoicesUnschedule](docs/sdks/invoices/README.md#publicapiv1invoicesunschedule) - Unschedule an invoice
* [publicApiV1InvoicesUnsend](docs/sdks/invoices/README.md#publicapiv1invoicesunsend) - Unsend an invoice
* [publicApiV1InvoicesVoid](docs/sdks/invoices/README.md#publicapiv1invoicesvoid) - Void an invoice

#### [Invoices.FaceSubmissions](docs/sdks/invoicesfacesubmissions/README.md)

* [publicApiV1InvoicesFaceSubmissionsList](docs/sdks/invoicesfacesubmissions/README.md#publicapiv1invoicesfacesubmissionslist) - List invoice FACe submissions
* [publicApiV1InvoicesFaceSubmissionsSubmit](docs/sdks/invoicesfacesubmissions/README.md#publicapiv1invoicesfacesubmissionssubmit) - Submit invoice to FACe

#### [Invoices.Quarterly](docs/sdks/quarterly/README.md)

* [publicApiV1InvoicesQuarterlyAvailable](docs/sdks/quarterly/README.md#publicapiv1invoicesquarterlyavailable) - List quarters with invoices
* [publicApiV1InvoicesQuarterlyDownloadZip](docs/sdks/quarterly/README.md#publicapiv1invoicesquarterlydownloadzip) - Generate quarterly ZIP archive
* [publicApiV1InvoicesQuarterlySendEmail](docs/sdks/quarterly/README.md#publicapiv1invoicesquarterlysendemail) - Email quarterly ZIP to accountant

### [MonthlyTimeRecordCloses](docs/sdks/monthlytimerecordcloses/README.md)

* [publicApiV1MonthlyTimeRecordClosesCreate](docs/sdks/monthlytimerecordcloses/README.md#publicapiv1monthlytimerecordclosescreate) - Close a monthly time record
* [publicApiV1MonthlyTimeRecordClosesList](docs/sdks/monthlytimerecordcloses/README.md#publicapiv1monthlytimerecordcloseslist) - List all monthly time record closes
* [publicApiV1MonthlyTimeRecordClosesExport](docs/sdks/monthlytimerecordcloses/README.md#publicapiv1monthlytimerecordclosesexport) - Download the closed register (RD-ley 8/2019)
* [publicApiV1MonthlyTimeRecordClosesPayrollExport](docs/sdks/monthlytimerecordcloses/README.md#publicapiv1monthlytimerecordclosespayrollexport) - Download the payroll export of a closed month
* [publicApiV1MonthlyTimeRecordClosesReport](docs/sdks/monthlytimerecordcloses/README.md#publicapiv1monthlytimerecordclosesreport) - Retrieve the report of a closed period
* [publicApiV1MonthlyTimeRecordClosesReopen](docs/sdks/monthlytimerecordcloses/README.md#publicapiv1monthlytimerecordclosesreopen) - Reopen a monthly time record close
* [publicApiV1MonthlyTimeRecordClosesSeal](docs/sdks/monthlytimerecordcloses/README.md#publicapiv1monthlytimerecordclosesseal) - Seal a monthly time record register
* [publicApiV1MonthlyTimeRecordClosesSealShow](docs/sdks/monthlytimerecordcloses/README.md#publicapiv1monthlytimerecordclosessealshow) - Retrieve the seal of a monthly register
* [publicApiV1MonthlyTimeRecordClosesShow](docs/sdks/monthlytimerecordcloses/README.md#publicapiv1monthlytimerecordclosesshow) - Retrieve a monthly time record close

### [PaymentMethods](docs/sdks/paymentmethods/README.md)

* [publicApiV1PaymentMethodsList](docs/sdks/paymentmethods/README.md#publicapiv1paymentmethodslist) - List payment methods

### [Payouts](docs/sdks/payouts/README.md)

* [publicApiV1PayoutsShow](docs/sdks/payouts/README.md#publicapiv1payoutsshow) - Retrieve a Stripe payout
* [publicApiV1PayoutsList](docs/sdks/payouts/README.md#publicapiv1payoutslist) - List Stripe payouts

### [PayrollExportFormats](docs/sdks/payrollexportformats/README.md)

* [publicApiV1PayrollExportFormatsList](docs/sdks/payrollexportformats/README.md#publicapiv1payrollexportformatslist) - List the supported payroll export formats

### [Presence](docs/sdks/presence/README.md)

* [publicApiV1PresenceShow](docs/sdks/presence/README.md#publicapiv1presenceshow) - Retrieve an employee’s live presence
* [publicApiV1PresenceLive](docs/sdks/presence/README.md#publicapiv1presencelive) - Get the live team presence
* [publicApiV1PresenceDaily](docs/sdks/presence/README.md#publicapiv1presencedaily) - List office/remote presence declarations

### [PriceLists](docs/sdks/pricelists/README.md)

* [publicApiV1PriceListsCreate](docs/sdks/pricelists/README.md#publicapiv1pricelistscreate) - Create a price list
* [publicApiV1PriceListsList](docs/sdks/pricelists/README.md#publicapiv1pricelistslist) - List price lists
* [publicApiV1PriceListsDelete](docs/sdks/pricelists/README.md#publicapiv1pricelistsdelete) - Delete a price list
* [publicApiV1PriceListsShow](docs/sdks/pricelists/README.md#publicapiv1pricelistsshow) - Retrieve a price list
* [publicApiV1PriceListsUpdate](docs/sdks/pricelists/README.md#publicapiv1pricelistsupdate) - Update a price list
* [publicApiV1PriceListsOptions](docs/sdks/pricelists/README.md#publicapiv1pricelistsoptions) - List active price list options
* [publicApiV1PriceListsResolve](docs/sdks/pricelists/README.md#publicapiv1pricelistsresolve) - Resolve a catalog price
* [publicApiV1PriceListsResolveMany](docs/sdks/pricelists/README.md#publicapiv1pricelistsresolvemany) - Resolve many catalog prices

#### [PriceLists.Items](docs/sdks/items/README.md)

* [publicApiV1PriceListsItemsDelete](docs/sdks/items/README.md#publicapiv1pricelistsitemsdelete) - Delete a price list item
* [publicApiV1PriceListsItemsList](docs/sdks/items/README.md#publicapiv1pricelistsitemslist) - List price list items
* [publicApiV1PriceListsItemsUpsert](docs/sdks/items/README.md#publicapiv1pricelistsitemsupsert) - Upsert a price list item
* [publicApiV1PriceListsItemsPurgeRetired](docs/sdks/items/README.md#publicapiv1pricelistsitemspurgeretired) - Permanently delete a retired price list item
* [publicApiV1PriceListsItemsReassignRetired](docs/sdks/items/README.md#publicapiv1pricelistsitemsreassignretired) - Reassign a retired price list item

### [Products](docs/sdks/products/README.md)

* [publicApiV1ProductsBulkDelete](docs/sdks/products/README.md#publicapiv1productsbulkdelete) - Delete multiple products in bulk
* [publicApiV1ProductsBulkStatus](docs/sdks/products/README.md#publicapiv1productsbulkstatus) - Bulk change product active state
* [publicApiV1ProductsBulkUpdateStock](docs/sdks/products/README.md#publicapiv1productsbulkupdatestock) - Update stock for many products
* [publicApiV1ProductsCreate](docs/sdks/products/README.md#publicapiv1productscreate) - Create a product
* [publicApiV1ProductsList](docs/sdks/products/README.md#publicapiv1productslist) - List all products
* [publicApiV1ProductsDelete](docs/sdks/products/README.md#publicapiv1productsdelete) - Delete a product
* [publicApiV1ProductsShow](docs/sdks/products/README.md#publicapiv1productsshow) - Retrieve a product
* [publicApiV1ProductsUpdate](docs/sdks/products/README.md#publicapiv1productsupdate) - Update a product
* [publicApiV1ProductsFindByExternalId](docs/sdks/products/README.md#publicapiv1productsfindbyexternalid) - Find a product by external ID
* [publicApiV1ProductsFindBySku](docs/sdks/products/README.md#publicapiv1productsfindbysku) - Find a product by SKU
* [publicApiV1ProductsLowStockReport](docs/sdks/products/README.md#publicapiv1productslowstockreport) - List products below the stock threshold
* [publicApiV1ProductsActivities](docs/sdks/products/README.md#publicapiv1productsactivities) - List product activity timeline
* [publicApiV1ProductsSalesAnalytics](docs/sdks/products/README.md#publicapiv1productssalesanalytics) - Get product sales analytics
* [publicApiV1ProductsStats](docs/sdks/products/README.md#publicapiv1productsstats) - Get product stats
* [publicApiV1ProductsResolveSelection](docs/sdks/products/README.md#publicapiv1productsresolveselection) - Resolve a catalog selection
* [publicApiV1ProductsSearch](docs/sdks/products/README.md#publicapiv1productssearch) - Search products
* [publicApiV1ProductsToggleActive](docs/sdks/products/README.md#publicapiv1productstoggleactive) - Toggle product active state
* [publicApiV1ProductsUpdateStock](docs/sdks/products/README.md#publicapiv1productsupdatestock) - Update product stock

#### [Products.Configurations](docs/sdks/configurations/README.md)

* [publicApiV1ProductsConfigurationsList](docs/sdks/configurations/README.md#publicapiv1productsconfigurationslist) - List product commercial combinations
* [publicApiV1ProductsConfigurationsImpactPreview](docs/sdks/configurations/README.md#publicapiv1productsconfigurationsimpactpreview) - Preview the impact of restricting a catalog

#### [Products.Gallery](docs/sdks/gallery/README.md)

* [publicApiV1ProductsGalleryDelete](docs/sdks/gallery/README.md#publicapiv1productsgallerydelete) - Remove a gallery image from a product
* [publicApiV1ProductsGalleryDownload](docs/sdks/gallery/README.md#publicapiv1productsgallerydownload) - Download a product gallery image binary
* [publicApiV1ProductsGalleryUpload](docs/sdks/gallery/README.md#publicapiv1productsgalleryupload) - Upload a gallery image to a product

#### [Products.Presentations](docs/sdks/presentations/README.md)

* [publicApiV1ProductsPresentationsCreate](docs/sdks/presentations/README.md#publicapiv1productspresentationscreate) - Create a product presentation
* [publicApiV1ProductsPresentationsList](docs/sdks/presentations/README.md#publicapiv1productspresentationslist) - List product presentations
* [publicApiV1ProductsPresentationsDelete](docs/sdks/presentations/README.md#publicapiv1productspresentationsdelete) - Delete a product presentation
* [publicApiV1ProductsPresentationsUpdate](docs/sdks/presentations/README.md#publicapiv1productspresentationsupdate) - Update a product presentation

#### [Products.ProductOptions](docs/sdks/productoptions/README.md)

* [publicApiV1ProductsOptionsList](docs/sdks/productoptions/README.md#publicapiv1productsoptionslist) - List product option groups

#### [Products.StockMovements](docs/sdks/stockmovements/README.md)

* [publicApiV1ProductsStockMovementsList](docs/sdks/stockmovements/README.md#publicapiv1productsstockmovementslist) - List stock movements of a product

#### [Products.SupplierOffers](docs/sdks/supplieroffers/README.md)

* [publicApiV1ProductsSupplierOffersCreate](docs/sdks/supplieroffers/README.md#publicapiv1productssupplierofferscreate) - Create a supplier offer
* [publicApiV1ProductsSupplierOffersList](docs/sdks/supplieroffers/README.md#publicapiv1productssupplierofferslist) - List supplier offers
* [publicApiV1ProductsSupplierOffersDelete](docs/sdks/supplieroffers/README.md#publicapiv1productssupplieroffersdelete) - Delete a supplier offer
* [publicApiV1ProductsSupplierOffersUpdate](docs/sdks/supplieroffers/README.md#publicapiv1productssupplieroffersupdate) - Update a supplier offer
* [publicApiV1ProductsSupplierOffersPreferred](docs/sdks/supplieroffers/README.md#publicapiv1productssupplierofferspreferred) - Set the preferred supplier offer

#### [Products.Variants](docs/sdks/variants/README.md)

* [publicApiV1ProductsVariantsCreate](docs/sdks/variants/README.md#publicapiv1productsvariantscreate) - Create a product variant
* [publicApiV1ProductsVariantsList](docs/sdks/variants/README.md#publicapiv1productsvariantslist) - List product variants
* [publicApiV1ProductsVariantsDelete](docs/sdks/variants/README.md#publicapiv1productsvariantsdelete) - Delete a product variant
* [publicApiV1ProductsVariantsUpdate](docs/sdks/variants/README.md#publicapiv1productsvariantsupdate) - Update a product variant

#### [Products.Video](docs/sdks/video/README.md)

* [publicApiV1ProductsVideoDelete](docs/sdks/video/README.md#publicapiv1productsvideodelete) - Remove the product video
* [publicApiV1ProductsVideoUpload](docs/sdks/video/README.md#publicapiv1productsvideoupload) - Upload a video to a product
* [publicApiV1ProductsVideoDownload](docs/sdks/video/README.md#publicapiv1productsvideodownload) - Download the product video binary

### [Proformas](docs/sdks/proformas/README.md)

* [publicApiV1ProformasAccept](docs/sdks/proformas/README.md#publicapiv1proformasaccept) - Accept a proforma
* [publicApiV1ProformasBulkDelete](docs/sdks/proformas/README.md#publicapiv1proformasbulkdelete) - Bulk delete proformas
* [publicApiV1ProformasBulkPdf](docs/sdks/proformas/README.md#publicapiv1proformasbulkpdf) - Bulk download proforma PDFs
* [publicApiV1ProformasBulkSend](docs/sdks/proformas/README.md#publicapiv1proformasbulksend) - Bulk send proformas
* [publicApiV1ProformasBulkStatus](docs/sdks/proformas/README.md#publicapiv1proformasbulkstatus) - Bulk change proforma status
* [publicApiV1ProformasConvert](docs/sdks/proformas/README.md#publicapiv1proformasconvert) - Convert proforma to invoice
* [publicApiV1ProformasConvertToSalesOrder](docs/sdks/proformas/README.md#publicapiv1proformasconverttosalesorder) - Convert proforma to sales order
* [publicApiV1ProformasCreate](docs/sdks/proformas/README.md#publicapiv1proformascreate) - Create a proforma
* [publicApiV1ProformasList](docs/sdks/proformas/README.md#publicapiv1proformaslist) - List all proformas
* [publicApiV1ProformasDelete](docs/sdks/proformas/README.md#publicapiv1proformasdelete) - Delete a proforma
* [publicApiV1ProformasShow](docs/sdks/proformas/README.md#publicapiv1proformasshow) - Retrieve a proforma
* [publicApiV1ProformasUpdate](docs/sdks/proformas/README.md#publicapiv1proformasupdate) - Update a proforma
* [publicApiV1ProformasPdf](docs/sdks/proformas/README.md#publicapiv1proformaspdf) - Download proforma PDF
* [publicApiV1ProformasDuplicate](docs/sdks/proformas/README.md#publicapiv1proformasduplicate) - Duplicate a proforma
* [publicApiV1ProformasFindByExternalId](docs/sdks/proformas/README.md#publicapiv1proformasfindbyexternalid) - Find a proforma by external ID
* [publicApiV1ProformasPublicLinkGet](docs/sdks/proformas/README.md#publicapiv1proformaspubliclinkget) - Retrieve proforma public link
* [publicApiV1ProformasPublicLinkUpdate](docs/sdks/proformas/README.md#publicapiv1proformaspubliclinkupdate) - Update proforma public link
* [publicApiV1ProformasStats](docs/sdks/proformas/README.md#publicapiv1proformasstats) - Get proforma stats
* [publicApiV1ProformasStatuses](docs/sdks/proformas/README.md#publicapiv1proformasstatuses) - List proforma statuses
* [publicApiV1ProformasReject](docs/sdks/proformas/README.md#publicapiv1proformasreject) - Reject a proforma
* [publicApiV1ProformasSend](docs/sdks/proformas/README.md#publicapiv1proformassend) - Send proforma by email

### [PurchaseInvoices](docs/sdks/purchaseinvoices/README.md)

* [publicApiV1PurchaseInvoicesAttachFile](docs/sdks/purchaseinvoices/README.md#publicapiv1purchaseinvoicesattachfile) - Attach a file to a purchase invoice
* [publicApiV1PurchaseInvoicesBulkDelete](docs/sdks/purchaseinvoices/README.md#publicapiv1purchaseinvoicesbulkdelete) - Bulk delete purchase invoices
* [publicApiV1PurchaseInvoicesBulkStatus](docs/sdks/purchaseinvoices/README.md#publicapiv1purchaseinvoicesbulkstatus) - Bulk change purchase invoice status
* [publicApiV1PurchaseInvoicesCreate](docs/sdks/purchaseinvoices/README.md#publicapiv1purchaseinvoicescreate) - Create a purchase invoice
* [publicApiV1PurchaseInvoicesList](docs/sdks/purchaseinvoices/README.md#publicapiv1purchaseinvoiceslist) - List all purchase invoices
* [publicApiV1PurchaseInvoicesDelete](docs/sdks/purchaseinvoices/README.md#publicapiv1purchaseinvoicesdelete) - Delete a purchase invoice
* [publicApiV1PurchaseInvoicesShow](docs/sdks/purchaseinvoices/README.md#publicapiv1purchaseinvoicesshow) - Retrieve a purchase invoice
* [publicApiV1PurchaseInvoicesUpdate](docs/sdks/purchaseinvoices/README.md#publicapiv1purchaseinvoicesupdate) - Update a purchase invoice
* [publicApiV1PurchaseInvoicesDeleteFile](docs/sdks/purchaseinvoices/README.md#publicapiv1purchaseinvoicesdeletefile) - Remove a purchase invoice file
* [publicApiV1PurchaseInvoicesFile](docs/sdks/purchaseinvoices/README.md#publicapiv1purchaseinvoicesfile) - Download the original purchase invoice file
* [publicApiV1PurchaseInvoicesPaymentReceipt](docs/sdks/purchaseinvoices/README.md#publicapiv1purchaseinvoicespaymentreceipt) - Download a purchase invoice payment receipt
* [publicApiV1PurchaseInvoicesFindByExternalId](docs/sdks/purchaseinvoices/README.md#publicapiv1purchaseinvoicesfindbyexternalid) - Find a purchase invoice by external ID
* [publicApiV1PurchaseInvoicesStats](docs/sdks/purchaseinvoices/README.md#publicapiv1purchaseinvoicesstats) - Get purchase invoice stats
* [publicApiV1PurchaseInvoicesOverdue](docs/sdks/purchaseinvoices/README.md#publicapiv1purchaseinvoicesoverdue) - List overdue purchase invoices
* [publicApiV1PurchaseInvoicesPending](docs/sdks/purchaseinvoices/README.md#publicapiv1purchaseinvoicespending) - List pending purchase invoices
* [publicApiV1PurchaseInvoicesListPayments](docs/sdks/purchaseinvoices/README.md#publicapiv1purchaseinvoiceslistpayments) - List purchase invoice payments
* [publicApiV1PurchaseInvoicesRegisterPayment](docs/sdks/purchaseinvoices/README.md#publicapiv1purchaseinvoicesregisterpayment) - Register a purchase invoice payment
* [publicApiV1PurchaseInvoicesMarkPaid](docs/sdks/purchaseinvoices/README.md#publicapiv1purchaseinvoicesmarkpaid) - Mark purchase invoice as paid

#### [PurchaseInvoices.Match](docs/sdks/match/README.md)

* [publicApiV1PurchaseInvoicesMatchAccept](docs/sdks/match/README.md#publicapiv1purchaseinvoicesmatchaccept) - Accept the deviation of a purchase invoice match
* [publicApiV1PurchaseInvoicesMatchLink](docs/sdks/match/README.md#publicapiv1purchaseinvoicesmatchlink) - Link a purchase invoice to a purchase order
* [publicApiV1PurchaseInvoicesMatchReject](docs/sdks/match/README.md#publicapiv1purchaseinvoicesmatchreject) - Reject a purchase invoice match

### [PurchaseOrders](docs/sdks/purchaseorders/README.md)

* [publicApiV1PurchaseOrdersBulkCreate](docs/sdks/purchaseorders/README.md#publicapiv1purchaseordersbulkcreate) - Bulk create purchase orders
* [publicApiV1PurchaseOrdersBulkDelete](docs/sdks/purchaseorders/README.md#publicapiv1purchaseordersbulkdelete) - Bulk delete purchase orders
* [publicApiV1PurchaseOrdersBulkStatus](docs/sdks/purchaseorders/README.md#publicapiv1purchaseordersbulkstatus) - Bulk change purchase order status
* [publicApiV1PurchaseOrdersCancel](docs/sdks/purchaseorders/README.md#publicapiv1purchaseorderscancel) - Cancel a purchase order
* [publicApiV1PurchaseOrdersClose](docs/sdks/purchaseorders/README.md#publicapiv1purchaseordersclose) - Close a purchase order
* [publicApiV1PurchaseOrdersConfirm](docs/sdks/purchaseorders/README.md#publicapiv1purchaseordersconfirm) - Confirm a purchase order
* [publicApiV1PurchaseOrdersCreate](docs/sdks/purchaseorders/README.md#publicapiv1purchaseorderscreate) - Create a purchase order
* [publicApiV1PurchaseOrdersList](docs/sdks/purchaseorders/README.md#publicapiv1purchaseorderslist) - List all purchase orders
* [publicApiV1PurchaseOrdersDelete](docs/sdks/purchaseorders/README.md#publicapiv1purchaseordersdelete) - Delete a purchase order
* [publicApiV1PurchaseOrdersShow](docs/sdks/purchaseorders/README.md#publicapiv1purchaseordersshow) - Retrieve a purchase order
* [publicApiV1PurchaseOrdersUpdate](docs/sdks/purchaseorders/README.md#publicapiv1purchaseordersupdate) - Update a purchase order
* [publicApiV1PurchaseOrdersPdf](docs/sdks/purchaseorders/README.md#publicapiv1purchaseorderspdf) - Download purchase order PDF
* [publicApiV1PurchaseOrdersFindBySupplierReference](docs/sdks/purchaseorders/README.md#publicapiv1purchaseordersfindbysupplierreference) - Find a purchase order by supplier reference
* [publicApiV1PurchaseOrdersMatch](docs/sdks/purchaseorders/README.md#publicapiv1purchaseordersmatch) - Retrieve the three-way match of a purchase order
* [publicApiV1PurchaseOrdersStats](docs/sdks/purchaseorders/README.md#publicapiv1purchaseordersstats) - Retrieve purchase order stats
* [publicApiV1PurchaseOrdersStatuses](docs/sdks/purchaseorders/README.md#publicapiv1purchaseordersstatuses) - List purchase order statuses
* [publicApiV1PurchaseOrdersMarkAsSent](docs/sdks/purchaseorders/README.md#publicapiv1purchaseordersmarkassent) - Mark a purchase order as sent
* [publicApiV1PurchaseOrdersSend](docs/sdks/purchaseorders/README.md#publicapiv1purchaseorderssend) - Send a purchase order to its supplier

#### [PurchaseOrders.Lines](docs/sdks/purchaseorderslines/README.md)

* [publicApiV1PurchaseOrdersLinesCreate](docs/sdks/purchaseorderslines/README.md#publicapiv1purchaseorderslinescreate) - Add a line to a purchase order
* [publicApiV1PurchaseOrdersLinesList](docs/sdks/purchaseorderslines/README.md#publicapiv1purchaseorderslineslist) - List the lines of a purchase order
* [publicApiV1PurchaseOrdersLinesDelete](docs/sdks/purchaseorderslines/README.md#publicapiv1purchaseorderslinesdelete) - Delete a line of a purchase order
* [publicApiV1PurchaseOrdersLinesUpdate](docs/sdks/purchaseorderslines/README.md#publicapiv1purchaseorderslinesupdate) - Update a line of a purchase order

#### [PurchaseOrders.Receipts](docs/sdks/receipts/README.md)

* [publicApiV1PurchaseOrdersReceiptsList](docs/sdks/receipts/README.md#publicapiv1purchaseordersreceiptslist) - List the goods receipts of a purchase order
* [publicApiV1PurchaseOrdersReceiptsCreate](docs/sdks/receipts/README.md#publicapiv1purchaseordersreceiptscreate) - Register a goods receipt for a purchase order

### [PurchaseReorderSuggestions](docs/sdks/purchasereordersuggestions/README.md)

* [publicApiV1PurchaseReorderSuggestionsAccept](docs/sdks/purchasereordersuggestions/README.md#publicapiv1purchasereordersuggestionsaccept) - Accept a reorder suggestion
* [publicApiV1PurchaseReorderSuggestionsList](docs/sdks/purchasereordersuggestions/README.md#publicapiv1purchasereordersuggestionslist) - List reorder suggestions

### [Quotes](docs/sdks/quotes/README.md)

* [publicApiV1QuotesAccept](docs/sdks/quotes/README.md#publicapiv1quotesaccept) - Accept a quote
* [publicApiV1QuotesBulkDelete](docs/sdks/quotes/README.md#publicapiv1quotesbulkdelete) - Bulk delete quotes
* [publicApiV1QuotesBulkPdf](docs/sdks/quotes/README.md#publicapiv1quotesbulkpdf) - Bulk download quote PDFs
* [publicApiV1QuotesBulkSend](docs/sdks/quotes/README.md#publicapiv1quotesbulksend) - Bulk send quotes
* [publicApiV1QuotesBulkStatus](docs/sdks/quotes/README.md#publicapiv1quotesbulkstatus) - Bulk change quote status
* [publicApiV1QuotesConvert](docs/sdks/quotes/README.md#publicapiv1quotesconvert) - Convert quote to invoice
* [publicApiV1QuotesConvertToSalesOrder](docs/sdks/quotes/README.md#publicapiv1quotesconverttosalesorder) - Convert quote to sales order
* [publicApiV1QuotesCreate](docs/sdks/quotes/README.md#publicapiv1quotescreate) - Create a quote
* [publicApiV1QuotesList](docs/sdks/quotes/README.md#publicapiv1quoteslist) - List all quotes
* [publicApiV1QuotesDelete](docs/sdks/quotes/README.md#publicapiv1quotesdelete) - Delete a quote
* [publicApiV1QuotesShow](docs/sdks/quotes/README.md#publicapiv1quotesshow) - Retrieve a quote
* [publicApiV1QuotesUpdate](docs/sdks/quotes/README.md#publicapiv1quotesupdate) - Update a quote
* [publicApiV1QuotesPdf](docs/sdks/quotes/README.md#publicapiv1quotespdf) - Download quote PDF
* [publicApiV1QuotesDuplicate](docs/sdks/quotes/README.md#publicapiv1quotesduplicate) - Duplicate a quote
* [publicApiV1QuotesFindByExternalId](docs/sdks/quotes/README.md#publicapiv1quotesfindbyexternalid) - Find a quote by external ID
* [publicApiV1QuotesPublicLinkGet](docs/sdks/quotes/README.md#publicapiv1quotespubliclinkget) - Retrieve quote public link
* [publicApiV1QuotesPublicLinkUpdate](docs/sdks/quotes/README.md#publicapiv1quotespubliclinkupdate) - Update quote public link
* [publicApiV1QuotesStats](docs/sdks/quotes/README.md#publicapiv1quotesstats) - Get quote stats
* [publicApiV1QuotesStatuses](docs/sdks/quotes/README.md#publicapiv1quotesstatuses) - List quote statuses
* [publicApiV1QuotesReject](docs/sdks/quotes/README.md#publicapiv1quotesreject) - Reject a quote
* [publicApiV1QuotesSend](docs/sdks/quotes/README.md#publicapiv1quotessend) - Send quote by email

### [RecurringInvoices](docs/sdks/recurringinvoices/README.md)

* [publicApiV1RecurringInvoicesActivate](docs/sdks/recurringinvoices/README.md#publicapiv1recurringinvoicesactivate) - Activate recurring invoice
* [publicApiV1RecurringInvoicesBulkDelete](docs/sdks/recurringinvoices/README.md#publicapiv1recurringinvoicesbulkdelete) - Bulk delete recurring invoices
* [publicApiV1RecurringInvoicesCancel](docs/sdks/recurringinvoices/README.md#publicapiv1recurringinvoicescancel) - Cancel recurring invoice
* [publicApiV1RecurringInvoicesCreate](docs/sdks/recurringinvoices/README.md#publicapiv1recurringinvoicescreate) - Create a recurring invoice
* [publicApiV1RecurringInvoicesList](docs/sdks/recurringinvoices/README.md#publicapiv1recurringinvoiceslist) - List all recurring invoices
* [publicApiV1RecurringInvoicesDelete](docs/sdks/recurringinvoices/README.md#publicapiv1recurringinvoicesdelete) - Delete a recurring invoice
* [publicApiV1RecurringInvoicesShow](docs/sdks/recurringinvoices/README.md#publicapiv1recurringinvoicesshow) - Retrieve a recurring invoice
* [publicApiV1RecurringInvoicesUpdate](docs/sdks/recurringinvoices/README.md#publicapiv1recurringinvoicesupdate) - Update a recurring invoice
* [publicApiV1RecurringInvoicesFindByExternalId](docs/sdks/recurringinvoices/README.md#publicapiv1recurringinvoicesfindbyexternalid) - Find a recurring invoice by external ID
* [publicApiV1RecurringInvoicesGenerate](docs/sdks/recurringinvoices/README.md#publicapiv1recurringinvoicesgenerate) - Generate an invoice from a recurring template
* [publicApiV1RecurringInvoicesStats](docs/sdks/recurringinvoices/README.md#publicapiv1recurringinvoicesstats) - Retrieve recurring invoice stats
* [publicApiV1RecurringInvoicesActivities](docs/sdks/recurringinvoices/README.md#publicapiv1recurringinvoicesactivities) - List recurring invoice activity
* [publicApiV1RecurringInvoicesLogs](docs/sdks/recurringinvoices/README.md#publicapiv1recurringinvoiceslogs) - List recurring invoice execution logs
* [publicApiV1RecurringInvoicesPause](docs/sdks/recurringinvoices/README.md#publicapiv1recurringinvoicespause) - Pause recurring invoice
* [publicApiV1RecurringInvoicesPreview](docs/sdks/recurringinvoices/README.md#publicapiv1recurringinvoicespreview) - Preview upcoming recurring invoice dates
* [publicApiV1RecurringInvoicesResume](docs/sdks/recurringinvoices/README.md#publicapiv1recurringinvoicesresume) - Resume recurring invoice
* [publicApiV1RecurringInvoicesSkip](docs/sdks/recurringinvoices/README.md#publicapiv1recurringinvoicesskip) - Skip the next recurring invoice generation

### [Returns](docs/sdks/returns/README.md)

* [publicApiV1ReturnsApprove](docs/sdks/returns/README.md#publicapiv1returnsapprove) - Approve a return
* [publicApiV1ReturnsCreate](docs/sdks/returns/README.md#publicapiv1returnscreate) - Request a return
* [publicApiV1ReturnsList](docs/sdks/returns/README.md#publicapiv1returnslist) - List all returns
* [publicApiV1ReturnsDelete](docs/sdks/returns/README.md#publicapiv1returnsdelete) - Delete a return
* [publicApiV1ReturnsShow](docs/sdks/returns/README.md#publicapiv1returnsshow) - Retrieve a return
* [publicApiV1ReturnsUpdate](docs/sdks/returns/README.md#publicapiv1returnsupdate) - Update a return
* [publicApiV1ReturnsStatuses](docs/sdks/returns/README.md#publicapiv1returnsstatuses) - List return statuses
* [publicApiV1ReturnsReturnableLines](docs/sdks/returns/README.md#publicapiv1returnsreturnablelines) - List the returnable lines of a document
* [publicApiV1ReturnsReceive](docs/sdks/returns/README.md#publicapiv1returnsreceive) - Receive a return
* [publicApiV1ReturnsRefund](docs/sdks/returns/README.md#publicapiv1returnsrefund) - Refund a return
* [publicApiV1ReturnsReject](docs/sdks/returns/README.md#publicapiv1returnsreject) - Reject a return

#### [Returns.CorrectiveCandidates](docs/sdks/correctivecandidates/README.md)

* [publicApiV1ReturnsCorrectiveCandidatesList](docs/sdks/correctivecandidates/README.md#publicapiv1returnscorrectivecandidateslist) - List the corrective invoice candidates of a return

### [SalesOrders](docs/sdks/salesorders/README.md)

* [publicApiV1SalesOrdersBulkCreate](docs/sdks/salesorders/README.md#publicapiv1salesordersbulkcreate) - Bulk create sales orders
* [publicApiV1SalesOrdersBulkDelete](docs/sdks/salesorders/README.md#publicapiv1salesordersbulkdelete) - Bulk delete sales orders
* [publicApiV1SalesOrdersBulkStatus](docs/sdks/salesorders/README.md#publicapiv1salesordersbulkstatus) - Bulk change sales order status
* [publicApiV1SalesOrdersCancel](docs/sdks/salesorders/README.md#publicapiv1salesorderscancel) - Cancel a sales order
* [publicApiV1SalesOrdersClose](docs/sdks/salesorders/README.md#publicapiv1salesordersclose) - Close a sales order
* [publicApiV1SalesOrdersConfirm](docs/sdks/salesorders/README.md#publicapiv1salesordersconfirm) - Confirm a sales order
* [publicApiV1SalesOrdersConvertToDeliveryNote](docs/sdks/salesorders/README.md#publicapiv1salesordersconverttodeliverynote) - Convert a sales order to a delivery note
* [publicApiV1SalesOrdersConvertToInvoice](docs/sdks/salesorders/README.md#publicapiv1salesordersconverttoinvoice) - Convert a sales order to an invoice
* [publicApiV1SalesOrdersCreate](docs/sdks/salesorders/README.md#publicapiv1salesorderscreate) - Create a sales order
* [publicApiV1SalesOrdersList](docs/sdks/salesorders/README.md#publicapiv1salesorderslist) - List all sales orders
* [publicApiV1SalesOrdersDelete](docs/sdks/salesorders/README.md#publicapiv1salesordersdelete) - Delete a sales order
* [publicApiV1SalesOrdersShow](docs/sdks/salesorders/README.md#publicapiv1salesordersshow) - Retrieve a sales order
* [publicApiV1SalesOrdersUpdate](docs/sdks/salesorders/README.md#publicapiv1salesordersupdate) - Update a sales order
* [publicApiV1SalesOrdersPdf](docs/sdks/salesorders/README.md#publicapiv1salesorderspdf) - Download sales order PDF
* [publicApiV1SalesOrdersFindByExternalId](docs/sdks/salesorders/README.md#publicapiv1salesordersfindbyexternalid) - Find a sales order by external id
* [publicApiV1SalesOrdersStats](docs/sdks/salesorders/README.md#publicapiv1salesordersstats) - Retrieve sales order stats
* [publicApiV1SalesOrdersStatuses](docs/sdks/salesorders/README.md#publicapiv1salesordersstatuses) - List sales order statuses
* [publicApiV1SalesOrdersSend](docs/sdks/salesorders/README.md#publicapiv1salesorderssend) - Send a sales order

#### [SalesOrders.Buyer](docs/sdks/buyer/README.md)

* [publicApiV1SalesOrdersBuyerUpdate](docs/sdks/buyer/README.md#publicapiv1salesordersbuyerupdate) - Update the buyer of a sales order

#### [SalesOrders.Lines](docs/sdks/salesorderslines/README.md)

* [publicApiV1SalesOrdersLinesCreate](docs/sdks/salesorderslines/README.md#publicapiv1salesorderslinescreate) - Add a line to a sales order
* [publicApiV1SalesOrdersLinesList](docs/sdks/salesorderslines/README.md#publicapiv1salesorderslineslist) - List the lines of a sales order
* [publicApiV1SalesOrdersLinesDelete](docs/sdks/salesorderslines/README.md#publicapiv1salesorderslinesdelete) - Delete a line of a sales order
* [publicApiV1SalesOrdersLinesUpdate](docs/sdks/salesorderslines/README.md#publicapiv1salesorderslinesupdate) - Update a line of a sales order

#### [SalesOrders.ShippingAddress](docs/sdks/shippingaddress/README.md)

* [publicApiV1SalesOrdersShippingAddressUpdate](docs/sdks/shippingaddress/README.md#publicapiv1salesordersshippingaddressupdate) - Update the shipping address of a sales order

### [Series](docs/sdks/series/README.md)

* [publicApiV1SeriesArchive](docs/sdks/series/README.md#publicapiv1seriesarchive) - Archive a series
* [publicApiV1SeriesBootstrap](docs/sdks/series/README.md#publicapiv1seriesbootstrap) - Bootstrap the default series of a company
* [publicApiV1SeriesCreate](docs/sdks/series/README.md#publicapiv1seriescreate) - Create a series
* [publicApiV1SeriesList](docs/sdks/series/README.md#publicapiv1serieslist) - List all series
* [publicApiV1SeriesFindByCode](docs/sdks/series/README.md#publicapiv1seriesfindbycode) - Find a series by code
* [publicApiV1SeriesDefault](docs/sdks/series/README.md#publicapiv1seriesdefault) - Get the default series for a document type
* [publicApiV1SeriesActivities](docs/sdks/series/README.md#publicapiv1seriesactivities) - List series activity timeline
* [publicApiV1SeriesStats](docs/sdks/series/README.md#publicapiv1seriesstats) - Get series stats
* [publicApiV1SeriesActive](docs/sdks/series/README.md#publicapiv1seriesactive) - List active series by document type
* [publicApiV1SeriesSetDefault](docs/sdks/series/README.md#publicapiv1seriessetdefault) - Mark a series as default for its type
* [publicApiV1SeriesShow](docs/sdks/series/README.md#publicapiv1seriesshow) - Retrieve a series
* [publicApiV1SeriesUnarchive](docs/sdks/series/README.md#publicapiv1seriesunarchive) - Unarchive a series

### [Shopify.Stores](docs/sdks/shopifystores/README.md)

* [publicApiV1ShopifyStoresConnectionTest](docs/sdks/shopifystores/README.md#publicapiv1shopifystoresconnectiontest) - Test a Shopify store connection

### [StockAvailability](docs/sdks/stockavailability/README.md)

* [publicApiV1StockAvailabilityBatch](docs/sdks/stockavailability/README.md#publicapiv1stockavailabilitybatch) - Retrieve stock availability in batch
* [publicApiV1StockAvailabilityList](docs/sdks/stockavailability/README.md#publicapiv1stockavailabilitylist) - List stock availability
* [publicApiV1StockAvailabilityShow](docs/sdks/stockavailability/README.md#publicapiv1stockavailabilityshow) - Retrieve the stock availability of an article

#### [StockAvailability.Commitments](docs/sdks/commitments/README.md)

* [publicApiV1StockAvailabilityCommitmentsList](docs/sdks/commitments/README.md#publicapiv1stockavailabilitycommitmentslist) - List the commitments over an article

### [StockReservations](docs/sdks/stockreservations/README.md)

* [publicApiV1StockReservationsCreate](docs/sdks/stockreservations/README.md#publicapiv1stockreservationscreate) - Create a stock reservation
* [publicApiV1StockReservationsList](docs/sdks/stockreservations/README.md#publicapiv1stockreservationslist) - List all stock reservations
* [publicApiV1StockReservationsFindByHolder](docs/sdks/stockreservations/README.md#publicapiv1stockreservationsfindbyholder) - Find the stock reservations of a holder
* [publicApiV1StockReservationsStatuses](docs/sdks/stockreservations/README.md#publicapiv1stockreservationsstatuses) - List stock reservation statuses
* [publicApiV1StockReservationsRelease](docs/sdks/stockreservations/README.md#publicapiv1stockreservationsrelease) - Release a stock reservation
* [publicApiV1StockReservationsReleaseByHolder](docs/sdks/stockreservations/README.md#publicapiv1stockreservationsreleasebyholder) - Release the stock reservations of a holder
* [publicApiV1StockReservationsShow](docs/sdks/stockreservations/README.md#publicapiv1stockreservationsshow) - Retrieve a stock reservation

### [StockTransfers](docs/sdks/stocktransfers/README.md)

* [publicApiV1StockTransfersCancel](docs/sdks/stocktransfers/README.md#publicapiv1stocktransferscancel) - Cancel a stock transfer
* [publicApiV1StockTransfersCreate](docs/sdks/stocktransfers/README.md#publicapiv1stocktransferscreate) - Create a stock transfer
* [publicApiV1StockTransfersList](docs/sdks/stocktransfers/README.md#publicapiv1stocktransferslist) - List all stock transfers
* [publicApiV1StockTransfersDispatch](docs/sdks/stocktransfers/README.md#publicapiv1stocktransfersdispatch) - Dispatch a stock transfer
* [publicApiV1StockTransfersPdf](docs/sdks/stocktransfers/README.md#publicapiv1stocktransferspdf) - Download stock transfer note PDF
* [publicApiV1StockTransfersStatuses](docs/sdks/stocktransfers/README.md#publicapiv1stocktransfersstatuses) - List stock transfer statuses
* [publicApiV1StockTransfersReceive](docs/sdks/stocktransfers/README.md#publicapiv1stocktransfersreceive) - Receive a stock transfer
* [publicApiV1StockTransfersSend](docs/sdks/stocktransfers/README.md#publicapiv1stocktransferssend) - Send a stock transfer note
* [publicApiV1StockTransfersShow](docs/sdks/stocktransfers/README.md#publicapiv1stocktransfersshow) - Retrieve a stock transfer

#### [StockTransfers.Lines](docs/sdks/stocktransferslines/README.md)

* [publicApiV1StockTransfersLinesCreate](docs/sdks/stocktransferslines/README.md#publicapiv1stocktransferslinescreate) - Add a line to a stock transfer
* [publicApiV1StockTransfersLinesList](docs/sdks/stocktransferslines/README.md#publicapiv1stocktransferslineslist) - List the lines of a stock transfer
* [publicApiV1StockTransfersLinesDelete](docs/sdks/stocktransferslines/README.md#publicapiv1stocktransferslinesdelete) - Remove a line from a stock transfer

### [Storefront.Availability](docs/sdks/availability/README.md)

* [publicApiV1StorefrontAvailabilityBulkResolve](docs/sdks/availability/README.md#publicapiv1storefrontavailabilitybulkresolve) - Resolve storefront availability in bulk
* [publicApiV1StorefrontAvailabilityShow](docs/sdks/availability/README.md#publicapiv1storefrontavailabilityshow) - Retrieve storefront availability

### [Storefront.CatalogSelections](docs/sdks/catalogselections/README.md)

* [publicApiV1StorefrontCatalogSelectionsResolve](docs/sdks/catalogselections/README.md#publicapiv1storefrontcatalogselectionsresolve) - Resolve a storefront catalog selection

### [Storefront.Categories](docs/sdks/categories/README.md)

* [publicApiV1StorefrontCategoriesList](docs/sdks/categories/README.md#publicapiv1storefrontcategorieslist) - List storefront categories

### [Storefront.Orders](docs/sdks/orders/README.md)

* [publicApiV1StorefrontOrdersConfirmPayment](docs/sdks/orders/README.md#publicapiv1storefrontordersconfirmpayment) - Confirm the payment of a storefront order
* [publicApiV1StorefrontOrdersCreate](docs/sdks/orders/README.md#publicapiv1storefrontorderscreate) - Create a storefront order
* [publicApiV1StorefrontOrdersShow](docs/sdks/orders/README.md#publicapiv1storefrontordersshow) - Retrieve a storefront order
* [publicApiV1StorefrontOrdersTracking](docs/sdks/orders/README.md#publicapiv1storefrontorderstracking) - Retrieve the shipment tracking of a storefront order
* [publicApiV1StorefrontOrdersCheckout](docs/sdks/orders/README.md#publicapiv1storefrontorderscheckout) - Start the checkout of a storefront order

#### [Storefront.Orders.BuyerIdentity](docs/sdks/buyeridentity/README.md)

* [publicApiV1StorefrontOrdersBuyerIdentityUpdate](docs/sdks/buyeridentity/README.md#publicapiv1storefrontordersbuyeridentityupdate) - Update the buyer fiscal identity of a storefront order

### [Storefront.Prices](docs/sdks/prices/README.md)

* [publicApiV1StorefrontPricesBulkResolve](docs/sdks/prices/README.md#publicapiv1storefrontpricesbulkresolve) - Resolve storefront prices in bulk
* [publicApiV1StorefrontPricesResolve](docs/sdks/prices/README.md#publicapiv1storefrontpricesresolve) - Resolve a storefront price

### [Storefront.Products](docs/sdks/storefrontproducts/README.md)

* [publicApiV1StorefrontProductsList](docs/sdks/storefrontproducts/README.md#publicapiv1storefrontproductslist) - List storefront products
* [publicApiV1StorefrontProductsSearch](docs/sdks/storefrontproducts/README.md#publicapiv1storefrontproductssearch) - Search storefront products
* [publicApiV1StorefrontProductsShow](docs/sdks/storefrontproducts/README.md#publicapiv1storefrontproductsshow) - Retrieve a storefront product

#### [Storefront.Products.Images](docs/sdks/images/README.md)

* [publicApiV1StorefrontProductsImagesList](docs/sdks/images/README.md#publicapiv1storefrontproductsimageslist) - List the images of a storefront product

#### [Storefront.Products.Presentations](docs/sdks/storefrontpresentations/README.md)

* [publicApiV1StorefrontProductsPresentationsList](docs/sdks/storefrontpresentations/README.md#publicapiv1storefrontproductspresentationslist) - List the presentations of a storefront product

#### [Storefront.Products.ProductOptions](docs/sdks/storefrontproductoptions/README.md)

* [publicApiV1StorefrontProductsOptionsList](docs/sdks/storefrontproductoptions/README.md#publicapiv1storefrontproductsoptionslist) - List the options of a storefront product

#### [Storefront.Products.Variants](docs/sdks/storefrontvariants/README.md)

* [publicApiV1StorefrontProductsVariantsList](docs/sdks/storefrontvariants/README.md#publicapiv1storefrontproductsvariantslist) - List the variants of a storefront product

### [Storefront.Sessions](docs/sdks/sessions/README.md)

* [publicApiV1StorefrontSessionsCreate](docs/sdks/sessions/README.md#publicapiv1storefrontsessionscreate) - Create a cart session
* [publicApiV1StorefrontSessionsRevalidate](docs/sdks/sessions/README.md#publicapiv1storefrontsessionsrevalidate) - Revalidate a cart session
* [publicApiV1StorefrontSessionsShow](docs/sdks/sessions/README.md#publicapiv1storefrontsessionsshow) - Retrieve a cart session
* [publicApiV1StorefrontSessionsUpdate](docs/sdks/sessions/README.md#publicapiv1storefrontsessionsupdate) - Update a cart session

### [StorefrontKeys](docs/sdks/storefrontkeys/README.md)

* [publicApiV1StorefrontKeysCreate](docs/sdks/storefrontkeys/README.md#publicapiv1storefrontkeyscreate) - Create a publishable storefront key
* [publicApiV1StorefrontKeysList](docs/sdks/storefrontkeys/README.md#publicapiv1storefrontkeyslist) - List publishable storefront keys
* [publicApiV1StorefrontKeysScopes](docs/sdks/storefrontkeys/README.md#publicapiv1storefrontkeysscopes) - List assignable storefront key scopes
* [publicApiV1StorefrontKeysRevoke](docs/sdks/storefrontkeys/README.md#publicapiv1storefrontkeysrevoke) - Revoke a publishable storefront key
* [publicApiV1StorefrontKeysRotateSecret](docs/sdks/storefrontkeys/README.md#publicapiv1storefrontkeysrotatesecret) - Rotate a storefront key secret
* [publicApiV1StorefrontKeysShow](docs/sdks/storefrontkeys/README.md#publicapiv1storefrontkeysshow) - Retrieve a publishable storefront key
* [publicApiV1StorefrontKeysUpdate](docs/sdks/storefrontkeys/README.md#publicapiv1storefrontkeysupdate) - Update a publishable storefront key

### [Stores](docs/sdks/stores/README.md)

* [publicApiV1StoresCreate](docs/sdks/stores/README.md#publicapiv1storescreate) - Connect a store
* [publicApiV1StoresIndex](docs/sdks/stores/README.md#publicapiv1storesindex) - List connected stores
* [publicApiV1StoresDisconnect](docs/sdks/stores/README.md#publicapiv1storesdisconnect) - Disconnect a store
* [publicApiV1StoresShow](docs/sdks/stores/README.md#publicapiv1storesshow) - Retrieve a connected store
* [publicApiV1StoresUpdate](docs/sdks/stores/README.md#publicapiv1storesupdate) - Update store settings

#### [Stores.ProductLinks](docs/sdks/productlinks/README.md)

* [publicApiV1StoresProductLinksList](docs/sdks/productlinks/README.md#publicapiv1storesproductlinkslist) - List the product links of a store
* [publicApiV1StoresProductLinksShow](docs/sdks/productlinks/README.md#publicapiv1storesproductlinksshow) - Retrieve a product link of a store

### [StripeAutoinvoicing.Accounts](docs/sdks/accounts/README.md)

* [publicApiV1StripeAutoinvoicingAccountsDisconnect](docs/sdks/accounts/README.md#publicapiv1stripeautoinvoicingaccountsdisconnect) - Disconnect a connected Stripe account
* [publicApiV1StripeAutoinvoicingAccountsShow](docs/sdks/accounts/README.md#publicapiv1stripeautoinvoicingaccountsshow) - Retrieve a connected Stripe account
* [publicApiV1StripeAutoinvoicingAccountsUpdate](docs/sdks/accounts/README.md#publicapiv1stripeautoinvoicingaccountsupdate) - Update a connected Stripe account
* [publicApiV1StripeAutoinvoicingAccountsList](docs/sdks/accounts/README.md#publicapiv1stripeautoinvoicingaccountslist) - List connected Stripe accounts

### [StripeAutoinvoicing.Config](docs/sdks/config/README.md)

* [publicApiV1StripeAutoinvoicingConfigShow](docs/sdks/config/README.md#publicapiv1stripeautoinvoicingconfigshow) - Retrieve Stripe autoinvoicing config
* [publicApiV1StripeAutoinvoicingConfigUpdate](docs/sdks/config/README.md#publicapiv1stripeautoinvoicingconfigupdate) - Update Stripe autoinvoicing config

### [StripeAutoinvoicing.Correctives](docs/sdks/correctives/README.md)

* [publicApiV1StripeAutoinvoicingCorrectivesList](docs/sdks/correctives/README.md#publicapiv1stripeautoinvoicingcorrectiveslist) - List Stripe autoinvoiced correctives

### [StripeAutoinvoicing.Payments](docs/sdks/payments/README.md)

* [publicApiV1StripeAutoinvoicingPaymentsList](docs/sdks/payments/README.md#publicapiv1stripeautoinvoicingpaymentslist) - List Stripe autoinvoiced charges

### [Suppliers](docs/sdks/suppliers/README.md)

* [publicApiV1SuppliersBulkDelete](docs/sdks/suppliers/README.md#publicapiv1suppliersbulkdelete) - Delete multiple suppliers in bulk
* [publicApiV1SuppliersBulkStatus](docs/sdks/suppliers/README.md#publicapiv1suppliersbulkstatus) - Bulk change supplier active state
* [publicApiV1SuppliersCreate](docs/sdks/suppliers/README.md#publicapiv1supplierscreate) - Create a supplier
* [publicApiV1SuppliersList](docs/sdks/suppliers/README.md#publicapiv1supplierslist) - List all suppliers
* [publicApiV1SuppliersDelete](docs/sdks/suppliers/README.md#publicapiv1suppliersdelete) - Delete a supplier
* [publicApiV1SuppliersShow](docs/sdks/suppliers/README.md#publicapiv1suppliersshow) - Retrieve a supplier
* [publicApiV1SuppliersUpdate](docs/sdks/suppliers/README.md#publicapiv1suppliersupdate) - Update a supplier
* [publicApiV1SuppliersFindByExternalId](docs/sdks/suppliers/README.md#publicapiv1suppliersfindbyexternalid) - Find a supplier by external ID
* [publicApiV1SuppliersFindByTaxId](docs/sdks/suppliers/README.md#publicapiv1suppliersfindbytaxid) - Find a supplier by tax ID
* [publicApiV1SuppliersActivities](docs/sdks/suppliers/README.md#publicapiv1suppliersactivities) - List supplier activity timeline
* [publicApiV1SuppliersStats](docs/sdks/suppliers/README.md#publicapiv1suppliersstats) - Get supplier stats
* [publicApiV1SuppliersSearch](docs/sdks/suppliers/README.md#publicapiv1supplierssearch) - Search suppliers
* [publicApiV1SuppliersToggleActive](docs/sdks/suppliers/README.md#publicapiv1supplierstoggleactive) - Toggle supplier active state

### [TaxCatalog](docs/sdks/taxcatalog/README.md)

* [publicApiV1TaxCatalogShow](docs/sdks/taxcatalog/README.md#publicapiv1taxcatalogshow) - Retrieve the tax catalog

### [Taxes](docs/sdks/taxes/README.md)

* [publicApiV1TaxesCalculate](docs/sdks/taxes/README.md#publicapiv1taxescalculate) - Calculate a tax over a base amount
* [publicApiV1TaxesCalculateTotals](docs/sdks/taxes/README.md#publicapiv1taxescalculatetotals) - Calculate totals for a set of lines
* [publicApiV1TaxesIsInUse](docs/sdks/taxes/README.md#publicapiv1taxesisinuse) - Check whether a tax is in use
* [publicApiV1TaxesCreate](docs/sdks/taxes/README.md#publicapiv1taxescreate) - Create a tax
* [publicApiV1TaxesList](docs/sdks/taxes/README.md#publicapiv1taxeslist) - List all taxes
* [publicApiV1TaxesDelete](docs/sdks/taxes/README.md#publicapiv1taxesdelete) - Delete a tax
* [publicApiV1TaxesShow](docs/sdks/taxes/README.md#publicapiv1taxesshow) - Retrieve a tax
* [publicApiV1TaxesUpdate](docs/sdks/taxes/README.md#publicapiv1taxesupdate) - Update a tax
* [publicApiV1TaxesActive](docs/sdks/taxes/README.md#publicapiv1taxesactive) - List active taxes
* [publicApiV1TaxesDefaults](docs/sdks/taxes/README.md#publicapiv1taxesdefaults) - Get default taxes for a document type
* [publicApiV1TaxesStats](docs/sdks/taxes/README.md#publicapiv1taxesstats) - Get tax stats
* [publicApiV1TaxesByType](docs/sdks/taxes/README.md#publicapiv1taxesbytype) - List taxes filtered by type
* [publicApiV1TaxesForPurchases](docs/sdks/taxes/README.md#publicapiv1taxesforpurchases) - List taxes applicable to purchases
* [publicApiV1TaxesForSales](docs/sdks/taxes/README.md#publicapiv1taxesforsales) - List taxes applicable to sales
* [publicApiV1TaxesSetDefault](docs/sdks/taxes/README.md#publicapiv1taxessetdefault) - Mark a tax as the default for its type
* [publicApiV1TaxesSetDefaultForDocument](docs/sdks/taxes/README.md#publicapiv1taxessetdefaultfordocument) - Set tax default for a document type
* [publicApiV1TaxesToggle](docs/sdks/taxes/README.md#publicapiv1taxestoggle) - Toggle tax active state

### [TaxReports](docs/sdks/taxreports/README.md)

* [publicApiV1TaxReportsDownload](docs/sdks/taxreports/README.md#publicapiv1taxreportsdownload) - Download tax report file
* [publicApiV1TaxReportsFindByPeriod](docs/sdks/taxreports/README.md#publicapiv1taxreportsfindbyperiod) - Find a tax report by period
* [publicApiV1TaxReportsGenerate130](docs/sdks/taxreports/README.md#publicapiv1taxreportsgenerate130) - Generate Modelo 130
* [publicApiV1TaxReportsGenerate303](docs/sdks/taxreports/README.md#publicapiv1taxreportsgenerate303) - Generate Modelo 303
* [publicApiV1TaxReportsGenerate347](docs/sdks/taxreports/README.md#publicapiv1taxreportsgenerate347) - Generate Modelo 347
* [publicApiV1TaxReportsActivities](docs/sdks/taxreports/README.md#publicapiv1taxreportsactivities) - List tax report activities
* [publicApiV1TaxReportsStats](docs/sdks/taxreports/README.md#publicapiv1taxreportsstats) - Retrieve tax report stats
* [publicApiV1TaxReportsHistory](docs/sdks/taxreports/README.md#publicapiv1taxreportshistory) - List tax report history
* [publicApiV1TaxReportsPreview](docs/sdks/taxreports/README.md#publicapiv1taxreportspreview) - Preview a tax report

### [TimeBalances](docs/sdks/timebalances/README.md)

* [publicApiV1TimeBalancesEmployee](docs/sdks/timebalances/README.md#publicapiv1timebalancesemployee) - Retrieve an employee’s time balance for a period
* [publicApiV1TimeBalancesMonthlySheet](docs/sdks/timebalances/README.md#publicapiv1timebalancesmonthlysheet) - Retrieve an employee’s monthly time sheet
* [publicApiV1TimeBalancesTeamSummary](docs/sdks/timebalances/README.md#publicapiv1timebalancesteamsummary) - Retrieve the team time balance summary

### [TimeCorrections](docs/sdks/timecorrections/README.md)

* [publicApiV1TimeCorrectionsApprove](docs/sdks/timecorrections/README.md#publicapiv1timecorrectionsapprove) - Approve a time entry correction
* [publicApiV1TimeCorrectionsList](docs/sdks/timecorrections/README.md#publicapiv1timecorrectionslist) - List all time entry corrections
* [publicApiV1TimeCorrectionsCreate](docs/sdks/timecorrections/README.md#publicapiv1timecorrectionscreate) - Request a time entry correction
* [publicApiV1TimeCorrectionsReject](docs/sdks/timecorrections/README.md#publicapiv1timecorrectionsreject) - Reject a time entry correction
* [publicApiV1TimeCorrectionsShow](docs/sdks/timecorrections/README.md#publicapiv1timecorrectionsshow) - Retrieve a time entry correction

### [TimeEntries](docs/sdks/timeentries/README.md)

* [publicApiV1TimeEntriesClockIn](docs/sdks/timeentries/README.md#publicapiv1timeentriesclockin) - Clock in an employee
* [publicApiV1TimeEntriesClockOut](docs/sdks/timeentries/README.md#publicapiv1timeentriesclockout) - Clock out an employee
* [publicApiV1TimeEntriesCurrent](docs/sdks/timeentries/README.md#publicapiv1timeentriescurrent) - Retrieve an employee’s current workday state
* [publicApiV1TimeEntriesList](docs/sdks/timeentries/README.md#publicapiv1timeentrieslist) - List all time entries
* [publicApiV1TimeEntriesPause](docs/sdks/timeentries/README.md#publicapiv1timeentriespause) - Start a pause
* [publicApiV1TimeEntriesManual](docs/sdks/timeentries/README.md#publicapiv1timeentriesmanual) - Record a manual retroactive entry
* [publicApiV1TimeEntriesResume](docs/sdks/timeentries/README.md#publicapiv1timeentriesresume) - Resume from a pause
* [publicApiV1TimeEntriesShow](docs/sdks/timeentries/README.md#publicapiv1timeentriesshow) - Retrieve a time entry

#### [TimeEntries.Chain](docs/sdks/timeentrieschain/README.md)

* [publicApiV1TimeEntriesChainValidate](docs/sdks/timeentrieschain/README.md#publicapiv1timeentrieschainvalidate) - Validate the time record hash chain

### [TimeTrackingSettings](docs/sdks/timetrackingsettings/README.md)

* [publicApiV1TimeTrackingSettingsShow](docs/sdks/timetrackingsettings/README.md#publicapiv1timetrackingsettingsshow) - Retrieve the time tracking settings
* [publicApiV1TimeTrackingSettingsUpdate](docs/sdks/timetrackingsettings/README.md#publicapiv1timetrackingsettingsupdate) - Update the time tracking settings

### [Verifactu](docs/sdks/verifactu/README.md)

* [publicApiV1VerifactuConfig](docs/sdks/verifactu/README.md#publicapiv1verifactuconfig) - Retrieve VeriFactu config
* [publicApiV1VerifactuStats](docs/sdks/verifactu/README.md#publicapiv1verifactustats) - Get VeriFactu stats

#### [Verifactu.AeatAccess](docs/sdks/aeataccess/README.md)

* [publicApiV1VerifactuAeatAccessList](docs/sdks/aeataccess/README.md#publicapiv1verifactuaeataccesslist) - List AEAT access records
* [publicApiV1VerifactuAeatAccessShow](docs/sdks/aeataccess/README.md#publicapiv1verifactuaeataccessshow) - Retrieve an AEAT access record

#### [Verifactu.Certificates](docs/sdks/certificates/README.md)

* [publicApiV1VerifactuCertificatesActivate](docs/sdks/certificates/README.md#publicapiv1verifactucertificatesactivate) - Activate a company certificate
* [publicApiV1VerifactuCertificatesActive](docs/sdks/certificates/README.md#publicapiv1verifactucertificatesactive) - Retrieve the active certificate
* [publicApiV1VerifactuCertificatesList](docs/sdks/certificates/README.md#publicapiv1verifactucertificateslist) - List company certificates
* [publicApiV1VerifactuCertificatesUpload](docs/sdks/certificates/README.md#publicapiv1verifactucertificatesupload) - Upload a company certificate
* [publicApiV1VerifactuCertificatesRevoke](docs/sdks/certificates/README.md#publicapiv1verifactucertificatesrevoke) - Revoke a company certificate

#### [Verifactu.Chain](docs/sdks/verifactuchain/README.md)

* [publicApiV1VerifactuChainValidate](docs/sdks/verifactuchain/README.md#publicapiv1verifactuchainvalidate) - Validate the VeriFactu hash chain

#### [Verifactu.Declaracion](docs/sdks/declaracion/README.md)

* [publicApiV1VerifactuDeclaracionHistory](docs/sdks/declaracion/README.md#publicapiv1verifactudeclaracionhistory) - List declaración responsable history
* [publicApiV1VerifactuDeclaracionCurrent](docs/sdks/declaracion/README.md#publicapiv1verifactudeclaracioncurrent) - Retrieve the current declaración responsable

#### [Verifactu.Events](docs/sdks/verifactuevents/README.md)

* [publicApiV1VerifactuEventsSummary](docs/sdks/verifactuevents/README.md#publicapiv1verifactueventssummary) - Get VeriFactu event summary
* [publicApiV1VerifactuEventsList](docs/sdks/verifactuevents/README.md#publicapiv1verifactueventslist) - List VeriFactu events
* [publicApiV1VerifactuEventsRetry](docs/sdks/verifactuevents/README.md#publicapiv1verifactueventsretry) - Retry a VeriFactu event
* [publicApiV1VerifactuEventsShow](docs/sdks/verifactuevents/README.md#publicapiv1verifactueventsshow) - Retrieve a VeriFactu event

#### [Verifactu.Records](docs/sdks/records/README.md)

* [publicApiV1VerifactuRecordsFindByCsv](docs/sdks/records/README.md#publicapiv1verifacturecordsfindbycsv) - Find a VeriFactu record by AEAT CSV
* [publicApiV1VerifactuRecordsFindByHuella](docs/sdks/records/README.md#publicapiv1verifacturecordsfindbyhuella) - Find a VeriFactu record by hash
* [publicApiV1VerifactuRecordsFindByInvoiceNumber](docs/sdks/records/README.md#publicapiv1verifacturecordsfindbyinvoicenumber) - Find a VeriFactu record by invoice number
* [publicApiV1VerifactuRecordsActivities](docs/sdks/records/README.md#publicapiv1verifacturecordsactivities) - List VeriFactu record activity timeline
* [publicApiV1VerifactuRecordsList](docs/sdks/records/README.md#publicapiv1verifacturecordslist) - List VeriFactu records
* [publicApiV1VerifactuRecordsRetry](docs/sdks/records/README.md#publicapiv1verifacturecordsretry) - Retry VeriFactu transmission
* [publicApiV1VerifactuRecordsShow](docs/sdks/records/README.md#publicapiv1verifacturecordsshow) - Retrieve a VeriFactu record
* [publicApiV1VerifactuRecordsSubsanar](docs/sdks/records/README.md#publicapiv1verifacturecordssubsanar) - Subsanar a rejected VeriFactu record

#### [Verifactu.Settings](docs/sdks/settings/README.md)

* [publicApiV1VerifactuSettingsUpdate](docs/sdks/settings/README.md#publicapiv1verifactusettingsupdate) - Update VeriFactu settings

### [Warehouses](docs/sdks/warehouses/README.md)

* [publicApiV1WarehousesDelete](docs/sdks/warehouses/README.md#publicapiv1warehousesdelete) - Archive a warehouse
* [publicApiV1WarehousesShow](docs/sdks/warehouses/README.md#publicapiv1warehousesshow) - Retrieve a warehouse
* [publicApiV1WarehousesUpdate](docs/sdks/warehouses/README.md#publicapiv1warehousesupdate) - Update a warehouse
* [publicApiV1WarehousesCreate](docs/sdks/warehouses/README.md#publicapiv1warehousescreate) - Create a warehouse
* [publicApiV1WarehousesList](docs/sdks/warehouses/README.md#publicapiv1warehouseslist) - List all warehouses
* [publicApiV1WarehousesFindByCode](docs/sdks/warehouses/README.md#publicapiv1warehousesfindbycode) - Find a warehouse by code
* [publicApiV1WarehousesDefault](docs/sdks/warehouses/README.md#publicapiv1warehousesdefault) - Retrieve the default warehouse
* [publicApiV1WarehousesStatuses](docs/sdks/warehouses/README.md#publicapiv1warehousesstatuses) - List warehouse statuses
* [publicApiV1WarehousesMarkAsDefault](docs/sdks/warehouses/README.md#publicapiv1warehousesmarkasdefault) - Mark a warehouse as default

#### [Warehouses.Locations](docs/sdks/locations/README.md)

* [publicApiV1WarehousesLocationsDelete](docs/sdks/locations/README.md#publicapiv1warehouseslocationsdelete) - Archive a location of a warehouse
* [publicApiV1WarehousesLocationsShow](docs/sdks/locations/README.md#publicapiv1warehouseslocationsshow) - Retrieve a location of a warehouse
* [publicApiV1WarehousesLocationsUpdate](docs/sdks/locations/README.md#publicapiv1warehouseslocationsupdate) - Update a location of a warehouse
* [publicApiV1WarehousesLocationsCreate](docs/sdks/locations/README.md#publicapiv1warehouseslocationscreate) - Create a location in a warehouse
* [publicApiV1WarehousesLocationsList](docs/sdks/locations/README.md#publicapiv1warehouseslocationslist) - List the locations of a warehouse

### [WebhookEndpoints](docs/sdks/webhookendpoints/README.md)

* [publicApiV1WebhookEndpointsCreate](docs/sdks/webhookendpoints/README.md#publicapiv1webhookendpointscreate) - Create a webhook endpoint
* [publicApiV1WebhookEndpointsList](docs/sdks/webhookendpoints/README.md#publicapiv1webhookendpointslist) - List all webhook endpoints
* [publicApiV1WebhookEndpointsDelete](docs/sdks/webhookendpoints/README.md#publicapiv1webhookendpointsdelete) - Delete a webhook endpoint
* [publicApiV1WebhookEndpointsShow](docs/sdks/webhookendpoints/README.md#publicapiv1webhookendpointsshow) - Retrieve a webhook endpoint
* [publicApiV1WebhookEndpointsUpdate](docs/sdks/webhookendpoints/README.md#publicapiv1webhookendpointsupdate) - Update a webhook endpoint
* [publicApiV1WebhookEndpointsPing](docs/sdks/webhookendpoints/README.md#publicapiv1webhookendpointsping) - Ping webhook endpoint
* [publicApiV1WebhookEndpointsRotateSecret](docs/sdks/webhookendpoints/README.md#publicapiv1webhookendpointsrotatesecret) - Rotate webhook secret
* [publicApiV1WebhookEndpointsTestEvent](docs/sdks/webhookendpoints/README.md#publicapiv1webhookendpointstestevent) - Send a test event

#### [WebhookEndpoints.Deliveries](docs/sdks/deliveries/README.md)

* [publicApiV1WebhookEndpointsDeliveriesList](docs/sdks/deliveries/README.md#publicapiv1webhookendpointsdeliverieslist) - List webhook deliveries
* [publicApiV1WebhookEndpointsDeliveriesReplay](docs/sdks/deliveries/README.md#publicapiv1webhookendpointsdeliveriesreplay) - Replay webhook delivery
* [publicApiV1WebhookEndpointsDeliveriesShow](docs/sdks/deliveries/README.md#publicapiv1webhookendpointsdeliveriesshow) - Retrieve webhook delivery

### [Woocommerce.Stores](docs/sdks/woocommercestores/README.md)

* [publicApiV1WoocommerceStoresConnectionTest](docs/sdks/woocommercestores/README.md#publicapiv1woocommercestoresconnectiontest) - Test a WooCommerce store connection

### [WorkSchedules](docs/sdks/workschedules/README.md)

* [publicApiV1WorkSchedulesArchive](docs/sdks/workschedules/README.md#publicapiv1workschedulesarchive) - Archive a work schedule
* [publicApiV1WorkSchedulesAssign](docs/sdks/workschedules/README.md#publicapiv1workschedulesassign) - Assign a schedule to an employee
* [publicApiV1WorkSchedulesCreate](docs/sdks/workschedules/README.md#publicapiv1workschedulescreate) - Create a work schedule
* [publicApiV1WorkSchedulesList](docs/sdks/workschedules/README.md#publicapiv1workscheduleslist) - List all work schedules
* [publicApiV1WorkSchedulesEmployeeSchedule](docs/sdks/workschedules/README.md#publicapiv1workschedulesemployeeschedule) - Get an employee’s current schedule
* [publicApiV1WorkSchedulesStats](docs/sdks/workschedules/README.md#publicapiv1workschedulesstats) - Get work schedule stats
* [publicApiV1WorkSchedulesAssignments](docs/sdks/workschedules/README.md#publicapiv1workschedulesassignments) - List a schedule’s assignments
* [publicApiV1WorkSchedulesShow](docs/sdks/workschedules/README.md#publicapiv1workschedulesshow) - Retrieve a work schedule
* [publicApiV1WorkSchedulesUpdate](docs/sdks/workschedules/README.md#publicapiv1workschedulesupdate) - Update a work schedule
* [publicApiV1WorkSchedulesUnarchive](docs/sdks/workschedules/README.md#publicapiv1workschedulesunarchive) - Unarchive a work schedule
* [publicApiV1WorkSchedulesUnassign](docs/sdks/workschedules/README.md#publicapiv1workschedulesunassign) - Unassign a schedule from an employee

</details>
<!-- End Available Resources and Operations [operations] -->

<!-- Start Retries [retries] -->
## Retries

Some of the endpoints in this SDK support retries. If you use the SDK without any configuration, it will fall back to the default retry strategy provided by the API. However, the default retry strategy can be overridden on a per-operation basis, or across the entire SDK.

To change the default retry strategy for a single API call, simply provide an `Options` object built with a `RetryConfig` object to the call:
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;
use Factuarea\Sdk\Utils\Retry;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1ProformasAcceptRequest(
    proforma: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\AcceptProformaRequest(
        reason: 'Cliente confirma pedido por telefono',
    ),
);

$response = $sdk->proformas->publicApiV1ProformasAccept(
    request: $request,
    options: Utils\Options->builder()->setRetryConfig(
        new Retry\RetryConfigBackoff(
            initialInterval: 1,
            maxInterval:     50,
            exponent:        1.1,
            maxElapsedTime:  100,
            retryConnectionErrors: false,
        ))->build()
);

if ($response->object !== null) {
    // handle response
}
```

If you'd like to override the default retry strategy for all operations that support retries, you can pass a `RetryConfig` object to the `SDKBuilder->setRetryConfig` function when initializing the SDK:
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;
use Factuarea\Sdk\Utils\Retry;

$sdk = Sdk\Factuarea::builder()
    ->setRetryConfig(
        new Retry\RetryConfigBackoff(
            initialInterval: 1,
            maxInterval:     50,
            exponent:        1.1,
            maxElapsedTime:  100,
            retryConnectionErrors: false,
        )
  )
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1ProformasAcceptRequest(
    proforma: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\AcceptProformaRequest(
        reason: 'Cliente confirma pedido por telefono',
    ),
);

$response = $sdk->proformas->publicApiV1ProformasAccept(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
<!-- End Retries [retries] -->

<!-- Start Error Handling [errors] -->
## Error Handling

Handling errors in this SDK should largely match your expectations. All operations return a response object or throw an exception.

By default an API error will raise a `Errors\APIException` exception, which has the following properties:

| Property       | Type                                    | Description           |
|----------------|-----------------------------------------|-----------------------|
| `$message`     | *string*                                | The error message     |
| `$statusCode`  | *int*                                   | The HTTP status code  |
| `$rawResponse` | *?\Psr\Http\Message\ResponseInterface*  | The raw HTTP response |
| `$body`        | *string*                                | The response content  |

When custom error responses are specified for an operation, the SDK may also throw their associated exception. You can refer to respective *Errors* tables in SDK docs for more details on possible exception types for each operation. For example, the `publicApiV1ProformasAccept` method throws the following exceptions:

| Error Type          | Status Code                  | Content Type     |
| ------------------- | ---------------------------- | ---------------- |
| Errors\Error        | 401, 403, 404, 409, 422, 429 | application/json |
| Errors\Error        | 500                          | application/json |
| Errors\APIException | 4XX, 5XX                     | \*/\*            |

### Example

```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Errors;
use Factuarea\Sdk\Models\Operations;

$sdk = Sdk\Factuarea::builder()
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

try {
    $request = new Operations\PublicApiV1ProformasAcceptRequest(
        proforma: '<value>',
        idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
        factuareaVersion: LocalDate::parse('2026-06-01'),
        xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
        body: new Components\AcceptProformaRequest(
            reason: 'Cliente confirma pedido por telefono',
        ),
    );

    $response = $sdk->proformas->publicApiV1ProformasAccept(
        request: $request
    );

    if ($response->object !== null) {
        // handle response
    }
} catch (Errors\ErrorThrowable $e) {
    // handle $e->$container data
    throw $e;
} catch (Errors\ErrorThrowable $e) {
    // handle $e->$container data
    throw $e;
} catch (Errors\APIException $e) {
    // handle default exception
    throw $e;
}
```
<!-- End Error Handling [errors] -->

<!-- Start Server Selection [server] -->
## Server Selection

### Override Server URL Per-Client

The default server can be overridden globally using the `setServerUrl(string $serverUrl)` builder method when initializing the SDK client instance. For example:
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use Brick\DateTime\LocalDate;
use Factuarea\Sdk;
use Factuarea\Sdk\Models\Components;
use Factuarea\Sdk\Models\Operations;

$sdk = Sdk\Factuarea::builder()
    ->setServerURL('https://api.factuarea.com/v1')
    ->setSecurity(
        new Components\Security(
            http: '<YOUR_BEARER_TOKEN_HERE>',
        )
    )
    ->build();

$request = new Operations\PublicApiV1ProformasAcceptRequest(
    proforma: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    xActiveProfile: '01931b3e-7c4a-7f2e-9a8b-3c5d6e7f8a0c',
    body: new Components\AcceptProformaRequest(
        reason: 'Cliente confirma pedido por telefono',
    ),
);

$response = $sdk->proformas->publicApiV1ProformasAccept(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
<!-- End Server Selection [server] -->

<!-- Placeholder for Future Speakeasy SDK Sections -->
